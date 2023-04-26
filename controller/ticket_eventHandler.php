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
    $type_id = !empty($user_data[1])?$user_data[1]:''; 
    $c_status = !empty($user_data[2])?$user_data[2]:''; 
    $assignee_id = !empty($user_data[3])?$user_data[3]:''; 
    $assigned_date = !empty($user_data[4])?$user_data[4]:''; 
    $plan_start_date = !empty($user_data[5])?$user_data[5]:0;
    $plan_end_date = !empty($user_data[6])?$user_data[6]:0;
    $planned_hrs = !empty($user_data[7])?$user_data[7]:0;

    $id = !empty($user_data[8])?$user_data[8]:0; 

    $actual_start_date = !empty($user_data[9])?$user_data[9]:0;
    $actual_end_date = !empty($user_data[10])?$user_data[10]:0;
    $actual_hrs = !empty($user_data[11])?$user_data[11]:0;

 
    $err = ''; 
    if(empty($ticket_id)){ 
        $err .= 'Please enter your Ticket Id.<br/>'; 
    }

     
    if(!empty($user_data) && empty($err)){ 
        if(!empty($id)){ 
            // Update user data into the database 
            $sqlQ = "UPDATE tickets SET ticket_id=?, type_id=?, c_status=?, assignee_id=?, assigned_date=?, plan_start_date=?, plan_end_date=?, actual_start_date=?, actual_end_date=?, planned_hrs=?, actual_hrs=?, updated_at=NOW()  WHERE id=?"; 
            $stmt = $conn->prepare($sqlQ); 
            $stmt->bind_param("siiisssssddi", $ticket_id, $type_id, $c_status, $assignee_id, $assigned_date, $plan_start_date, $plan_end_date, $actual_start_date, $actual_end_date, $planned_hrs, $actual_hrs,  $id); 
            $update = $stmt->execute(); 
 
            if($update){ 
                //Also add in the log_timings
                //TODO - using assignee_id as of now instead of logged in user
                addTiming($conn, $id, $assignee_id,  $c_status, 'UPDATE_TICKET');

                $output = [ 
                    'status' => 1, 
                    'msg' => 'Ticket updated successfully!' 
                ]; 
                echo json_encode($output); 
            }else{ 
                echo json_encode(['error' => 'Ticket Update request failed!']); 
            } 
        }else{ 
            //check if this ticket id already exist or not
            $sql = "SELECT id FROM tickets WHERE ticket_id=?";
            $stmt1 = $conn->prepare($sql); 
            $stmt1->bind_param("s", $ticket_id);
            $stmt1->execute();
            $result1 = $stmt1->get_result(); // get the mysqli result
            $select_data = $result1->fetch_assoc(); // fetch data

            if($select_data) {
                // $err .= "Ticket $ticket_id already exist.<br/>";
                echo json_encode(['error' => "Ticket $ticket_id already exist.<br/>"]);
            } else {
                // Insert event data into the database 
                $sqlQ = "INSERT INTO tickets (ticket_id,type_id,c_status,assignee_id,assigned_date,plan_start_date,plan_end_date,actual_start_date,actual_end_date,planned_hrs,actual_hrs)
                VALUES (?,?,?,?,?,?,?,?,?,?,?)"; 
                $stmt = $conn->prepare($sqlQ); 
                $stmt->bind_param("siiisssssdd", $ticket_id, $type_id, $c_status, $assignee_id, $assigned_date, $plan_start_date, $plan_end_date, $actual_start_date, $actual_end_date, $planned_hrs, $actual_hrs); 
                $insert = $stmt->execute(); 


                if ($insert) { 

                    //TODO - using assignee_id as of now instead of logged in user
                    addTiming($conn, $ticket_id, $assignee_id,  $c_status, 'ADD_TICKET');
                    $output = [ 
                        'status' => 1, 
                        'msg' => 'Ticket added successfully!' 
                    ]; 
                    echo json_encode($output); 
                }else{ 
                    echo json_encode(['error' => 'Ticket Add request failed!']); 
                } 
            }
        } 
    }else{ 
        echo json_encode(['error' => trim($err, '<br/>')]); 
    } 
}elseif($jsonObj->request_type == 'deleteUser'){ 
    $id = $jsonObj->user_id; 
 
    $sql = "DELETE FROM members WHERE id=$id"; 
    $delete = $conn->query($sql); 
    if($delete){ 
        $output = [ 
            'status' => 1, 
            'msg' => 'Member deleted successfully!' 
        ]; 
        echo json_encode($output); 
    }else{ 
        echo json_encode(['error' => 'Member Delete request failed!']); 
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