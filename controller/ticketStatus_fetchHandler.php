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
        // $sql2 = " SELECT  ts.type_name, COUNT(t.c_status) AS statusCount, ts.id as status_id
        //            FROM tickets AS t
        //            JOIN c_status_types AS ts ON ts.id=t.c_status
        //            WHERE t.project_id = $projectSelected
        //            AND t.created_at > $allDaysColArr[0] 
        //            GROUP BY t.c_status";  
        $sql2 = "SELECT  cs.type_name AS status_name, name as extract_status_id, COUNT(t.id) AS total_ticketCount, t.created_at, t.actual_end_date
                    FROM c_status_types AS cs 
                                RIGHT JOIN (
                                                SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS name 
                                                FROM c_status_types 
                                                INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                                                WHERE configurations.name = 'ticket_status_c_status_types' 
                                            )  AS subs  ON cs.id= name 
                                    LEFT JOIN tickets as t ON t.c_status = name
                                    WHERE t.project_id = $projectSelected
                                    AND DATE_FORMAT(t.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND DATE_FORMAT(t.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]'
                                    GROUP BY cs.id";
    } else {
        //fetch all projects
        // $sql2 = "SELECT  cs.type_name AS status_name, name as extract_status_id, COUNT(t.id) AS total_ticketCount 
        //             FROM c_status_types AS cs 
        //             RIGHT JOIN (
        //                            SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS name 
        //                            FROM c_status_types 
        //                            INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
        //                            WHERE configurations.name = 'ticket_status_c_status_types' 
        //                        )  AS subs  ON cs.id= name 
        //                        LEFT JOIN tickets as t ON t.c_status = name
        //            # WHERE  t.created_at > $allDaysColArr[0] 
        //                   GROUP BY cs.id";
        $sql2 = "SELECT  cs.type_name AS status_name, name as extract_status_id, COUNT(t.id) AS total_ticketCount, t.created_at, t.actual_end_date
                    FROM c_status_types AS cs 
                                RIGHT JOIN (
                                                SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS name 
                                                FROM c_status_types 
                                                INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                                                WHERE configurations.name = 'ticket_status_c_status_types' 
                                            )  AS subs  ON cs.id= name 
                                    LEFT JOIN tickets as t ON t.c_status = name
                                    WHERE DATE_FORMAT(t.`created_at`, '%Y-%m-%d') <= '$allDaysColArr[6]' AND DATE_FORMAT(t.`actual_end_date`, '%Y-%m-%d') >= '$allDaysColArr[0]'
                                    GROUP BY cs.id"; 
    }
    

    $logStatusQuery2 = $conn->query($sql2);
    $user_ticketsArr = [];
    if ($logStatusQuery2->num_rows > 0) {
        while ($row2 = $logStatusQuery2->fetch_assoc()) {
            $user_ticketsArr[$row2['status_name']] =  $row2;
        }
    }

    // echo "<pre>"; print_r($user_ticketsArr); die();

    $sql3 = "SELECT cs.type_name AS status_name, extract_status_id 
                FROM c_status_types AS cs RIGHT JOIN 
                ( 
                    SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(configurations.value1, ',', c_status_types.id), ',', -1) AS extract_status_id 
                    FROM c_status_types 
                    INNER JOIN configurations ON CHAR_LENGTH(configurations.value1) - CHAR_LENGTH(REPLACE(configurations.value1, ',', ''))>=c_status_types.id-1 
                    WHERE configurations.name = 'ticket_status_c_status_types' 
                ) AS subs ON cs.id= extract_status_id";


            $logStatusQuery3 = $conn->query($sql3);
            $extract_status_idArr = [];
            if ($logStatusQuery3->num_rows > 0) {
                while ($row3 = $logStatusQuery3->fetch_assoc()) {
                    $extract_status_idArr[$row3['status_name']] = $row3;
                }
            }

// echo "<pre>"; print_r($extract_status_idArr); die();
?>
    <!-- <span class="ticket_status_fetch_html" id="ticket_status_fetch_html"> -->
        <table id="phptable2" class=" phptable2class display m-3" style="width:70%">
            <tr>
                <?php 
                foreach ($extract_status_idArr as $key => $value) {
                    echo "<th>$key</th>";
                }
                ?>
            </tr>

            <tr>
                <?php 
                    foreach ($extract_status_idArr as $status_name => $value) {
                        if(array_key_exists($status_name, $user_ticketsArr)) {
                            echo "<td>".$user_ticketsArr[$status_name]['total_ticketCount']."</td>";
                        } else {
                            echo "<td>0</td>";
                        }
                    }
                ?>
            </tr>
        </table>
    <!-- </span> -->
<?php } ?>