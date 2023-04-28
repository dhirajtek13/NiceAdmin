
 <?php include('layout/head.php'); ?>

<body>
<?php include('layout/header.php'); ?>
<?php include('layout/sidebar.php'); ?>


  <main id="main" class="main">

    <div class="pagetitle">
      <div><h1>Log List for <b><?php echo $_GET['ticket']; ?></b></h1></div>
      
      <div class="addDataBtn">
        <a href="javascript:void(0);" class="btn btn-success " onclick="addData()"><i class="bi bi-plus-circle-fill"></i> Add New Log</a>
        <?php if($_GET['ticket']) echo '<a href="/timeline.php?ticket='.$_GET['ticket'].'" class="btn btn-secondary"><i class="bi bi-eye-fill"></i> View Timeline</a>'; ?>
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
  <?php 
  if($_SESSION['user_type'] != 1) {
    echo '<script src="assets/js/devlog-table.js"></script>';
  } else {
    echo '<script src="assets/js/log-table.js"></script>';
  }
  ?>
  <script>
   
  </script>

</body>

</html>