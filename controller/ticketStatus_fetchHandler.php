<?php

require_once '../db/config.php';
require_once '../controller/customFunctions.php';

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

    if ($projectSelected) {
        $sql2 = " SELECT  ts.type_name, COUNT(t.c_status) AS statusCount 
                   FROM tickets AS t
                   JOIN c_status_types AS ts ON ts.id=t.c_status
                   WHERE t.project_id = $projectSelected
                   AND t.created_at > $allDaysColArr[0] 
                   GROUP BY t.c_status";
    } else {
        //fetch all projects
        $sql2 = " SELECT  ts.type_name, COUNT(t.c_status) AS statusCount 
        FROM tickets AS t
        JOIN c_status_types AS ts ON ts.id=t.c_status
        WHERE t.created_at > $allDaysColArr[0] 
        GROUP BY t.c_status";
    }

    $logStatusQuery2 = $conn->query($sql2);
    $user_ticketsArr = [];
    if ($logStatusQuery2->num_rows > 0) {
        while ($row2 = $logStatusQuery2->fetch_assoc()) {
            $user_ticketsArr[$row2['type_name']] =  $row2;
        }
    }

?>
    <!-- <span class="ticket_status_fetch_html" id="ticket_status_fetch_html"> -->
        <table id="phptable2" class=" phptable2class display m-3" style="width:70%">
        <tr>
            <?php 
            foreach ($user_ticketsArr as $key => $value) {
                echo "<th>$key<th>";
            }
            ?>
        </tr>
        <tr>
            <?php 
            foreach ($user_ticketsArr as $key => $value) {
                echo "<td>".$value['statusCount']."<td>";
            }
            ?>
        </tr>
        </table>
    <!-- </span> -->
<?php } ?>