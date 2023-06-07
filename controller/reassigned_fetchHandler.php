<?php



//get status to consider 
if ($projectSelected) {
    $sql51 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                            FROM c_status_types 
                            INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                            WHERE configurations.name = 'reassigned_c_status_types'
                            AND project_id = $projectSelected";
} else {
    $sql51 = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS status_consider, type_name
                            FROM c_status_types 
                            INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                            WHERE configurations.name = 'reassigned_c_status_types'";
}

$logStatusQuery51 = $conn->query($sql51);
$Reassigned_tickets = [];
// $extract_prod_status_idAr = [];
if ($logStatusQuery51->num_rows > 0) {
    while ($row51 = $logStatusQuery51->fetch_assoc()) {
        $Reassigned_tickets[] = $row51['status_consider'];
        // $extract_prod_status_idAr[] = $row51;
    }
}


$Reassigned_tickets_implode = implode(',', $Reassigned_tickets);
// print_r($Reassigned_tickets_implode); die();

$sql61 = "SELECT COUNT(c_status) AS reassigned_count_of_ticket FROM `log_timing` WHERE c_status IN ($Reassigned_tickets_implode) GROUP BY ticket_id";

$logStatusQuery61 = $conn->query($sql61);
$total_reassigned = 0;
if ($logStatusQuery61->num_rows > 0) {
    while ($row61 = $logStatusQuery61->fetch_assoc()) {
        $total_reassigned += $row61['reassigned_count_of_ticket'];
    }
}

$rt_kpi_calc = $total_reassigned;
$rt_metricstext = $total_reassigned.' tickets';

$sql63 = "SELECT kpi_name, target_operator, target_value, description FROM kpis WHERE kpi_name='Reassigned Tickets'";

$logStatusQuery63 = $conn->query($sql63);
$rt_kpisArr = [];
if ($logStatusQuery63->num_rows > 0) {
    while ($rows63 = $logStatusQuery63->fetch_assoc()) {
        $rt_kpisArr[$rows63['kpi_name']] = $rows63;
    }
}

if(isset($rt_kpisArr['Reassigned Tickets'])) {
    $rt_target_value = $rt_kpisArr['Reassigned Tickets']['target_value'];
}

$rt_kpi_success = '<i class="bx bxs-x-circle kpi_status_i"></i>';
if($rt_kpi_calc <= $rt_target_value) {
    $rt_kpi_success =  '<i class="bx bxs-check-square kpi_status_i"></i>';
}

?>