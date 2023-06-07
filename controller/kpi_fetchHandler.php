<?php

require_once '../db/config.php';
require_once '../controller/customFunctions.php';


// require_once '../controller/otd_fetchHandler.php';//ODD


//get current user id
session_start();
$current_user_id =  $_SESSION['user_id'];

// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);

if ($jsonObj->request_type == 'fetch') {
    $startdate =  $jsonObj->startdate;
    $enddate = $jsonObj->enddate;
    $projectSelected = $jsonObj->projectselected;
    $config_actual_hrs = $jsonObj->actual_hrs;
    
    $allDaysColArr = x_week_range($startdate);

    /**
     * OTD Calculation
     */
    require_once '../controller/otd_fetchHandler.php'; //OTD

    /**
     * ODD
     */
    require_once '../controller/odd_fetchHandler.php'; //ODD


    /**
     * Resource Utilization
     */
    require_once '../controller/resource_utilization_fetchHandler.php'; //resource utilization


    /**
     * FTR
     */
     require_once '../controller/ftr_fetchHandler.php';//resource utilization

    /**
     * Productivity
     */
    require_once '../controller/productivity_fetchHandler.php';//productivity

    /**
     * Productivity
     */
    require_once '../controller/reassigned_fetchHandler.php';//productivity
    // $prod_kpi_status = false;



}
?>
<div class="card-body mt-4">
    <table id="kpiTable1" class="kpiTable1class display table table-striped " style="width:100%">
        <tr>
            <th>KPI Name</th>
            <th>Target</th>
            <th>Metrics</th>
            <th>% Achieved</th>
            <th>Status</th>
        </tr>

<?php 
//fetch KPIS from KPI Master
$sql_01 = "SELECT * FROM kpis";
$logStatusQuery01 = $conn->query($sql_01);
$kpis_data = [];
if ($logStatusQuery01->num_rows > 0) {
    while ($row01 = $logStatusQuery01->fetch_assoc()) {
        $kpis_data[] = $row01;
    }
}


foreach ($kpis_data as $key => $kpi) {
   
   
    echo "<tr>";
    $target_value = $kpi['shortname']."_target_value";
    $metricstext = $kpi['shortname']."_metricstext";
    $kpi_calc = $kpi['shortname']."_kpi_calc";
    $kpi_success = $kpi['shortname']."_kpi_success";
    echo "<td>".$kpi['kpi_name']."</td>";
    echo "<td>". $kpi['target_value']. "%</td>";
    echo "<td>".$$metricstext. "</td>";
    echo "<td>".$$kpi_calc. "</td>";
    echo "<td>".$$kpi_success. "</td>";
    echo "</tr>";

    // if($kpi['kpi_name'] == 'Reassigned Tickets') {

    //     echo "<pre>"; print_r($$kpi_calc); die();
    // }
}

?>

        <!-- <tr class="">
            <td>OTD</td>
            <td><?= $otd_target_value ?></td>
            <td><?= $metricstext ?></td>
            <td><?= $kpi_calc ?></td>
            <td><?= $kpi_success ?></td>
        </tr>
        <tr class="">
            <td>ODD</td>
            <td><?= $odd_target_value ?></td>
            <td><?= $odd_metricstext ?></td>
            <td><?= $odd_kpi_calc ?></td>
            <td><?= $odd_kpi_success ?></td>
        </tr>
        <tr>
            <td>Resource Utilization</td>
            <td><?= $ru_target_value ?></td>
            <td><?= $ru_metricstext ?></td>
            <td><?= $ru_kpi_calc ?></td>
            <td><?= $ru_kpi_success ?></td>
        </tr>
        <tr>
            <td>Quality (FTR)</td>
            <td><?= $ftr_target_value ?></td>
            <td><?= $ftr_metricstext ?></td>
            <td><?= $ftr_kpi_calc ?></td>
            <td><?= $ftr_kpi_success ?></td>
        </tr> -->

        <!-- <tr>
            <td>Productivity</td>
            <td><?= $prod_target_value ?></td>
            <td><?= $prod_metricstext ?></td>
            <td><?= $prod_kpi_calc ?></td>
            <td><?= $prod_kpi_success ?></td>
        </tr> -->
        
        
        <!-- <tr>
            <td>Productivity</td>
            <td><?= $prod_kpisArr['productivity']['target_value'] ?>%</td>
            <td><?= $prod_metricstext ?></td>
            <td><?= $productivityPerc ?>%</td>
            <td>
                <?php if ($prod_kpi_success == true) {
                    echo '<i class="bx bxs-check-square kpi_status_i"></i>';
                } else {
                    echo '<i class="bx bxs-x-circle kpi_status_i"></i>';
                }
                ?>
            </td>
        </tr> -->
    </table>
</div>