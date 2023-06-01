<?php

//Resource Utilization [( total hrs actual hrs * working day * members in project )if logged hrs of each day are there / 40hrs   220/240 log => 90% (compare with 80%). green if it greater target   ]
    $CODE_REVIEW_STATUS = 7;
    $WORKING_HRS = $config_actual_hrs;// from dashboard configuration
    $WORKING_DAY = 5;


    if ($projectSelected) {
        $sql31 = "SELECT tickets.id, planned_hrs, actual_hrs, project_id, c_status FROM `tickets`  
                   # WHERE c_status= $CODE_REVIEW_STATUS
                    WHERE project_id = $projectSelected
                    AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
                    
                    ";
    } else {
        $sql31 = "SELECT tickets.id, planned_hrs, actual_hrs, project_id, c_status FROM `tickets`  
                #WHERE c_status= $CODE_REVIEW_STATUS
                WHERE DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
                ";
    }

    $logStatusQuery31 = $conn->query($sql31);
    $res_utilArr=[];
    if ($logStatusQuery31->num_rows > 0) {
        $total_tickets = $logStatusQuery31->num_rows;
        $total_actual_hrs = 0;
        while ($row31 = $logStatusQuery31->fetch_assoc()) {
            $total_actual_hrs += $row31['actual_hrs'];
        }
        $res_utilArr['total_actual_hrs'] = $total_actual_hrs;
    }


    //members in projects 
    if ($projectSelected) {
        $sql33 = "SELECT COUNT(user_id) as project_members FROM `project_user_map` WHERE project_id = $projectSelected GROUP BY project_id";
    } else {
        $sql33 = "SELECT COUNT(user_id) as project_members FROM `project_user_map`  GROUP BY project_id  ";
    }

    $logStatusQuery33 = $conn->query($sql33);
    $res_utilArr=[];
    if ($logStatusQuery33->num_rows > 0) {
        $total_members = 0;
        while ($row33 = $logStatusQuery33->fetch_assoc()) {
            $total_members +=  $row33['project_members'];
        }
    }

    $actual_hrs_week = $total_actual_hrs * $WORKING_DAY * $total_members;
    $shouldbe_hrs_week = $WORKING_HRS * $WORKING_DAY * $total_members;
    $ru_kpi_calc = round((( $actual_hrs_week ) / ( $shouldbe_hrs_week ) ) * 100 , 2);

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
        $ru_metricstext .= $actual_hrs_week ." / ".$shouldbe_hrs_week." hours ($total_members members)";
        // $metricstext .= "160/160 hours";
    // }

    if ( $ru_kpi_success == true) {
        $ru_kpi_success =  '<i class="bx bxs-check-square kpi_status_i"></i>';
    } else {
        $ru_kpi_success = '<i class="bx bxs-x-circle kpi_status_i"></i>';
    }

    // echo "<pre>"; print_r($res_utilArr['total_actual_hrs']); die();

?>