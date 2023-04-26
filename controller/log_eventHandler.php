<?php      
/**
 * Reference: https://phpdelusions.net/mysqli_examples/prepared_select
 */
// Include database configuration file
require_once '../db/config.php'; 
 
// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input'); 
$jsonObj = json_decode($jsonStr); 
 
if($jsonObj->request_type == 'addEdit'){ 
    $user_data = $jsonObj->user_data;

    $ticket_id = !empty($user_data[0])?$user_data[0]:''; 
    $dates = !empty($user_data[2])?$user_data[2]:''; 
    $hrs = !empty($user_data[3])?$user_data[3]:'0.0'; 
    $c_status = !empty($user_data[4])?$user_data[4]: 1; 
    $what_is_done = !empty($user_data[5])?$user_data[5]:'NA'; 
    $what_is_pending = !empty($user_data[6])?$user_data[6]:'NA';
    $what_support_required = !empty($user_data[7])?$user_data[7]: 'NA';

    $id = !empty($user_data[8])?$user_data[8]:0; 
 
    $err = ''; 
    if(empty($dates)){ 
        $err .= 'Please enter date for worklog.<br/>'; 
    }
    if(empty($hrs)){ 
        $err .= 'Please enter hours for worklog.<br/>'; 
    }

    if(!empty($user_data) && empty($err)){ 
        if(!empty($id)){ 
            // Update user data into the database 
            $sqlQ = "UPDATE log_history SET ticket_id=?, dates=?, hrs=?, c_status=?, what_is_done=?, what_is_pending=?, what_support_required=?, updated_at=NOW()  WHERE id=?"; 
            $stmt = $conn->prepare($sqlQ); 
            $stmt->bind_param("isdisssi", $ticket_id, $dates, $hrs, $c_status, $what_is_done, $what_is_pending, $what_support_required,  $id); 
            $update = $stmt->execute(); 
 
            if($update){ 
                //Also add in the log_timings
                //TODO - get the logged in user / ticket assigned user
                addTiming($conn, $ticket_id, USERID,  $c_status, 'UPDATE_LOG');
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
                $sqlQ = "INSERT INTO log_history (ticket_id,dates,hrs,c_status,what_is_done,what_is_pending,what_support_required)
                VALUES (?,?,?,?,?,?,?)"; 
                $stmt = $conn->prepare($sqlQ); 
                $stmt->bind_param("isdisss", $ticket_id, $dates, $hrs, $c_status, $what_is_done, $what_is_pending, $what_support_required); 
                $insert = $stmt->execute(); 

                if ($insert) { 
                    //Also add in the log_timings
                    addTiming($conn, $ticket_id, USERID,  $c_status, 'ADD_LOG');
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

function addTiming($conn, $ticket_id, $user_id,  $ticket_status, $activity_type) {

    $sqlQ = "INSERT INTO log_timing (ticket_id,user_id, c_status,activity_type)
                VALUES (?,?,?,?)"; 
                $stmt = $conn->prepare($sqlQ); 
                $stmt->bind_param("iiis", $ticket_id, $user_id,  $ticket_status, $activity_type); 
                $insert = $stmt->execute();
                //TODO return and handle return
}