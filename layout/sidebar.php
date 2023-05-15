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

      <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) { ?>
        
        <!-- Ticket management -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="tickets.php">
          <i class="bi bi-layout-text-window-reverse"></i>
            <span>Ticket Management</span>
          </a>
        </li>

        <!-- Project management -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="project_managment.php">
          <i class="bi bi-layout-text-window-reverse"></i>
            <span>Project Management</span>
          </a>
        </li>

        <!-- KPI management -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="kpi_managment.php">
          <i class="bi bi-layout-text-window-reverse"></i>
            <span>KPI Master</span>
          </a>
        </li>

        <!-- Configuration Tab -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="configuration.php">
          <i class="bi bi-layout-text-window-reverse"></i>
            <span>Configuration Tab</span>
          </a>
        </li>

        <!-- Master -->
        <li class="nav-item">
          <a class="nav-link " data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Master</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="master-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li class="nav-item">
              <a class="nav-link collapsed" href="master_cstatus.php">
                <i class="bi bi-card-list"></i>
                <span>Ticket Status Master</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link collapsed" href="master_tickettypes.php">
                <i class="bi bi-card-list"></i>
                <span>Ticket Type Master</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link collapsed" href="master_usertypes.php">
                <i class="bi bi-card-list"></i>
                <span>User Type Master</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- User management -->
        <li class="nav-item">
          <a class="nav-link " data-bs-target="#user-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-person"></i><span>User Management</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="user-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li class="nav-item">
              <a class="nav-link collapsed" href="add-user.php">
                <i class="bi bi-card-list"></i>
                <span>Add User</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link collapsed" href="user_management.php">
              <i class="bi bi-circle"></i>
                <span>View Users</span>
              </a>
            </li>
          </ul>
        </li>

      <?php } ?>

      <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] != 1) { ?>
        <li class="nav-item">
          <a class="nav-link collapsed" href="pages-ticket.php">
            <i class="bi bi-card-list"></i>
            <span>Tickets</span>
          </a>
        </li>
      <?php } ?>
   

    </ul>

  </aside><!-- End Sidebar-->