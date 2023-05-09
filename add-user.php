
<?php include('layout/head.php'); ?>
<?php

  // session_start();
  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: pages-login.php");
    exit;
  }

  // if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
  //   $loggedin= true;
  // }
  // else{
  //   $loggedin = false;
  // }

  //TODO - move this into user_eventHandler
  $showAlert = false;
  $showError = false;
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // include "db/config.php";
    $username = $_POST["username"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $employee_id = $_POST["employee_id"];
    $designation = $_POST["designation"];
    $user_type = $_POST["user_type"];
    
    // Check whether this username exists
    $existSql = "SELECT * FROM `users` WHERE username = '$username'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if($numExistRows > 0){
        // $exists = true;
        $showError = "Username Already Exists";
    } 
    else {
      
          $hash = password_hash($password, PASSWORD_DEFAULT);


          $sql = "INSERT INTO `users` ( `username`, `fname`, `lname`, `email`, `password`, `employee_id`, `designation`, `user_type`) VALUES ('$username', '$fname', '$lname','$email', '$hash', '$employee_id', '$designation', '$user_type')";
          $result = mysqli_query($conn, $sql);
          // echo '<pre>'; print_r($result); die();

          // print_r($sql);
          // die();
          if($result){
            $showAlert = true;
            // header("Location: /forum/index.php?signupsuccess=true");
            // exit();
        }
      
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
          if($showAlert){
            echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
                  Successfully created new user!
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
          }
          if($showError){
                echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
                '. $showError.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div> ';
          }
        ?>

          <div class="row justify-content-center">
          
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
            
              <div class="d-flex justify-content-center py-4">
                <!-- <a href="index.php" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">NiceAdmin</span>
                </a> -->
              </div>
              <!-- End Logo -->
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Create an User</h5>

                  <!-- Floating Labels Form -->
                  <form class="row g-3" method="post" action="add-user.php">
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input  name="username" type="text" class="form-control" id="username" placeholder="Your Name">
                        <label for="username">Username</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input  style="text-transform: capitalize;"  name="fname" type="text" class="form-control makeUppercase" id="fname" placeholder="First Name">
                        <label for="floatingfname">First Name</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input   style="text-transform: capitalize;" name="lname" type="text" class="form-control makeUppercase" id="lname" placeholder="Last Name">
                        <label for="floatinglname">Last Name</label>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input name="email" type="email" class="form-control" id="floatingEmail" placeholder="Your Email">
                        <label for="floatingEmail">Your Email</label>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-floating">
                        <input name="employee_id" type="text" class="form-control" id="employee_id" placeholder="Your Name">
                        <label for="employee_id">Employee Id</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input name="designation" type="text" class="form-control" id="designation" placeholder="Your Name">
                        <label for="designation">Designation</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating mb-3">
                        <?php echo $user_type_row; ?>
                        
                        <label for="floatingSelect">User Type</label>
                      </div>
                    </div>
                    
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
<script src="assets/js/ticket-table.js"></script>
</body>

</html>