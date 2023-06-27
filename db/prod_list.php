<?php
require_once '../db/config.php';
//should be hours of each 

// [dhirajtekade] => [ 'working days', 'actual logged',  'should be logged', '%logged'];


// require_once '../db/fetchConfiguration.php';

// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);

if ($jsonObj->request_type == 'fetch') {

    $startdate =  $jsonObj->startdate;
    $enddate = $jsonObj->enddate;
    $projectSelected = $jsonObj->projectSelected;

    // $startdate = '2023-06-01';
    // $enddate = '2023-06-29';

    //1) pick which status to choose 
    //get status to consider 
    if ($projectSelected) {
        $sql51 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                                FROM c_status_types 
                                INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                                WHERE configurations.name = 'prod_c_status_types'
                                AND project_id = $projectSelected";
    } else {
        $sql51 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                                FROM c_status_types 
                                INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                                WHERE configurations.name = 'prod_c_status_types'";
    }

    $logStatusQuery51 = $conn->query($sql51);
    $extract_prod_status_idArr = [];
    $extract_prod_status_idAr = [];
    if ($logStatusQuery51->num_rows > 0) {
        while ($row51 = $logStatusQuery51->fetch_assoc()) {
            $extract_prod_status_idArr[] = $row51['status_consider'];
            $extract_prod_status_idAr[] = $row51;
        }
    }

    $extract_prod_status_idArrImplode = implode(",", $extract_prod_status_idArr);


    /**
     * 2) Fetch tickets to consider as per the date range and final(last) ticket status in that date range
     */
    if ($projectSelected) {
        $sql522 = "WITH ranked_messages AS (
                            SELECT cs.type_name, tickets.plan_end_date, m.*, ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn, tickets.project_id FROM log_timing AS m 
                            LEFT JOIN tickets ON tickets.id = m.ticket_id
                            LEFT JOIN c_status_types AS cs ON cs.id = m.c_status
                            WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$enddate' 
                            AND tickets.project_id = $projectSelected
                        ) 
                        SELECT ticket_id,plan_end_date,dates,c_status,  type_name FROM ranked_messages WHERE rn = 1 AND c_status IN ($extract_prod_status_idArrImplode)";
    } else {
        $sql522 = "WITH ranked_messages AS (
                            SELECT  cs.type_name, tickets.plan_end_date, m.*, ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn FROM log_timing AS m 
                            LEFT JOIN tickets ON tickets.id = m.ticket_id
                            LEFT JOIN c_status_types AS cs ON cs.id = m.c_status
                            WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$enddate' 
                        ) 
                        SELECT ticket_id,plan_end_date,dates, c_status,  type_name FROM ranked_messages WHERE rn = 1 AND c_status IN ($extract_prod_status_idArrImplode)";
    }



    $logStatusQuery522 = $conn->query($sql522);
    $tickets_to_consider = [];
    $ticket_status_map = [];
    if ($logStatusQuery522->num_rows > 0) {
        // $prod_total_tickets = $logStatusQuery522->num_rows;
        // $total_planned_hrs = 0;
        // $total_actual_hrs = 0;
        // $prod_metricsArr = [];
        while ($row522 = $logStatusQuery522->fetch_assoc()) {
            $tickets_to_consider[] = $row522['ticket_id'];
            $ticket_status_map[$row522['ticket_id']]['final_status'] = $row522['type_name'];
        }
    }

    $memberData = [];


    if (!empty($tickets_to_consider)) {
        $extract_tickets_to_considerArrImplode = implode(",", $tickets_to_consider);
        /**
         * 3) get total log hrs in between that date range of the eligible tickets as per above queries result
         */

        $sql533 = "SELECT tickets.id, tickets.ticket_id as ticket_name, SUM(log_history.hrs) as log_hrs, planned_hrs, actual_hrs, project_id, log_history.c_status, cs.type_name 
                FROM `log_history` 
                LEFT JOIN tickets ON log_history.ticket_id = tickets.id 
                LEFT JOIN c_status_types AS cs ON cs.id = log_history.c_status 
                WHERE DATE_FORMAT(log_history.`dates`, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(log_history.`dates`, '%Y-%m-%d') <= '$enddate' 
                AND log_history.ticket_id IN ($extract_tickets_to_considerArrImplode) 
                GROUP BY tickets.id
                
                ";

        // echo "<pre>"; print_r($sql533); die(); 


        $logStatusQuery533 = $conn->query($sql533);
        $productivityArr = [];
        if ($logStatusQuery533->num_rows > 0) {
            $prod_total_tickets = $logStatusQuery533->num_rows;
            while ($row533 = $logStatusQuery533->fetch_assoc()) {
                $memberData[$row533['id']]['ticket_id'] = $row533['id'];
                $memberData[$row533['id']]['ticket_name'] = $row533['ticket_name'];
                $memberData[$row533['id']]['planned_hrs'] = $row533['planned_hrs'];
                $memberData[$row533['id']]['total_hrs'] = $row533['log_hrs'];

                $memberData[$row533['id']]['kpi'] = '0';
                $extrahrsleft = $row533['planned_hrs'] - $row533['total_hrs'];
                $prod_kpi_calc = round(($extrahrsleft / $row533['planned_hrs']) * 100, 2);
                if ($prod_kpi_calc >= -10) {
                    $memberData[$row533['id']]['kpi'] = '100';
                }
            }
        }
    }



    // echo "<pre>"; print_r($memberData);die();

    echo '<table id="phptable" class="phptableclass display" style="">';
    echo '<thead>
                  <tr>
                    <th>S.N</th>
                    <th scope="col">Ticket</th>
                    <th scope="col">Planned Hrs</th>
                    <th scope="col">Actual Hrs</th>
                    <th scope="col">KPI Status</th>
                  </tr>
                </thead>';
    if (empty($memberData)) {
        echo "<tr><td>No record found!</td></tr>";
    } else {
        foreach ($memberData as $key => $member) {
            //remove leaves of members in this project in given date range

            // $ru_member_data[]
            $sr++;
            echo "<tr>";
            echo "<td>$sr</td>";
            echo "<td>";
            echo "<a href='/tickets.php?ticket_id=" . $member['ticket_name'] . "'>" . $member['ticket_name'] . "</a>";
            echo "</td>";
            echo "<td>" . $member['planned_hrs'] . "</td>";
            echo "<td>" . $member['total_hrs'] . "</td>";

            $td_background = ($member['kpi'] == 100) ? 'green': 'red';
            echo "<td style='background:".$td_background."'>".$member['kpi']."</td>";

            // echo "<td>" . $member['kpi'] . "</td>";

            echo "</tr>";
        }
    }

    echo '</table>';

    //  echo $phptableclass;

    // echo '<pre>'; print_r( $memberData); die();
}
