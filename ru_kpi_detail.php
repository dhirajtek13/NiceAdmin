<?php include('layout/head.php'); ?>
<?php
//Middleware: check user type and restrict access 
if (isset($_SESSION) && $_SESSION['user_type'] != 1) {
  header("location: pages-ticket.php");
  exit;
}

require_once './controller/customFunctions.php';
include('./db/fetchConfiguration.php');


// echo "<pre>"; print_r($CONFIG_ALL); die();
$today = date('Y-m-d');
if(isset($_GET['start_date'])) {
  $start_date = $_GET['start_date'];
  $end_date = $_GET['end_date'];
} else {
  $today = date('Y-m-d');
  $allDaysColArr = x_week_range($today);

  $start_date = $allDaysColArr[0];
  $end_date = $allDaysColArr[6];
}
if(isset($_GET['project'])) {
  $project = $_GET['project'];
} else {
  //default project
  $project = $CONFIG_ALL['default_project']['value1'];

} 

?>

<body>
  <?php include('layout/header.php'); ?>
  <?php include('layout/sidebar.php'); ?>


  <main id="main" class="main">

    <div class="pagetitle">
      <div>
        <h1>Resource Utilization List</h1>
      </div>

      <div class="row m-2">
        <div class="col-3 ">
          <div class="form-floating">
            <input type="date" value="<?=$start_date;?>" name="kpi_start_date" id="kpi_start_date" class="form-control" oninput="fetchRUData()">
            <label for="kpi_start_date">start date</label>
          </div>
        </div>
        <div class="col-3">
          <div class="form-floating">
            <input type="date" value="<?=$end_date;?>" name="kpi_end_date" id="kpi_end_date" class="form-control" oninput="fetchRUData()">
            <label for="kpi_end_date">end date</label>
          </div>
        </div>
        <div class="col-3">
          
        </div>

        <input type="hidden" name="hidden_actual_hrs" id="hidden_actual_hrs" value="<?= $CONFIG_ALL['actual_hrs']['value1'] ?>">
        <input type="hidden" name="hidden_working_days" id="hidden_working_days" value="<?= $CONFIG_ALL['working_days']['value1'] ?>">
        <input type="hidden" name="hidden_project" id="hidden_project" value="<?=$project  ?>">
      </div>

      <!-- <div class="addDataBtn"><a href="javascript:void(0);" class="btn btn-primary my-2 " onclick="addData()" >Add New Ticket Status</a></div> -->
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <!-- <h5 class="card-title">Datatables</h5> -->

              <!-- Table with stripped rows -->
              <table id="phptable" class="phptableclass display" style="width:100%">
              </table>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php include('layout/footer.php'); ?>
  <script src="assets/js/ru-table.js"></script>
  <script>

  </script>

</body>

</html>