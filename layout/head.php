<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
  header("location: pages-login.php");
  exit;
}
include "db/config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Tables / Data - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- version 1 inherited --> 
    <script src="./assets/libraries/js/jquery-3.6.4.min.js"  crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/css/custom_bt1.css" >
    <!-- <link rel="stylesheet" href="./assets/css/datatable_custom.css" > -->
    <!-- <link rel="stylesheet" href="./assets/css/mateial-icons.css" > -->
    <link rel="stylesheet" type="text/css" href="./assets/libraries/DataTables/jquery.dataTables.min.css"/>
    <script type="text/javascript" src="./assets/libraries/DataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="./assets/libraries/DataTables/dataTables.fixedHeader.min.js"></script>    
    <script src="./assets/libraries/js/sweetalert2.all.min.js"  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Mar 09 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>