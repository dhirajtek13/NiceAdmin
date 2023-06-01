<?php

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

    //tickets to consider
    $extract_status_idArrImplode = implode(",", $extract_status_idArr);
    //no of tickets completed on datetime
    // echo "<pre>"; print_r($extract_status_idArrImplode); die(); //3,7

    // $allDaysColArr[0] = '2023-05-01';
    // $allDaysColArr[6] = '2023-05-14';
    /**
     * 2) Fetch tickets to consider as per the date range and final(last) ticket status in that date range
     */
    if ($projectSelected) {
        $sql12 = "WITH ranked_messages AS (
                    SELECT cs.type_name, tickets.plan_end_date, m.*, ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn, tickets.project_id FROM log_timing AS m 
                    LEFT JOIN tickets ON tickets.id = m.ticket_id
                    LEFT JOIN c_status_types AS cs ON cs.id = m.c_status
                    WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$allDaysColArr[0]' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$allDaysColArr[6]' 
                    AND tickets.project_id = $projectSelected
                ) 
                SELECT ticket_id,plan_end_date,dates,c_status,  type_name FROM ranked_messages WHERE rn = 1 AND c_status IN ($extract_status_idArrImplode)";
    } else {
        $sql12 = "WITH ranked_messages AS (
                    SELECT  cs.type_name, tickets.plan_end_date, m.*, ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn FROM log_timing AS m 
                    LEFT JOIN tickets ON tickets.id = m.ticket_id
                    LEFT JOIN c_status_types AS cs ON cs.id = m.c_status
                    WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$allDaysColArr[0]' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$allDaysColArr[6]' 
                ) 
                SELECT ticket_id,plan_end_date,dates, c_status,  type_name FROM ranked_messages WHERE rn = 1 AND c_status IN ($extract_status_idArrImplode)";
    }

    $logStatusQuery12 = $conn->query($sql12);
    $tickets_to_consider = [];
    $for_ODD = [];
    $odd_ticketsArr = [];
    if ($logStatusQuery12->num_rows > 0) {
        $total_tickets = $logStatusQuery12->num_rows; //total tickets //also used for ODD
        while ($row12 = $logStatusQuery12->fetch_assoc()) {        
            $tickets_to_consider[] = $row12['ticket_id'];//for OTD itself
            // if($value['plan_end_date'] >= $value['dates']){
                // $for_ODD[$row12['ticket_id']] = $row12;

                //for ODD. will be used in other file
                if(!isset($odd_metricsArr[$row12['type_name']])){
                    $odd_totalCounter = 0;
                    $odd_otdCounter = 0;
                }

                if($row12['plan_end_date'] >= $row12['dates']){
                    $odd_ticketsArr[$row12['ticket_id']] =  $row12;
                    $odd_otdCounter++;
                }
                $odd_totalCounter++;
                $odd_metricsArr[$row12['type_name']]['total'] = $odd_totalCounter; 
                $odd_metricsArr[$row12['type_name']]['otd'] = $odd_otdCounter; 

            // }
        }
    }

    // echo "<pre>"; print_r($tickets_to_consider); die();
    $kpi_success = false;
    $metricstext = '0 ticket';
    $kpi_calc = '0';
    $kpisArr = [];
    // $otd_target_value = '-';
    if(!empty($tickets_to_consider)) {
        // echo "<pre>"; print_r($tickets_to_consider); die(); // Array ( [0] => 1 [1] => 2)
        $extract_tickets_to_considerArrImplode = implode(",", $tickets_to_consider);
        
        /**
         * 3) get total log hrs in between that date range of the eligible tickets as per above queries result
        */
        $sql13 = "SELECT lh.ticket_id, lh.dates, SUM(lh.hrs) AS log_hrs_sum, tickets.planned_hrs, tickets.actual_hrs, cs.type_name 
                    FROM log_history AS lh 
                    LEFT JOIN tickets AS tickets ON tickets.id = lh.ticket_id 
                    LEFT JOIN c_status_types AS cs ON cs.id = lh.c_status 
                    WHERE DATE_FORMAT(lh.`dates`, '%Y-%m-%d') >= '$allDaysColArr[0]' 
                    AND DATE_FORMAT(lh.`dates`, '%Y-%m-%d') <= '$allDaysColArr[6]' 
                    AND lh.ticket_id IN ($extract_tickets_to_considerArrImplode) 
                    GROUP BY lh.ticket_id";


        $logStatusQuery13 = $conn->query($sql13);
        $user_ticketsArr = [];
        if ($logStatusQuery13->num_rows > 0) {
            //  $total_tickets = $logStatusQuery13->num_rows;
            
            while ($row13 = $logStatusQuery13->fetch_assoc()) {        
                if(!isset($metricsArr[$row13['type_name']])){
                    $totalCounter = 0;
                    $otdCounter = 0;
                }
                
                if ($row13['planned_hrs'] >= $row13['log_hrs_sum']) {
                    $user_ticketsArr[$row13['ticket_id']] =  $row13;
                    $otdCounter++;
                }
                $totalCounter++;
                $metricsArr[$row13['type_name']]['total'] = $totalCounter; 
                $metricsArr[$row13['type_name']]['otd'] = $otdCounter; 
                }
            }

           

        $metricstext = '';
        foreach ($metricsArr as $key => $value) {
            $metricstext .= $value['total']. ' <i>'.$key.'</i>, '. $value['otd'].' <i>'.$key.'</i> delivered on time. <br>';
        }
       
        //tickets plan hrs <= act hrs / tickets in code review count => kpi
        $kpi_calc =  round(count($user_ticketsArr) / $total_tickets * 100, 2);

    } 

    
    //fetch kpi configuration
    $sql3 = "SELECT kpi_name, target_operator, target_value, description FROM kpis WHERE kpi_name='OTD'";

    $logStatusQuery3 = $conn->query($sql3);
    
    if ($logStatusQuery3->num_rows > 0) {
        while ($row3 = $logStatusQuery3->fetch_assoc()) {
            $kpisArr[$row3['kpi_name']] = $row3;
        }
    }

    if(isset($kpisArr['OTD'])) {
        $otd_target_value = $kpisArr['OTD']['target_value'];
        switch ($kpisArr['OTD']['target_operator']) {
            case '>':
                if ($kpi_calc > $otd_target_value) {
                    $kpi_success = true;
                }
                break;
            case '<':
                if ($kpi_calc < $otd_target_value) {
                    $kpi_success = true;
                }
                break;
            case '>=':
                if ($kpi_calc >= $otd_target_value) {
                    $kpi_success = true;
                }
                break;
            case '<=':
                if ($kpi_calc <= $otd_target_value) {
                    $kpi_success = true;
                }
                break;
            case '==':
                if ($kpi_calc == $otd_target_value) {
                    $kpi_success = true;
                }
                break;
            
            default:
                $kpi_success = false;
                break;
        }

        $otd_target_value = $otd_target_value.'%';

    }

    if ( $kpi_success == true) {
        $kpi_success =  '<i class="bx bxs-check-square kpi_status_i"></i>';
    } else {
        $kpi_success = '<i class="bx bxs-x-circle kpi_status_i"></i>';
    }
   

   
    // $metrics = "$total_tickets ";
