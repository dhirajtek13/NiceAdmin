<?php

        $existSql = "SELECT * FROM `configurations` ";
        $result = mysqli_query($conn, $existSql);
        $CONFIG_ALL = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $CONFIG_ALL[$row['name']] = $row;
            }
        }

        
