<?php
/** declined collections */
$query = "SELECT count(collection_status) FROM revenue_collections 
WHERE collection_status = 'Declined' 
AND collection_user_id = '{$_SESSION['user_id']}' 
";
// AND collection_fy = '2024/2025' 
// AND DATE_FORMAT(collection_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m');";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($declined_collections);
$stmt->fetch();
$stmt->close();
?>
<aside class="main-sidebar sidebar-light-primary elevation-4" style="background-color: #ffffff;">
    <!-- Brand Logo -->
    <a href="dashboard" class="brand-link text-center">
        <img src="../public/img/merged_logos.png" alt="Brand Logo" class=""
            style="width: 150px; height: auto; opacity: .9;">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom">
            <div class="image">
                <a href="collector_profile" class="btn btn-sm mt-2">
                    <img src="<?php echo isset($_SESSION['user_image']) ? $_SESSION['user_image'] : '../public/img/no-profile.png'; ?>"
                        class="img-circle elevation-2" alt="User Image">
                </a>
            </div>
            <div class="info pt-0 mt-0">
                <a href="collector_profile" class="text-primary">
                    <?php echo $_SESSION['user_names']; ?>
                    <br />
                    <strong><?php echo $_SESSION['user_access_level']; ?></strong>
                </a>
            </div>

        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt text-success"></i>
                        <p class="text-dark font-weight-medium">Dashboard</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="All_reports" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart text-success"></i>
                        <p>
                            Collections
                            <i class="fas fa-angle-down right"></i>
							<?php
							if ($declined_collections > 0) {
								?>
								<span class="badge badge-danger right"><?php echo $declined_collections; ?></span>
							<?php } ?>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="collection" class="nav-link">
                                <i class="nav-icon fas fa-coins text-warning"></i>
                                <p>
                                    Submit
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="rejected_collections" class="nav-link">
                                <i class="nav-icon fas fa-times-circle text-warning"></i>
                                <p>
                                    Declined
									<?php
									if ($declined_collections > 0) {
										?>
										<span class="badge badge-danger right"><?php echo $declined_collections; ?></span>
									<?php } ?>
                                </p>
								
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="collector_approved_col" class="nav-link">
                                <i class="nav-icon fas fa-check-circle text-warning"></i>
                                <p>
                                    Approved
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- performance -->
                <li class="nav-item">
                    <a href="performance.php" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar text-success"></i>
                        <p class="text-dark font-weight-medium">Performance</p>
                    </a>
                </li>
                <!-- imprest -->
                <li class="nav-item">
                    <a href="imprest" class="nav-link">
                        <i class="nav-icon fas fa-coins text-success"></i>
                        <p class="text-dark font-weight-medium">Imprest</p>
                    </a>
                </li>
                <!-- reports -->
                <li class="nav-item">
                    <a href="approved_collections" class="nav-link">
                        <i class="nav-icon fas fa-book text-success"></i>
                        <p class="text-dark font-weight-medium">Reports</p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a class="nav-link" data-toggle="modal" data-target="#end_session" href="#">
                        <i class="nav-icon fas fa-power-off text-danger"></i> <!-- Red icon -->
                        <span class="text-dark">Logout</span> <!-- Dark text -->
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>