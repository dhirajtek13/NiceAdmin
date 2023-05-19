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
        $sql2 = "SELECT COUNT(c_status_types.id) AS status_count, c_status_types.type_name 
                    FROM c_status_types 
                    JOIN (
                            SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS name 
                            FROM c_status_types 
                            INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                            WHERE configurations.name = 'ticket_status_c_status_types' 
                        ) AS subs 
                    ON id= name 
                    LEFT JOIN tickets AS t ON t.c_status=c_status_types.id 
                    #WHERE  t.created_at > $allDaysColArr[0] AND t.project_id = $projectSelected
                    GROUP BY c_status_types.id";
    } else {
        //fetch all projects
        $sql2 = "SELECT COUNT(c_status_types.id) AS status_count, c_status_types.type_name 
                    FROM c_status_types 
                    JOIN (
                            SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS name 
                            FROM c_status_types 
                            INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                            WHERE configurations.name = 'ticket_status_c_status_types' 
                        ) AS subs 
                    ON id= name 
                    LEFT JOIN tickets AS t ON t.c_status=c_status_types.id 
                    #WHERE  t.created_at > $allDaysColArr[0] 
                    GROUP BY c_status_types.id";
    }

    $logStatusQuery2 = $conn->query($sql2);
    $user_ticketsArr = [];
    if ($logStatusQuery2->num_rows > 0) {
        while ($row2 = $logStatusQuery2->fetch_assoc()) {
            $user_ticketsArr[$row2['type_name']] =  $row2;
        }
    }

    echo "<pre>"; print_r($user_ticketsArr); die();

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