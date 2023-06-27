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
    $username = !empty($user_data[0])?$user_data[0]:''; 
    $email = !empty($user_data[1])?$user_data[1]: '';
    $employee_id = !empty($user_data[2])?$user_data[2]:'0'; 
    $designation = !empty($user_data[3])?$user_data[3]:'NA';
    $fname = !empty($user_data[4])?$user_data[4]:''; 
    $lname = !empty($user_data[5])?$user_data[5]:'0.0'; 
    $user_type = !empty($user_data[6])?$user_data[6]: 1; 
    $projects = !empty($user_data[7])?$user_data[7]: []; 

    $id = !empty($user_data[8])?$user_data[8]:0; 
 
    $err = ''; 

    if(!empty($user_data) && empty($err)){ 
        if(!empty($id)){ 
            // Update user data into the database 
            $sqlQ = "UPDATE users SET username=?, email=?, employee_id=?, designation=?, fname=?, lname=?, user_type=?, updated_at=NOW()  WHERE id=?"; 
            $stmt = $conn->prepare($sqlQ); 
            $stmt->bind_param("ssisssii", $username, $email, $employee_id, $designation, $fname, $lname, $user_type,  $id); 
            $update = $stmt->execute(); 
 
            if($update){ 
                //Also add in the log_timings
                // $details = json_encode($user_data);
                // addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'UPDATE_LOG', $details, $current_user_id);
                if(!empty($projects)) {
                    //check and update the project_user_map with new map record
                     //remove all projects for this user and add new as per selected
                     $sql = "DELETE FROM project_user_map WHERE user_id=$id"; 
                    $delete = $conn->query($sql); 
                    if($delete) {
                        foreach ($projects as $key => $value) {
                            //if this combo not exist then add
                            $sql2 = "Select * from project_user_map where project_id='$value' and user_id='$id'";
                            $result2 = mysqli_query($conn, $sql2);
                            $num2 = mysqli_num_rows($result2);
    
                            // print_r(mysqli_num_rows($result2)); die();
                           
                            if ( mysqli_num_rows($result2) == 0) {
                                $sqlQ3 = "INSERT INTO project_user_map (project_id, user_id)
                                    VALUES (?,?)"; 
                                    $stmt3 = $conn->prepare($sqlQ3); 
                                    $stmt3->bind_param("ii", $value, $id); 
                                    $insert = $stmt3->execute();
                            } 
                        }
                    }

                    
                }
                $output = [ 
                    'status' => 1, 
                    'msg' => 'User updated successfully!' 
                ]; 
                echo json_encode($output); 
            }else{ 
                echo json_encode(['error' => 'User Update request failed!']); 
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
                //TODO- this is unless as of now untill add-user insert is not moved here
                $sqlQ = "INSERT INTO log_history (user_id,ticket_id,dates,hrs,c_status,what_is_done,what_is_pending,what_support_required)
                VALUES (?,?,?,?,?,?,?,?)"; 
                $stmt = $conn->prepare($sqlQ); 
                $stmt->bind_param("iisdisss", $current_user_id, $ticket_id, $dates, $hrs, $c_status, $what_is_done, $what_is_pending, $what_support_required); 
                $insert = $stmt->execute(); 

                if ($insert) { 
                    //Also add in the log_timings

                    // $sqlQ3 = "INSERT INTO project_user_map (project_id, user_id)
                    //             VALUES (?,?)"; 
                    //             $stmt3 = $conn->prepare($sqlQ3); 
                    //             $stmt3->bind_param("ii", $value, $id); 
                    //             $insert = $stmt3->execute(); 


                    $details = json_encode($user_data);
                    addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'ADD_LOG', $details, $current_user_id);
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
} elseif ($jsonObj->request_type == 'changePassword') {
    $user_data = $jsonObj->user_data;

    // $current_password = !empty($user_data[0])?$user_data[0]:''; 
    $password = !empty($user_data[1])?$user_data[0]: '';
    $cpassword = !empty($user_data[2])?$user_data[1]:'0'; 
   
    $id = !empty($user_data[2])?$user_data[2]:0; 

    $err = '';
    
    if(!empty($user_data) && empty($err)){ 
        // print_r($user_data); die();
        if(!empty($id)){
           
            $sql = "Select * from users where id='$id' ";
            $result = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($result);
            if ($num == 1){
                while($row=mysqli_fetch_assoc($result)){
                    if ($password){ 
                    // if (password_verify($current_password, $row['password'])){ 
                        $hash = password_hash($password, PASSWORD_DEFAULT);

                        $sqlQ = "UPDATE users SET password=?, updated_at=NOW()  WHERE id=?"; 
                        $stmt = $conn->prepare($sqlQ); 
                        $stmt->bind_param("si", $hash,  $id); 
                        $update = $stmt->execute();

                        if($update){ 
                            //Also add in the log_timings
                            // $details = json_encode($user_data);
                            // addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'UPDATE_LOG', $details, $current_user_id);
                            $output = [ 
                                'status' => 1, 
                                'msg' => 'User password updated successfully!' 
                            ]; 
                            echo json_encode($output); 
                        }else{ 
                            echo json_encode(['error' => 'User password Update request failed!']); 
                        }
                    } else {
                        echo json_encode(['error' => 'Incorrect current password !!']); 
                    }
                }
            }
        }else{ 
            //         
        } 
    }else{ 
        echo json_encode(['error' => trim($err, '<br/>')]); 
    }

} elseif ($jsonObj->request_type == 'changeUserStatus') {
    $user_data = $jsonObj->user_data;
   
    $user_status = !empty($user_data[0])?$user_data[0]:0; 
    $id = !empty($user_data[1])?$user_data[1]:0; 

    $err = '';
    
    if(!empty($user_data) && empty($err)){
        if(!empty($id)){
            $newUserStatus = ($user_status == 1) ? 0 : 1;
            $sqlQ = "UPDATE users SET user_status=?, updated_at=NOW()  WHERE id=?"; 
            $stmt = $conn->prepare($sqlQ); 
            $stmt->bind_param("ii", $newUserStatus,  $id);
            $update = $stmt->execute();

            if($update){ 
                //Also add in the log_timings
                // $details = json_encode($user_data);
                // addTiming($conn, $ticket_id, $current_user_id,  $c_status, 'UPDATE_LOG', $details, $current_user_id);
                $output = [ 
                    'status' => 1, 
                    'msg' => 'User status updated successfully!' 
                ]; 
                echo json_encode($output); 
            }else{ 
                echo json_encode(['error' => 'Failed to update user status']); 
            }
        } else {
            echo json_encode(['error' => 'User not found!']); 
        }
    }else{ 
        echo json_encode(['error' => trim($err, '<br/>')]); 
    }

} elseif($jsonObj->request_type == 'deleteUser'){ 
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

function addTiming($conn, $ticket_id, $user_id,  $ticket_status, $activity_type, $details='', $assignee_id) {

    $sqlQ = "INSERT INTO log_timing (ticket_id,user_id, c_status,activity_type,details,assignee_id)
                VALUES (?,?,?,?,?,?)"; 
                $stmt = $conn->prepare($sqlQ); 
                $stmt->bind_param("iiissi", $ticket_id, $user_id,  $ticket_status, $activity_type,$details,$assignee_id); 
                $insert = $stmt->execute();
                //TODO return and handle return
}