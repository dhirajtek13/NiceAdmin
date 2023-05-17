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
    *
    FROM projects 
    ORDER BY id DESC
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
    array( 'db' => 'project_name',  'dt' => 1 ),
    array( 'db' => 'region',  'dt' => 2 ),
    array( 'db' => 'description',  'dt' => 3 ),
    array( 
        'db'        => 'start_date', 
        'dt'        => 4, 
        'formatter' => function( $d, $row ) { 
           return ($d != '0000-00-00 00:00:00') ?  date( 'd-m-Y', strtotime($d)) : ''; 
        } 
    ),
    array( 
        'db'        => 'end_date', 
        'dt'        => 5, 
        'formatter' => function( $d, $row ) { 
           return ($d != '0000-00-00 00:00:00') ?  date( 'd-m-Y', strtotime($d)) : ''; 
        } 
    ),
    array( 
        'db'        => 'renewal_date', 
        'dt'        => 6, 
        'formatter' => function( $d, $row ) { 
           return ($d != '0000-00-00 00:00:00') ?  date( 'd-m-Y', strtotime($d)) : ''; 
        } 
    ),
    array( 'db' => 'customer_name',  'dt' => 7 ),
    array( 'db' => 'planned_billing',  'dt' => 8 ),
    array( 'db' => 'actual_billing',  'dt' => 9 ),
    array( 
        'db'        => 'id',
        'dt'        => 10,
        'formatter' => function( $d, $row ) { 
            
            return ' <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <a href="javascript:void(0);" class="btn btn-warning" onclick="editData('.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').')">Edit</a>&nbsp;
                &nbsp;
                <a href="javascript:void(0);" class="btn btn-danger"  onclick="deleteData('.$d.')">Delete</a>&nbsp;
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
