<?php

/** pending Approvals */
$query = "SELECT count(collection_status) FROM revenue_collections 
WHERE collection_status = 'Pending' 
AND collection_ward_id = '{$_SESSION['user_ward_id']}' 
";
// AND collection_fy = '2024/2025' 
// AND DATE_FORMAT(collection_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m');";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($pending_approvals);
$stmt->fetch();
$stmt->close();

$today = date('Y/m/d'); // Current date
$kesho = (new DateTime($today))->modify('+1 day')->format('Y/m/d');

?>

<aside class="main-sidebar sidebar-light-primary elevation-4" style="background-color: #ffffff ">
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
				<a href="../views/change_password.php" class="btn btn-sm mt-2">
					<img src="<?php echo isset($_SESSION['user_image']) ? $_SESSION['user_image'] : '../public/img/no-profile.png'; ?>"
						class="img-circle elevation-2" alt="User Image">
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
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

				<li class="nav-item">
					<a href="dashboard" class="nav-link">
						<i class="nav-icon fas fa-tachometer-alt text-success"></i>
						<p>
							Dashboard
						</p>
					</a>
				</li>


				<!-- Reports -->
				<li class="nav-item">
					<a href="All_reports" class="nav-link">
						<i class="nav-icon fas fa-coins text-success"></i>
						<p>
							Ward Collections
							<i class="fas fa-angle-down right"></i>
							<?php
							if ($pending_approvals > 0) {
								?>
								<span class="badge badge-danger right"><?php echo $pending_approvals; ?></span>
							<?php } ?>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="admin_collections" class="nav-link">
								<i class="nav-icon fas fa-shopping-cart text-warning"></i>
								<p>
									Submit Collections
								</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="All_reports?status=Pending&date=<?php echo date('Y/m/d').' - '.$kesho; ?>&fy=2024/2025"
								class="nav-link">
								<i class="far fa-clock nav-icon text-warning"></i>
								<p>
									Pending Collections
									<?php
									if ($pending_approvals > 0) {
										?>
										<span class="badge badge-danger right"><?php echo $pending_approvals; ?></span>
									<?php } ?>
								</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="All_reports?status=Approved&date=<?php echo date('Y/m/d') . ' - ' . $kesho; ?>&fy=2024/2025"
								class="nav-link">
								<i class="far fa-check-circle nav-icon text-warning"></i>
								<p>Approved Collections</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="All_reports?status=Declined&date=<?php echo date('Y/m/d') . ' - ' . $kesho;?>&fy=2024/2025"
								class="nav-link">
								<i class="far fa-times-circle nav-icon text-warning"></i>
								<p>Declined Collections</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="All_reports?status=All&date=<?php echo date('Y/m/d') . ' - ' .$kesho;?>&fy=2024/2025"
								class="nav-link">
								<i class="fas fa-layer-group nav-icon text-warning"></i>
								<p>All Collections</p>
							</a>
						</li>
					</ul>
				</li>



				<!-- Collectors -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-user-cog text-success"></i>
						<p>
							Officer Analytics
							<i class="fas fa-angle-down right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="staff_annual_target" class="nav-link">
								<i class="fas fa-gem nav-icon text-warning"></i>
								<p>Officer Targets</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="staff_collections" class="nav-link">
								<i class="fas fa-coins nav-icon text-warning"></i>
								<p>Officer Collections</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="staff_performance" class="nav-link">
								<i class="fas fa-chart-bar nav-icon text-warning"></i>
								<p>Officer Performance</p>
							</a>
						</li>
					</ul>
				</li>


				<!-- ward -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-chart-pie text-success"></i>
						<p>
							Stream Analytics
							<i class="fas fa-angle-down right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="ward_revenue_target" class="nav-link">
								<i class="fas fa-gem nav-icon text-warning"></i>
								<p>Stream Targets</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="ward_collections" class="nav-link">
								<i class="fas fa-coins nav-icon text-warning"></i>
								<p>Stream Collections</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="ward_performance" class="nav-link">
								<i class="fas fa-chart-line nav-icon text-warning"></i>
								<p>Stream Performance</p>
							</a>
						</li>

					</ul>
				</li>

				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-book-open text-success"></i>
						<p>
							Ward Register
							<i class="fas fa-angle-down right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="ward_staff" class="nav-link">
								<i class="fas fa-users nav-icon text-warning"></i>
								<p>Ward Staff </p>
							</a>
						</li>
						<!-- <li class="nav-item">
							<a href="#" class="nav-link">
								<i class="fas fa-store nav-icon text-warning"></i>
								<p>Ward Markets</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="#" class="nav-link">
								<i class="fas fa-info-circle nav-icon text-warning"></i>
								<p>About Ward</p>
							</a>
						</li> -->


					</ul>
				</li>

				<!-- Imprest -->
				<li class="nav-item">
					<a href="staff_imprest" class="nav-link">
						<i class="nav-icon fas fa-file-invoice-dollar text-success"></i>
						<p>
							Imprest
						</p>
					</a>
				</li>

				<!-- Notices -->
				<li class="nav-item">
					<a href="receiver_notices" class="nav-link">
						<i class="nav-icon fas fa-envelope text-success"></i>
						<p>
							Notices
						</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="receiver_reports" class="nav-link">
						<i class="nav-icon fas fa-book text-success"></i>
						<p>
							Reports
						</p>
					</a>
				</li>
				<!-- Logout -->
				<li class="nav-item">
					<a class="nav-link" data-toggle="modal" data-target="#end_session" href="">
						<i class="nav-icon fas fa-power-off text-danger
						"></i>
						<p>
						Logout
						</p>
					</a>
				</li>

				<!-- 
					<li class="nav-item">
						<a href="ward_revenue_target" class="nav-link">
						<i class="nav-icon fas fa-file-invoice-dollar text-success"></i>
							<p>
								Ward Targets
							</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="staff_annual_target" class="nav-link">
						<i class="nav-icon fas fa-file-invoice-dollar text-success"></i>
							<p>
								Staff Targets
							</p>
						</a>
					</li> -->
				<!-- <li class="nav-item">
						<a href="annual_ward_target" class="nav-link">
							<i class="nav-icon fas fa-columns"></i>
							<p>
								ward Annual Target
							</p>
						</a>
					</li> -->
				<!-- <li class="nav-item">
						<a href="staff_monthly_targets" class="nav-link">
							<i class="nav-icon fas fa-columns"></i>
							<p>
								Monthly Target
							</p>
						</a>
					</li> -->
				<!-- <li class="nav-item">
						<a href="annual_ward_target" class="nav-link">
							<i class="nav-icon fas fa-columns"></i>
							<p>
								staff Target
							</p>
						</a>
					</li> -->
				<!-- <li class="nav-item">
						<a href="ward_staff" class="nav-link">
						<i class="nav-icon fas fa-file-invoice-dollar text-success"></i>
							<p>
								Staff
							</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="All_reports" class="nav-link">
						<i class="nav-icon fas fa-file-invoice-dollar text-success"></i>
							<p>
								Reports
							</p>
						</a>
					</li>   -->
				<!-- <li class="nav-item">
						<a href="approved_reports" class="nav-link">
							<i class="nav-icon fas fa-columns"></i>
							<p>
								Approved Reports
							</p>
						</a>
					</li>  
					<li class="nav-item">
						<a href="pending_approvals" class="nav-link">
							<i class="nav-icon fas fa-columns"></i>
							<p>
								Pending Reports
								<span class="badge badge-info right">1</span>
							</p>
						</a>
					</li>  
					<li class="nav-item">
						<a href="rejected_reports" class="nav-link">
							<i class="nav-icon fas fa-columns"></i>
							<p>
								Rejected Reports
								<span class="badge badge-info right">5</span>
							</p>
						</a>
					</li>   -->


			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>