
<?php include('layout/head.php'); ?>

<?php

  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){ //if already loggedin then can not access login again page, first log out
    header("location: tickets.php");
    exit;
  }

  $login = false;
  $showError = false;
  if($_SERVER["REQUEST_METHOD"] == "POST"){
      include "db/config.php";
      $username = $_POST["username"];
      $password = $_POST["password"]; 
      
      
      $sql = "Select * from users where username='$username' ";
      $result = mysqli_query($conn, $sql);
      $num = mysqli_num_rows($result);
      if ($num == 1){
          while($row=mysqli_fetch_assoc($result)){
              if (password_verify($password, $row['password'])){ 
                  $login = true;
                  session_start();
                  $_SESSION['loggedin'] = true;
                  $_SESSION['username'] = $username;
                  $_SESSION['full_name'] = $row['fname'].' '.$row['lname'];
                  $_SESSION['user_type'] = $row['user_type'];
                  $_SESSION['user_id'] = $row['id'];
                  $_SESSION['designation'] = $row['designation'];
                  //TODO -fetch other important data of user into session and use it
                  header("location: tickets.php");
              } 
              else{
                  $showError = "Invalid Credentials";
              }
          }
      } else {
        $showError = "Invalid Credentials";
      }
    }
?>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">NiceAdmin</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    <p class="text-center small">Enter your username & password to login</p>
                  </div>

                  <form class="row g-3 needs-validation" method="post" action="pages-login.php">

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" name="username" class="form-control" id="username" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="password" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input onclick="lsRememberMe()" class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="add-user.php">Create an account</a></p>
                    </div>
                  </form>

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
  </main><!-- End #main -->

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

  <script>
    const rmCheck = document.getElementById("rememberMe"),
        usernameInput = document.getElementById("username"),
        passwordInput = document.getElementById("password");

    if (localStorage.checkbox && localStorage.checkbox !== "") {
        rmCheck.setAttribute("checked", "checked");
        usernameInput.value = localStorage.username;
        passwordInput.value = localStorage.password;
      } else {
        rmCheck.removeAttribute("checked");
        usernameInput.value = "";
      }

    function lsRememberMe() {
        if (rmCheck.checked && usernameInput.value !== "") {
          localStorage.username = usernameInput.value;
          localStorage.password = passwordInput.value;
          localStorage.checkbox = rmCheck.value;
        } else {
          localStorage.username = "";
          localStorage.checkbox = "";
          localStorage.password = "";
        }
    }
  </script>

</body>

</html>