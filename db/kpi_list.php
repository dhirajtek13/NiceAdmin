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

// $this_ticket = $_GET['ticket'];
$db_string = "SELECT 
                kpi.*, p.project_name
                FROM kpis AS kpi
                LEFT JOIN projects AS p
                ON p.id = kpi.project_id";

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
        'db'        => 'kpi_name', 
        'dt'        => 1 
    ), 
    array( 'db' => 'service_level', 'dt' => 2 ), 
    array( 'db' => 'description',  'dt' => 3 ), 
    array( 'db' => 'target_operator',      'dt' => 4 ), 
    array( 'db' => 'target_value',     'dt' => 5 ), 
    array( 'db' => 'project_id',     'dt' => 6 ), 
    array( 'db' => 'project_name',     'dt' => 7 ), 
    array( 
        'db'        => 'id', 
        'dt'        => 8,
        'formatter' => function( $d, $row ) { 
            return ' 
                <a href="javascript:void(0);" class="edit onlyDevAction" data-toggle="modal"  onclick="editData('.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').')">
                    <i class="bi bi-pencil-fill"></i> 
                </a>&nbsp;
                <a href="javascript:void(0);"  class="delete onlyDevAction" data-toggle="modal" onclick="deleteData('.$d.')">
                    <i class="bi bi-trash-fill"></i> 
                </a>
                
            '; 
        } 
    ),
); 
 
// Include SQL query processing class 
require '../assets/libraries/DataTables/ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);
