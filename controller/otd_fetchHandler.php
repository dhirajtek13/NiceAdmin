<?php


    //get status to consider 
    if ($projectSelected) {
        $sql211 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                        FROM c_status_types 
                        INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                        WHERE configurations.name = 'kpi_c_status_types'
                        AND project_id = $projectSelected";
    } else {
        $sql211 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                        FROM c_status_types 
                        INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                        WHERE configurations.name = 'kpi_c_status_types'";
    }

    $logStatusQuery211 = $conn->query($sql211);
    $extract_status_idArr = [];
    $extract_status_idAr = [];
    if ($logStatusQuery211->num_rows > 0) {
        while ($row211 = $logStatusQuery211->fetch_assoc()) {
            $extract_status_idArr[] = $row211['status_consider'];
            $extract_status_idAr[] = $row211;
        }
    }

    // echo "<pre>"; print_r($extract_status_idAr); die();
    $extract_status_idArrImplode = implode(",", $extract_status_idArr);
    //no of tickets completed on datetime
    //TODO - can make single query from above and below query
    if ($projectSelected) {
        $sql2 = "SELECT tickets.id, planned_hrs, actual_hrs, project_id, c_status, cs.type_name FROM `tickets` 
                    LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status
                    #WHERE planned_hrs <= actual_hrs
                    WHERE c_status IN ( $extract_status_idArrImplode )
                    AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
                    AND project_id = $projectSelected ";
    } else {
        $sql2 = "SELECT tickets.id, planned_hrs, actual_hrs, project_id, c_status, cs.type_name FROM `tickets` 
                    LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status
                     WHERE c_status IN ( $extract_status_idArrImplode )
                     AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
                    #WHERE planned_hrs <= actual_hrs";
    }

    // echo "<pre>"; print_r($sql2); die();

    $logStatusQuery2 = $conn->query($sql2);
    $user_ticketsArr = [];
    if ($logStatusQuery2->num_rows > 0) {
        $total_tickets = $logStatusQuery2->num_rows;
        
        while ($row2 = $logStatusQuery2->fetch_assoc()) {        
            if(!isset($metricsArr[$row2['type_name']])){
                $totalCounter = 0;
                $otdCounter = 0;
            }
            
            if ($row2['planned_hrs'] >= $row2['actual_hrs']) {
                $user_ticketsArr[$row2['id']] =  $row2;
                $otdCounter++;
            }
            $totalCounter++;
            $metricsArr[$row2['type_name']]['total'] = $totalCounter; 
            $metricsArr[$row2['type_name']]['otd'] = $otdCounter; 
        }
    }

    $metricstext = '';
    foreach ($metricsArr as $key => $value) {
        $metricstext .= $value['total']. ' <i>'.$key.'</i>, '. $value['otd'].' <i>'.$key.'</i> delivered on time. <br>';
    }

    //tickets plan hrs <= act hrs / tickets in code review count => kpi
    $kpi_calc = count($user_ticketsArr) / $total_tickets * 100;
    // echo "<pre>"; print_r($metricsArr); die();


    //fetch kpi configuration
    $sql3 = "SELECT kpi_name, target_operator, target_value, description FROM kpis WHERE kpi_name='OTD'";

    $logStatusQuery3 = $conn->query($sql3);
    $kpisArr = [];
    if ($logStatusQuery3->num_rows > 0) {
        while ($row3 = $logStatusQuery3->fetch_assoc()) {
            $kpisArr[$row3['kpi_name']] = $row3;
        }
    }

    $kpi_success = false;
    $metrics = "$total_tickets "

?>