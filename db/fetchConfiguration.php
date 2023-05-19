<?php

    // echo "<pre>"; print_r($_SESSION); die();

   
        //Fetch projects of the logged in user only
        $existSql = "SELECT * FROM `projects` AS p LEFT JOIN project_user_map AS pu ON pu.project_id = p.id WHERE pu.user_id = ".$_SESSION['user_id'];
        $result = mysqli_query($conn, $existSql);
        $PROJECTS_ALL = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $PROJECTS_ALL[$row['project_code']] = $row;
            }
        }



        $existSql = "SELECT * FROM `configurations` ";
        $result = mysqli_query($conn, $existSql);
        $CONFIG_ALL = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $CONFIG_ALL[$row['name']] = $row;
            }
        }



        
