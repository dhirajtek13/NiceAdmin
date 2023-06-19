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

        if ($projectSelected) {
            $sql51 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                                    FROM c_status_types 
                                    INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                                    WHERE configurations.name = 'reassigned_c_status_types'
                                    AND project_id = $projectSelected";
        } else {
            $sql51 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                                    FROM c_status_types 
                                    INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                                    WHERE configurations.name = 'reassigned_c_status_types'";
        }

        $logStatusQuery51 = $conn->query($sql51);
        $Reassigned_tickets = [];
        // $extract_prod_status_idAr = [];
        if ($logStatusQuery51->num_rows > 0) {
            while ($row51 = $logStatusQuery51->fetch_assoc()) {
                $Reassigned_tickets[] = $row51['status_consider'];
                // $extract_prod_status_idAr[] = $row51;
            }
        }


    $Reassigned_tickets_implode = implode(',', $Reassigned_tickets);
    
    // $Reassigned_tickets_implode = 5;
   $sql33 = "SELECT users.id, users.username, CONCAT(users.fname, ' ', users.lname) AS fullname, COUNT(log_timing.c_status) AS reassigned_count_of_ticket,
                    GROUP_CONCAT(tickets.ticket_id) AS tickets_collection,  GROUP_CONCAT(tickets.id) AS tickets_id_collection
                    FROM users 
                    LEFT JOIN `log_timing` ON log_timing.user_id = users.id
                    LEFT JOIN `tickets` ON tickets.id = log_timing.ticket_id
                    WHERE log_timing.c_status IN ($Reassigned_tickets_implode) 
                    AND DATE_FORMAT(log_timing.dates, '%Y-%m-%d') >= '$startdate' 
                    AND DATE_FORMAT(log_timing.dates, '%Y-%m-%d') <= '$enddate'
                    GROUP BY users.id";

    $logStatusQuery33 = $conn->query($sql33);
    // $res_utilArr=[];
    $memberData = [];
    if ($logStatusQuery33->num_rows > 0) {
        while ($row33 = $logStatusQuery33->fetch_assoc()) {
            // echo "<pre>"; print_r($row33);die();
            $memberData[] =  $row33;
        }
    }


    $ru_member_data = []; $sr = 0;
    // echo "<pre>"; print_r($sql33);die();

    echo '<table id="phptable" class="phptableclass display" style="">';
     echo '<thead>
                  <tr>
                    <th>S.N</th>
                    <th scope="col">Member Name</th>
                    <th scope="col">Reassigned Tickets</th>
                    <th scope="col">Tickets</th>
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
            echo "<td>".$member['fullname']."</td>";
            echo "<td>".$member['reassigned_count_of_ticket']."</td>";

            $implode_tickets_collection = explode(",", $member['tickets_collection']);
            $implode_tickets_id_collection = explode(",", $member['tickets_id_collection']);

            $tickets_list_html = '';
            foreach ($implode_tickets_collection as $key => $value) {
                $tickets_list_html .= "<a href='/tickets.php?ticket_id=".$value."'>$value</a>, ";
                // $tickets_list_html .= "<a href='/tickets.php?ticket_id=".$value."'>$value</a>, ";
            }
            $tickets_list_html = rtrim($tickets_list_html, ', ');


            echo "<td>".$tickets_list_html."</td>";
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