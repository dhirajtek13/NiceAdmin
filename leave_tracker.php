
<?php include('layout/head.php'); ?>
 <?php 
        //Middleware: check user type and restrict access 
        if(isset($_SESSION) && $_SESSION['user_type'] != 1) {
          header("location: pages-ticket.php");
          exit;
        }
?>

<body>
<?php include('layout/header.php'); ?>
<?php include('layout/sidebar.php'); ?>


  <main id="main" class="main">

    <div class="pagetitle">
      <div><h1>Leave Tracker</h1></div>
      
      <!-- <div class="addDataBtn"><a href="javascript:void(0);" class="btn btn-primary my-2 " onclick="addData()" >Add New Holiday</a></div> -->
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
        </div>
    </section>

  </main><!-- End #main -->


  <?php include('layout/footer.php'); ?>
  <!-- <script src="assets/js/holiday-management-table.js"></script> -->
  <script>
   
  </script>

</body>

</html>