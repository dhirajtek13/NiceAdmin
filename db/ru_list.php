<?php
require_once '../db/config.php';
//should be hours of each 

// [dhirajtekade] => [ 'working days', 'actual logged',  'should be logged', '%logged'];


// require_once '../db/fetchConfiguration.php';

// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);

if ($jsonObj->request_type == 'fetch') {
    
    $WORKING_HRS = $jsonObj->config_actual_hrs;// from dashboard configuration
    $WORKING_DAY = $jsonObj->config_working_days;

    $startdate =  $jsonObj->startdate;
    $enddate = $jsonObj->enddate;
    $projectSelected = $jsonObj->projectSelected;

// $startdate = '2023-06-01';
// $enddate = '2023-06-29';
    
    $TOTAL_DAYS_IN_RANGE = daysWithoutWeekend($startdate, $enddate);

    

        //check if any holiday comes in betwwen start and end date selected
        $sql1 = "SELECT * FROM holidays 
                    WHERE DATE_FORMAT(hol_start_date, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(hol_end_date, '%Y-%m-%d')  <= '$enddate'";
        $logStatusQuery1 = $conn->query($sql1);
        $holidays = [];
        if ($logStatusQuery1->num_rows > 0) {
            while ($row1 = $logStatusQuery1->fetch_assoc()) {
                //remove substract days from $TOTAL_DAYS_IN_RANGE
                //holiday should not be in weekend days
                $skip_weekend_in_holiday = daysWithoutWeekend($row1['hol_start_date'], $row1['hol_end_date']);
                $TOTAL_DAYS_IN_RANGE = $TOTAL_DAYS_IN_RANGE - $skip_weekend_in_holiday;
                // $holidays[] = $row1;
            }
        }


        $sql2 = "SELECT * FROM leave_tracker 
                            WHERE DATE_FORMAT(leave_start_date, '%Y-%m-%d') >= '$startdate' 
                            AND DATE_FORMAT(leave_end_date, '%Y-%m-%d')  <= '$enddate'";

        $logStatusQuery2 = $conn->query($sql2);
        $members_leaveTracker = [];

        if ($logStatusQuery2->num_rows > 0) {
            while ($row2 = $logStatusQuery2->fetch_assoc()) {
                //remove substract days from $TOTAL_DAYS_IN_RANGE
                if(!isset($members_leaveTracker[$row2['user_id']])) {
                    $common_total_working_days = $TOTAL_DAYS_IN_RANGE;

                    $skip_weekend_in_holiday2 = daysWithoutWeekend($row2['leave_start_date'], $row2['leave_end_date']);
                    $members_leaveTracker[$row2['user_id']]['should_be_working_days'] = $common_total_working_days - $skip_weekend_in_holiday2;
                    // $members_leaveTracker[$row2['user_id']]['should_be_working_days'] = $common_total_working_days;


                } else {
                    $skip_weekend_in_holiday3 = daysWithoutWeekend($row2['leave_start_date'], $row2['leave_end_date']);
                    $members_leaveTracker[$row2['user_id']]['should_be_working_days'] = $members_leaveTracker[$row2['user_id']]['should_be_working_days'] - $skip_weekend_in_holiday3;

                }

               

                // $members_leaveTracker[$row2['user_id']]['start_date'][] = $row2['leave_start_date'];
                // $members_leaveTracker[$row2['user_id']]['end_date'][] = $row2['leave_end_date'];
                // $skip_weekend_in_holiday = daysWithoutWeekend($row2['leave_start_date'], $row2['leave_end_date']);
                // $TOTAL_DAYS_IN_RANGE = $TOTAL_DAYS_IN_RANGE - $skip_weekend_in_holiday;
                // $leave_days[] = $row2;
            }
        }

       

    //members in projects 
    if ($projectSelected) {
        $sql33 = "SELECT SUM(log_history.hrs) AS logged_hrs, users.id AS user_id, users.username, CONCAT(users.fname, ' ', users.lname) AS fullname FROM users 
                            LEFT JOIN log_history ON log_history.user_id = users.id
                            LEFT JOIN project_user_map ON project_user_map.user_id = users.id 
                            WHERE users.user_type != 1  
                            AND project_user_map.project_id = $projectSelected
                            GROUP BY users.id
                            ";
    } else {
        $sql33 = "SELECT SUM(log_history.hrs) AS logged_hrs, users.id AS user_id, users.username, CONCAT(users.fname, ' ', users.lname) AS fullname FROM users 
                    LEFT JOIN log_history ON log_history.user_id = users.id 
                    WHERE users.user_type != 1
                    GROUP BY users.id";
    }


    $logStatusQuery33 = $conn->query($sql33);
    $res_utilArr=[];
    if ($logStatusQuery33->num_rows > 0) {
        $memberData = [];
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
                    <th scope="col">Working Days</th>
                    <th scope="col">Actual Logged</th>
                    <th scope="col">Shouldbe Logged</th>
                    <th scope="col">% Logged</th>
                  </tr>
                </thead>';

    foreach ($memberData as $key => $member) {
         //remove leaves of members in this project in given date range
         
        // $ru_member_data[]
        $sr++;
         echo "<tr>";
         echo "<td>$sr</td>"; 
         echo "<td>".$member['fullname']."</td>"; 
        
       
        if(in_array($member['user_id'], array_keys($members_leaveTracker))){
            $shouldbe_days = $members_leaveTracker[$member['user_id']]['should_be_working_days'];
            $shouldbe_hrs = $shouldbe_days * $WORKING_HRS;
        } else {
            $shouldbe_days = $TOTAL_DAYS_IN_RANGE;
            $shouldbe_hrs = $shouldbe_days * $WORKING_HRS;
        }
         echo "<td>$shouldbe_days</td>"; 
         $actual_logged = ($member['logged_hrs'] != '') ? $member['logged_hrs'] : 0;
         echo "<td>$actual_logged</td>"; 
         echo "<td>$shouldbe_hrs</td>"; 


         echo "<td>".round( ($member['logged_hrs'] / $shouldbe_hrs) * 100,2)."</td>";  

         echo "</tr>";
    }

     echo '</table>';

  //  echo $phptableclass;
    
    // echo '<pre>'; print_r( $memberData); die();
}




function daysWithoutWeekend($startdate, $enddate) {
    $start = new DateTime($startdate);
    $end = new DateTime($enddate);
    $oneday = new DateInterval("P1D");
    $daysWithoutWeekend = 0;
    foreach(new DatePeriod($start, $oneday, $end->add($oneday)) as $day) {
        $day_num = $day->format("N"); /* 'N' number days 1 (mon) to 7 (sun) */
        if($day_num < 6) { /* weekday */
            $daysWithoutWeekend++;
        } 
    }  
    return  $daysWithoutWeekend;
}










?>