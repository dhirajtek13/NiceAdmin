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

    $hol_name = !empty($user_data[0])?$user_data[0]:'';
    $hol_desc = !empty($user_data[1])?$user_data[1]:'';
    $hol_start_date = !empty($user_data[2])?$user_data[2]:'';
    $hol_end_date = !empty($user_data[3])?$user_data[3]:'';
    $id = !empty($user_data[4])?$user_data[4]:0; 
 
    $err = ''; 

    if(!empty($user_data) && empty($err)){ 
        if(!empty($id)){ 
            // Update user data into the database 
            $sqlQ = "UPDATE holidays SET hol_name=?,hol_desc=?,hol_start_date=?,hol_end_date=?   WHERE id=?"; 
            $stmt = $conn->prepare($sqlQ); 
            $stmt->bind_param("ssssi", $hol_name, $hol_desc, $hol_start_date, $hol_end_date, $id); 
            $update = $stmt->execute(); 
 
            if($update){ 
                //Also add in the log_timings
                // $details = json_encode($user_data);
                // addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'UPDATE_LOG', $details, $current_user_id);
                $output = [ 
                    'status' => 1, 
                    'msg' => 'Holiday updated successfully!' 
                ]; 
                echo json_encode($output); 
            }else{ 
                echo json_encode(['error' => 'Holiday Update request failed!']); 
            } 
        }else{ 
                // Insert event data into the database 
                //TODO- this is unless as of now untill add-user insert is not moved here
                $sqlQ = "INSERT INTO holidays (hol_name, hol_desc, hol_start_date, hol_end_date)
                VALUES (?,?,?,?)"; 
                $stmt = $conn->prepare($sqlQ); 
                $stmt->bind_param("ssss", $hol_name, $hol_desc, $hol_start_date, $hol_end_date);
                $insert = $stmt->execute(); 

                if ($insert) { 
                    //Also add in the log_timings
                    $details = json_encode($user_data);
                    //addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'ADD_LOG', $details, $current_user_id);
                    $output = [ 
                        'status' => 1, 
                        'msg' => 'New Holiday added successfully!' 
                    ]; 
                    echo json_encode($output); 
                }else{ 
                    echo json_encode(['error' => 'Holiday Add request failed!']); 
                } 
            
        } 
    }else{ 
        echo json_encode(['error' => trim($err, '<br/>')]); 
    } 
} elseif($jsonObj->request_type == 'deleteUser'){ 
    $id = $jsonObj->user_id; 
 
    $sql = "DELETE FROM holidays WHERE id=$id"; 
    $delete = $conn->query($sql); 
    if($delete){ 
        $output = [ 
            'status' => 1, 
            'msg' => 'Holiday deleted successfully!' 
        ]; 
        echo json_encode($output); 
    }else{ 
        echo json_encode(['error' => 'Holiday Delete request failed!']); 
    } 
}
