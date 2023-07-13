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
$STORY_TICKET_TYPE  = 2;//TODO
$ACTIVITY_TICKET_TYPE = 7;//TODO

$db_string = "SELECT t.id, CONCAT(users.fname, ' ', users.lname) AS username, t.ticket_id, tt.ticket_id AS source_ticket_id, t.source, t.assignee_id, t.actual_hrs , SUM(lh.hrs) AS total_hrs 
                FROM `tickets` AS t 
                LEFT JOIN users ON users.id = t.assignee_id 
                JOIN tickets AS tt ON tt.id = t.source 
                LEFT JOIN log_history AS lh ON lh.ticket_id = t.id
                WHERE DATE_FORMAT(lh.dates, '%Y-%m-%d') <= '$enddate' AND DATE_FORMAT(lh.dates, '%Y-%m-%d') >= '$startdate'
                AND t.type_id = $STORY_TICKET_TYPE || t.type_id = $ACTIVITY_TICKET_TYPE
                GROUP BY t.source
                ";

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
    array( 'db' => 'username',  'dt' => 1 ),
    array( 'db' => 'source_ticket_id',  'dt' => 2 ),
    array( 'db' => 'total_hrs',  'dt' => 3 , 'formatter' => function ($d, $row){  return ($d != '') ? $d: 0; } ),  
); 
 
// Include SQL query processing class 
require '../assets/libraries/DataTables/ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);
