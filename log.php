
 <?php include('layout/head.php'); ?>
 <?php 
        //Middleware: check user type and restrict access 
        // if(isset($_SESSION) && $_SESSION['user_type'] != 1) {
        //   header("location: pages-ticket.php");
        //   exit;
        // }
      if(!isset($_GET['ticket'])) { //redirect if no ticket id given to list its log
          header("Location: /");
      } else {
          //TODO recheck check if $_GET['ticket'] ticket belongs to current user otherwise redirect


          echo "<input type='hidden' name='ticketId' id='ticketId' value='".$_GET['ticket']."'>";
      }
?>
<!-- Fetch type, status, assignee dropdown -->
<?php include "db/fetch_dropdown_data.php"; ?>
<?php include "db/fetch_ticket_data.php"; ?>

<body>
<?php include('layout/header.php'); ?>
<?php include('layout/sidebar.php'); ?>


  <main id="main" class="main">

    <div class="pagetitle">
      <div><h1>Log List</h1></div>
      
      <div class="addDataBtn">
        <a href="javascript:void(0);" class="btn btn-success " onclick="addData()"><i class="bi bi-plus-circle-fill"></i> Add New Log</a>
        <?php if($_GET['ticket']) echo '<a href="/timing.php?ticket='.$_GET['ticket'].'" class="btn btn-secondary"><i class="bi bi-eye-fill"></i> View Timeline</a>'; ?>
      </div>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <!-- <h5 class="card-title">Datatables</h5> -->
             
              <!-- Table with stripped rows -->
              <table id="dataList" class="display" style="width:100%">
                  <thead>
                      <tr>
                          <th>S.N</th>
                          <th>Date</th>
                          <th>Hours</th>
                          <th>Status</th>
                          <th>What Is Done</th>
                          <th>What is pending</th>
                          <th>What support is required</th>
                          <th></th>
                      </tr>
                  </thead>
                  <tfoot style="display:table-header-group">
                      <tr>
                          <th>S.N</th>
                          <th>Date</th>
                          <th>Hours</th>
                          <th>Status</th>
                          <th>What Is Done</th>
                          <th>What is pending</th>
                          <th>What support is required</th>
                          <th></th>
                      </tr>
                  </tfoot>
                  <tbody>
                      
                  </tbody>
              </table>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php include "layout/modal/log_modal.php"; ?>

  <?php include('layout/footer.php'); ?>
  <script src="assets/js/log-table.js"></script>
  <script>
   
  </script>

</body>

</html> -->