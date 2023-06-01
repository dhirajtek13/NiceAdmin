<?php      
/**
 * Reference: https://phpdelusions.net/mysqli_examples/prepared_select
 */
// Include database configuration file
require_once '../db/config.php'; 
 
//get current user id
session_start();
$current_user_id =  $_SESSION['user_id'];

// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input'); 
$jsonObj = json_decode($jsonStr); 


 
if($jsonObj->request_type == 'addEdit'){ 
    $user_data = $jsonObj->user_data;
    // print_r($user_data); die();

    $ticket_id = !empty($user_data[0])?$user_data[0]:''; 
    $dates = !empty($user_data[2])?$user_data[2]:''; 
    $hrs = !empty($user_data[3])?$user_data[3]:'0.0'; 
    $c_status = !empty($user_data[4])?$user_data[4]: 1; 
    $what_is_done = !empty($user_data[5])?$user_data[5]:'NA'; 
    $what_is_pending = !empty($user_data[6])?$user_data[6]:'NA';
    $what_support_required = !empty($user_data[7])?$user_data[7]: 'NA';

    $id = !empty($user_data[8])?$user_data[8]:0;

    $remark = !empty($user_data[9])?$user_data[9]: 'NA';
    $previousStatus = !empty($user_data[10])?$user_data[10]: 'NA';
    $updatedStatus = !empty($user_data[11])?$user_data[11]: 'NA';

    //TODO - for multi purpose
    // $details = [
    //     'ticket_id' => $ticket_id,
    //     'dates' => $dates,
    //     'hrs' => $hrs,
    //     'c_status' => $c_status,
    //     'what_is_done' => $what_is_done,
    //     'what_is_pending' => $what_is_pending,
    //     'what_support_required' => $what_support_required,
    //     'ticket_id' => $ticket_id,
    //     'ticket_id' => $ticket_id,
    // ];
 
    $err = ''; 
    if(empty($dates)){ 
        $err .= 'Please enter date for worklog.<br/>'; 
    }
    if(empty($hrs)){ 
        $err .= 'Please enter hours for worklog.<br/>'; 
    }

    $shouldUpdate_wip_start_datetime = NULL;
    $shouldUpdate_wip_close_datetime = NULL;
    if($previousStatus != $updatedStatus) {
        if(empty($remark) || $remark == 'NA'){
            $err .= 'Please enter remark for status change.<br/>'; 
        }
        if($updatedStatus === 'WIP') {
            $shouldUpdate_wip_start_datetime = date('Y-m-d');
        }
        if($previousStatus === 'WIP') {
            $shouldUpdate_wip_close_datetime = date('Y-m-d');
        }
    }

    $sql11 = "Select assignee_id from tickets where id='$ticket_id' ";
            $result11 = mysqli_query($conn, $sql11);
            $row11 = mysqli_fetch_assoc($result11);
            $ticket_assigned_user = $row11['assignee_id'];

    if(!empty($user_data) && empty($err)){ 
        if(!empty($id)){ 
            // Update user data into the database 
            //TODO - user_id is added as current_user_id (logged in user) but not the ticket assigned user's id. so if PM add or update the ticket then his user_id will get added.
            // to resolve this we either need to get current assigned id of the ticket and add his user_id. But this also will restrict multiple user logging into same ticket functionality.
            // But again we have not implemented multiple user logged into same ticket. as of now only PM can access the ticket log and add/update it.

            // i think may be PM wants to just update comments so user_id we need to get as ticket assigned user's id
            

            $sqlQ = "UPDATE log_history SET user_id=?, ticket_id=?, dates=?, hrs=?, c_status=?, what_is_done=?, what_is_pending=?, what_support_required=?, updated_at=NOW()  WHERE id=?"; 
            $stmt = $conn->prepare($sqlQ); 
            // $stmt->bind_param("iisdisssi", $current_user_id, $ticket_id, $dates, $hrs, $c_status, $what_is_done, $what_is_pending, $what_support_required,  $id); 
            $stmt->bind_param("iisdisssi", $ticket_assigned_user, $ticket_id, $dates, $hrs, $c_status, $what_is_done, $what_is_pending, $what_support_required,  $id); 
            $update = $stmt->execute(); 
 
            if($update){ 
                //Also add in the log_timings
                $details = json_encode($user_data);
                addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'UPDATE_LOG', $dates, $details, $ticket_assigned_user, $remark);
                
                if($shouldUpdate_wip_start_datetime) {
                    updateWIP_start($conn, $shouldUpdate_wip_start_datetime, $ticket_id);
                }
                if($shouldUpdate_wip_close_datetime) {
                    updateWIP_close($conn, $shouldUpdate_wip_close_datetime, $ticket_id);
                }
                if($previousStatus != $updatedStatus) {
                    updateTicketStatus($conn, $c_status, $ticket_id);
                }
                updateActualHrs($conn, $ticket_id, $hrs);

                $output = [ 
                    'status' => 1, 
                    'msg' => 'Log updated successfully!' 
                ]; 
                echo json_encode($output); 
            }else{ 
                echo json_encode(['error' => 'Log Update request failed!']); 
            } 
        }else{ 
            //check if this ticket id already exist or not
            // $sql = "SELECT id FROM log_history WHERE ticket_id=?";
            // $stmt1 = $conn->prepare($sql); 
            // $stmt1->bind_param("s", $ticket_id);
            // $stmt1->execute();
            // $result1 = $stmt1->get_result(); // get the mysqli result
            // $select_data = $result1->fetch_assoc(); // fetch data

            // if($select_data) {
            //     // $err .= "Ticket $ticket_id already exist.<br/>";
            //     echo json_encode(['error' => "Ticket $ticket_id already exist.<br/>"]);
            // } else {
                // Insert event data into the database 
                $sqlQ = "INSERT INTO log_history (user_id,ticket_id,dates,hrs,c_status,what_is_done,what_is_pending,what_support_required)
                VALUES (?,?,?,?,?,?,?,?)"; 
                $stmt = $conn->prepare($sqlQ); 
                // $stmt->bind_param("iisdisss", $current_user_id, $ticket_id, $dates, $hrs, $c_status, $what_is_done, $what_is_pending, $what_support_required); 
                $stmt->bind_param("iisdisss", $ticket_assigned_user, $ticket_id, $dates, $hrs, $c_status, $what_is_done, $what_is_pending, $what_support_required); 
                $insert = $stmt->execute(); 

                if ($insert) { 
                    //Also add in the log_timings
                    $details = json_encode($user_data);
                    addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'ADD_LOG', $dates, $details, $ticket_assigned_user);

                    if($shouldUpdate_wip_start_datetime) {
                        updateWIP_start($conn, $shouldUpdate_wip_start_datetime, $ticket_id);
                    }
                    if($shouldUpdate_wip_close_datetime) {
                        updateWIP_close($conn, $shouldUpdate_wip_close_datetime, $ticket_id);
                    }
                    if($previousStatus != $updatedStatus) {
                        updateTicketStatus($conn, $c_status, $ticket_id);
                    }

                    updateActualHrs($conn, $ticket_id, $hrs);
                    
                    $output = [ 
                        'status' => 1, 
                        'msg' => 'Log added successfully!' 
                    ]; 
                    echo json_encode($output); 
                }else{ 
                    echo json_encode(['error' => 'Log Add request failed!']); 
                } 
            
        } 
    }else{ 
        echo json_encode(['error' => trim($err, '<br/>')]); 
    } 
}elseif($jsonObj->request_type == 'deleteUser'){ 
    $id = $jsonObj->user_id; 
 
    $sql = "DELETE FROM log_history WHERE id=$id"; 
    $delete = $conn->query($sql); 
    if($delete){ 
        $output = [ 
            'status' => 1, 
            'msg' => 'Log deleted successfully!' 
        ]; 
        echo json_encode($output); 
    }else{ 
        echo json_encode(['error' => 'Log Delete request failed!']); 
    } 
}

function addTiming($conn, $ticket_id, $user_id,  $ticket_status, $activity_type, $dates, $details='', $assignee_id, $remark='') {

    $sqlQ = "INSERT INTO log_timing (ticket_id,user_id, c_status,activity_type,details,assignee_id,remark, dates)
                VALUES (?,?,?,?,?,?,?,?)"; 
                $stmt = $conn->prepare($sqlQ); 
                $stmt->bind_param("iiississ", $ticket_id, $user_id,  $ticket_status, $activity_type,$details,$assignee_id,$remark,$dates); 
                $insert = $stmt->execute();
                //TODO return and handle return
}

function updateWIP_start($conn, $shouldUpdate_wip_start_datetime, $ticket_id) {
    $sqlQ = "UPDATE tickets SET wip_start_datetime=?, wip_close_datetime=NULL WHERE id=?"; 
    $stmt = $conn->prepare($sqlQ);
    $stmt->bind_param("si", $shouldUpdate_wip_start_datetime, $ticket_id); 
    $update = $stmt->execute(); 
}
function updateWIP_close($conn, $shouldUpdate_wip_close_datetime, $ticket_id) {
    $sqlQ = "UPDATE tickets SET wip_close_datetime=?  WHERE id=?"; 
    $stmt = $conn->prepare($sqlQ);
    $stmt->bind_param("si", $shouldUpdate_wip_close_datetime, $ticket_id); 
    $update = $stmt->execute(); 
}
function updateTicketStatus($conn, $c_status, $ticket_id) {
    $sqlQ = "UPDATE tickets SET c_status=?  WHERE id=?"; 
    $stmt = $conn->prepare($sqlQ);
    $stmt->bind_param("ii", $c_status, $ticket_id); 
    $update = $stmt->execute(); 
}

function updateActualHrs($conn, $ticket_id, $newhrs) {
    //calcualte original actual hrs of this ticket

    $sql11 = "SELECT SUM(log_history.hrs) AS actual_hrs FROM tickets 
                    LEFT JOIN 	log_history ON tickets.id = log_history.ticket_id
                    WHERE tickets.id='$ticket_id' 
                    GROUP BY log_history.ticket_id
                    ";
    $result11 = mysqli_query($conn, $sql11);
    $row11 = mysqli_fetch_assoc($result11);
    $actual_hrs = $row11['actual_hrs'];

    $sqlQ = "UPDATE tickets SET actual_hrs=?  WHERE id=?";
    $stmt = $conn->prepare($sqlQ);
    $stmt->bind_param("ii", $actual_hrs, $ticket_id); 
    $update = $stmt->execute(); 

}
