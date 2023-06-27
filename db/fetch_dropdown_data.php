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
         $projects_row = '<select name="projects[]" id="projects" class="form-control"  multiple aria-label="multiple select">';
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
        $prod_selected_status_arr = explode(",", $CONFIG_ALL['prod_c_status_types']['value1']);
        $reassigned_selected_status_arr = explode(",", $CONFIG_ALL['reassigned_c_status_types']['value1']);
        $ticket_status_selected_status_arr = explode(",", $CONFIG_ALL['ticket_status_c_status_types']['value1']);
        $default_project_selected_status_arr = explode(",", $CONFIG_ALL['default_project']['value1']);

        $sql = "SELECT * FROM c_status_types";
        $sql2 = "SELECT * FROM projects";
        $c_status_types = $conn->query($sql);
        $c_status_types2 = $conn->query($sql2);
        //IMP: row variable here should be same as name in configurations table
        $kpi_c_status_types_row = '<select name="kpi_c_status_types[]" id="kpi_c_status_types" class="form-control"  multiple aria-label="multiple select">';
        $ftr_c_status_types_row = '<select name="ftr_c_status_types[]" id="ftr_c_status_types" class="form-control"  multiple aria-label="multiple select">';
        $prod_c_status_types_row = '<select name="prod_c_status_types[]" id="prod_c_status_types" class="form-control"  multiple aria-label="multiple select">';
        $reassigned_c_status_types_row = '<select name="reassigned_c_status_types[]" id="reassigned_c_status_types" class="form-control"  multiple aria-label="multiple select">';
        $ticket_status_c_status_types_row = '<select name="ticket_status_c_status_types[]" id="ticket_status_c_status_types" class="form-control"  multiple aria-label="multiple select">';
        $default_project_row = '<select name="default_project[]" id="default_project" class="form-control"  multiple aria-label="select">';
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
                if (in_array($id, $prod_selected_status_arr)) {
                    $prod_c_status_types_row .= '<option value="'.$id.'" selected>'.$name.'</option>';
                } else {
                    $prod_c_status_types_row .= '<option value="'.$id.'">'.$name.'</option>';
                }
                if (in_array($id, $reassigned_selected_status_arr)) {
                    $reassigned_c_status_types_row .= '<option value="'.$id.'" selected>'.$name.'</option>';
                } else {
                    $reassigned_c_status_types_row .= '<option value="'.$id.'">'.$name.'</option>';
                }
                if (in_array($id, $ticket_status_selected_status_arr)) {
                    $ticket_status_c_status_types_row .= '<option value="'.$id.'" selected>'.$name.'</option>';
                } else {
                    $ticket_status_c_status_types_row .= '<option value="'.$id.'">'.$name.'</option>';
                }
            }
        }
        if ($c_status_types2->num_rows > 0) {
            while($row2 = $c_status_types2->fetch_assoc()) {
                $id = $row2['id'];
                $name = $row2['project_name'];
                if (in_array($id, $default_project_selected_status_arr)) {
                    $default_project_row .= '<option value="'.$id.'" selected>'.$name.'</option>';
                } else {
                    $default_project_row .= '<option value="'.$id.'">'.$name.'</option>';
                }
            }
        }
       $kpi_c_status_types_row .= '</option></select>';
       $ftr_c_status_types_row .= '</option></select>';
       $prod_c_status_types_row .= '</option></select>';
       $reassigned_c_status_types_row .= '</option></select>';
       $ticket_status_c_status_types_row .= '</option></select>';
       $default_project_row .= '</option></select>';
} else if ($_SERVER['PHP_SELF'] == '/leave_tracker.php') {
        //ticket_types dropdown
        $sql = "SELECT * FROM leave_type";
        $leave_types = $conn->query($sql);
        // $leave_types_row = [];
        $leave_types_row = '<select name="leave_type" id="leave_type" class="form-control">';
        if ($leave_types->num_rows > 0) {
            while($row = $leave_types->fetch_assoc()) {
                // $leave_types_row[] = $row;
                $id = $row['id'];
                $name = $row['type_name'];
                $leave_types_row .= '<option value="'.$id.'">'.$name.'</option>';
            }
        }
        $leave_types_row .= '</option></select>';

        $sql2 = "SELECT * FROM day_type";
        $day_types = $conn->query($sql2);
        // $day_types_row = [];
        $day_types_row = '<select name="day_type" id="day_type" class="form-control">';
        if ($day_types->num_rows > 0) {
            while($row = $day_types->fetch_assoc()) {
                // $day_types_row[] = $row;
                $id = $row['id'];
                $name = $row['type_name'];
                $day_types_row .= '<option value="'.$id.'">'.$name.'</option>';
            }
        }
        $day_types_row .= '</option></select>';
    // die($sql2);

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
    $disabled = ($_SESSION['user_type'] != 1) ? 'disabled': '';//while editing ticket assignining should not get changed
    $assignees_row = '<select name="assignee_id" id="assignee_id" class="form-control" '.$disabled.' >';
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


    /**
         * fetch projects list
         */
        $sql = "SELECT * FROM projects";
        $c_status_types = $conn->query($sql);
        $projects_row = '<select name="project_id" id="project_id" class="form-control"   aria-label="select">';
        // $projects_row .= '<option value="0" disabled>Select Project</option>';
        if ($c_status_types->num_rows > 0) {
            while($row = $c_status_types->fetch_assoc()) { 
                $id = $row['id'];
                $name = $row['project_name'];
                
                if(isset($CONFIG_ALL) && $CONFIG_ALL['default_project']['value1']  == $id ){
                    $projects_row .= '<option value="'.$id.'" selected>'.$name.'</option>';

                } else {
                    $projects_row .= '<option value="'.$id.'">'.$name.'</option>';
                }
            }
        }
       $projects_row .= '</option></select>';

    //   print_r( $CONFIG_ALL); die();

}




