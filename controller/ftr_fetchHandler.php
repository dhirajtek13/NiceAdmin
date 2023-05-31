<?php


/**
 * [code review rework. actual 10% time me ho jana chahiye. then FTR is 100.  week 4 tickt review. 1 tikcet return. 100actual hrs. 10hrs i have completed. 4/4. otherwise if 11hr then 3/4. ]
 */

 $REWORK_STATUS = '10, 11, 12';


    if ($projectSelected) {
        $sql41 = "SELECT tickets.id, c_status, actual_hrs, planned_hrs, ROUND(((planned_hrs /10) + planned_hrs), 2) AS ten_percent_planned_hrs, cs.type_name FROM `tickets` 
                        LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status
                        WHERE c_status IN ($REWORK_STATUS)
                        #AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
                        AND project_id = $projectSelected";
    } else {
        $sql41 = "SELECT tickets.id, c_status, actual_hrs, planned_hrs, ROUND(((planned_hrs /10) + planned_hrs), 2) AS ten_percent_planned_hrs ,cs.type_name FROM `tickets` 
                    LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status
                    WHERE c_status IN ($REWORK_STATUS)
                    #AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
                    ";
    }

    $logStatusQuery41 = $conn->query($sql41);
    $ftr_ticketsArr = [];
    $extract_prod_status_idAr = [];
    if ($logStatusQuery41->num_rows > 0) {
        $total_tickets = $logStatusQuery41->num_rows;
        while ($row41 = $logStatusQuery41->fetch_assoc()) {

            $extract_prod_status_idAr[$row41['id']] =  $row41;//all data

            if(!isset($ftr_metricsArr[$row41['type_name']])){
                $totalCounter = 0;
                $ftrCounter = 0;
            }
            if($row41['actual_hrs'] <= $row41['ten_percent_planned_hrs']){
                $ftrCounter++;
                $ftr_ticketsArr[$row41['id']] =  $row41; //eligible combine(all) ftr
            }
            $totalCounter++;
            $ftr_metricsArr[$row41['type_name']]['ftrCounter'] = $ftrCounter; //eligible individual ftr
            $ftr_metricsArr[$row41['type_name']]['total'] = $totalCounter; //all individual ftr

           // $extract_prod_status_idArr[] = $row41['type_name'];
            // $extract_prod_status_idAr[$row41['type_name']][] = $row41;
        }
    }

     $ftrPerc =  round((count($ftr_ticketsArr) / $total_tickets )* 100  ,2 );


    // $extrahrsleft = $total_planned_hrs - $total_actual_hrs;
    // $productivityPerc = round(($extrahrsleft / $total_planned_hrs) * 100, 2) ;
    // print_r((count($ftr_ticketsArr) / $total_tickets )* 100); echo "<br>";die();
    // // print_r($total_actual_hrs);  echo "<br>";
    // // print_r($productivityPerc);  echo "<br>";
    // $prod_kpi_success = false;
   


    $ftr_metricstext = '';
    foreach ($ftr_metricsArr as $key => $value) {
        $ftr_metricstext .= $value['total']." '".$key."' ticket, ".$value['ftrCounter']." delivered on time. <br>";
        // $metricstext .= $value['total']. ' <i>'.$key.'</i>, '. $value['otd'].' <i>'.$key.'</i> delivered on time. <br>';
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

    $ftr_kpi_success = false;
    if($ftrPerc >= $ftr_kpisArr['FTR']['target_value'] ) {
        $ftr_kpi_success = true;
        $ftrPerc = 100;
    }

//  echo "<pre>"; print_r($ftr_kpisArr); echo "<hr>"; die();

?>