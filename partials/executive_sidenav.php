<aside class="main-sidebar sidebar-light-primary elevation-4" style="background-color: #ffffff">
   <!-- Brand Logo -->
   <a href="dashboard" class="brand-link text-center">
        <img src="../public/img/merged_logos.png" alt="Brand Logo" class="" style="width: 150px; height: auto; opacity: .9;">
    </a>
    
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <a href="../views/change_password.php" class="btn btn-sm mt-2">
                <img src="../public/img/user.jpg" class="img-circle elevation-2" alt="User Image">
                </a>
            </div>

            <div class="info pt-0 mt-0">
				<a href="#" class="text-primary">
				<?php echo $_SESSION['user_names']; ?>
					<br />
					<strong><?php echo $_SESSION['user_access_level']; ?></strong>
				</a>
            </div>

        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">

                <!-- Dashboard Section -->
                <li class="nav-item">
                    <a href="../views/dashboard.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt text-success"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
               
                <!-- Total Revenue Collected -->
                <li class="nav-item">
                    <a href="../views/total_revenue.php" class="nav-link">
                        <i class="nav-icon fas fa-chart-line text-success"></i>
                        <p>Collection Trend</p>
                    </a>
                </li>

                <!-- Performance Section -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar text-success"></i>
                        <p>
                            Performance
                            <i class="right fas fa-angle-down"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../views/stream_analysis.php" class="nav-link">
                                <i class="nav-icon fas fa-chart-pie text-warning"></i>
                                <p>Streams</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../views/ward_overview.php" class="nav-link">
                                <i class="nav-icon fas fa-store text-warning"></i>
                                <p>Wards</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Top Section -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-medal text-success"></i>
                        <p>
                            Champions
                            <i class="right fas fa-angle-down"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../views/top_officer.php" class="nav-link">
                                <i class="nav-icon fas fa-user-shield text-warning"></i>
                                <p>Officers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../views/top_streams.php" class="nav-link">
                                <i class="nav-icon fas fa-trophy text-warning"></i>
                                <p>Streams</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Municipal Section -->
                <li class="nav-item">
                    <a href="../views/exec_performance_municipal.php" class="nav-link ">
                        <i class="nav-icon fas fa-building text-success"></i>
                        <p>Municipal</p>
                    </a>
                </li>

                <!-- Reports Section -->
                <li class="nav-item">
                    <a href="../views/report_perfomance.php" class="nav-link">
                        <i class="nav-icon fas fa-file-alt text-success"></i>
                        <p>Reports</p>
                    </a>
                </li>

                <!-- Collector Imprests Section -->
                <li class="nav-item">
                    <a href="../views/collector_imprests.php" class="nav-link">
                        <i class="nav-icon fas fa-wallet text-success"></i>
                        <p>Imprest</p>
                    </a>
                </li>

                <!-- Notifications Section -->
                <li class="nav-item">
                    <a href="executive_notice" class="nav-link">
                        <i class="nav-icon fas fa-bell text-success"></i>
                        <p>
                            Notifications
                            <?php if ($notices > 0) { ?>
                                <span class="badge badge-warning right"><?php echo $notices; ?></span>
                            <?php } ?>
                        </p>
                    </a>
                </li>

                <!-- Logout Section -->
                <li class="nav-item">
                    <a class="nav-link" data-toggle="modal" data-target="#end_session" href="#">
                        <i class="nav-icon fas fa-power-off text-danger"></i>
                       <p></p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
</aside>