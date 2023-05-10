<?php include('layout/head.php'); ?>

<body>
  <?php include('layout/header.php'); ?>

  <?php include('controller/customFunctions.php'); ?>


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
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="card">

            <div class="card-body mt-4">
            <?php

              //get current year, month and maxdays of it
              $dateSelected = date("Y-m-d"); //today 
              if(isset($_GET['dateselected'])){
                $dateSelected = $_GET['dateselected'];
              }

              $allDaysColArr = x_week_range($dateSelected);//from customFunctions.php

              $sql = "SELECT  user_id, CONCAT(users.fname, ' ', users.lname) as fullname, ticket_id, SUM(hrs) as total_hrs, DATE_FORMAT(`dates`, '%Y-%m-%d') as dates 
                FROM log_history 
                JOIN users on users.id = log_history.user_id
                GROUP BY DATE_FORMAT(`dates`, '%Y-%m-%d'), user_id 
                HAVING dates >= '" . $allDaysColArr[0] . "' && dates <= '" . $allDaysColArr[6] . "' 
                ORDER BY user_id, dates";

              $logStatusQuery = $conn->query($sql);

              //set structure to be used to loop into table tr
              $allData = [];
              if ($logStatusQuery->num_rows > 0) {
                while ($row = $logStatusQuery->fetch_assoc()) {
                  // $allData[] =  $row ;
                  $allData[$row['fullname']][$row['dates']] =  $row['total_hrs'];
                }
              } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      No record found.
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
              }

              ?>

              <table id="phptable" class="display" style="width:100%">
                <thead>
                  <tr>
                    <th>S.N</th>
                    <th>Employee Name</th>
                    <?php
                    foreach ($allDaysColArr as $value) {
                      echo "<th class='no-sort'>$value</th>";
                    }
                    ?>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  $sr = 0;

                  foreach ($allData as $key => $value) {
                    $sr++;
                    echo '<tr>';
                    echo "<td>$sr</td>";
                    echo "<td>$key</td>";
                    // echo "<td>".array_keys($value)."</td>";

                    foreach ($allDaysColArr as $k => $v) {
                      if (isset($value[$v])) {
                        echo "<td>$value[$v]</td>";
                      } else {
                        echo '<td> <i class="bi bi-x"></i> </td>';
                      }
                    }
                    echo '</tr>';
                  }
                  ?>
                </tbody>
                <tbody>

                </tbody>
              </table>
            </div>

          </div>
        </div><!-- End Reports -->

      </div>
    </section>

  </main><!-- End #main -->

  <?php include('layout/footer.php'); ?>

  <script src="assets/js/weeklylog-table.js"></script>
</body>

</html>