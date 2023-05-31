<?php


     //get status to consider 
     if ($projectSelected) {
        $odd_sql211 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                        FROM c_status_types 
                        INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                        WHERE configurations.name = 'kpi_c_status_types'
                        AND project_id = $projectSelected";
    } else {
        $odd_sql211 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                        FROM c_status_types 
                        INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                        WHERE configurations.name = 'kpi_c_status_types'";
    }

    $odd_logStatusQuery211 = $conn->query($odd_sql211);
    $odd_extract_status_idArr = [];
    $odd_extract_status_idAr = [];
    if ($odd_logStatusQuery211->num_rows > 0) {
        while ($odd_row211 = $odd_logStatusQuery211->fetch_assoc()) {
            $odd_extract_status_idArr[] = $odd_row211['status_consider'];
            $odd_extract_status_idAr[] = $odd_row211;
        }
    }

    // echo "<pre>"; print_r($odd_extract_status_idAr); die();
    $odd_extract_status_idArrImplode = implode(",", $odd_extract_status_idArr);


    if ($projectSelected) {
        $odd_sql = "SELECT tickets.id, DATE_FORMAT(`plan_end_date`, '%Y-%m-%d') AS plan_end_date, DATE_FORMAT(`actual_end_date`, '%Y-%m-%d') AS actual_end_date, cs.type_name 
                    FROM `tickets` 
                    LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status
                    WHERE c_status IN ( $odd_extract_status_idArrImplode )
                    AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
                    AND project_id = $projectSelected ";
    } else {
        $odd_sql = "SELECT tickets.id, DATE_FORMAT(`plan_end_date`, '%Y-%m-%d') AS plan_end_date, DATE_FORMAT(`actual_end_date`, '%Y-%m-%d') AS actual_end_date, cs.type_name 
                FROM `tickets`
                LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status
                WHERE c_status IN ( $odd_extract_status_idArrImplode )
                AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')";
    }

    $odd_logStatusQuery1 = $conn->query($odd_sql);
    $odd_ticketsArr = [];
    if ($odd_logStatusQuery1->num_rows > 0) {
        $odd_total_tickets = $odd_logStatusQuery1->num_rows;
        while ($odd_row2 = $odd_logStatusQuery1->fetch_assoc()) {
            if(!isset($odd_metricsArr[$odd_row2['type_name']])){
                $odd_totalCounter = 0;
                $odd_otdCounter = 0;
            }

            if ($odd_row2['plan_end_date'] >= $odd_row2['actual_end_date']) {
                $odd_ticketsArr[$odd_row2['id']] =  $odd_row2;
                $odd_otdCounter++;
            }
            $odd_totalCounter++;
            $odd_metricsArr[$odd_row2['type_name']]['total'] = $odd_totalCounter; 
            $odd_metricsArr[$odd_row2['type_name']]['otd'] = $odd_otdCounter; 
        }
    }
    
    $odd_metricstext = '';
    foreach ($odd_metricsArr as $odd_key => $odd_value) {
        $odd_metricstext .= $odd_value['total']. ' <i>'.$odd_key.'</i>, '. $odd_value['otd'].' <i>'.$odd_key.'</i> delivered on time. <br>';
    }
    //  in given date range planned end date >= actual end date 
    // echo "<pre>"; print_r($odd_metricsArr); die();
    //tickets plan hrs <= act hrs / tickets in code review count => kpi
    $odd_kpi_calc = count($odd_ticketsArr) / $odd_total_tickets * 100;
    

     //fetch kpi configuration
     $odd_sql3 = "SELECT kpi_name, target_operator, target_value, description FROM kpis WHERE kpi_name='ODD'";

     $odd_logStatusQuery3 = $conn->query($odd_sql3);
     $odd_kpisArr = [];
     if ($odd_logStatusQuery3->num_rows > 0) {
         while ($odd_row3 = $odd_logStatusQuery3->fetch_assoc()) {
             $odd_kpisArr[$odd_row3['kpi_name']] = $odd_row3;
         }
     }
 
     $odd_kpi_success = false;
?>
    <!-- <table id="kpiTable2" class=" display m-3" style="width:70%">
        <tr class="kpiTable1class">
            <td>ODD</td>
            <td><?= $odd_kpisArr['ODD']['target_value'] ?>%</td>
            <td><?= $odd_metricstext ?></td>
            <td><?= round($odd_kpi_calc, 2) ?>%</td>
            <td>
                <?php
                switch ($odd_kpisArr['ODD']['target_operator']) {
                    case '>':
                        if ($odd_kpi_calc > $odd_kpisArr['ODD']['target_value']) {
                            $odd_kpi_success = true;
                        }
                        break;
                    case '<':
                        if ($odd_kpi_calc < $odd_kpisArr['ODD']['target_value']) {
                            $odd_kpi_success = true;
                        }
                        break;
                    case '>=':
                        if ($odd_kpi_calc >= $odd_kpisArr['ODD']['target_value']) {
                            $odd_kpi_success = true;
                        }
                        break;
                    case '<=':
                        if ($odd_kpi_calc <= $odd_kpisArr['ODD']['target_value']) {
                            $odd_kpi_success = true;
                        }
                        break;
                    case '==':
                        if ($odd_kpi_calc == $odd_kpisArr['ODD']['target_value']) {
                            $odd_kpi_success = true;
                        }
                        break;
                    
                    default:
                        $odd_kpi_success = false;
                        break;
                }
                if ($odd_kpi_success == true) {
                    // if ($odd_kpi_calc . $odd_kpisArr['ODD']['target_operator'] . $odd_kpisArr['ODD']['target_value']) {
                    // $odd_kpi_success = true;
                    //show green
                    echo '<i class="bx bxs-check-square kpi_status_i"></i>';
                } else {
                    echo '<i class="bx bxs-x-circle kpi_status_i"></i>';
                }
                ?>
            </td>
        </tr>
    </table> -->