<?php


/**
 * [code review rework. actual 10% time me ho jana chahiye. then FTR is 100.  week 4 tickt review. 1 tikcet return. 100actual hrs. 10hrs i have completed. 4/4. otherwise if 11hr then 3/4. ]
 */

//  $REWORK_STATUS = '10, 11, 12';
// $startdate = '2023-05-01';
// $enddate = '2023-06-10';

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
    // echo "<pre>"; print_r($reworkStatus_FTR_Arr); echo "<hr>"; die();
    
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
    

    $ftr_kpi_success = false;
    $ftr_metricstext = '0 ticket';
    $ftr_kpi_calc = '0';
    $ftr_kpisArr = [];
    if(!empty($tickets_to_consider)) {
        $tickets_to_consider = array_unique($tickets_to_consider);
        $extract_tickets_to_considerArrImplode = implode(',', $tickets_to_consider);
        // echo "<pre>"; print_r($extract_tickets_to_considerArrImplode); die();

        /**
         * 3) get total log hrs in between that date range of the eligible tickets as per above queries result
        */
        $sql43 = "SELECT lh.id, lh.ticket_id, lh.dates,  lh.hrs, tickets.planned_hrs, ROUND(((planned_hrs /10) + planned_hrs), 2) AS ten_percent_planned_hrs, tickets.actual_hrs, cs.type_name,  cs.id as c_status_id 
                    FROM log_history AS lh 
                    LEFT JOIN tickets AS tickets ON tickets.id = lh.ticket_id 
                    LEFT JOIN c_status_types AS cs ON cs.id = lh.c_status 
                    WHERE DATE_FORMAT(lh.`dates`, '%Y-%m-%d') >= '$startdate' 
                    AND DATE_FORMAT(lh.`dates`, '%Y-%m-%d') <= '$enddate' 
                    AND lh.ticket_id IN ($extract_tickets_to_considerArrImplode)";

// echo "<pre>"; print_r($sql43); die();  //not using group as of now because of       

        $logStatusQuery43 = $conn->query($sql43);
        $total_tickets = 0;

        if ($logStatusQuery43->num_rows > 0) {
            while ($row43 = $logStatusQuery43->fetch_assoc()) {

                // echo "<pre>"; print_r($row43); die();
                // if(!isset($ftr_Arr1[$row43['type_name']][$row43['ticket_id']])){
                //     $sum_actual_hrs = 0;
                // } else {
                   
                // }
                // $sum_actual_hrs += $row43['hrs'];
                // $ftr_Arr1[$row43['type_name']][$row43['ticket_id']] = $sum_actual_hrs;


                if(!isset($ftr_Arr[$row43['ticket_id']])){
                    $sum_actual_hrs = 0;
                    $total_tickets++;

                } 
                $sum_actual_hrs += $row43['hrs'];
                $ftr_Arr[$row43['ticket_id']]['sum_actual_hrs'] = $sum_actual_hrs;
                $ftr_Arr[$row43['ticket_id']]['planned_hrs'] = $row43['planned_hrs'];
                $ftr_Arr[$row43['ticket_id']]['ten_percent_planned_hrs'] = $row43['ten_percent_planned_hrs'];
                $ftr_Arr[$row43['ticket_id']]['type_name'] = $row43['type_name'];
                $ftr_Arr[$row43['ticket_id']]['c_status_id'] = $row43['c_status_id'];
                
                // $ftr_Arr['total_tickets'] = $total_tickets;
            }

        }

       
        $allowedLastStatus = [3, 7, 9, 14, 15, 16, 17, 18, 19];//TODO - fetch from configuration
        $ftr_metricsArr=[];$ftrCounter = 0;
        $total_tickets = 0;
        foreach ($ftr_Arr as $key => $value) {
            //  if(in_array($value['c_status_id'], $allowedLastStatus)) {
                            // echo "<pre>"; print_r($value); die(); 

                if(!isset( $ftr_metricsArr[$value['type_name']])){
                    $ftr_metricsArr[$value['type_name']][] = $value;
                   // $ftrCounter = 0;
                }

                if($value['sum_actual_hrs'] <= $value['ten_percent_planned_hrs'] ){
                    $ftrCounter++;
                    $ftr_metricsArr[$value['type_name']]['ftrCounter'] = $ftrCounter;
                }
                $total_tickets++;
            //  }
        }
        // echo "<pre>"; print_r($ftrCounter); die(); 

        // 3 code review, 1 delivered on time
        // 1 wip, 0  delivered on time

        $metrixArr = [];
        //$total_all = 0;
        foreach ($ftr_Arr as $key => $value) {
            
            // echo "<pre>"; print_r($ftr_Arr); die();

            if(!isset($metrixArr[$value['type_name']]['total_all'])){
                $total_typename = 'total_all'.'_'.$value['c_status_id'];
                $total_typename_ontime = 'total_ontime'.'_'.$value['c_status_id'];
                $total_typename_ontime = 0;
                $total_typename = 1;
                $metrixArr[$value['type_name']]['total_all'] = $total_typename;
                $metrixArr[$value['type_name']]['total_ontime'] = $total_typename_ontime;
            } else {
                $total_typename++;
                $metrixArr[$value['type_name']]['total_all'] = $total_typename;
            }
            if($value['sum_actual_hrs'] <= $value['ten_percent_planned_hrs']){
                if(!isset($metrixArr[$value['type_name']]['total_ontime'])){
                    
                    $total_typename_ontime = 1;
                    $metrixArr[$value['type_name']]['total_ontime'] = $total_typename_ontime;
                } else {
                    $total_typename_ontime++;
                    $metrixArr[$value['type_name']]['total_ontime'] = $total_typename_ontime;

                }

            }

            // $metrixArr[$value['type_name']]['total_all'] = $total_all;
            // $metrixArr[$value['type_name']]['total_ontime'] = $total_ontime;
            
        }

        // echo "<pre>"; print_r($metrixArr); die();

        // $logStatusQuery43 = $conn->query($sql43);
        // $user_ticketsArr = [];
        // if ($logStatusQuery43->num_rows > 0) {
        //     $total_tickets = $logStatusQuery43->num_rows;
        //     while ($row43 = $logStatusQuery43->fetch_assoc()) {  
        //         if(!isset($ftr_metricsArr[$row43['type_name']])){
        //             $totalCounter = 0;
        //             $ftrCounter = 0;
        //             $sum_actual_hrs = 0;
        //         }
        //         // if(!isset($ftr_metricsArr[$row43['ticket_id']])){
        //         //     $ftr_metricsArr_actual_sum = $row43['hrs'];
        //         // }
        //         if($row43['log_hrs_sum'] <= $row43['ten_percent_planned_hrs']){
        //             $ftrCounter++;
        //             $sum_actual_hrs +=  $row43['hrs'];
        //             $ftr_ticketsArr[$row43['ticket_id']] =  $row43; //eligible combine(all) ftr
                    
        //         }
        //         $totalCounter++;
        //         $ftr_metricsArr[$row43['ticket_id']]['actual_hrs_sum'] =  $sum_actual_hrs;
        //         $ftr_metricsArr[$row43['type_name']]['ftrCounter'] = $ftrCounter; //eligible individual ftr
        //         $ftr_metricsArr[$row43['type_name']]['total'] = $totalCounter; //all individual ftr
        //     }
        // }


        

        $ftr_kpi_calc =  round(($ftrCounter / $total_tickets )* 100  ,2 );
        // echo "<pre>"; print_r($ftr_kpi_calc); die(); 

        $ftr_metricstext = '';
        foreach ($metrixArr as $key => $value) {
            $ftr_metricstext .= $value['total_all']." '".$key."' ticket, ".$value['total_ontime']." delivered on time. <br>";
            // $metricstext .= $value['total']. ' <i>'.$key.'</i>, '. $value['otd'].' <i>'.$key.'</i> delivered on time. <br>';
        }
    
    
        
    }
    //fetch kpi configuration
    $sql42 = "SELECT kpi_name, target_operator, target_value, description FROM kpis WHERE kpi_name='FTR'";
    
    $logStatusQuery42 = $conn->query($sql42);
    $ftr_kpisArr = [];
    if ($logStatusQuery42->num_rows > 0) {
        while ($row42 = $logStatusQuery42->fetch_assoc()) {
            $ftr_kpisArr[$row42['kpi_name']] = $row42;
        }
    }


    if(isset($ftr_kpisArr['FTR'])) {
        $ftr_target_value = $ftr_kpisArr['FTR']['target_value'];
    }
    if($ftr_kpi_calc >= $ftr_target_value ) {
        $ftr_kpi_success = true;
        $ftr_kpi_calc = 100;
    }
    
    $ftr_target_value = $ftr_target_value.'%';

    if ( $ftr_kpi_success == true) {
        $ftr_kpi_success =  '<i class="bx bxs-check-square kpi_status_i"></i>';
    } else {
        $ftr_kpi_success = '<i class="bx bxs-x-circle kpi_status_i"></i>';
    }

   


     





    // if ($projectSelected) {
    //     $sql41 = "SELECT tickets.id, c_status, actual_hrs, planned_hrs, ROUND(((planned_hrs /10) + planned_hrs), 2) AS ten_percent_planned_hrs, cs.type_name FROM `tickets` 
    //                     LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status
    //                     WHERE c_status IN ($reworkStatus_FTR)
    //                     #AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$enddate' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$startdate' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
    //                     AND project_id = $projectSelected";
    // } else {
    //     $sql41 = "SELECT tickets.id, c_status, actual_hrs, planned_hrs, ROUND(((planned_hrs /10) + planned_hrs), 2) AS ten_percent_planned_hrs ,cs.type_name FROM `tickets` 
    //                 LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status
    //                 WHERE c_status IN ($reworkStatus_FTR)
    //                 #AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$enddate' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$startdate' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
    //                 ";
    // }

    // $logStatusQuery41 = $conn->query($sql41);
    // $ftr_ticketsArr = [];
    // $extract_prod_status_idAr = [];
    // if ($logStatusQuery41->num_rows > 0) {
    //     $total_tickets = $logStatusQuery41->num_rows;
    //     while ($row41 = $logStatusQuery41->fetch_assoc()) {

    //         $extract_prod_status_idAr[$row41['id']] =  $row41;//all data

    //         if(!isset($ftr_metricsArr[$row41['type_name']])){
    //             $totalCounter = 0;
    //             $ftrCounter = 0;
    //         }
    //         if($row41['actual_hrs'] <= $row41['ten_percent_planned_hrs']){
    //             $ftrCounter++;
    //             $ftr_ticketsArr[$row41['id']] =  $row41; //eligible combine(all) ftr
    //         }
    //         $totalCounter++;
    //         $ftr_metricsArr[$row41['type_name']]['ftrCounter'] = $ftrCounter; //eligible individual ftr
    //         $ftr_metricsArr[$row41['type_name']]['total'] = $totalCounter; //all individual ftr

    //        // $extract_prod_status_idArr[] = $row41['type_name'];
    //         // $extract_prod_status_idAr[$row41['type_name']][] = $row41;
    //     }
    // }

     


    // $extrahrsleft = $total_planned_hrs - $total_actual_hrs;
    // $productivityPerc = round(($extrahrsleft / $total_planned_hrs) * 100, 2) ;
    // print_r((count($ftr_ticketsArr) / $total_tickets )* 100); echo "<br>";die();
    // // print_r($total_actual_hrs);  echo "<br>";
    // // print_r($productivityPerc);  echo "<br>";
    // $prod_kpi_success = false;
   


   
    // if($ftrPerc >= $ftr_kpisArr['FTR']['target_value'] ) {
    //     $ftr_kpi_success = true;
    //     $ftrPerc = 100;
    // }

//  echo "<pre>"; print_r($ftr_kpisArr); echo "<hr>"; die();

?>