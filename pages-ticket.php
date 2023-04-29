 <?php include('layout/head.php'); ?>
 <!-- Fetch type, status, assignee dropdown -->
<?php include "db/fetch_dropdown_data.php"; ?>
<body>
<?php include('layout/header.php'); ?>
<?php include('layout/sidebar.php'); ?>


  <main id="main" class="main">

    <div class="pagetitle">
    </div><!-- End Page Title -->
    <!-- <?php echo $_SESSION['user_id']; ?> -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
             
              <!-- Table with stripped rows -->
              <table  id="dataList" class="display" style="width:170%">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th scope="col">Ticket Id</th>
                        <th scope="col">Type</th>
                        <th scope="col">C.Status</th>
                        <!-- <th scope="col">Assignee</th> -->
                        <th scope="col">Assigned Date</th>
                        <th scope="col">P.Start Date</th>
                        <th scope="col">P.End Date</th>
                        <th scope="col">Planned Hours</th>
                        <th scope="col">A.Start Date</th>
                        <th scope="col">A.End Date</th>
                        <th scope="col">A . Hours</th>
                        <th scope="col">Variance </th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tfoot style="display:table-header-group">
                    <tr>
                        <th>S.N</th>
                        <th>Ticket Id</th>
                        <th>Type</th>
                        <th>C.Status</th>
                        <!-- <th>Assignee</th> -->
                        <th>Assigned Date</th>
                        <th>P.Start Date</th>
                        <th>P.End Date</th>
                        <th>Planned Hours</th>
                        <th>A.Start Date</th>
                        <th>A.End Date</th>
                        <th>A . Hours</th>
                        <th>Variance </th>
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
  <?php include "layout/modal/devticket_modal.php"; ?>
  <?php include('layout/footer.php'); ?>
  <script src="assets/js/pages-ticket-table.js"></script>

</body>

</html>