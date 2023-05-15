<?php

session_start();
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

$showAlert = false;
$showError = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
  include "db/config.php";
  $username = $_POST["username"];
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


        $sql = "INSERT INTO `users` ( `username`, `email`, `password`, `employee_id`, `designation`, `user_type`) VALUES ('$username', '$email', '$hash', '$employee_id', '$designation', '$user_type')";
        $result = mysqli_query($conn, $sql);

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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Register - <?=SITE_NAME ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Mar 09 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">

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
                <a href="index.php" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block"><?=SITE_NAME ?></span>
                </a>
              </div><!-- End Logo -->

              

              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Create an User</h5>

                  <!-- Floating Labels Form -->
                  <form class="row g-3" method="post" action="add-user.php">
                    <div class="col-md-12">
                      <div class="form-floating">
                        <input name="username" type="text" class="form-control" id="floatingName" placeholder="Your Name">
                        <label for="floatingName">Username</label>
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
                        <input name="employee_id" type="text" class="form-control" id="floatingName" placeholder="Your Name">
                        <label for="floatingName">Employee Id</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating">
                        <input name="designation" type="text" class="form-control" id="floatingName" placeholder="Your Name">
                        <label for="floatingName">Designation</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-floating mb-3">
                        <select name="user_type" class="form-select" id="floatingSelect" aria-label="State">
                          <!-- <option selected>New York</option> -->
                          <option value="1">PM</option>
                          <option value="2" selected>Dev</option>
                          <option value="3">TL</option>
                          <option value="4">QA</option>
                        </select>
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

              <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main>
  <!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <!-- <script src="assets/vendor/tinymce/tinymce.min.js"></script> -->
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>