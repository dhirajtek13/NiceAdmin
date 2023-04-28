<?php 

include_once 'config.php'; 

/**
 * Reference: https://www.codexworld.com/datatables-crud-operations-with-php-mysql/
 * 
 */

// Database connection info 
$dbDetails = array( 
    'host' => DB_HOST, 
    'user' => DB_USER, 
    'pass' => DB_PASS, 
    'db'   => DB_NAME 
); 
// print_r($dbDetails); die();
// DB table to use 
// $table = 'tickets';
$table = <<<EOT
 (
    SELECT 
    users.id, username, CONCAT(fname, ' ', lname ) as full_name, user_type.type_name as user_type_name, employee_id, designation, email, user_type, password, fname, lname 
    FROM users 
    LEFT JOIN user_type 
    ON users.user_type = user_type.id 
    ORDER BY users.id DESC
 ) temp
EOT; 
 
// Table's primary key 
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables. 
// The `db` parameter represents the column name in the database.  
// The `dt` parameter represents the DataTables column identifier. 
$columns = array( 
    array( 
        'db' => 'id', 
        'dt' => 0, 
        'formatter' => function ($d, $row){
            return $row['id']; 
        }
    ), 
    array( 'db' => 'username',  'dt' => 1 ), 
    array( 'db' => 'full_name',  'dt' => 2 ), 
    array( 'db' => 'user_type_name',  'dt' => 3 ), 
    array( 'db' => 'employee_id',  'dt' => 4 ), 
    array( 'db' => 'designation',  'dt' => 5 ), 
    array( 'db' => 'email',  'dt' => 6 ), 
    array( 'db' => 'user_type',  'dt' => 7 ), 
    // array( 'db' => 'password',  'dt' => 8 ), 
    // array( 
    //     'db'        => 'password', 
    //     'dt'        => 8,
    //     'formatter' => function( $d, $row ) { 
    //         return password_verify($row['password'], $row['password']);
    //     }
    // ),
    array( 'db' => 'fname',  'dt' => 8 ), 
    array( 'db' => 'lname',  'dt' => 9 ), 
   

    // array( 
    //     'db'        => 'planned_hrs', 
    //     'dt'        => 12, 
    //     'formatter' => function( $d, $row ) {
    //         $variance = $row['planned_hrs'] - $row['actual_hrs'];// json_encode($row);
    //         return ($variance != '0') ?  $variance : ''; 
    //     } 
    // ), 
    array( 
        'db'        => 'id', 
        'dt'        => 10,
        'formatter' => function( $d, $row ) { 
            
            return ' <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <a href="javascript:void(0);" class="btn btn-warning" onclick="editData('.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').')">Edit</a>&nbsp;
                <a href="javascript:void(0);" class="btn btn-danger" onclick="changePassword('.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').')">Change Password</a>&nbsp;
                </div>
            '; 
        } 
    ),
    // array( 
    //     'db'        => 'id', 
    //     'dt'        => 7, 
    //     'formatter' => function( $d, $row ) { 
    //         return ' 
    //             <a href="javascript:void(0);" class="btn btn-warning" onclick="editData('.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').')">Edit</a>&nbsp; 
    //             <a href="javascript:void(0);" class="btn btn-danger" onclick="deleteData('.$d.')">Delete</a> 
    //         '; 
    //     } 
    // )  
); 
 
// Include SQL query processing class 
require '../assets/libraries/DataTables/ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);
