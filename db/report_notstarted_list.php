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

$db_string = "SELECT t.id, t.ticket_id, DATE_FORMAT(lh.dates,  '%d-%m-%Y') AS first_log_date, DATE_FORMAT(t.plan_start_date, '%d-%m-%Y') AS plan_start_date, DATE_FORMAT(t.actual_start_date, '%d-%m-%Y') AS actual_start_date
                FROM log_history AS lh 
                LEFT JOIN tickets AS t ON t.id = lh.ticket_id 
                WHERE plan_start_date > actual_start_date
                AND t.c_status NOT IN ($CLOSED_STATUS_ID)
                GROUP BY lh.ticket_id ";

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
    array( 
        'db'        => 'plan_start_date', 
        'dt'        => 2, 
        'formatter' => function( $d, $row ) { 
           return ($d != '0000-00-00') ?  date( 'd-m-Y', strtotime($d)) : ''; 
        } 
    ),
    array( 
        'db'        => 'actual_start_date', 
        'dt'        => 3, 
        'formatter' => function( $d, $row ) {
           return ($d != '00-00-0000') ?  date( 'd-m-Y', strtotime($d)) : '-'; 
        } 
    ),
    array( 
        'db'        => 'first_log_date', 
        'dt'        => 4, 
        'formatter' => function( $d, $row ) { 
           return ($d != '0000-00-00') ?  date( 'd-m-Y', strtotime($d)) : ''; 
        } 
    )
); 
 
// Include SQL query processing class 
require '../assets/libraries/DataTables/ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);
