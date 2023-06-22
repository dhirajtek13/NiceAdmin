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
    $leave_desc = !empty($user_data[0])?$user_data[0]:''; 
    $leave_type = !empty($user_data[1])?$user_data[1]:'1'; 
    $day_type = !empty($user_data[2])?$user_data[2]:'1'; 
    $leave_start_date = !empty($user_data[3])?$user_data[3]: '';
    $leave_end_date = !empty($user_data[4])?$user_data[4]: date('Y-m-d'); 

    $id = !empty($user_data[5])?$user_data[5]:0;
    $user_id = !empty($user_data[6])?$user_data[6]:0;

    $datediff =  (strtotime($leave_end_date) - strtotime($leave_start_date)) ;
    $leave_days = round($datediff / (60 * 60 * 24)) + 1;

    $leave_apply_date =  date('Y-m-d H:i:s'); 
    $leave_status = 0;
 
    $err = ''; 
    if(empty($leave_start_date)){ 
        $err .= 'Please enter date for leave.<br/>'; 
    }
    if(empty($leave_desc)){ 
        $err .= 'Please enter leave reason.<br/>'; 
    }

    if(!empty($user_data) && empty($err)){
        if(!empty($id)){ 
            

            $sqlQ = "UPDATE leave_tracker SET user_id=?, leave_desc=?, day_type=?, leave_type=?, leave_start_date=?, leave_end_date=?, leave_days=?, leave_apply_date=?, leave_status=?  WHERE id=?"; 
            $stmt = $conn->prepare($sqlQ); 
            $stmt->bind_param("isiissisii", $user_id, $leave_desc, $day_type, $leave_type, $leave_start_date, $leave_end_date, $leave_days, $leave_apply_date, $leave_status, $id); 
            $update = $stmt->execute(); 
 
            if($update){ 
                $output = [ 
                    'status' => 1, 
                    'msg' => 'Leave updated successfully!' 
                ]; 
                echo json_encode($output); 
            }else{ 
                echo json_encode(['error' => 'Log Update request failed!']); 
            } 
        }else{ 
            
                $sqlQ = "INSERT INTO leave_tracker (user_id, leave_desc, day_type, leave_type, leave_start_date, leave_end_date, leave_days, leave_apply_date, leave_status ) VALUES (?,?,?,?,?,?,?,?,?)"; 
                $stmt = $conn->prepare($sqlQ);
                $stmt->bind_param("isiissisi", $user_id, $leave_desc, $day_type, $leave_type, $leave_start_date, $leave_end_date, $leave_days, $leave_apply_date, $leave_status); 
                $insert = $stmt->execute(); 

                if ($insert) {
                    $details = json_encode($user_data);
                    $output = [ 
                        'status' => 1, 
                        'msg' => 'Leave added successfully!' 
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

    $user_data = $jsonObj->user_data;
    $id = !empty($user_data[5])?$user_data[5]:0;
 
    $sql = "DELETE FROM leave_tracker WHERE id=$id"; 
    $delete = $conn->query($sql); 
    if($delete){ 
        $output = [ 
            'status' => 1, 
            'msg' => 'Leave deleted successfully!' 
        ]; 
        echo json_encode($output); 
    }else{ 
        echo json_encode(['error' => 'Leave Delete request failed!']); 
    } 
}

