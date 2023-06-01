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



//  /**
//  * 2) Fetch tickets to consider as per the date range and final(last) ticket status in that date range
//  below query already run in OTD
//  */
// if ($projectSelected) {
//     $sql22 = "WITH ranked_messages AS (
//                 SELECT m.*, ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn, tickets.project_id FROM log_timing AS m 
//                 LEFT JOIN tickets ON tickets.id = m.ticket_id
//                 WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$allDaysColArr[0]' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$allDaysColArr[6]' 
//                 AND m.c_status IN ($extract_status_idArrImplode)
//                 AND tickets.project_id = $projectSelected
//             ) 
//             SELECT ticket_id FROM ranked_messages WHERE rn = 1";
// } else {
//     $sql22 = "WITH ranked_messages AS (
//                 SELECT m.*, ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn FROM log_history AS m 
//                 WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$allDaysColArr[0]' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$allDaysColArr[6]' 
//                 AND m.c_status IN ($extract_status_idArrImplode)
//             ) 
//             SELECT ticket_id FROM ranked_messages WHERE rn = 1";
// }

// $logStatusQuery22 = $conn->query($sql22);
// $tickets_to_consider = [];
// if ($logStatusQuery22->num_rows > 0) {
//     $total_tickets = $logStatusQuery22->num_rows; //total tickets
//     while ($row22 = $logStatusQuery22->fetch_assoc()) {        
//         $tickets_to_consider[] = $row22['ticket_id'];
//     }
// }

//$tickets_to_consider
// echo "<pre>"; print_r($odd_metricsArr); die();


// if ($projectSelected) {
//     $odd_sql23 = "SELECT tickets.id, DATE_FORMAT(`plan_end_date`, '%Y-%m-%d') AS plan_end_date, DATE_FORMAT(`actual_end_date`, '%Y-%m-%d') AS actual_end_date, cs.type_name 
//                 FROM `tickets` 
//                 LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status
//                 WHERE c_status IN ( $odd_extract_status_idArrImplode )
//                 AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')
//                 AND project_id = $projectSelected ";
// } else {
//     $odd_sql23 = "SELECT tickets.id, DATE_FORMAT(`plan_end_date`, '%Y-%m-%d') AS plan_end_date, DATE_FORMAT(`actual_end_date`, '%Y-%m-%d') AS actual_end_date, cs.type_name 
//             FROM `tickets`
//             LEFT JOIN c_status_types AS cs ON cs.id = tickets.c_status
//             WHERE c_status IN ( $odd_extract_status_idArrImplode )
//             AND DATE_FORMAT(tickets.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND ( DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]' OR  DATE_FORMAT(tickets.`actual_end_date`, '%Y-%m-%d') = '0000-00-00')";
// }

$odd_kpi_success = false;
$odd_metricstext = '0 ticket';
$odd_kpi_calc = '0';
$odd_kpisArr = [];
// $otd_target_value = '-';
if (isset($odd_metricsArr)) {


    $odd_metricstext = '';
    foreach ($odd_metricsArr as $odd_key => $odd_value) {
        $odd_metricstext .= $odd_value['total'] . ' <i>' . $odd_key . '</i>, ' . $odd_value['otd'] . ' <i>' . $odd_key . '</i> delivered on time. <br>';
    }
    //  in given date range planned end date >= actual end date 
    // echo "<pre>"; print_r($odd_metricsArr); die();
    //tickets plan hrs <= act hrs / tickets in code review count => kpi
    $odd_kpi_calc = round(count($odd_ticketsArr) / $total_tickets * 100, 2);
}

//fetch kpi configuration
$odd_sql3 = "SELECT kpi_name, target_operator, target_value, description FROM kpis WHERE kpi_name='ODD'";

$odd_logStatusQuery3 = $conn->query($odd_sql3);
$odd_kpisArr = [];
if ($odd_logStatusQuery3->num_rows > 0) {
    while ($odd_row3 = $odd_logStatusQuery3->fetch_assoc()) {
        $odd_kpisArr[$odd_row3['kpi_name']] = $odd_row3;
    }
}

if (isset($kpisArr['OTD'])) {
    $odd_target_value = $odd_kpisArr['ODD']['target_value'];
    switch ($odd_kpisArr['ODD']['target_operator']) {
        case '>':
            if ($odd_kpi_calc > $odd_target_value) {
                $odd_kpi_success = true;
            }
            break;
        case '<':
            if ($odd_kpi_calc < $odd_target_value) {
                $odd_kpi_success = true;
            }
            break;
        case '>=':
            if ($odd_kpi_calc >= $odd_target_value) {
                $odd_kpi_success = true;
            }
            break;
        case '<=':
            if ($odd_kpi_calc <= $odd_target_value) {
                $odd_kpi_success = true;
            }
            break;
        case '==':
            if ($odd_kpi_calc == $odd_target_value) {
                $odd_kpi_success = true;
            }
            break;

        default:
            $odd_kpi_success = false;
            break;
    }

    $odd_target_value = $odd_target_value . '%';
}
if ($odd_kpi_success == true) {
    // $odd_kpi_success = true;
    //show green
    $odd_kpi_success =  '<i class="bx bxs-check-square kpi_status_i"></i>';
} else {
    $odd_kpi_success = '<i class="bx bxs-x-circle kpi_status_i"></i>';
}
