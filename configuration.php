<?php include('layout/head.php'); ?>
<?php

// session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
  header("location: pages-login.php");
  exit;
}

//fetch system configuration
 include('db/fetchConfiguration.php');

//TODO - move this into user_eventHandler
$showAlert = false;
$showError = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $actual_hrs = $_POST['actual_hrs'];
  $kpi_ticket_status = $_POST['kpi_ticket_status'];

  // print_r($_POST); die();
  foreach ($_POST as $conf_name => $conf_values) {
    // print_r($conf_name); echo '<br>';
    // print_r($conf_values);
    // die();
    if(is_array($conf_values)) {
      $conf_values = (string)implode(",", $conf_values);
      // print_r($conf_values); die();
    }
    // $conf_values = (string)$conf_values;
    //check if this name already exist
    $existSql = "SELECT * FROM `configurations` WHERE name = '$conf_name'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if ($numExistRows > 0) {
      //if exist then update
      $sql = "UPDATE configurations SET value1='".$conf_values."' WHERE name='".$conf_name."'";
      if ($conn->query($sql) === TRUE) {
        // print_r($conf_name); die();
        // echo "Record updated successfully";
      } else {
        $showError = "Update failed! error: ". $conn->error;
      }
    } else {
      //if not then create
      $sqlQ = "INSERT INTO configurations (name,value1) VALUES (?,?)"; 
      $stmt = $conn->prepare($sqlQ);
      $stmt->bind_param("ss", $conf_name, $conf_values); 
      $insert = $stmt->execute(); 
    }

  }
  $showAlert = true;
} else {

    // echo "<pre>"; print_r($configuration_arr); die();
}
  //TODO fetch values from table and display in form
  $existSql = "SELECT * FROM `configurations`";
    $result = mysqli_query($conn, $existSql);
    // $numExistRows = mysqli_num_rows($result);
    $configuration_arr = [];
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $configuration_arr[] = $row;
      }
    }
?>
<!-- Fetch type, status, assignee dropdown -->
<?php include "db/fetch_dropdown_data.php"; ?>

<body>
  <?php include('layout/header.php'); ?>
  <?php include('layout/sidebar.php'); ?>


  <main id="main" class="main mt-2">
    <section class="section dashboard">
      <div class="row">
        <main>
          <div class="container mt-5">
            <?php
            if ($showAlert) {
              echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
                  Successfully created new user!
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            if ($showError) {
              echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ' . $showError . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div> ';
            }
            ?>

            <div class="row justify-content-center">

              <div class="col-lg-8 col-md-6 d-flex flex-column align-items-center justify-content-center">
                <!-- End Logo -->
                <div class="card col-lg-10">
                  <div class="card-body">
                    <h5 class="card-title">Configuration</h5>

                    <!-- Floating Labels Form -->
                    <form class="row " method="post" action="configuration.php">

                      <?php 
                      // echo "<pre>"; print_r($configuration_arr); die();
                      foreach ($configuration_arr as $key => $value) {
                        // echo "<pre>"; print_r($key); echo "<br>"; print_r($value); die();
                        $input_type = $value['type'];
                        switch ($input_type) {
                          case 'number':
                            echo '<div class="row mb-3">
                                    <label for="'.$value['name'].'" class="col-sm-4 col-form-label">'.$value['label'].'</label>
                                    <div class="col-sm-6">
                                      <input type="number" step=".01" class="form-control" id="'.$value['name'].'" name="'.$value['name'].'" value="'.$value['value1'].'">
                                    </div>
                                  </div>';
                            break;
                          case 'select':
                              echo '<div class="row mb-3">
                                      <label for="'.$value['name'].'" class="form-label col-sm-4">'.$value['label'].'</label>
                                      <div class="col-sm-6">';
                              $fetch_dropdown_data_variable = $value['name']."_row";
                              echo $$fetch_dropdown_data_variable;
                              echo '</div></div>';
                              break;
                          
                          default:
                            # code...
                            break;
                        }
                      }
                      
                      ?>
                      <!-- <div class="row mb-3">
                        <label for="actual_hrs" class="col-sm-4 col-form-label">Actual Hrs of the day: </label>
                        <div class="col-sm-6">
                          <input type="number" class="form-control" id="actual_hrs" name="actual_hrs">
                        </div>
                      </div>

                      <div class="row mb-3">
                        <label for="kpi_ticket_status" class="form-label col-sm-4">Ticket status for KPI (OTD):</label>
                        <div class="col-sm-6">
                          <?php echo $ticket_status_row?>
                        </div>
                      </div> -->

                      <div class="text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <!-- <button type="reset" class="btn btn-secondary">Reset</button> -->
                      </div>
                    </form><!-- End floating Labels Form -->

                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </section>

  </main>

  <?php include('layout/footer.php'); ?>

</body>

</html>