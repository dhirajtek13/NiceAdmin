<?php include('layout/head.php'); ?>

<body>
  <?php include('layout/header.php'); ?>
  <?php include('layout/sidebar.php'); ?>


  <main id="main" class="main">

    <div class="pagetitle">
      <div>
        <h1>Log Status Check</h1>
      </div>

    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body mt-4">

              <?php
              //get current year, month and maxdays of it
              $maxDays = date('t');
              $month = date('m');
              $year = date('Y');

              // array of all days in the current month
              $allDaysColArr = [];
              for ($i = 1; $i <= date('t'); $i++) {
                $days = mktime(12, 0, 0, $month, $i, $year);
                $allDaysColArr[] = date('Y-m-d', $days);
              }

              $start_date = $year."-".$month."-01";
              $end_date = $year."-".$month."-".$maxDays;
                
              $sql = "SELECT  user_id, CONCAT(users.fname, ' ', users.lname) as fullname, ticket_id, SUM(hrs) as total_hrs, DATE_FORMAT(`dates`, '%Y-%m-%d') as dates 
                FROM log_history 
                JOIN users on users.id = log_history.user_id
                GROUP BY DATE_FORMAT(`dates`, '%Y-%m-%d') 
                HAVING dates >= '". $start_date."' && dates <= '". $end_date."' 
                ORDER BY user_id, dates";

              $logStatusQuery = $conn->query($sql);

              //set structure to be used to loop into table tr
              $allData = [];
              if ($logStatusQuery->num_rows > 0) {
                    while ($row = $logStatusQuery->fetch_assoc()) {
                      // $allData[] =  $row ;
                      $allData[$row['fullname']] [$row['dates']] =  $row['total_hrs'];
                  }
              } else {
                echo 'no monthly log status found!';
              }

              
              ?>

              <table id="phptable" class="display" style="width:350%">
                <thead>
                  <tr>
                    <th>S.N</th>
                    <th>Employee Name</th>
                    <?php
                      foreach ($allDaysColArr as $key => $value) {
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
                      if(isset($value[$v])){
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

        </div>
      </div>
    </section>

  </main><!-- End #main -->


  <?php include('layout/footer.php'); ?>

  <script src="assets/js/log-status-table.js"></script>



</body>

</html>