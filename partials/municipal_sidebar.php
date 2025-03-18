<aside class="main-sidebar sidebar-light-primary elevation-4 " style="background-color: #ffffff">
    <!-- Brand Logo -->
	<a href="dashboard" class="brand-link text-center">
        <img src="../public/img/merged_logos.png" alt="Brand Logo" class="" style="width: 150px; height: auto; opacity: .9;">
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
			<a href="../views/change_password.php" class="">
				<img src="../public/dist/img/user.jpg" class="img-circle elevation-2" alt="User Image">
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

	
        <!-- SidebarSearch Form -->
		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

				<li class="nav-item">
					<a href="../views/dashboard.php" class="nav-link">
						<i class="nav-icon fas fa-tachometer-alt text-success"></i>
						<p>
							Dashboard
						</p>
					</a>
				</li>
				<!--Collections-->
				<li class="nav-item">
					<a href="../views/municipal_collections.php" class="nav-link">
						<i class="nav-icon fas fa-shopping-cart text-success"></i>
						<p>
							Collections
						</p>
					</a>
				</li>
				<!--Performance -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-chart-bar text-success"></i>
						<p>Performance<i class="fas fa-angle-down right"></i></p>
					</a>
					<ul class="nav nav-treeview">
						 
					    <li class="nav-item">
							<a href="../views/municipal_ward_performance.php" class="nav-link">
								<i class="nav-icon fas fa-users text-warning"></i>
								<p >Wards</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="../views/municipal_stream_performance.php" class="nav-link">
								<i class="nav-icon fas fa-columns text-warning"></i>
								<p>Streams</p>
							</a>
						</li>
						
					</ul>
				</li>
             	<!-- Imprest -->
				<li class="nav-item">
					<a href="../views/municipal_imprest" class="nav-link">
						<i class="nav-icon fas fa-wallet text-success"></i>
						<p>
							Imprest
							
						</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="../views/municipal_reports?status=All&date=<?php echo date('Y-m-d') . ' - ' . date('Y-m-d'); ?>&fy=2024/2025" class="nav-link">
						<i class="fas fa-book nav-icon text-success"></i>
						<p>Reports</p>
					</a>
                </li><!-- End reports Nav -->

				<li class="nav-item">
					<a class="nav-link " href="../views/municipal_staff.php"> 
						<i class="nav-icon fas fa-users text-success"></i>
						<p>Profile</p>
					</a>
                </li><!-- End officers Profile Nav -->

				<li class="nav-item">
					<a class="nav-link" data-toggle="modal" data-target="#end_session" href="../views/login.php">
						<i class="fas fa-power-off text-danger"></i>
					</a>
                </li>

			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>