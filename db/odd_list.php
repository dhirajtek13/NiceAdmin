<?php
require_once '../db/config.php';

// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);

if ($jsonObj->request_type == 'fetch') {

    $startdate =  $jsonObj->startdate;
    $enddate = $jsonObj->enddate;
    $projectSelected = $jsonObj->projectSelected;

    /**
     * 1) fetch ticket status to consider for OTD
     */
    //get status to consider 
    if ($projectSelected) {
        $sql11 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                        FROM c_status_types 
                        INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                        WHERE configurations.name = 'kpi_c_status_types'
                        AND project_id = $projectSelected";
    } else {
        $sql11 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                        FROM c_status_types 
                        INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                        WHERE configurations.name = 'kpi_c_status_types'";
    }

    $logStatusQuery11 = $conn->query($sql11);
    $extract_status_idArr = [];
    $extract_status_idAr = [];
    if ($logStatusQuery11->num_rows > 0) {
        while ($row11 = $logStatusQuery11->fetch_assoc()) {
            $extract_status_idArr[] = $row11['status_consider'];
            $extract_status_idAr[] = $row11;
        }
    }

    $extract_status_idArrImplode = implode(",", $extract_status_idArr);
    
    /**
     * 2) Fetch tickets to consider as per the date range and final(last) ticket status in that date range
     */
    if ($projectSelected) {
        $sql12 = "WITH ranked_messages AS (
                    SELECT tickets.ticket_id as ticket_name, cs.type_name,  DATE_FORMAT(tickets.plan_end_date, '%Y-%m-%d') as plan_end_date, 
                    m.id, DATE_FORMAT(m.dates, '%Y-%m-%d') as dates, m.ticket_id as ticket_id, m.c_status as c_status,
                    ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn, 
                    tickets.project_id, tickets.planned_hrs, tickets.actual_hrs
                    FROM log_timing AS m 
                    LEFT JOIN tickets ON tickets.id = m.ticket_id
                    LEFT JOIN c_status_types AS cs ON cs.id = m.c_status
                    WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$enddate' 
                    AND tickets.project_id = $projectSelected
                ) 
                SELECT planned_hrs, actual_hrs, ticket_name, ticket_id, plan_end_date,dates,c_status,  type_name FROM ranked_messages WHERE rn = 1 AND c_status IN ($extract_status_idArrImplode)";
    } else {
        $sql12 = "WITH ranked_messages AS (
                    SELECT  tickets.ticket_id as ticket_name, cs.type_name, DATE_FORMAT(tickets.plan_end_date, '%Y-%m-%d') as plan_end_date, 
                    m.id, DATE_FORMAT(m.dates, '%Y-%m-%d') as dates, m.ticket_id as ticket_id, m.c_status as c_status,
                    ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn ,
                    tickets.planned_hrs, tickets.actual_hrs
                    FROM log_timing AS m 
                    LEFT JOIN tickets ON tickets.id = m.ticket_id
                    LEFT JOIN c_status_types AS cs ON cs.id = m.c_status
                    WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$enddate' 
                ) 
                SELECT planned_hrs, actual_hrs, ticket_name, ticket_id,plan_end_date,dates, c_status,  type_name FROM ranked_messages WHERE rn = 1 AND c_status IN ($extract_status_idArrImplode)";
    }

    $logStatusQuery12 = $conn->query($sql12);
    $tickets_to_consider = [];
    $memberData = [];
    //  echo "<pre>"; print_r($sql12); die(); 
    if ($logStatusQuery12->num_rows > 0) {
        $total_tickets = $logStatusQuery12->num_rows; //total tickets //also used for ODD
        while ($row12 = $logStatusQuery12->fetch_assoc()) {     
            $tickets_to_consider[] = $row12['ticket_id'];//for OTD itself

            if($row12['plan_end_date'] >= $row12['dates']){
                // $odd_ticketsArr[$row12['ticket_id']] =  $row12;
                // $odd_otdCounter++;
                $memberData[$row12['ticket_id']]['ticket_id'] = $row12['ticket_id'];
                $memberData[$row12['ticket_id']]['ticket_name'] = $row12['ticket_name'];
                $memberData[$row12['ticket_id']]['plan_end_date'] = $row12['plan_end_date'];
                $memberData[$row12['ticket_id']]['actual_end_date'] = $row12['dates'];

                $memberData[$row12['ticket_id']]['variance'] = $row12['planned_hrs'] - $row12['actual_hrs'];

                $memberData[$row12['ticket_id']]['kpi'] = '0';
                if ($row12['plan_end_date'] >= $row12['dates']) {
                    $memberData[$row12['ticket_id']]['kpi'] = '100';
                }
               

            }

        }
    }
    // echo "<pre>"; print_r($memberData); die();
   


    $sr = 0;
    echo '<table id="phptable" class="phptableclass display" style="">';
     echo '<thead>
                  <tr>
                    <th>S.N</th>
                    <th scope="col">Ticket Id</th>
                    <th scope="col">Planned date</th>
                    <th scope="col">Actual Date</th>
                    <th scope="col">Variance</th>
                    <th scope="col">KPI Status</th>
                  </tr>
                </thead>';
    if(empty($memberData)) {
        echo "<tr><td>No record found!</td></tr>";
    } else {
        foreach ($memberData as $key => $member) {
            //remove leaves of members in this project in given date range
            
            // $ru_member_data[]
            $sr++;
            echo "<tr>";
            echo "<td>$sr</td>"; 
            echo "<td>";
            echo "<a href='/tickets.php?ticket_id=".$member['ticket_name']."'>".$member['ticket_name']."</a>";
            echo "</td>";
            echo "<td>".$member['plan_end_date']."</td>";
            echo "<td>".$member['actual_end_date']."</td>";

            echo "<td>".$member['variance']."</td>";

            $td_background = ($member['kpi'] == 100) ? 'green': 'red';
            echo "<td style='background:".$td_background."'>".$member['kpi']."</td>";

            // echo "<td>".$member['kpi']."</td>";

            echo "</tr>";
        }
    }
     echo '</table>';

  //  echo $phptableclass;
    
    // echo '<pre>'; print_r( $memberData); die();
}



?>