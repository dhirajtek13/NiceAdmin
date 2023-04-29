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

$this_ticket = $_GET['ticket'];
$db_string = "SELECT 
                log_timing.*, c_status_types.type_name as c_type_name, tickets.ticket_id as ticket, CONCAT(u1.fname, ' ', u1.lname) as updated_by,  CONCAT(u2.fname, ' ', u2.lname) as assignee 
                FROM log_timing 
                LEFT JOIN tickets
                ON tickets.id = log_timing.ticket_id
                LEFT JOIN c_status_types
                ON log_timing.c_status = c_status_types.id
                LEFT JOIN users as u1
                ON log_timing.user_id = u1.id
                LEFT JOIN users as u2
                ON log_timing.assignee_id = u2.id
                WHERE tickets.ticket_id = '".$this_ticket."'";

// print_r($db_string);
// die();

$table = <<<EOT
    (
        $db_string
    ) temp
    EOT;

 //TODO -commented to handle with ticket or no ticket in the url
// DB table to use 
// if(isset($_GET['ticket'])) {
//     $table = <<<EOT
//     (
//        SELECT 
//                log_history.*, 
//                FROM log_history 
//                LEFT JOIN tickets
//                ON tickets.id = log_history.ticket_id
//                WHERE tickets.ticket_id = '".$this_ticket."'
//                ORDER BY log_history.id DESC
//     ) temp
//    EOT; 
// } else {
//     //TODO
//     $table = <<<EOT
//     (
//        SELECT 
//                log_history.*
//                FROM log_history 
//                LEFT JOIN tickets
//                ON tickets.id = log_history.ticket_id
//                ORDER BY log_history.id DESC
//     ) temp
//    EOT; 
// }

// Table's primary key 
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables. 
// The `db` parameter represents the column name in the database.  
// The `dt` parameter represents the DataTables column identifier. 
$columns = array( 
    array( 
        'db'        => 'id', 
        'dt'        => 0, 
        'orderable' => false,
        'formatter' => function( $d, $row ) { 
            return $row['id'];
            // return date( 'Y-m-d', strtotime($d)); 
        } 
    ), 
    // array( 'db' => 'ticket_id', 'dt' => 1 ), 
    array( 'db' => 'updated_by', 'dt' => 1 ), 
    array( 'db' => 'assignee', 'dt' => 2 ), 
    array( 'db' => 'c_type_name',  'dt' => 3 ), 
    array( 'db' => 'activity_type',      'dt' => 4 ), 
    array( 
        'db'        => 'datetime', 
        'dt'        => 5, 
        'formatter' => function( $d, $row ) { 
            return ($d != '0000-00-00 00:00:00') ?  date( 'Y-m-d H:i:s', strtotime($d)) : '';
            // return date( 'Y-m-d', strtotime($d)); 
        } 
    ), //TODO
    // array( 
    //     'db'        => 'id', 
    //     'dt'        => 5,
    //     'formatter' => function( $d, $row ) { 
            
    //         return ' 
    //             <a href="javascript:void(0);" class="btn btn-warning" onclick="viewData('.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').')">Details</a>&nbsp;
    //         '; 
    //     } 
    // ),
); 
 
// Include SQL query processing class 
require '../assets/libraries/DataTables/ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);
