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

      <?php
       $dateSelected = date("Y-m-d"); //today 
       if(isset($_GET['dateselected'])){
         $dateSelected = $_GET['dateselected'];
       }
      ?>
      <div>
        Min. hrs considered: <?= MIN_HRS ?>
        <input type="date" value="<?php echo $dateSelected; ?>"name="dateSelected" id="dateSelected" class="form-control w-25" oninput="reloadData()">
      </div>

    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          
          <div class="card">

            <div class="card-body mt-4">
              
            <?php

              //get current year, month and maxdays of it
            
              $allDaysColArr = x_week_range($dateSelected);//from customFunctions.php


              $sql2 = " SELECT  t.assignee_id,  GROUP_CONCAT(t.id) as user_tickets, CONCAT(users.fname, ' ', users.lname) as fullname
                        FROM `tickets` as t  
                        JOIN users on users.id = t.assignee_id
                        GROUP BY t.assignee_id";
              $logStatusQuery2 = $conn->query($sql2);
              $user_ticketsArr = [];
              if ($logStatusQuery2->num_rows > 0) {
                while ($row2 = $logStatusQuery2->fetch_assoc()) {
                  $user_ticketsArr[$row2['fullname']] =  $row2['user_tickets'];
                }
              }


              $sql3 = " SELECT t.id as ticket_id, t.assignee_id, DATE_FORMAT(t.wip_close_datetime, '%Y-%m-%d') AS wip_close_datetime, DATE_FORMAT(t.wip_start_datetime, '%Y-%m-%d') AS wip_start_datetime, CONCAT(users.fname, ' ', users.lname) as fullname
                        FROM tickets AS t
                        JOIN users on users.id = t.assignee_id
                        WHERE t.wip_start_datetime";
              $logStatusQuery3 = $conn->query($sql3);
              $closedTicketsArr = [];
              if ($logStatusQuery3->num_rows > 0) {
                while ($row3 = $logStatusQuery3->fetch_assoc()) {
                  $closedTicketsArr[$row3['fullname']][$row3['ticket_id']] =  $row3;
                }
              }
              
              $sql = "SELECT 
                  user_id, fullname, id as ticket_id, SUM(atr.hrs) as total_hrs,  atr.c_status as c_status, atr.logTicketStatus as logTicketStatus, atr.datesl as dates, atr.wip_start_datetime as wip_start_datetime, atr.wip_close_datetime as wip_close_datetime, DAYNAME(atr.datesl) as dayname, GROUP_CONCAT(atr.id) as ticket_collection, GROUP_CONCAT(atr.hrs) as hrs_collection
                FROM week_days wd
                  LEFT JOIN (
                      SELECT 
                        t.id, CONCAT(users.fname, ' ', users.lname) as fullname,  t.c_status, l.c_status as logTicketStatus, DATE_FORMAT(t.wip_start_datetime, '%Y-%m-%d') as wip_start_datetime , DATE_FORMAT(t.wip_close_datetime, '%Y-%m-%d') as wip_close_datetime, DATE_FORMAT(l.dates, '%Y-%m-%d') as datesl, t.assignee_id, l.user_id, l.hrs 
                      FROM `tickets` as t JOIN log_history as l ON l.ticket_id = t.id 
                      JOIN users on users.id = l.user_id
                    ) atr
                    ON wd.week_day_num = DAYOFWEEK(atr.datesl)
                    GROUP BY DAYOFWEEK(atr.datesl), user_id
                    HAVING atr.datesl >= '" . $allDaysColArr[0] . "' && atr.datesl <= '" . $allDaysColArr[6] . "'
                    ORDER BY user_id, dates
                    ";

              $logStatusQuery = $conn->query($sql);

              //set structure to be used to loop into table tr
              $allData = [];
              if ($logStatusQuery->num_rows > 0) {
                while ($row = $logStatusQuery->fetch_assoc()) {
                  // $allData[] =  $row ;
                  // echo "<pre>"; print_r($row); die();
                  // $allData[$row['fullname']][$row['dates']] =  $row['total_hrs'];
                  $row['ticket_collection_arr'] = explode(",", $row['ticket_collection']);
                  $row['ticket_collection_all_arr'] = explode(",", $user_ticketsArr[$row['fullname']]);
                  if(isset($closedTicketsArr[$row['fullname']])) {
                    $row['wip_status_arr'] = $closedTicketsArr[$row['fullname']];
                  }

                  
                  $row['hrs_collection_arr'] = explode(",", $row['hrs_collection']);
                  $allData[$row['fullname']][$row['dates']] =  $row;
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
                    foreach ($allDaysColArr as $k => $this_weekday) {
                      if (isset($value[$this_weekday])) {
                        // $thisWeekDay = $value[$this_weekday];
                        //calculate total hrs
                        $total_hrs = $value[$this_weekday]['total_hrs'];

                        //get all tickets count which are WIP before this date
                        if($total_hrs >= MIN_HRS) {
                            //get log entry collection count 
                            $ticket_collection_arr = $value[$this_weekday]['ticket_collection_arr'];
                            $ticket_collection_all_arr = $value[$this_weekday]['ticket_collection_all_arr'];
                            $hrs_collection_arr = $value[$this_weekday]['hrs_collection_arr'];
                            // $logCollectionCount = count( $ticket_collection_arr);

                            //check those tickets only of which today exist in range of wip start and end date 
                            //all tickets of the user wip lies in this weekday
                              $wip_status_tickets_arr = $value[$this_weekday]['wip_status_arr'];
                              
                            $ignoreTicketId = [];
                            $a = $ticket_collection_all_arr;
                            foreach ($wip_status_tickets_arr as $ticket => $ticket_status_data) {        
                              if(( $this_weekday <  $ticket_status_data['wip_start_datetime'] ) || ( $ticket_status_data['wip_close_datetime'] && $this_weekday >  $ticket_status_data['wip_close_datetime'])){
                                //$ignoreTicketId[] = $ticket_status_data['ticket_id'];
                                //unset this id from tickets to consider
                                if (($key = array_search($ticket_status_data['ticket_id'], $ticket_collection_all_arr)) !== false) {
                                  unset($ticket_collection_all_arr[$key]);
                                }
                              }
                            }
                            
                            if(count($ticket_collection_all_arr) <= count($hrs_collection_arr)){//hrs entry should same as tickets or greater
                              echo '<td> <i class="bx bxs-check-square"></i> ('.$value[$this_weekday]['total_hrs'].') </td>';
                            } else {
                              echo '<td> <i class="bx bxs-x-circle"></i> ('.$total_hrs.')</td>';
                            }
                        } else {
                          echo '<td> <i class="bx bxs-x-circle"></i> ('.$total_hrs.')</td>';
                        }
                      } else {
                        //if we want to also check if there should be log then we can check
                        //means check if this user have any open ticket for this day
                        // echo '<td> <i class="bx bxs-x-circle"></i>( no log )</td>';
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