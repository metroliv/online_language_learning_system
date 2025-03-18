  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" > <span class ="text-danger">Makueni County Treasury</span></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- User Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <img src="../public/img/favicon.ico" alt="User Image" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="d-none d-md-inline text-dark font-weight-bold"><?php echo $_SESSION['user_names']; ?></span>
                 </a>
                 <div class="dropdown-menu dropdown-menu-right">
                    <div class="info ml-3">
                        <a href="#" class="d-block text-dark font-weight-bold">
                        <?php echo $_SESSION['user_names']; ?><br />
                        <small class="text-muted"><?php echo $_SESSION['user_email']; ?></small><br />
                        <span class="badge badge-success mt-1"><?php echo $_SESSION['user_access_level']; ?></span>
                        </a>
                    </div>
                    </a>
                    <div class="dropdown-divider"></div>
                        <a class="nav-link text-danger" data-toggle="modal" data-target="#end_session" href="">
                            <i class="fas fa-power-off"></i> Logout
                        </a>
                    </div>
            </li>
        </ul>
    </nav>

