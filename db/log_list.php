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
                log_history.*, c_status_types.type_name as c_type_name, tickets.c_status AS ticketFinalStatus
                FROM log_history 
                LEFT JOIN tickets
                ON tickets.id = log_history.ticket_id
                LEFT JOIN c_status_types
                ON log_history.c_status = c_status_types.id
                WHERE tickets.ticket_id = '".$this_ticket."'";

// print_r($db_string);
// die();

$table = <<<EOT
    (
        $db_string
    ) temp
    EOT;

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
            // return date( 'd-m-Y', strtotime($d)); 
        } 
    ), 
    array( 
        'db'        => 'dates', 
        'dt'        => 1, 
        'formatter' => function( $d, $row ) { 
            return ($d != '0000-00-00 00:00:00') ?  date( 'd-m-Y', strtotime($d)) : '';
            // return date( 'd-m-Y', strtotime($d)); 
        } 
    ), 
    array( 'db' => 'hrs', 'dt' => 2 ), 
    array( 'db' => 'c_type_name',  'dt' => 3 ), 
    array( 'db' => 'what_is_done',      'dt' => 4 ), 
    array( 'db' => 'what_is_pending',     'dt' => 5 ), 
    array( 'db' => 'what_support_required',     'dt' => 6 ), 
    array( 
        'db'        => 'id', 
        'dt'        => 7,
        'formatter' => function( $d, $row ) { 
            // print_r($row); die();
            return ' 
            <a href="javascript:void(0);" class="edit onlyDevAction getTicketStatusRef" data-toggle="modal" data-ticketstatus="'.$row['ticketFinalStatus'].'"  onclick="editData('.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').')">
            <i class="bi bi-pencil-fill"></i> 
                </a>&nbsp;
                <a href="javascript:void(0);"  class="delete onlyDevAction" data-toggle="modal" onclick="deleteData('.$d.')">
                <i class="bi bi-trash-fill"></i> 
                </a>
                
                '; 
            } 
        ),
        array( 'db' => 'ticketFinalStatus',     'dt' => 8 ), 
); 
 
// Include SQL query processing class 
require '../assets/libraries/DataTables/ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);
