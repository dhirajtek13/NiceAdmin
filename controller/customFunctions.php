<?php
              //function to get week range
              function x_week_range($date) {
                $ts = strtotime($date);
                $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);

                return array(date('Y-m-d', $start), 
                              date('Y-m-d', strtotime('next monday', $start)),
                              date('Y-m-d', strtotime('next tuesday', $start)),
                              date('Y-m-d', strtotime('next wednesday', $start)),
                              date('Y-m-d', strtotime('next thursday', $start)),
                              date('Y-m-d', strtotime('next friday', $start)),
                              date('Y-m-d', strtotime('next saturday', $start)));
              }

              function dates_month($month, $year) {
                $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $dates_month = array();
            
                for ($i = 1; $i <= $num; $i++) {
                    $mktime = mktime(0, 0, 0, $month, $i, $year);
                    $date = date("d-m-Y", $mktime);
                    $dates_month[$i] = $date;
                }
            
                return $dates_month;
            }


              ?>