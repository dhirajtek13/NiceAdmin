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
                    SELECT cs.type_name, tickets.plan_end_date, m.*, ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn, tickets.project_id FROM log_timing AS m 
                    LEFT JOIN tickets ON tickets.id = m.ticket_id
                    LEFT JOIN c_status_types AS cs ON cs.id = m.c_status
                    WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$enddate' 
                    AND tickets.project_id = $projectSelected
                ) 
                SELECT ticket_id,plan_end_date,dates,c_status,  type_name FROM ranked_messages WHERE rn = 1 AND c_status IN ($extract_status_idArrImplode)";
    } else {
        $sql12 = "WITH ranked_messages AS (
                    SELECT  cs.type_name, tickets.plan_end_date, m.*, ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn FROM log_timing AS m 
                    LEFT JOIN tickets ON tickets.id = m.ticket_id
                    LEFT JOIN c_status_types AS cs ON cs.id = m.c_status
                    WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$enddate' 
                ) 
                SELECT ticket_id,plan_end_date,dates, c_status,  type_name FROM ranked_messages WHERE rn = 1 AND c_status IN ($extract_status_idArrImplode)";
    }

    $logStatusQuery12 = $conn->query($sql12);
    $tickets_to_consider = [];
    if ($logStatusQuery12->num_rows > 0) {
        $total_tickets = $logStatusQuery12->num_rows; //total tickets //also used for ODD
        while ($row12 = $logStatusQuery12->fetch_assoc()) {     
            $tickets_to_consider[] = $row12['ticket_id'];//for OTD itself
        }
    }

    $memberData = [];
    if(!empty($tickets_to_consider)) {
        $extract_tickets_to_considerArrImplode = implode(",", $tickets_to_consider);
        
        /**
         * 3) get total log hrs in between that date range of the eligible tickets as per above queries result
        */
        $sql13 = "SELECT l.ticket_id, t.ticket_id as ticket_name, sum(hrs) as total_hrs, t.planned_hrs FROM `log_history` as l 
                    LEFT JOIN tickets as t on t.id = l.ticket_id
                    WHERE DATE_FORMAT(l.`dates`, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(l.`dates`, '%Y-%m-%d') <= '$enddate'
                    AND l.ticket_id IN ($extract_tickets_to_considerArrImplode)
                    GROUP BY l.ticket_id ";


        $logStatusQuery13 = $conn->query($sql13);
       
        if ($logStatusQuery13->num_rows > 0) {
            while ($row13 = $logStatusQuery13->fetch_assoc()) {

                $memberData[$row13['ticket_id']]['ticket_id'] = $row13['ticket_id'];
                $memberData[$row13['ticket_id']]['ticket_name'] = $row13['ticket_name'];
                $memberData[$row13['ticket_id']]['total_hrs'] = $row13['total_hrs'];
                $memberData[$row13['ticket_id']]['planned_hrs'] = $row13['planned_hrs'];


                $memberData[$row13['ticket_id']]['variance'] = $row13['planned_hrs'] - $row13['total_hrs'];

                $memberData[$row13['ticket_id']]['kpi'] = '0';
                if ($row13['planned_hrs'] >= $row13['total_hrs']) {
                    $memberData[$row13['ticket_id']]['kpi'] = '100';
                }
            }
        }
    }

    $sr = 0;
    echo '<table id="phptable" class="phptableclass display" style="">';
     echo '<thead>
                  <tr>
                    <th>S.N</th>
                    <th scope="col">Ticket Id</th>
                    <th scope="col">Planned Hrs</th>
                    <th scope="col">Actual Hrs</th>
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
            echo "<td>".$member['planned_hrs']."</td>";
            echo "<td>".$member['total_hrs']."</td>";
            echo "<td>".$member['variance']."</td>";

            $td_background = ($member['kpi'] == 100) ? 'green': 'red';
            echo "<td style='background:".$td_background."'>".$member['kpi']."</td>";
            echo "</tr>";
        }
    }
     echo '</table>';

  //  echo $phptableclass;
    
    // echo '<pre>'; print_r( $memberData); die();
}




// function daysWithoutWeekend($startdate, $enddate) {
//     $start = new DateTime($startdate);
//     $end = new DateTime($enddate);
//     $oneday = new DateInterval("P1D");
//     $daysWithoutWeekend = 0;
//     foreach(new DatePeriod($start, $oneday, $end->add($oneday)) as $day) {
//         $day_num = $day->format("N"); /* 'N' number days 1 (mon) to 7 (sun) */
//         if($day_num < 6) { /* weekday */
//             $daysWithoutWeekend++;
//         } 
//     }  
//     return  $daysWithoutWeekend;
// }










?>