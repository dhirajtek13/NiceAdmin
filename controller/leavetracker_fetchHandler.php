<?php

require_once '../db/config.php';
require_once '../controller/customFunctions.php';

//get current user id
session_start();
$current_user_id =  $_SESSION['user_id'];

// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);

if ($jsonObj->request_type == 'fetch') {
    $month =  $jsonObj->month;
    $year =  $jsonObj->year;


    //get current year, month and maxdays of it
    $allDaysColArr = dates_month($month, $year); //from customFunctions.php

    $responseHtml = '';


    

    // echo "<pre>"; print_r($holidaysData); die();

    $sql2 = "SELECT * 
          FROM users 
          LEFT JOIN leave_tracker ON leave_tracker.user_id = users.id";

    $logStatusQuery2 = $conn->query($sql2);
    $userData = [];
    if ($logStatusQuery2->num_rows > 0) {
        while ($row2 = $logStatusQuery2->fetch_assoc()) {
            // $user_ticketsArr[$row2['fullname']] =  $row2['user_tickets'];
            // $userData[$row2['username']][] = $row2;
            $userData[$row2['username']]['details'] = $row2;
            if ($row2['leave_start_date']) {

                $userData[$row2['username']]['leave_start_date'][] = date("d-m-Y", strtotime($row2['leave_start_date']));
                $userData[$row2['username']]['leave_end_date'][] =  date("d-m-Y", strtotime($row2['leave_end_date']));
            } else {
            }
        }
    }

        //holidays
        $sql3 = "SELECT id, hol_start_date, hol_end_date  FROM holidays";
        $logStatusQuery3 = $conn->query($sql3);
        $holidaysData = [];
        if ($logStatusQuery3->num_rows > 0) {
            while ($row3 = $logStatusQuery3->fetch_assoc()) {
                // $holidaysData['start_date'][] = date("d-m-Y", strtotime($row3['hol_start_date']));
                // $holidaysData['end_date'][] = date("d-m-Y", strtotime($row3['hol_end_date']));

                foreach ($userData as $username => $info) {
                    $userData[$username]['leave_start_date'][] = date("d-m-Y", strtotime($row3['hol_start_date']));
                    $userData[$username]['leave_end_date'][] = date("d-m-Y", strtotime($row3['hol_end_date']));
                }
            }
        }

?>
    <!-- <span class="weekly_fetch_html" id="weekly_fetch_html"> -->
    <table id="phptable" class="phptableclass display" style="width:200%; border:5px solid;">
        <thead>
            <tr>
                <th>S.N</th>
                <th>Employee Name</th>
                <?php
                foreach ($allDaysColArr as $index => $value) {
                    $day1 = strtotime($value);
                    $day2 = date("D", $day1);
                    echo "<th class='no-sort'>$value ($day2)</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $sr = 0;
        //    echo "<pre>";  print_r($userData); die();
        foreach ($userData as $k => $v) {
                $disabled = ($_SESSION['user_type'] != 1) ? 'disabled' : '';
                $disabled = ($_SESSION['user_id'] == $v['details']['user_id']) ? '' : $disabled;

                $sr++;
                echo '<tr >';
                echo "<td>$sr</td>";
                echo "<td>" . $v['details']['fname'] . " " . $v['details']['lname'] . "</td>";

                foreach ($allDaysColArr as $index => $value) {
                    $checked = '';

                    //leaves from leave tracker
                     if(isset($v['leave_start_date'])) {
                        foreach ($v['leave_start_date'] as $index => $leavestartdate) {
                            if($value >= $leavestartdate && $value <= $v['leave_end_date'][$index]) {
                                $checked = 'checked';
                            }
                        }
                     }

                     //holiday check
                    // $searchKey = array_search($value, $holidaysData['start_date']);
                    //  if($searchKey != '') {
                    //     if($value >= $holidaysData['start_date'][$searchKey] && $value <= $holidaysData['end_date'][$searchKey]) {
                    //         $checked = 'checked';
                    //     }

                    // }
                    
                    // echo '<pre>'; print_r($value); die();

                    //weekend check //- working days not fetched. directly 5 working days considered
                    $dt1 = strtotime($value);
                    $dt2 = date("l", $dt1);
                    $dt3 = strtolower($dt2);
                    if(($dt3 == "saturday" )|| ($dt3 == "sunday")) {
                        $checked = 'checked';
                    }

        
                    echo "<td>";
                        echo "<div class='form-check'>";
                            echo '<input class="form-check-input" type="checkbox"  '.$disabled.' id="gridCheck1"   '.$checked.'  onclick="updateLeave(\''.date('Y-m-d',strtotime($value)).'\', \''.$checked.'\')">';
                            echo "<label class='form-check-label' for='gridCheck1'>";

                            echo "</label>";
                        echo "</div>";
                    echo "</td>";
            
                    // echo "<td>$value</td>";
                }


                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    <!-- </span> -->
    <?php


    ?>






<?php } ?>