<?php 

include_once 'config.php'; 
// Database connection info 
$dbDetails = array( 
    'host' => DB_HOST, 
    'user' => DB_USER, 
    'pass' => DB_PASS, 
    'db'   => DB_NAME 
); 

$today = date('Y-m-d');
$startdate = date('Y-m-01');
$enddate = date("Y-m-t", strtotime($today));
if(isset($_GET['start_date'])) {
    $startdate = $_GET['start_date'];
    $enddate = $_GET['end_date'];
}

// DB table to use 
$CLOSED_STATUS_ID = '3,7,19,18,17,16';

$db_string = "SELECT tickets.id as id1, ticket_id, planned_hrs, actual_hrs, c_status_types.type_name, (100 - ROUND((actual_hrs / planned_hrs )*100,2)) AS per_completed, (planned_hrs-actual_hrs) AS variance 
                FROM `tickets` 
                LEFT JOIN c_status_types ON c_status_types.id = tickets.c_status
                WHERE (100 - ROUND((actual_hrs / planned_hrs )*100,2)) <  30 
                AND DATE_FORMAT(tickets.`assigned_date`, '%Y-%m-%d') <= '$enddate' AND DATE_FORMAT(tickets.`assigned_date`, '%Y-%m-%d') >= '$startdate'
                AND c_status NOT IN ($CLOSED_STATUS_ID) ";

$table = <<<EOT
    (
        $db_string
    ) temp
    EOT;
 
// Table's primary key 
$primaryKey = 'id1';
 
$columns = array( 
    array( 
        'db' => 'id1', 
        'dt' => 0, 
        'formatter' => function ($d, $row){
            return $row['id1']; 
        }
    ), 
    array( 
        'db' => 'ticket_id',  
        'dt' => 1, 
        'formatter' => function( $d, $row ) { 
            return "<a href='/tickets.php?ticket_id=".$d."'>".$d. "</a>"; 
         } 
     ),
    array( 'db' => 'type_name',  'dt' => 2 ),
    array( 'db' => 'planned_hrs',  'dt' => 3 ),
    array( 'db' => 'actual_hrs',  'dt' => 4 ),
    array( 'db' => 'per_completed',  'dt' => 5 ),
    array( 'db' => 'variance',  'dt' => 6 ),
); 
 
// Include SQL query processing class 
require '../assets/libraries/DataTables/ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);
