<?php include('layout/head.php'); ?>
<?php
//Middleware: check user type and restrict access 
if (isset($_SESSION) && $_SESSION['user_type'] != 1) {
  header("location: pages-ticket.php");
  exit;
}
$today = date('Y-m-d');
$start_date = date('Y-m-01');
$end_date = date("Y-m-t", strtotime($today));
?>
<!-- Fetch type, status, assignee dropdown -->
<?php include "db/fetchConfiguration.php"; ?>
<?php include "db/fetch_dropdown_data.php"; ?>

<body>
  <?php include('layout/header.php'); ?>
  <?php include('layout/sidebar.php'); ?>


  <main id="main" class="main">

    <div class="pagetitle">
      <div>
        <h1>Ticket List</h1>
      </div>

      <div class="row mt-2">
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
        <div class="col-3"></div>
        <div class="col-3">
          <div class="addDataBtn"><a href="javascript:void(0);" class="btn btn-primary my-2 " onclick="addData()">Add New Ticket</a></div>
        </div>
      </div>


    </div><!-- End Page Title -->
    <?php
    $ticket_id = (isset($_GET['ticket_id'])) ? $_GET["ticket_id"] : '';
    ?>
    <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>" id="ticket_id_hidden">

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <!-- <h5 class="card-title">Datatables</h5> -->

              <!-- Table with stripped rows -->
              <table id="dataList" class="display " style="width:170%">
                <thead>
                  <tr>
                    <th>S.N</th>
                    <th scope="col">Ticket Id</th>
                    <th scope="col">Type</th>
                    <th scope="col">C.Status</th>
                    <th scope="col">Assignee</th>
                    <th scope="col">Assigned Date</th>
                    <th scope="col">P.Start Date</th>
                    <th scope="col">P.End Date</th>
                    <th scope="col">Planned Hours</th>
                    <th scope="col">A . Hours</th>
                    <th scope="col">A.Start Date</th>
                    <th scope="col">A.End Date</th>
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
                    <th>Assignee</th>
                    <th>Assigned Date</th>
                    <th>P.Start Date</th>
                    <th>P.End Date</th>
                    <th>A.Start Date</th>
                    <th>A.End Date</th>
                    <th>Planned Hours</th>
                    <th>A . Hours</th>
                    <th>Variance </th>
                    <th></th>
                  </tr>
                </tfoot>
                <tbody>

                </tbody>
              </table>
              <!-- <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Position</th>
                    <th scope="col">Age</th>
                    <th scope="col">Start Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row">1</th>
                    <td>Brandon Jacob</td>
                    <td>Designer</td>
                    <td>28</td>
                    <td>2016-05-25</td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Bridie Kessler</td>
                    <td>Developer</td>
                    <td>35</td>
                    <td>2014-12-05</td>
                  </tr>
                  <tr>
                    <th scope="row">3</th>
                    <td>Ashleigh Langosh</td>
                    <td>Finance</td>
                    <td>45</td>
                    <td>2011-08-12</td>
                  </tr>
                  <tr>
                    <th scope="row">4</th>
                    <td>Angus Grady</td>
                    <td>HR</td>
                    <td>34</td>
                    <td>2012-06-11</td>
                  </tr>
                  <tr>
                    <th scope="row">5</th>
                    <td>Raheem Lehner</td>
                    <td>Dynamic Division Officer</td>
                    <td>47</td>
                    <td>2011-04-19</td>
                  </tr>
                </tbody>
              </table> -->
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php include "layout/modal/pmticket_modal.php"; ?>

  <?php include('layout/footer.php'); ?>
  <script src="assets/js/ticket-table.js"></script>
  <script>

  </script>

</body>

</html>