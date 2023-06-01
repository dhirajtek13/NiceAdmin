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

    $allDaysColArr = x_week_range($startdate);


    /**
     * OTD Calculation
     */
        require_once '../controller/otd_fetchHandler.php';//OTD

    /**
     * ODD
     */ 
        require_once '../controller/odd_fetchHandler.php';//ODD
    

    /**
     * Resource Utilization
    */ 

     //   require_once '../controller/resource_utilization_fetchHandler.php';//resource utilization
    
    
    /**
     * FTR
     */
  //  require_once '../controller/ftr_fetchHandler.php';//resource utilization
    
    /**
     * Productivity
     */
   // require_once '../controller/productivity_fetchHandler.php';//productivity
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
        <tr class="">
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
        <!--
        <tr>
            <td>Resource Utilization</td>
            <td><?= $ru_kpisArr['Resource Utilization']['target_value'] ?>%</td>
            <td><?= $ru_metricstext ?></td>
            <td><?= $ruPerc ?>%</td>
            <td>
                <?php if ($ru_kpi_success == true) {
                    echo '<i class="bx bxs-check-square kpi_status_i"></i>';
                } else {
                    echo '<i class="bx bxs-x-circle kpi_status_i"></i>';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Quality (FTR)</td>
            <td><?= $ftr_kpisArr['FTR']['target_value'] ?>%</td>
            <td><?= $ftr_metricstext ?></td>
            <td><?= $ftrPerc ?>%</td>
            <td>
                <?php if ($ftr_kpi_success == true) {
                    echo '<i class="bx bxs-check-square kpi_status_i"></i>';
                } else {
                    echo '<i class="bx bxs-x-circle kpi_status_i"></i>';
                }
                ?>
            </td>
        </tr>
        <tr>
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