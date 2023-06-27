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
        $sql2 = " SELECT  tt.type_name, SUM(lh.hrs) AS total_hrs
                    FROM tickets AS t
                    LEFT JOIN log_history AS lh ON lh.ticket_id = t.id
                    LEFT JOIN ticket_types AS tt ON tt.id=t.type_id
                    WHERE t.project_id = $projectSelected 
                    AND DATE_FORMAT(lh.`dates`, '%Y-%m-%d') <= '$enddate' AND  DATE_FORMAT(lh.`dates`, '%Y-%m-%d') >= '$startdate'
                    GROUP BY t.type_id";
    } else {
        //fetch all projects
        $sql2 = " SELECT  tt.type_name, SUM(lh.hrs) AS total_hrs
                    FROM tickets AS t
                    LEFT JOIN log_history AS lh ON lh.ticket_id = t.id
                    LEFT JOIN ticket_types AS tt ON tt.id=t.type_id
                    WHERE  DATE_FORMAT(lh.`dates`, '%Y-%m-%d') <= '$enddate' AND  DATE_FORMAT(lh.`dates`, '%Y-%m-%d') >= '$startdate'
                    GROUP BY t.type_id";
    }

    $logStatusQuery2 = $conn->query($sql2);
    $user_ticketsArr = [];
    if ($logStatusQuery2->num_rows > 0) {
        while ($row2 = $logStatusQuery2->fetch_assoc()) {
            $user_ticketsArr[$row2['type_name']] =  $row2;
        }
    }

    // echo "<pre>"; print_r($sql2); die();
?>
    <!-- <span class="ticket_status_fetch_html" id="ticket_status_fetch_html"> -->
    <table id="phptable3" class=" phptable3class display m-3" style="width:70%">
        <tr>
            <th>Type Name</th>
            <th>Total Hrs</th>
        </tr>
        <?php
        foreach ($user_ticketsArr as $key => $value) {
            echo "<tr>";
            echo "<td>" . $key . "</td>";
            echo "<td>"; echo ($value['total_hrs'] != '') ? $value['total_hrs'] : '0'; echo  "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <!-- </span> -->
<?php } ?>