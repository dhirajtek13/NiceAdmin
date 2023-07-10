<?php

$sql = "SELECT id, type_id FROM tickets ";

if($_GET['ticket']) {
    $ticket = $_GET['ticket'];
    $sql .= " WHERE ticket_id=?";

}

$stmt1 = $conn->prepare($sql); 

if($_GET['ticket']) {
    $stmt1->bind_param("s", $_GET['ticket']);
}
$stmt1->execute();
$result1 = $stmt1->get_result(); // get the mysqli result
$select_data = $result1->fetch_assoc(); // fetch data




// $sql3 = "SELECT id, type_id FROM tickets ";


// if($_GET['ticket']) {
//     $ticket = $_GET['ticket'];
//     $sql .= " WHERE ticket_id='". $ticket. "'";
// }

// $logStatusQuery = $conn->query($sql);
// $userData = [];
// if ($logStatusQuery->num_rows > 0) {
//     while ($row = $logStatusQuery->fetch_assoc()) {
//         $select_data['id'] = $row['id'];
//         $select_data['type_id'] = $row['type_id'];
//     }
// }



if($_GET['ticket'] && isset( $select_data['id'])) {

    $ticket_id = $select_data['id'];
    $ticket_input_html = '<input type="text" class="form-control" id="ticket" value="'.$ticket.'" disabled>';
    $ticket_input_html .= '<input type="hidden" id="ticket_id" name="ticket_id" value="'.$ticket_id.'">';

    if($select_data['type_id'] == 2) {//if story then list the activities

        $sql3 = "SELECT id,ticket_id, c_status  FROM tickets WHERE parent_id=$ticket_id";
        $logStatusQuery3 = $conn->query($sql3);
        $userData = [];
        if ($logStatusQuery3->num_rows > 0) {
            while ($row3 = $logStatusQuery3->fetch_assoc()) {
                $userData[$row3['id']]['act_name'] =  $row3['ticket_id'];
                $userData[$row3['id']]['c_status'] =  $row3['c_status'];
                // $userData[]['act_id'] =  $row3['id'];
            }
        }
        // echo "<pre>";print_r($userData); die();
        if(!empty($userData)) {
            $ticket_input_html .= "Select Activity to log.";
            foreach ($userData as $key => $value) {
                $ticket_input_html .= '<div class="form-check">
                        <input class="form-check-input" type="radio" name="activity_selected" value="'.$key.'" id="activity_'.$key.'" onClick="fetchActData('. $userData[$key]['c_status'].')">
                        <label class="form-check-label">
                        '.$value['act_name'].'
                        </label>
                    </div>';
            }
        }

    }

} else {
    //TODO - display dropdown to select ticket to add log for it.

}