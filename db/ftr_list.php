<?php
require_once '../db/config.php';
//should be hours of each 

// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);

if ($jsonObj->request_type == 'fetch') {

    $startdate =  $jsonObj->startdate;
    $enddate = $jsonObj->enddate;
    $projectSelected = $jsonObj->projectSelected;

// $startdate = '2023-06-01';
// $enddate = '2023-06-29';

    //get status to consider 
    if ($projectSelected) {
        $odd_sql40 = "SELECT value1 FROM configurations WHERE name='ftr_c_status_types'
                        AND project_id = $projectSelected";
    } else {
        $odd_sql40 = "SELECT value1 FROM configurations WHERE name='ftr_c_status_types'";
    }

    $odd_logStatusQuery40 = $conn->query($odd_sql40);
    $reworkStatus_FTR = [];
    if ($odd_logStatusQuery40->num_rows > 0) {
        while ($odd_row40 = $odd_logStatusQuery40->fetch_assoc()) {
            // $odd_extract_status_idArr[] = $odd_row40['status_consider'];
            $reworkStatus_FTR = $odd_row40['value1'];
        }
    }

    $reworkStatus_FTR_Arr = explode(",", $reworkStatus_FTR );

    //tickets to consider. pick those tickets of which reworks is done in between selected time range

    if ($projectSelected) {
        $sql411 = "SELECT log_timing.ticket_id, log_timing.c_status FROM `log_timing` LEFT JOIN tickets ON tickets.id = log_timing.ticket_id 
                        WHERE DATE_FORMAT(`dates`, '%Y-%m-%d') >= '$startdate'  
                        AND DATE_FORMAT(`dates`, '%Y-%m-%d') <= '$enddate' AND tickets.project_id=$projectSelected";
    } else {
        $sql411 = "SELECT ticket_id, c_status FROM `log_timing` WHERE DATE_FORMAT(`dates`, '%Y-%m-%d') >= '$startdate'  AND DATE_FORMAT(`dates`, '%Y-%m-%d') <= '$enddate'";
    }
    $logStatusQuery411 = $conn->query($sql411);
    if ($logStatusQuery411->num_rows > 0) {
        // $total_tickets = $logStatusQuery41->num_rows;
        $tickets_to_consider = [];
        while ($row411 = $logStatusQuery411->fetch_assoc()) {
            if(in_array($row411['c_status'], $reworkStatus_FTR_Arr)){
                $tickets_to_consider[] = $row411['ticket_id'];
            }
        }
    }


    if(!empty($tickets_to_consider)) {
        $tickets_to_consider = array_unique($tickets_to_consider);
        $extract_tickets_to_considerArrImplode = implode(',', $tickets_to_consider);
        // echo "<pre>"; print_r($extract_tickets_to_considerArrImplode); die();

        /**
         * 3) get total log hrs in between that date range of the eligible tickets as per above queries result
        */
        $sql43 = "SELECT lh.ticket_id, tickets.ticket_id AS ticket_name, tickets.planned_hrs, tickets.actual_hrs, lh.c_status, cs.type_name, lh.hrs,  lh.dates
                    FROM log_history AS lh 
                    LEFT JOIN tickets AS tickets ON tickets.id = lh.ticket_id 
                    LEFT JOIN c_status_types AS cs ON cs.id = lh.c_status 
                    WHERE lh.ticket_id IN ($extract_tickets_to_considerArrImplode)"
                    ;

        $logStatusQuery43 = $conn->query($sql43);
        // $total_tickets = 0;
        $CODE_REVIEW_STATUS = 7;
        if ($logStatusQuery43->num_rows > 0) {
            while ($row43 = $logStatusQuery43->fetch_assoc()) {
                if(!isset($ftr_Arr[$row43['ticket_id']])) {
                    // $sum_actual_hrs = 0;
                    // $total_tickets++;
                    $ftr_Arr[$row43['ticket_id']]['ticket_name'] = $row43['ticket_name'];
                    $ftr_Arr[$row43['ticket_id']]['planned_hrs'] = $row43['planned_hrs'];
                    $ftr_Arr[$row43['ticket_id']]['actual_hrs'] = $row43['actual_hrs'];
                }

                if(in_array($row43['c_status'], $reworkStatus_FTR_Arr)) {
                    // $reworkCountHtml = $row43['ticket_id']."_".
                    $ftr_Arr[$row43['ticket_id']]['rework'][$row43['type_name']][] = $row43['hrs'];

                }
            }
        }
    }

    // echo "<pre>"; print_r($ftr_Arr); die();

    $sr = 0;
//ticketid (rework), planned hrs,  actual hrs(without date range), rework count, reworks hrs( without date range), total rework hrs, rework percentage 
    echo '<table id="phptable" class="phptableclass display" style="">';
     echo '<thead>
                  <tr>
                    <th>S.N</th>
                    <th scope="col">Ticket</th>
                    <th scope="col">Planned Hrs</th>
                    <th scope="col">Actual Hrs</th>
                    <th scope="col">Rework Count</th>
                    <th scope="col">Rework Hrs</th>
                    <th scope="col">Total Rework Hrs</th>
                    <th scope="col">Rework percentage</th>
                  </tr>
                </thead>';
    if(empty($ftr_Arr)) {
        echo "<tr><td>No record found!</td></tr>";
    } else {
        foreach ($ftr_Arr as $key => $member) {
            //remove leaves of members in this project in given date range
            
            // $ru_member_data[]
            $sr++;
            echo "<tr>";
            echo "<td>$sr</td>"; 
            echo "<td>";
            echo "<a href='/tickets.php?ticket_id=".$member['ticket_name']."'>".$member['ticket_name']."</a>";
            echo "</td>";
            echo "<td>".$member['planned_hrs']."</td>";
            echo "<td>".$member['actual_hrs']."</td>";

            $rework_statuses =  array_keys($member['rework']);
            $rework_statuses_implode = implode(", ", $rework_statuses);
            echo "<td>".$rework_statuses_implode."</td>";
            
            $reworkHrsHtml = '';
            $total_rework_hrs = 0;
            foreach ($member['rework'] as $key => $values) {
                $total_rework_hrs += array_sum($values);
               $reworkHrsHtml .= array_sum($values).", ";
            }
            $reworkHrsHtml = rtrim($reworkHrsHtml, ', ');
            echo "<td>".$reworkHrsHtml."</td>";
            echo "<td>".$total_rework_hrs."</td>";
            
            $reworks_per = 0;
            $reworks_per = round(($total_rework_hrs / $member['actual_hrs'])* 100  ,2 );
            $td_background = ($reworks_per < 10) ? 'green': 'red';
            echo "<td style='background:".$td_background."'>".$reworks_per."</td>";
            echo "</tr>";
        }
    }

     echo '</table>';
  //  echo $phptableclass;
    
    // echo '<pre>'; print_r( $memberData); die();
}

?>