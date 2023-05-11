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

              $sql = "                
                SELECT 
                  user_id, fullname, id as ticket_id, SUM(atr.hrs) as total_hrs,  atr.datesl as dates, atr.wip_start_datetime as wip_start_datetime, DAYNAME(atr.datesl) as dayname, GROUP_CONCAT(atr.id) as ticket_collection, GROUP_CONCAT(atr.hrs) as hrs_collection
                FROM week_days wd
                  LEFT JOIN (
                      SELECT 
                        t.id, CONCAT(users.fname, ' ', users.lname) as fullname,  t.c_status, DATE_FORMAT(t.wip_start_datetime, '%Y-%m-%d') as wip_start_datetime , DATE_FORMAT(l.dates, '%Y-%m-%d') as datesl, t.assignee_id, l.user_id, l.hrs 
                      FROM `tickets` as t JOIN log_history as l ON l.ticket_id = t.id 
                      JOIN users on users.id = l.user_id
                    ) atr
                    ON wd.week_day_num = DAYOFWEEK(atr.datesl)
                    GROUP BY DAYOFWEEK(atr.datesl), user_id
                    HAVING atr.datesl >= '" . $allDaysColArr[0] . "' && atr.datesl <= '" . $allDaysColArr[6] . "'
                    ORDER BY user_id, dates
                    "
                
                ;

              $logStatusQuery = $conn->query($sql);

              //set structure to be used to loop into table tr
              $allData = [];
              if ($logStatusQuery->num_rows > 0) {
                while ($row = $logStatusQuery->fetch_assoc()) {
                  // $allData[] =  $row ;
                  // echo "<pre>"; print_r($row); die();
                  // $allData[$row['fullname']][$row['dates']] =  $row['total_hrs'];
                  $row['ticket_collection_arr'] = explode(",", $row['ticket_collection']);
                  $row['hrs_collection_arr'] = explode(",", $row['hrs_collection']);
                  $allData[$row['fullname']][$row['dates']] =  $row;
                }
              } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      No record found.
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
              }

              // echo "<pre>"; print_r($allData); die();
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
                        if( (count($value[$v]['ticket_collection_arr']) == count($value[$v]['hrs_collection_arr'])) &&  $value[$v]['total_hrs'] >= 7.5) {
                          echo '<td> <i class="bx bxs-check-square"></i> ('.$value[$v]['total_hrs'].') </td>';
                        } else {
                          echo '<td> <i class="bx bxs-x-circle"></i> ('.$value[$v]['total_hrs'].')</td>';
                        }
                      } else {
                        echo '<td> <i class="bx bxs-x-circle"></i></td>';
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