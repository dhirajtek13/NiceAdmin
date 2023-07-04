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

    $sql2 = "SELECT u.id as uid, u.username, CONCAT(u.fname, ' ', u.lname) AS fullname, u.user_type, u.user_status,
            l.id AS leave_id, l.leave_start_date, l.leave_end_date, l.leave_days, l.leave_apply_date, l.leave_status, l.leave_desc, l.leave_type, l.day_type
            FROM users AS u
            LEFT JOIN leave_tracker AS l ON l.user_id = u.id";

    $logStatusQuery2 = $conn->query($sql2);
    $userData = [];
    if ($logStatusQuery2->num_rows > 0) {
        while ($row2 = $logStatusQuery2->fetch_assoc()) {
            // $user_ticketsArr[$row2['fullname']] =  $row2['user_tickets'];
            // $userData[$row2['username']][] = $row2;
            $userData[$row2['username']]['details'] = $row2;
            if ($row2['leave_start_date']) {
                $userData[$row2['username']]['leave_start_date']['l'][] = date("d-m-Y", strtotime($row2['leave_start_date']));
                $userData[$row2['username']]['leave_end_date']['l'][] =  date("d-m-Y", strtotime($row2['leave_end_date']));
                $userData[$row2['username']]['leave_start_date']['leave_id'][] =  $row2['leave_id'];
                $userData[$row2['username']]['leave_end_date']['leave_id'][] =  $row2['leave_id'];
                $userData[$row2['username']]['leave_desc'][] =  $row2['leave_desc'];
                $userData[$row2['username']]['leave_apply_date'][] =  $row2['leave_apply_date'];
                $userData[$row2['username']]['leave_status'][] =  $row2['leave_status'];
                $userData[$row2['username']]['leave_type'][] =  $row2['leave_type'];
                $userData[$row2['username']]['day_type'][] =  $row2['day_type'];
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
                    $userData[$username]['leave_start_date']['h'][] = date("d-m-Y", strtotime($row3['hol_start_date']));
                    $userData[$username]['leave_end_date']['h'][] = date("d-m-Y", strtotime($row3['hol_end_date']));
                }
            }
        }

?>
    <!-- <span class="weekly_fetch_html" id="weekly_fetch_html"> -->
    <table id="phptable" class="phptableclass display" style="width:225%;">
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
        <tbody >
            <?php
            $sr = 0;
            // echo "<pre>"; print_r($userData); die();
            foreach ($userData as $k => $v) {
                //    echo "<pre>";  print_r($v['details']['uid']); die();
                $disabled = ($_SESSION['user_type'] != 1) ? 'disabled' : '';
                $disabled = ($_SESSION['user_id'] == $v['details']['uid']) ? '' : $disabled;

                $userid = $v['details']['uid'];
                // $leave_id = '';
                // $leave_id = $v['details']['leave_id'];
                
                $sr++;
                echo '<tr >';
                echo "<td>$sr</td>";
                echo "<td>" . $v['details']['fullname'] . "</td>";

                foreach ($allDaysColArr as $index => $value) {
                    $checked = '';

                    $checked_bgcolor = 'background-color:#69e869';
                    $checked_color = 'border-color:green';

                    //leaves from leave tracker
                    $leave_id = '';$leave_data =[];$leave_data_encode = json_encode( $leave_data );
                     if(isset($v['leave_start_date']['l'])) {
                        foreach ($v['leave_start_date']['l'] as $indexl => $leavestartdatel) {
                            if(strtotime($value) >= strtotime($leavestartdatel) && strtotime($value) <= strtotime($v['leave_end_date']['l'][$indexl])) {


                                $checked_bgcolor = 'background-color:red';
                                $checked_color = 'border-color:brown';
                                $checked = 'checked';
                                $leave_id = $v['leave_end_date']['leave_id'][$indexl];

                                // echo "<pre>";  print_r($v['leave_end_date']['l'][$indexl]); 
                                // echo "<br>"; print_r($value);
                                // die();

                                $leave_data = [
                                    'leave_id' => $leave_id,
                                    'leave_start_date' => date('Y-m-d', strtotime($v['leave_start_date']['l'][$indexl])),
                                    'leave_end_date' => date('Y-m-d', strtotime($v['leave_end_date']['l'][$indexl])),
                                    'leave_leave_desc' => $v['leave_desc'][$indexl],
                                    'leave_leave_type' => $v['leave_type'][$indexl],
                                    'leave_day_type' => $v['day_type'][$indexl],
                                ];
                                $leave_data_encode = json_encode( $leave_data );
                            }
                        }
                     }

                     //holiday check
                     $holiday = false;
                     if(isset($v['leave_start_date']['h'])) {
                        foreach ($v['leave_start_date']['h'] as $indexh => $leavestartdateh) {
                            if(strtotime($value) >= strtotime($leavestartdateh) && strtotime($value) <= strtotime($v['leave_end_date']['h'][$indexh])) {
                                // $checked_bgcolor = 'background-color:purple';
                                // $checked_color = 'border-color:blue';
                                // $checked = 'checked';
                                $holiday = true;
                            }
                        }
                     }
                    
                    // echo '<pre>'; print_r($value); die();

                    //weekend check //- working days not fetched. directly 5 working days considered
                    $dt1 = strtotime($value);
                    $dt2 = date("l", $dt1);
                    $dt3 = strtolower($dt2);
                    // $disabled = '';
                    
                    if(($dt3 == "saturday" )|| ($dt3 == "sunday")) {
                        $checked = 'checked';
                        // $disabled  = 'disabled';
                        $checked_bgcolor = 'background-color:#f69a10';
                        $checked_color = 'border-color:red';

                        echo "<td>";
                            echo '<div style="" class="form-check">';
                                echo "<b style='color:orange'>H<b>";
                            echo "</div>";
                        echo "</td>";
                    } else {

                        if($holiday === true) {
                            echo "<td>";
                                echo '<div style="" class="form-check">';
                                    echo "<b style='color:orange'>H<b>";
                                echo "</div>";
                            echo "</td>";

                        } else {
                            echo "<td>";
                                echo "<div class='form-check'>";
                                    echo '<input dataleave=\''.$leave_data_encode.'\'  style="'.$checked_bgcolor.'; '.$checked_color.'"  class="form-check-input" type="checkbox"  '.$disabled.' id="gridCheck1"   '.$checked.'  
                                    onclick="updateLeave(\''.date('Y-m-d',strtotime($value)).'\', \''.$checked.'\', '.$userid.', \''.$leave_id.'\',  this )">';
                                    echo "<label class='form-check-label' for='gridCheck1'>";

                                    echo "</label>";
                                echo "</div>";
                            echo "</td>";
                        }
                    }
                }
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    <!-- </span> -->

<?php } ?>