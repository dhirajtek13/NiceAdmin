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
// $CLOSED_STATUS_ID = '3,7,19,18,17,16';

$db_string = "SELECT t.id, CONCAT(users.fname, ' ', users.lname) AS username, t.source, t.type_id, t.assignee_id, t.actual_hrs, 
                COUNT(IF(t.type_id = 2,1,NULL)) storyCount, 
                COUNT(IF(t.type_id = 1,1,NULL)) bugCount, 
                SUM(IF((t.type_id = 2 || t.type_id = 7), t.actual_hrs,0)) storyEfforts, 
                SUM(IF(t.type_id = 1, t.actual_hrs,0)) bugEfforts, 
                SUM(IF(t.type_id = 6, t.actual_hrs,0)) supportEfforts,
                SUM(IF(t.type_id NOT IN (2,1,7,6), t.actual_hrs,0)) otherEfforts
                FROM `tickets` AS t 
                LEFT JOIN users ON users.id = t.assignee_id 
                WHERE DATE_FORMAT(t.assigned_date, '%Y-%m-%d') <= '$enddate' AND DATE_FORMAT(t.assigned_date, '%Y-%m-%d') >= '$startdate'
                GROUP BY t.assignee_id";
// print_r($db_string); die();
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
    array( 'db' => 'storyCount',  'dt' => 2 ),
    array( 'db' => 'bugCount',  'dt' => 3 ),
    array( 'db' => 'storyEfforts',  'dt' => 4 ),
    array( 'db' => 'bugEfforts',  'dt' => 5 ),
    array( 'db' => 'supportEfforts',  'dt' => 6 ),
    array( 'db' => 'otherEfforts',  'dt' => 7 ),
    
); 
 
// Include SQL query processing class 
require '../assets/libraries/DataTables/ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);
