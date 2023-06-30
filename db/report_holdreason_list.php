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
$CLOSED_STATUS_ID = '2';//hold

$db_string = "WITH ranked_messages AS 
                ( SELECT lt.id, t.ticket_id, lt.remark, t.c_status, lt.dates, cs.type_name,  ROW_NUMBER() OVER (PARTITION BY lt.ticket_id ORDER BY lt.id DESC) AS rn 
                    FROM log_timing AS lt 
                    LEFT JOIN tickets AS t ON t.id = lt.ticket_id 
                    LEFT JOIN c_status_types AS cs ON cs.id = t.c_status
                    WHERE t.c_status IN  ($CLOSED_STATUS_ID)
                    AND t.c_status = lt.c_status 
                ) 
                SELECT * FROM ranked_messages WHERE rn = 1 ";

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
    array( 'db' => 'remark',  'dt' => 4 ),
    array( 'db' => 'type_name',  'dt' => 2 ),
    array( 
        'db'        => 'dates', 
        'dt'        => 3, 
        'formatter' => function( $d, $row ) { 
           return ($d != '0000-00-00 00:00:00') ?  date( 'd-m-Y', strtotime($d)) : ''; 
        } 
    )
); 
 
// Include SQL query processing class 
require '../assets/libraries/DataTables/ssp.class.php'; 
 
// Output data as json format 
echo json_encode( 
    SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns ) 
);
