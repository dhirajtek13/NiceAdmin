<?php
include('layout/head.php');

//Middleware: check user type and restrict access 
if (isset($_SESSION) && $_SESSION['user_type'] != 1) {
  header("location: pages-ticket.php");
  exit;
}
$today = date('Y-m-d');
$start_date = date('Y-m-01');
$end_date = date("Y-m-t", strtotime($today));
?>

<body>
  <?php include('layout/header.php'); ?>
  <?php include('layout/sidebar.php'); ?>


  <main id="main" class="main">

    <div class="pagetitle">
      <div>
        <h1>Report - Tickets status with Reason </h1>
      </div>
      

      <div class="row mt-2">
      <div class="form-floating col-2">
        <select name="status_selector" id="status_selector" class="form-control" onchange="statusChanged()">
          <option value="2">Hold</option>
          <option value="4">Reassigned</option>
          <option value="10,11,12">Rework</option>
        </select>
        <label for="floatingName">Select Status</label>
      </div>
        <div class="col-3 ">
          <div class="form-floating">
            <input type="date" value="<?= $start_date; ?>" name="kpi_start_date" id="kpi_start_date" class="form-control" oninput="fetchRUData()">
            <label for="kpi_start_date">start date</label>
          </div>
        </div>
        <div class="col-3">
          <div class="form-floating">
            <input type="date" value="<?= $end_date; ?>" name="kpi_end_date" id="kpi_end_date" class="form-control" oninput="fetchRUData()">
            <label for="kpi_end_date">end date</label>
          </div>
        </div>
        <div class="col-3">
        </div>
      </div>

    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <table id="dataList" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>S.N</th>
                    <th scope="col">Ticket Id</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date</th>
                    <th scope="col">Reason</th>
                  </tr>
                </thead>
                <tfoot style="display:table-header-group">
                  <tr>
                    <th>S.N</th>
                    <th>Ticket Id</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Reason</th>
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

  <?php include('layout/footer.php'); ?>
  <script src="assets/js/report_holdreason-table.js"></script>
  <script>

  </script>

</body>

</html>