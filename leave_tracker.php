<?php include('layout/head.php');
require_once './controller/customFunctions.php';
require_once './db/fetch_dropdown_data.php';
?>
<?php
//Middleware: check user type and restrict access 
if (isset($_SESSION) && $_SESSION['user_type'] != 1) {
  // header("location: pages-ticket.php");
  //exit;
}

$current_user_id = $_SESSION['user_id'];
// print_r($current_user_id); die();
$dateSelected = date('m');


$allDaysColArr = dates_month(date('m'), date('Y'));
// echo "<pre>"; print_r($allDaysColArr); die();

//get current year, month and maxdays of it
// $allDaysColArr = dates_month(date('m'), date('Y'));
?>

<body>
  <?php include('layout/header.php'); ?>
  <?php include('layout/sidebar.php'); ?>


  <main id="main" class="main">

    <div class="pagetitle">
      <div>
        <h1>Leave Tracker</h1>
      </div>

      <!-- <div class="addDataBtn"><a href="javascript:void(0);" class="btn btn-primary my-2 " onclick="addData()" >Add New Holiday</a></div> -->

      <div class="form-floating">
        <!-- <input type="date" value="2023-06-07" name="dateSelected" id="dateSelected" class="form-control w-25" oninput="fetchLeaveTrackerData()"> -->
        <input type="month" value="<?php echo date('Y').'-'.date('m')  ?>" name="dateSelected" id="dateSelected" class="form-control w-25" oninput="fetchLeaveTrackerData()">
        <label for="floatingName">Select month and year</label>

      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-12">
          <table id="phptable" class="phptableclass display" style="width:225%;">
            <thead class="org_thead">
              <tr>

                <th>S.N</th>
                <th>Employee Name</th>
                <?php
                foreach ($allDaysColArr as $index => $value) {
                  $day1 = strtotime($value);
                  $day2 = date("D", $day1);
                  echo "<th class='no-sort'>$value ($day2)</th>";
                }
                ?>
              </tr>

            </thead>
          </table>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php include "layout/modal/leaveupdate_modal.php"; ?>

  <?php include('layout/footer.php'); ?>
  <script src="assets/js/leave-tracker-table.js"></script>
  <script>

  </script>

</body>

</html>