<?php

/**
 * Productivity: fetch all code review for that date range. planned  >= actual hrs + 10%  => KPI is green. 
 *	date range close 28-------------------110 / 100 
 *	 	
 *		10 tickets => totoal planned hrs 100 & actual 120 
 *		planned hrs - actual hrs => 100 -120 = -20 => 20/planned*100=> 80% productity yellow
 *		
 *		10 tickets => totoal pallned hrs 100 & actual 90 
 *		100-90 = 10 => 100% green
 * 
 */
$allDaysColArr[0] = '2023-06-01';
$allDaysColArr[6] = '2023-06-14';

//1) pick which status to choose 
//get status to consider 
if ($projectSelected) {
    $sql51 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                            FROM c_status_types 
                            INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                            WHERE configurations.name = 'prod_c_status_types'
                            AND project_id = $projectSelected";
} else {
    $sql51 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                            FROM c_status_types 
                            INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                            WHERE configurations.name = 'prod_c_status_types'";
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

// echo "<pre>"; print_r($extract_prod_status_idArrImplode); die();

/**
 * 2) Fetch tickets to consider as per the date range and final(last) ticket status in that date range
 */
if ($projectSelected) {
    $sql522 = "WITH ranked_messages AS (
                    SELECT cs.type_name, tickets.plan_end_date, m.*, ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn, tickets.project_id FROM log_timing AS m 
                    LEFT JOIN tickets ON tickets.id = m.ticket_id
                    LEFT JOIN c_status_types AS cs ON cs.id = m.c_status
                    WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$allDaysColArr[0]' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$allDaysColArr[6]' 
                    AND tickets.project_id = $projectSelected
                ) 
                SELECT ticket_id,plan_end_date,dates,c_status,  type_name FROM ranked_messages WHERE rn = 1 AND c_status IN ($extract_prod_status_idArrImplode)";
} else {
    $sql522 = "WITH ranked_messages AS (
                    SELECT  cs.type_name, tickets.plan_end_date, m.*, ROW_NUMBER() OVER (PARTITION BY ticket_id ORDER BY id DESC) AS rn FROM log_timing AS m 
                    LEFT JOIN tickets ON tickets.id = m.ticket_id
                    LEFT JOIN c_status_types AS cs ON cs.id = m.c_status
                    WHERE DATE_FORMAT(m.`dates`, '%Y-%m-%d') >= '$allDaysColArr[0]' AND DATE_FORMAT(m.`dates`, '%Y-%m-%d') <= '$allDaysColArr[6]' 
                ) 
                SELECT ticket_id,plan_end_date,dates, c_status,  type_name FROM ranked_messages WHERE rn = 1 AND c_status IN ($extract_prod_status_idArrImplode)";
}



$logStatusQuery522 = $conn->query($sql522);
$tickets_to_consider = []; $ticket_status_map = [];
if ($logStatusQuery522->num_rows > 0) {
    // $prod_total_tickets = $logStatusQuery522->num_rows;
    // $total_planned_hrs = 0;
    // $total_actual_hrs = 0;
    // $prod_metricsArr = [];
    while ($row522 = $logStatusQuery522->fetch_assoc()) {
        $tickets_to_consider[] = $row522['ticket_id'];
        $ticket_status_map[$row522['ticket_id']]['final_status'] = $row522['type_name'];
    }
}

$prod_metricstext = '0 ticket';
$prod_kpi_success = false;
$prod_kpi_calc = '0';
$prod_kpisArr = [];
if (!empty($tickets_to_consider)) {
    $extract_tickets_to_considerArrImplode = implode(",", $tickets_to_consider);
    /**
     * 3) get total log hrs in between that date range of the eligible tickets as per above queries result
     */

    $sql533 = "SELECT tickets.id, SUM(log_history.hrs) as log_hrs, planned_hrs, actual_hrs, project_id, log_history.c_status, cs.type_name 
        FROM `log_history` 
        LEFT JOIN tickets ON log_history.ticket_id = tickets.id 
        LEFT JOIN c_status_types AS cs ON cs.id = log_history.c_status 
        WHERE DATE_FORMAT(log_history.`dates`, '%Y-%m-%d') >= '$allDaysColArr[0]' AND DATE_FORMAT(log_history.`dates`, '%Y-%m-%d') <= '$allDaysColArr[6]' 
        AND log_history.ticket_id IN ($extract_tickets_to_considerArrImplode) 
        GROUP BY tickets.id
        
        ";

    // echo "<pre>"; print_r($sql533); die(); 


    $logStatusQuery533 = $conn->query($sql533);
    $productivityArr = [];
    if ($logStatusQuery533->num_rows > 0) {
        $prod_total_tickets = $logStatusQuery533->num_rows;
        $total_planned_hrs = 0;
        $total_actual_hrs = 0;
        $prod_metricsArr = [];
        while ($row533 = $logStatusQuery533->fetch_assoc()) {
            $row533['final_type_name'] = $ticket_status_map[$row533['id']]['final_status'];
            $t[] = $row533;
           
            // if (!isset($prod_metricsArr[$row533['final_type_name']][$row533['id']])) {
            //     // echo "<pre>111=>"; print_r($row533); die(); 
            //     $planner_hrs = $row533['planned_hrs'];
            //     $log_hrs = $row533['log_hrs'];
            //     $prod_metricsArr[$row533['final_type_name']][$row533['id']] = $row533;
            // } 
            // else {
            //     $planner_hrs += $row533['planned_hrs'];
            //     $log_hrs += $row533['log_hrs'];
            // }
            if (!isset($prod_metricsArr[$row533['final_type_name']])) {
                // $planner_hrs = 'planner_hrs'.'_'.str_replace(" ", "_",$row533['final_type_name']);
                // $log_hrs = 'log_hrs'.'_'.str_replace(" ", "_",$row533['final_type_name']);
                // $$planner_hrs =  $row533['planned_hrs'];
                // $$log_hrs =  $row533['log_hrs'];
                $prod_metricsArr[$row533['final_type_name']]['type_name_actual_hrs'] = $row533['log_hrs'];
                $prod_metricsArr[$row533['final_type_name']]['type_name_planned_hrs'] = $row533['planned_hrs'];
                
            } else {
                // echo "<pre>"; print_r($$log_hrs); die();
                $prod_metricsArr[$row533['final_type_name']]['type_name_actual_hrs'] += $row533['log_hrs'];
                $prod_metricsArr[$row533['final_type_name']]['type_name_planned_hrs'] += $row533['planned_hrs'];
                // $planner_hrs = 'planner_hrs'.'_'.str_replace(" ", "_",$row533['final_type_name']);
                // $log_hrs = 'log_hrs'.'_'.str_replace(" ", "_",$row533['final_type_name']);
                // $$planner_hrs +=  $row533['planned_hrs'];
                // $$log_hrs +=  $row533['log_hrs'];
                // $tt['else'][] = $row533;
            }

            
         //   $prod_metricsArr[$row533['final_type_name']]['type_name_planned_hrs'] =  $$planner_hrs;
            

            $total_planned_hrs += $row533['planned_hrs'];
            $total_actual_hrs += $row533['log_hrs'];
        }
    }

    $extrahrsleft = $total_planned_hrs - $total_actual_hrs;
    $prod_kpi_calc = round(($extrahrsleft / $total_planned_hrs) * 100, 2);
}

// echo "<pre>"; print_r($prod_metricsArr); die(); 


$prod_kpi_success = false;
if ($prod_kpi_calc >= -10) {
    $prod_kpi_success = true;
    $prod_kpi_calc = 100;
}


$prod_metricstext = '';
//143/101 (For Code Review) +29.37
// echo "<pre>";  print_r($prod_metricsArr); die();
foreach ($prod_metricsArr as $key => $value) {
    // $ticket_final_status = $ticket_status_map[$value['ticket_id']]['final_status'];
    $prod_metricstext .= $value['type_name_planned_hrs'] . ' / ' . $value['type_name_actual_hrs'] . ' (For ' . $key . '). <br>';
}


//3
//fetch kpi configuration
$sql53 = "SELECT kpi_name, target_operator, target_value, description FROM kpis WHERE kpi_name='Productivity'";

$logStatusQuery53 = $conn->query($sql53);
$prod_kpisArr = [];
if ($logStatusQuery53->num_rows > 0) {
    while ($rows53 = $logStatusQuery53->fetch_assoc()) {
        $prod_kpisArr[$rows53['kpi_name']] = $rows53;
    }
}

if(isset($prod_kpisArr['Productivity'])) {
    $prod_target_value = $prod_kpisArr['Productivity']['target_value'];
}

if ( $prod_kpi_success == true) {
    $prod_kpi_success =  '<i class="bx bxs-check-square kpi_status_i"></i>';
} else {
    $prod_kpi_success = '<i class="bx bxs-x-circle kpi_status_i"></i>';
}
