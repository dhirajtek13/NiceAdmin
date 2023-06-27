<?php

//Resource Utilization [( total hrs actual hrs * working day * members in project )if logged hrs of each day are there / 40hrs   220/240 log => 90% (compare with 80%). green if it greater target   ]
   
//fetch hrs to work a day
//fetch working days in week
//fetch members in project

$WORKING_HRS = $config_actual_hrs;// from dashboard configuration
$WORKING_DAY = $CONFIG_ALL['working_days']['value1'];

//check if we need start and end date as per date range or entire month
// $startdate = '2023-06-01';
// $enddate = '2023-06-29';

//get days without weekends////remove non-working days (weekends) in given range
$TOTAL_DAYS_IN_RANGE = daysWithoutWeekend($startdate, $enddate);

        //check if anyholiday comes in betwwen start and end date selected
        $sql1 = "SELECT * FROM holidays 
                    WHERE DATE_FORMAT(hol_start_date, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(hol_end_date, '%Y-%m-%d')  <= '$enddate'";
        $logStatusQuery1 = $conn->query($sql1);
        $holidays = [];
        if ($logStatusQuery1->num_rows > 0) {
            while ($row1 = $logStatusQuery1->fetch_assoc()) {
                //remove substract days from $TOTAL_DAYS_IN_RANGE
                //holiday should not be in weekend days
                $days_skipping_weekend_in_holiday = daysWithoutWeekend($row1['hol_start_date'], $row1['hol_end_date']);
                $TOTAL_DAYS_IN_RANGE = $TOTAL_DAYS_IN_RANGE - $days_skipping_weekend_in_holiday;
                // $holidays[] = $row1;
            }
        }

       

        //remove leaves of members in this project in given date range
        if ($projectSelected) {
            $sql2 = "SELECT * FROM leave_tracker  
                        LEFT JOIN project_user_map ON project_user_map.user_id=leave_tracker.user_id
                        WHERE DATE_FORMAT(leave_start_date, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(leave_end_date, '%Y-%m-%d')  <= '$enddate'
                        AND project_user_map.project_id=$projectSelected
                        ";
        } else {
            $sql2 = "SELECT * FROM leave_tracker 
                        WHERE DATE_FORMAT(leave_start_date, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(leave_end_date, '%Y-%m-%d')  <= '$enddate'";
        }
        $logStatusQuery2 = $conn->query($sql2);
        $leave_days = []; $leave_dayscount = 0;
        if ($logStatusQuery2->num_rows > 0) {
            while ($row2 = $logStatusQuery2->fetch_assoc()) {
                //remove substract days from $TOTAL_DAYS_IN_RANGE
                $days_skipping_weekend_in_holiday = daysWithoutWeekend($row2['leave_start_date'], $row2['leave_end_date']);
                $leave_dayscount += $days_skipping_weekend_in_holiday;
              //  $TOTAL_DAYS_IN_RANGE = $TOTAL_DAYS_IN_RANGE - $days_skipping_weekend_in_holiday;
                // $leave_days[] = $row2;
            }
        }
        // echo "<pre>"; print_r( $sql2 );die();  

$FINAL_TOTAL_DAYS_TO_COUNT = $TOTAL_DAYS_IN_RANGE;


        //get total members
        if ($projectSelected) {
            $sql3 = "SELECT * FROM users 
                        LEFT JOIN project_user_map ON project_user_map.user_id=users.id
                        WHERE project_user_map.project_id=$projectSelected
                        ";
        } else {
            $sql3 = "SELECT * FROM users ";
        }

            $logStatusQuery3 = $conn->query($sql3);
            $membersData = [];
            if ($logStatusQuery3->num_rows > 0) {
                $total_members = $logStatusQuery3->num_rows;
                $PM_count = 0;
                while ($row3 = $logStatusQuery3->fetch_assoc()) {
                    if($row3['user_type'] != 1) { //skipping PM
                        // $membersData[] = $row3;
                    } else {
                        $PM_count++;
                    }
                }
                $total_members = $total_members - $PM_count;
            }


//SHOULD BE HRS TO CONSIDER
$shouldbe_hrs_range = $WORKING_HRS * (( $FINAL_TOTAL_DAYS_TO_COUNT * $total_members) - $leave_dayscount);

// echo "<pre>"; print_r( ( $FINAL_TOTAL_DAYS_TO_COUNT * $total_members) - $leave_dayscount );die();

            //ACTUAL HRS LOGGED
            if ($projectSelected) {
                $sql4 = "SELECT SUM(hrs) AS logged_hrs FROM log_history 
                            LEFT JOIN project_user_map ON project_user_map.user_id=log_history.user_id
                            WHERE DATE_FORMAT(dates, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(dates, '%Y-%m-%d')  <= '$enddate'
                            AND project_user_map.project_id=$projectSelected
                            ";
            } else {
                $sql4 = "SELECT  SUM(hrs) AS logged_hrs FROM log_history 
                            WHERE DATE_FORMAT(dates, '%Y-%m-%d') >= '$startdate' AND DATE_FORMAT(dates, '%Y-%m-%d')  <= '$enddate' ";
            }
            $logStatusQuery4 = $conn->query($sql4);
            $logged_hrs = 0;
            if ($logStatusQuery4->num_rows > 0) {
                while ($row4 = $logStatusQuery4->fetch_assoc()) {
                    $logged_hrs += $row4['logged_hrs'];
                }
            }

//ACTUAL BE HRS TO CONSIDER
$actual_hrs_range = $logged_hrs;



    $ru_kpi_calc = round((( $actual_hrs_range ) / ( $shouldbe_hrs_range ) ) * 100 , 2);

    //fetch kpi configuration
    $sql32 = "SELECT kpi_name, target_operator, target_value, description FROM kpis WHERE kpi_name='Resource Utilization'";

    $logStatusQuery32 = $conn->query($sql32);
    $ru_kpisArr = [];
    if ($logStatusQuery32->num_rows > 0) {
        while ($row32 = $logStatusQuery32->fetch_assoc()) {
            $ru_kpisArr[$row32['kpi_name']] = $row32;
        }
    }

    $ru_kpi_success = false;
    $ru_target_value = $ru_kpisArr['Resource Utilization']['target_value'];
    if($ru_kpi_calc >= $ru_target_value ) {
        $ru_kpi_success = true;
        $ru_kpi_calc = 100;
    }

    $ru_target_value =  $ru_target_value.'%';
    $ru_metricstext = '';
    // foreach ($ftr_metricsArr as $key => $value) {
        $ru_metricstext .= $actual_hrs_range ." / ".$shouldbe_hrs_range." hours ($total_members members)";
        // $metricstext .= "160/160 hours";
    // }

    if ( $ru_kpi_success == true) {
        $ru_kpi_success =  '<i class="bx bxs-check-square kpi_status_i"></i>';
    } else {
        $ru_kpi_success = '<i class="bx bxs-x-circle kpi_status_i"></i>';
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