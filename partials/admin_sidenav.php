<aside class="main-sidebar sidebar-light-primary elevation-4" style="background-color: #ffffff">
    <!-- Brand Logo
	<a href="index3.html" class="brand-link">
		<img src="../public/img/merged_logos.png" alt="makueni Logo" class="brand-image" style="opacity: .8">
		<span class="brand-text font-weight-light">GoMC</span>
	</a> -->

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="../public/dist/img/user.jpg" class="img-circle elevation-2" alt="User Image">
			</div>

			<div class="info pt-0 mt-0">
				<a href="#" class="">
					<?php echo $_SESSION['user_names']; ?>
					<br />
					<?php echo $_SESSION['user_access_level']; ?>
				</a>
			</div>
		</div>

		<!-- SidebarSearch Form -->
		<!-- <div class="form-inline">
			<div class="input-group" data-widget="sidebar-search">
				<input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
				<div class="input-group-append">
					<button class="btn btn-sidebar">
						<i class="fas fa-search fa-fw"></i>
					</button>
				</div>
			</div>
		</div> -->

        
		</div>        

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->               
                
                
                <li class="nav-item">
                    <a href="dashboard" class="nav-link">
						<i class="nav-icon fas fa-tachometer-alt text-success"></i>
                            <p>
                                Dashboard
                            </p>
                    </a>
                </li>
				<li class="nav-item">
                    <a href="transfer" class="nav-link">
						<i class="nav-icon fas fa-exchange-alt text-success"></i>
                            <p>
                                Transfer
                            </p>
                    </a>
                </li>
               
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-flag text-success"></i>
                        <p>
                            Targets
                            <i class="right fas fa-angle-down"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="revenue_target_setter" class="nav-link">
                            <i class="fas fa-edit nav-icon text-warning"></i>
                            <p class="text-warning">Setter</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="revenue_target1"  class="nav-link">
                            <i class="fas fa-book nav-icon text-warning"></i>
                            <p class="text-warning">Generator</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="revenue_target" class="nav-link">
                            <i class="fas fa-bullseye nav-icon text-warning"></i>
                            <p class="text-warning">View</p>
                            </a>
                        </li>
						
                    </ul>  
                </li>
                <li class="nav-item">
                    <a href="revenue_collected" class="nav-link">
						<i class="nav-icon fas fa-wallet text-success"></i>
						<p>
                            Collections
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="target_achieved" class="nav-link">
					<i class="nav-icon fas fa-flag text-success"></i>
					<p>
                            Performance
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-flag text-success"></i>
                        <p>
                            System Settings
                            <i class="right fas fa-angle-down"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="users" class="nav-link">
                            <i class="fas fa-edit nav-icon text-warning"></i>
                            <p class="text-warning">Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="stream"  class="nav-link">
                            <i class="fas fa-book nav-icon text-warning"></i>
                            <p class="text-warning">Streams</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="services" class="nav-link">
                            <i class="fas fa-bullseye nav-icon text-warning"></i>
                            <p class="text-warning">Services</p>
                            </a>
                        </li>
						
                    </ul>  
                </li>


				<!-- Imprest -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-wallet text-success"></i>
						<p>
							Imprest
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="admin_imprest" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Annual</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="staff_imprest_payment" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Disbursment</p>
							</a>
						</li>
					</ul>
				</li>
				<!-- notifications -->
                <li class="nav-item">
                    <a href="admin_notice" class="nav-link">
						<i class="nav-icon fas fa-bell text-success"></i>
						<p>
                            Notifications
							<?php
							if ($notices > 0) {
							?>
								<span class="badge badge-info right"><?php echo $notices; ?></span>
							<?php } ?>
                        </p>
                    </a>
                </li>
				
                        
				<!-- Logout -->
				<li class="nav-item">					
					<a class="nav-link" data-toggle="modal" data-target="#end_session" href="">
						<i class="nav-icon fas fa-power-off text-success"></i>
						<p>
							Logout
						</p>
					</a>
				</li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>


<!-- <nav class="nav">
            <div> <a href="#" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">BBBootstrap</span> </a>
                <div class="nav_list"> <a href="#" class="nav_link active"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Dashboard</span> </a>
                 <a href="#" class="nav_link"> <i class='bx bx-user nav_icon'></i> <span class="nav_name">Users</span> </a>
                  <a href="#" class="nav_link"> <i class='bx bx-message-square-detail nav_icon'></i> <span class="nav_name">Messages</span> </a> <a href="#" class="nav_link"> <i class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Bookmark</span> </a> <a href="#" class="nav_link"> <i class='bx bx-folder nav_icon'></i> <span class="nav_name">Files</span> </a> <a href="#" class="nav_link"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span class="nav_name">Stats</span> </a> </div>
            </div> <a href="#" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">SignOut</span> </a>
        </nav>
    </div> -->