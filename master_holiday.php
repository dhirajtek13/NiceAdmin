
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
      <div><h1>Holiday List</h1></div>
      
      <div class="addDataBtn"><a href="javascript:void(0);" class="btn btn-primary my-2 " onclick="addData()" >Add New Holiday</a></div>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <!-- <h5 class="card-title">Datatables</h5> -->
             
              <!-- Table with stripped rows -->
              <table  id="dataList" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tfoot style="display:table-header-group">
                    <tr>
                        <th>S.N</th>
                        <th> Name</th>
                        <th> Description</th>
                        <th> Start Date</th>
                        <th> End Date</th>
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

  <?php include "layout/modal/holiday_management_modal.php"; ?>

  <?php include('layout/footer.php'); ?>
  <script src="assets/js/holiday-management-table.js"></script>
  <script>
   
  </script>

</body>

</html>