  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="index.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->


      <li class="nav-heading">Pages</li>

      <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.php">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li> -->
      <!-- End Profile Page Nav -->

      <?php 
        if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
          echo '<li class="nav-item">
            <a class="nav-link collapsed" href="add-user.php">
              <i class="bi bi-card-list"></i>
              <span>Add User</span>
            </a>
          </li>';
        } 
      ?>
      <!-- End Register Page Nav -->

      <!-- End Login Page Nav -->

      <?php 
        // if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
          // echo '<li class="nav-item">
          //   <a class="nav-link collapsed" href="pages-login.php">
          //     <i class="bi bi-box-arrow-in-right"></i>
          //     <span>Login</span>
          //   </a>
          // </li>';
        // } 
      ?>

      

      <?php 
        if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
          echo '<li class="nav-item">
            <a class="nav-link collapsed" href="tickets.php">
            <i class="bi bi-circle"></i>
              <span>Ticket Management</span>
            </a>
          </li>';
        } 
      ?>

      <?php 
        if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
          echo '<li class="nav-item">
            <a class="nav-link collapsed" href="missing.php">
            <i class="bi bi-circle"></i>
              <span>Weekly Log Report</span>
            </a>
          </li>';
        } 
      ?>

      <?php 
        if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
          echo '<li class="nav-item">
            <a class="nav-link collapsed" href="user_management.php">
            <i class="bi bi-circle"></i>
              <span>User Managment</span>
            </a>
          </li>';
        } 
      ?>

    <?php 
        if(isset($_SESSION) && $_SESSION['user_type'] != 1) {
          echo '<li class="nav-item">
            <a class="nav-link collapsed" href="pages-ticket.php">
              <i class="bi bi-card-list"></i>
              <span>Tickets</span>
            </a>
          </li>';
        } 
      ?>

    </ul>

  </aside><!-- End Sidebar-->