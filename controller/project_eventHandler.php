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
    $project_name = !empty($user_data[0])?$user_data[0]:''; 
    $project_code = !empty($user_data[1])?$user_data[1]:''; 
    $region = !empty($user_data[2])?$user_data[2]:''; 
    $description = !empty($user_data[3])?$user_data[3]:''; 
    $start_date = !empty($user_data[4])?$user_data[4]:''; 
    $end_date = !empty($user_data[5])?$user_data[5]:''; 
    $renewal_date = !empty($user_data[6])?$user_data[6]:0;
    $customer_name = !empty($user_data[7])?$user_data[7]:0;
    $planned_billing = !empty($user_data[8])?$user_data[8]:0;
    $actual_billing = !empty($user_data[9])?$user_data[9]:0;

    $id = !empty($user_data[10])?$user_data[10]:0; 
 
    $err = ''; 

    if(!empty($user_data) && empty($err)){ 
        if(!empty($id)){ 
            // Update user data into the database 
            $sqlQ = "UPDATE projects SET project_name=?, region=?, description=?, start_date=?, end_date=?, renewal_date=?, customer_name=?, planned_billing=?, actual_billing=?, project_code=?   WHERE id=?"; 
            $stmt = $conn->prepare($sqlQ); 
            $stmt->bind_param("ssssssssssi", $project_name, $region, $description, $start_date, $end_date, $renewal_date, $customer_name, $planned_billing, $actual_billing, $project_code, $id); 
            $update = $stmt->execute(); 
 
            if($update){ 
                //Also add in the log_timings
                // $details = json_encode($user_data);
                // addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'UPDATE_LOG', $details, $current_user_id);
                $output = [ 
                    'status' => 1, 
                    'msg' => 'Project updated successfully!' 
                ]; 
                echo json_encode($output); 
            }else{ 
                echo json_encode(['error' => 'Project Update request failed!']); 
            } 
        }else{ 
                // Insert event data into the database 
                //TODO- this is unless as of now untill add-user insert is not moved here
                $sqlQ = "INSERT INTO projects (project_name,region,description, start_date, end_date, renewal_date, customer_name, planned_billing, actual_billing, project_code)
                VALUES (?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $conn->prepare($sqlQ); 
                $stmt->bind_param("ssssssssss", $project_name, $region,$description, $start_date, $end_date, $renewal_date, $customer_name, $planned_billing, $actual_billing, $project_code);
                $insert = $stmt->execute(); 

                if ($insert) {
                    //Also add in the log_timings
                    $details = json_encode($user_data);
                    //addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'ADD_LOG', $details, $current_user_id);
                    $output = [ 
                        'status' => 1, 
                        'msg' => 'New Project added successfully!' 
                    ]; 
                    echo json_encode($output); 
                }else{ 
                    echo json_encode(['error' => 'Project Add request failed!']); 
                } 
            
        } 
    }else{ 
        echo json_encode(['error' => trim($err, '<br/>')]); 
    } 
} elseif($jsonObj->request_type == 'deleteUser'){ 
    $id = $jsonObj->user_id; 
 
    $sql = "DELETE FROM projects WHERE id=$id"; 
    $delete = $conn->query($sql); 
    if($delete){ 
        $output = [ 
            'status' => 1, 
            'msg' => 'Project deleted successfully!' 
        ]; 
        echo json_encode($output); 
    }else{ 
        echo json_encode(['error' => 'Project Delete request failed!']); 
    } 
}
