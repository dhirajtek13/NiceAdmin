<?php 

include_once 'config.php'; 
// Database connection info 
$dbDetails = array( 
    'host' => DB_HOST, 
    'user' => DB_USER, 
    'pass' => DB_PASS, 
    'db'   => DB_NAME 
); 
// print_r($dbDetails); die();
// DB table to use 
$CLOSED_STATUS_ID = '3,7,19,18,17,16';

$db_string = "SELECT id, ticket_id, planned_hrs, actual_hrs, plan_end_date, (planned_hrs-actual_hrs) AS variance, DATEDIFF(NOW(), plan_end_date) AS days_behind
                FROM `tickets` 
                WHERE planned_hrs < actual_hrs
                AND c_status NOT IN ($CLOSED_STATUS_ID) ";

$table = <<<EOT
    (
        $db_string
    ) temp
    EOT;
 
// Table's primary key 
$primaryKey = 'id';
 
$columns = array( 
    array( 
        'db' => 'id', 
        'dt' => 0, 
        'formatter' => function ($d, $row){
            return $row['id']; 
        }
    ), 
    array( 
        'db' => 'ticket_id',  
        'dt' => 1, 
        'formatter' => function( $d, $row ) { 
            return "<a href='/tickets.php?ticket_id=".$d."'>".$d. "</a>"; 
         } 
     ),
    array( 'db' => 'planned_hrs',  'dt' => 2 ),
    array( 'db' => 'actual_hrs',  'dt' => 3 ),
    array( 
        'db'        => 'plan_end_date', 
        'dt'        => 4, 
        'formatter' => function( $d, $row ) { 
           return ($d != '0000-00-00 00:00:00') ?  date( 'd-m-Y', strtotime($d)) : ''; 
        } 
    ),
    array( 'db' => 'variance',  'dt' => 5 ),
    array( 'db' => 'days_behind',  'dt' => 6 ),
); 
 
// Include SQL query processing class 
require '../assets/libraries/DataTables/ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);
