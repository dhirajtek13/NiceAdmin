<?php

    //1) pick which status to choose 
       //get status to consider 
        if ($projectSelected) {
            $sql51 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                            FROM c_status_types 
                            INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                            WHERE configurations.name = 'kpi_c_status_types'
                            AND project_id = $projectSelected";
        } else {
            $sql51 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                            FROM c_status_types 
                            INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                            WHERE configurations.name = 'kpi_c_status_types'";
        }

        $logStatusQuery51 = $conn->query($sql51);
        $extract_prod_status_idArr = [];
        $extract_prod_status_idAr = [];
        if ($logStatusQuery51->num_rows > 0) {
            while ($row51 = $logStatusQuery51->fetch_assoc()) {
                $extract_prod_status_idArr[] = $row51['status_consider'];
                $extract_prod_status_idAr[] = $row51;
            }
        }

        $extract_prod_status_idArrImplode = implode(",", $extract_prod_status_idArr);

    /**
     *      10 tickets => total planned hrs 100 & actual 120 
	*		planned hrs - actual hrs => 100 -120 = -20 => 20/planned*100=> 80% productity yellow
    *
	*		10 tickets => total pallned hrs 100 & actual 90 
	*		100-90 = 10 => 100% green
     */
    if ($projectSelected) {
        $sql52 = "SELECT tickets.id, planned_hrs, actual_hrs, project_id, c_status, c_status, cs.type_name FROM `tickets`  
                    LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status
                    WHERE c_status IN ( $extract_prod_status_idArrImplode )
                    AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
                    AND project_id = $projectSelected
                    ";
    } else {
        $sql52 = "SELECT tickets.id, planned_hrs, actual_hrs, project_id, c_status, c_status, cs.type_name FROM `tickets` 
                LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status 
                WHERE c_status IN ( $extract_prod_status_idArrImplode )
                AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
                
                ";
    }

    // print_r($sql52); die();
    $logStatusQuery52 = $conn->query($sql52);
    $productivityArr=[];
    if ($logStatusQuery52->num_rows > 0) {
        $prod_total_tickets = $logStatusQuery52->num_rows;
        $total_planned_hrs = 0;
        $total_actual_hrs = 0;
        $prod_metricsArr = [];
        while ($row52 = $logStatusQuery52->fetch_assoc()) {
           

            if(!isset($prod_metricsArr[$row52['type_name']])){
                $type_name_planned_hrs = $row52['planned_hrs'];
                $type_name_actual_hrs = $row52['actual_hrs'];
                $prod_metricsArr[$row52['type_name']]['type_name_planned_hrs'] = $row52['planned_hrs']; 
                $prod_metricsArr[$row52['type_name']]['type_name_actual_hrs'] = $row52['actual_hrs']; 

            } else {
                $prod_metricsArr[$row52['type_name']]['type_name_planned_hrs'] += $row52['planned_hrs']; 
                $prod_metricsArr[$row52['type_name']]['type_name_actual_hrs'] += $row52['actual_hrs']; 
                // echo "<pre>";  print_r($prod_metricsArr); die();
            }
                $productivityArr[] = $row52;
                $total_planned_hrs += $row52['planned_hrs'];
                $total_actual_hrs += $row52['actual_hrs'];

        }
    }
    
    //palnned - actual 
    $extrahrsleft = $total_planned_hrs - $total_actual_hrs;
    $productivityPerc = round(($extrahrsleft / $total_planned_hrs) * 100, 2) ;
    // print_r($total_planned_hrs); echo "<br>";
    // print_r($total_actual_hrs);  echo "<br>";
    // print_r($productivityPerc);  echo "<br>";
    $prod_kpi_success = false;
    if($productivityPerc >= -10 ) {
        $prod_kpi_success = true;
        $productivityPerc = 100;
    }


    $prod_metricstext = '';
    //143/101 (For Code Review) +29.37
    foreach ($prod_metricsArr as $key => $value) {
        $prod_metricstext .= $value['type_name_planned_hrs']. ' / '.$value['type_name_actual_hrs'].' (For '.$key.'). <br>';
    }
    // echo "<pre>";  print_r($prod_metricstext); die();

    //3
     //fetch kpi configuration
     $sql53 = "SELECT kpi_name, target_operator, target_value, description FROM kpis WHERE kpi_name='productivity'";

     $logStatusQuery53 = $conn->query($sql53);
     $prod_kpisArr = [];
     if ($logStatusQuery53->num_rows > 0) {
         while ($rows53 = $logStatusQuery53->fetch_assoc()) {
             $prod_kpisArr[$rows53['kpi_name']] = $rows53;
         }
     }
 
    //  $prod_kpi_success = false;
    //  $prod_metricstext = "$total_tickets "


?>