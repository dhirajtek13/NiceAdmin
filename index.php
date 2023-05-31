<?php include('layout/head.php'); 
 if($_SESSION['user_type'] != 1) {
  header("Location: tickets.php");
 } 
?>

<body>
  <?php include('layout/header.php'); ?>

  <?php include('controller/customFunctions.php'); ?>
  <?php include('db/fetchConfiguration.php'); ?>


  <?php include('layout/sidebar.php'); ?>


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>



      <div class="form-floating col-2">
        <?php //echo "<pre>"; print_r($PROJECTS_ALL); die();

        ?>
        <select name="project_selection" id="project_selection" class="form-control" onchange="projectLoad()">
          <option value='0'>All</option>
          <?php
          foreach ($PROJECTS_ALL as $project_code => $project_details) {
            if (isset($projectSelected) && $projectSelected == $project_details['project_id']) {
              echo "<option value='" . $project_details['project_id'] . "' selected>" . $project_details['project_name'] . "</option>";
            } else {
              echo "<option value='" . $project_details['project_id'] . "'>" . $project_details['project_name'] . "</option>";
            }
          }
          ?>
        </select>
        <label for="floatingName">Select Project</label>
        <input type="hidden" name="project_selection_value" id="project_selection_value" value="0">
        <input type="hidden" name="hidden_actual_hrs" id="hidden_actual_hrs" value="<?= $CONFIG_ALL['actual_hrs']['value1'] ?>">
      </div>

    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <div class="col-lg-12">
          <div class="card info-card">
            <h2 class="cardh1">Weekly Log Check</h2>
            <?php //include('layout/dashboard/weekly_log.php'); 
            ?>
            <?php $dateSelected = date("Y-m-d"); ?>
            <div class="m-3">
              Min. hrs considered: <?= $CONFIG_ALL['actual_hrs']['value1'] ?><br />
              <div class="form-floating">
                <input type="date" value="<?php echo $dateSelected; ?>" name="dateSelected" id="dateSelected" class="form-control w-25" oninput="reloadData()">
                <label for="floatingName">Select any day of the week</label>
              </div>
            </div>
            <div class="card-body mt-4">
              <span class="weekly_report_table_response">
                <table id="phptable" class="display table table-striped " style="width:100%">
                </table>
              </span>
            </div>
          </div>
        </div>


        <div class="col-6">
          <div class="card info-card">
            <div class="row">
              <div class="col-12">
                <h2 class="cardh1">Efforts</h2>
                <?php include('layout/dashboard/efforts.php'); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card info-card">
            <div class="row">
              <div class="col-12">
                <h2 class="cardh1">Count</h2>
                <?php include('layout/dashboard/counts.php'); ?>
              </div>
            </div>
          </div>
        </div>


        <!-- Ticket Status -->
        <div class="col-6">
          <div class="card info-card">
            <h2 class="cardh1">Ticket Status</h2>
            <?php include('layout/dashboard/ticket_status.php'); ?>
            <div class="card-body mt-4">
              <span class="ticket_status_table_response">
                <table id="phptable2" class="display table table-striped " style="width:100%">
                </table>
              </span>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="card info-card">
            <h2 class="cardh1">KPIs</h2>
            <?php include('layout/dashboard/otd_data.php'); ?>
            <div class="card-body mt-4">
              <span class="kpiTable1_table_response">
                <table id="kpiTable1" class="display table table-striped " style="width:100%">
                </table>
              </span>
            </div>
          </div>
        </div>



      </div>
    </section>

  </main><!-- End #main -->

  <?php include('layout/footer.php'); ?>

  <script src="assets/js/weeklylog-table.js"></script>
  <!-- <script src="assets/js/kpi-table.js"></script> -->
</body>

</html>