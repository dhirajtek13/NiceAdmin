<?php

define('BASE_PATH', 'http://niceadmin.local'); 
define('SITE_NAME', 'BugTracker'); 
define('USERID', '1'); //temporay value as long there is no login system

// Database credentials 
define('DB_HOST', 'localhost'); 
define('DB_USER', 'root'); 
define('DB_PASS', ''); 
define('DB_NAME', 'niceadmin'); 

// Connect to the database  
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);  
if($conn->connect_error){  
    die("Failed to connect with MySQL: " . $conn->connect_error);  
} 

// session_start();

?> 