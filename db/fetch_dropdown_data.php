<?php

//ticket_types dropdown
$sql = "SELECT * FROM ticket_types";
$ticket_types = $conn->query($sql);
// $ticket_types_row = [];
$ticket_types_row = '<select name="type_id" id="type_id" class="form-control">';
if ($ticket_types->num_rows > 0) {
    while($row = $ticket_types->fetch_assoc()) {
        // $ticket_types_row[] = $row;
        $id = $row['id'];
        $name = $row['type_name'];
        $ticket_types_row .= '<option value="'.$id.'">'.$name.'</option>';
    }
}
$ticket_types_row .= '</option></select>';


//ticket stauts types dropdown
$sql = "SELECT * FROM c_status_types";
$c_status_types = $conn->query($sql);
$c_status_types_row = '<select name="c_status" id="c_status" class="form-control">';
if ($c_status_types->num_rows > 0) {
    while($row = $c_status_types->fetch_assoc()) {
        $id = $row['id'];
        $name = $row['type_name'];
        $c_status_types_row .= '<option value="'.$id.'">'.$name.'</option>';
    }
}
$c_status_types_row .= '</option></select>';


//fetch assignees dropdown
$sql = "SELECT * FROM users";
$assignees = $conn->query($sql);
$assignees_row = '<select name="assignee_id" id="assignee_id" class="form-control">';
if ($assignees->num_rows > 0) {
    while($row = $assignees->fetch_assoc()) {
        if($row['user_type'] != 1){ //dont select PM
            $id = $row['id'];
            $name = $row['fname']." ".$row['lname'];
            $assignees_row .= '<option value="'.$id.'">'.$name.'</option>';
        }
    }
}
$assignees_row .= '</option></select>';


