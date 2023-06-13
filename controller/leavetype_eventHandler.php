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

    $type_name = !empty($user_data[0])?$user_data[0]:''; 
    $id = !empty($user_data[1])?$user_data[1]:0; 
 
    $err = ''; 

    if(!empty($user_data) && empty($err)){ 
        if(!empty($id)){ 
            // Update user data into the database 
            $sqlQ = "UPDATE leave_type SET type_name=?  WHERE id=?"; 
            $stmt = $conn->prepare($sqlQ); 
            $stmt->bind_param("si", $type_name,  $id); 
            $update = $stmt->execute(); 
 
            if($update){ 
                //Also add in the log_timings
                // $details = json_encode($user_data);
                // addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'UPDATE_LOG', $details, $current_user_id);
                $output = [ 
                    'status' => 1, 
                    'msg' => 'Leave Type updated successfully!' 
                ]; 
                echo json_encode($output); 
            }else{ 
                echo json_encode(['error' => 'Leave Type Update request failed!']); 
            } 
        }else{ 
                // Insert event data into the database 
                //TODO- this is unless as of now untill add-user insert is not moved here
                $sqlQ = "INSERT INTO leave_type (type_name)
                VALUES (?)"; 
                $stmt = $conn->prepare($sqlQ); 
                $stmt->bind_param("s", $type_name);
                $insert = $stmt->execute(); 

                if ($insert) { 
                    //Also add in the log_timings
                    $details = json_encode($user_data);
                    //addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'ADD_LOG', $details, $current_user_id);
                    $output = [ 
                        'status' => 1, 
                        'msg' => 'New Leave Type added successfully!' 
                    ]; 
                    echo json_encode($output); 
                }else{ 
                    echo json_encode(['error' => 'User Type Add request failed!']); 
                } 
            
        } 
    }else{ 
        echo json_encode(['error' => trim($err, '<br/>')]); 
    } 
} elseif($jsonObj->request_type == 'deleteUser'){ 
    $id = $jsonObj->user_id; 
 
    $sql = "DELETE FROM leave_type WHERE id=$id"; 
    $delete = $conn->query($sql); 
    if($delete){ 
        $output = [ 
            'status' => 1, 
            'msg' => 'Type deleted successfully!' 
        ]; 
        echo json_encode($output); 
    }else{ 
        echo json_encode(['error' => 'Type Delete request failed!']); 
    } 
}
