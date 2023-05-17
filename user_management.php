
<?php include('layout/head.php'); ?>
 <?php 
        //Middleware: check user type and restrict access 
        if(isset($_SESSION) && $_SESSION['user_type'] != 1) {
          header("location: pages-ticket.php");
          exit;
        }
?>
<!-- Fetch type, status, assignee dropdown -->
<?php include "db/fetch_dropdown_data.php"; ?>

<body>
<?php include('layout/header.php'); ?>
<?php include('layout/sidebar.php'); ?>


  <main id="main" class="main">

    <div class="pagetitle">
      <div><h1>Users List</h1></div>
      
      <div class="addDataBtn"><a href="add-user.php" class="btn btn-primary my-2 " >Add New User</a></div>
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
                        <th scope="col">S.N</th>
                        <th scope="col">Username</th>
                        <th scope="col">Name</th>
                        <th scope="col">User Type</th>
                        <th scope="col">Employee Id</th>
                        <th scope="col">Designation</th>
                        <th scope="col">Email</th>
                        <!-- <th scope="col">Password</th> -->
                        <th scope="col">Fname</th>
                        <th scope="col">Lname</th>
                        <th scope="col">Project ID</th>
                        <th scope="col">Project Name</th>
                        <th scope="col">Project Name</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tfoot style="display:table-header-group">
                    <tr>
                        <th>S.N</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>User Type</th>
                        <th>Employee Id</th>
                        <th>Designation</th>
                        <th>Email</th>
                        <!-- <th>Password</th> -->
                        <th>Fname</th>
                        <th>Lname</th>
                        <th>Project ID</th>
                        <th>Project Name</th>
                        <th>Project Name</th>
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

  <?php include "layout/modal/user_management_modal.php"; ?>
  <?php include "layout/modal/change_passsword_modal.php"; ?>

  <?php include('layout/footer.php'); ?>
  <script src="assets/js/user-management-table.js"></script>
  <script>
   
  </script>

</body>

</html>