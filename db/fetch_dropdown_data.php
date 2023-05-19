<?php

if($_SERVER['PHP_SELF'] == '/add-user.php' || $_SERVER['PHP_SELF'] == '/user_management.php') {//just if add-user page
        //ticket stauts types dropdown
        $sql = "SELECT * FROM user_type";
        $c_status_types = $conn->query($sql);
        $user_type_row = '<select name="user_type" id="user_type" class="form-control">';
        if ($c_status_types->num_rows > 0) {
            while($row = $c_status_types->fetch_assoc()) {
                $id = $row['id'];
                $name = $row['type_name'];
                $user_type_row .= '<option value="'.$id.'">'.$name.'</option>';
            }
        }
        $user_type_row .= '</option></select>';

        /**
         * fetch projects list
         */
         $sql = "SELECT * FROM projects";
         $c_status_types = $conn->query($sql);
         $projects_row = '<select name="projects" id="projects" class="form-control"  multiple aria-label="multiple select">';
         $projects_row .= '<option value="0" disabled>Select Project</option>';
         if ($c_status_types->num_rows > 0) {
             while($row = $c_status_types->fetch_assoc()) { 
                 $id = $row['id'];
                 $name = $row['project_name'];
                 $projects_row .= '<option value="'.$id.'">'.$name.'</option>';
             }
         }
        $projects_row .= '</option></select>';
    //     $sql = "SELECT * FROM projects";
    //     $c_status_types = $conn->query($sql);
    //     $projects_row = '<select name="projects" id="projects" class="form-control" >';
    //    //  $projects_row .= '<option value="0">Select Project</option>';
    //     if ($c_status_types->num_rows > 0) {
    //         while($row = $c_status_types->fetch_assoc()) {
    //             $id = $row['id'];
    //             $name = $row['project_name'];
    //             $projects_row .= '<option value="'.$id.'">'.$name.'</option>';
    //         }
    //     }
    //     $projects_row .= '</option></select>';
} else if ($_SERVER['PHP_SELF'] == '/configuration.php') {
        /**
         * fetch projects list
         */
        
        // echo "<pre>"; print_r($CONFIG_ALL); die();
        $kpi_selected_status_arr = explode(",", $CONFIG_ALL['kpi_c_status_types']['value1']);
        $ftr_selected_status_arr = explode(",", $CONFIG_ALL['ftr_c_status_types']['value1']);
        $ticket_status_selected_status_arr = explode(",", $CONFIG_ALL['ticket_status_c_status_types']['value1']);

        $sql = "SELECT * FROM c_status_types";
        $c_status_types = $conn->query($sql); 
        //IMP: row variable here should be same as name in configurations table
        $kpi_c_status_types_row = '<select name="kpi_c_status_types[]" id="kpi_c_status_types" class="form-control"  multiple aria-label="multiple select">';
        $ftr_c_status_types_row = '<select name="ftr_c_status_types[]" id="ftr_c_status_types" class="form-control"  multiple aria-label="multiple select">';
        $ticket_status_c_status_types_row = '<select name="ticket_status_c_status_types[]" id="ticket_status_c_status_types" class="form-control"  multiple aria-label="multiple select">';
        // $c_status_types_row .= '<option value="0" disabled>Select Project</option>';
        if ($c_status_types->num_rows > 0) {
            while($row = $c_status_types->fetch_assoc()) { 
                $id = $row['id'];
                $name = $row['type_name'];
                if (in_array($id, $kpi_selected_status_arr)) {
                    $kpi_c_status_types_row .= '<option value="'.$id.'" selected>'.$name.'</option>';
                } else {
                    $kpi_c_status_types_row .= '<option value="'.$id.'">'.$name.'</option>';
                }
                if (in_array($id, $ftr_selected_status_arr)) {
                    $ftr_c_status_types_row .= '<option value="'.$id.'" selected>'.$name.'</option>';
                } else {
                    $ftr_c_status_types_row .= '<option value="'.$id.'">'.$name.'</option>';
                }
                if (in_array($id, $ticket_status_selected_status_arr)) {
                    $ticket_status_c_status_types_row .= '<option value="'.$id.'" selected>'.$name.'</option>';
                } else {
                    $ticket_status_c_status_types_row .= '<option value="'.$id.'">'.$name.'</option>';
                }
            }
        }
       $kpi_c_status_types_row .= '</option></select>';
       $ftr_c_status_types_row .= '</option></select>';
       $ticket_status_c_status_types_row .= '</option></select>';
} else {

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
                //show self as selected if not pm 
                if($_SESSION['user_type'] != 1 && $_SESSION['user_id'] == $id ){
                    $assignees_row .= '<option value="'.$id.'" selected>'.$name.'</option>';
                } else {
                    $assignees_row .= '<option value="'.$id.'">'.$name.'</option>';
                }
            }
        }
    }
    $assignees_row .= '</option></select>';

}




