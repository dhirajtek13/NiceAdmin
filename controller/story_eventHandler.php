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

    $parent_id = $jsonObj->ticket_id;
    

    //holidays
    $sql3 = "SELECT id, ticket_id, actual_hrs  FROM tickets WHERE parent_id=$parent_id";
    $logStatusQuery3 = $conn->query($sql3);
    $userData = [];
    if ($logStatusQuery3->num_rows > 0) {
        while ($row3 = $logStatusQuery3->fetch_assoc()) {
            $userData[$row3['id']]['ticket_id'] =  $row3['ticket_id'];
            $userData[$row3['id']]['actual_hrs'] =  $row3['actual_hrs'];
        }
    }
    // print_r($userData); die();
?>

    <table id="phptable" class="table phptableclass2 display" style="width:225%;" >
         <thead>
            <tr>
                <td>S.N</td>
                <td>Activity Name</td>
                <td>Logged Hrs</td>
            </tr>
        </thead> 
        <tbody >
            <?php
            $sr = 0;
            // echo "<pre>"; print_r($userData); die();
            if(empty($userData)) {
                echo "<tr><td colspan='2'>No record found!</td></tr>";
            } else {
            foreach ($userData as $k => $v) {
                // echo "<pre>"; print_r($v); die();
                echo '<tr>';
                $sr++;
                echo "<td>$sr</td>";
                echo "<td>".$v['ticket_id']."</td>";
                echo "<td>".$v['actual_hrs']."</td>";
                echo '</tr>';
            }}
            ?>
        </tbody>
    </table>

<?php
}

?>