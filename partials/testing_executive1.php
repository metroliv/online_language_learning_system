<!-- Main content -->
<section class="content">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						<?php echo $greeting . ', ' . $_SESSION['user_names'] ?>
					</h1>
					<small></small>
					<h2 class="text-dark">
						<?php
						echo "<br/>";
						if ($annual) {
							echo $active_fy;
						} else {
							echo $fyMonths[$months] . ', 2024';
						}
						?>
					</h2>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="dashboard">Home</a></li>
						<li class="breadcrumb-item active">Dashboard</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>

	<!-- filters -->
	<div class="container-fluid">
		<div class="text-right">
			<a data-toggle="modal" data-target="#filterDashboard"><button type="button" class="btn btn-outline-success btn-sm">Filter</button></a>
		</div>
	</div>
	<?php
	$myAction = 'Dashboadfiters';
	include('../modals/filters.php')
	?>
	<!-- ./ filters -->

	<div class="container-fluid">
		
		<!-- widgets -->
		<div class="row">
			<div class="col-md-4 col-sm-6 col-12">
				<a href="revenue_target?month=<?php echo $months ?>&fy=<?php echo $fy ?>" class="text-dark" style="text-decoration: none">
					<div class="info-box">
						<span class="info-box-icon" style="background: #ffcd3d"><i class="far fa-flag"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Targets</span>
							<span class="info-box-number">
								<?php
								/** target amount */
								$query = "SELECT sum(info_amount) FROM collectortarget_info ci
								INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
								INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
								INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
								WHERE fy.fy_id = '{$fy}'
								AND ct.collectortarget_month IN ({$months})
								";
								$stmt = $mysqli->prepare($query);
								$stmt->execute();
								$stmt->bind_result($target);
								$stmt->fetch();
								$stmt->close();

								/** defecit amount */
								$query = "SELECT sum(info_defecit) FROM collectortarget_info ci
								INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
								INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
								INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
								WHERE fy.fy_id = '{$fy}'
								AND ct.collectortarget_month IN ({$months})
								";
								$stmt = $mysqli->prepare($query);
								$stmt->execute();
								$stmt->bind_result($defecit);
								$stmt->fetch();
								$stmt->close();

								$targetTotal = $target + $defecit;								

								if ($targetTotal > 0) {
									if($annual){
										echo 'Ksh. ' . number_format($target);
									}else{
										echo 'Ksh. ' . number_format($targetTotal);
									}
								} else {
									echo 'Ksh. 0';
								}
								?>
							</span>
						</div>
						<!-- /.info-box-content -->
					</div>
				</a>
				<!-- /.info-box -->
			</div>
			<!-- /.col -->
			<div class="col-md-4 col-sm-6 col-12">
				<a href="revenue_collected?month=<?php echo $months ?>&fy=<?php echo $fy ?>" class="text-dark" style="text-decoration: none">
					<div class="info-box">
						<span class="info-box-icon" style="background: #ffcd3d"><i class="fas fa-wallet"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Collections</span>
							<span class="info-box-number">
								<?php
								/** Total collections */
								$query = "SELECT SUM(collection_amount) FROM revenue_collections rc
								INNER JOIN financial_year fy ON rc.collections_fy = fy.fy_id
								WHERE collection_status = 'Approved' 
								AND fy.fy_status = '1'																														AND collections_month IN ({$months})
								AND collections_month IN ({$months})
								";

								$stmt = $mysqli->prepare($query);
								$stmt->execute();
								$stmt->bind_result($collections_total);
								$stmt->fetch();
								$stmt->close();
								if ($collections_total > 0) {
									echo 'Ksh. ' . number_format($collections_total);
								} else {
									echo 'Ksh. 0';
								}
								?>
						</div>
						<!-- /.info-box-content -->
					</div>
				</a>
				<!-- /.info-box -->
			</div>
			<!-- /.col -->
			<div class="col-md-4 col-sm-6 col-12">
				<a href="target_achieved?month=<?php echo $months ?>&fy=<?php echo $fy ?>" class="text-dark" style="text-decoration: none">
					<div class="info-box">
						<span class="info-box-icon" style="background: #ffcd3d"><i class="fas fa-percent"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Target Achieved</span>
							<span class="info-box-number">
								<?php
								if ($targetTotal > 0) {
									if($annual){
										echo number_format(($collections_total * 100) / $target, 2) . '%';
									}else{
										echo number_format(($collections_total * 100) / $targetTotal, 2) . '%';
									}
								} else {
									echo '0%';
								}
								
								?>
							</span>
						</div>
						<!-- /.info-box-content -->
					</div>
				</a>
				<!-- /.info-box -->
			</div>
			<!-- /.col -->

			<!-- /.col -->
		</div>
		<!-- /.row -->

		<!-- Main row -->
		<!-- /.row (main row) -->
	</div>
	<!-- /.container-fluid -->

		<!-- Bar graph -->
		<div class="row">
			<div class="col-12 d-none">
				<!-- Bar chart -->
				<div class="card card-primary card-outline">
					<div class="card-header">
						<h3 class="card-title">
							<i class="far fa-chart-bar"></i>
							Revenue collections per month
						</h3>
					</div>
					<div class="card-body">
						<div id="bar-chart" style="height: 300px;"></div>
					</div>
					<!-- /.card-body-->
				</div>
			</div>
		</div>
		<!-- End Bar graph -->

		<!--Logs Table--> 
        <div class="row">
			<div class="col-12 col-sm-12 col-md-12">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title m-0">Today, <?php echo date('d M Y'); ?> Authentication Logs</h5>
						 <div class="text-right">
							<small class="mt-2 text-danger">
								<?php
								if ($annual) {
									// echo '2024/2025 FY';
									echo '2024/2025 FY';
								} else {
									$trimonth = trim($months, "'");
									$monthWithoutZero = (int)$trimonth; 

									echo $myMonths[$monthWithoutZero] . ', 2024';
								}

								?>
							</small>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
										<!-- filters -->
						<div class="container-fluid pb-2">
							<!-- <div class="col-md-3 col-sm-3 col-3"> -->
							
							<div class="text-right">
								<a data-toggle="modal" data-target="#filterDashboard"><button type="button" class="btn btn-outline-success btn-sm">filter</button></a>
							</div>
							<!-- </div> -->
						</div>
						<!-- ./ filters -->
						<table id="example1" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Log</th>
									<th>User </th>
									<th>IP Address</th>
									<th>Device</th>
									<th>Time</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$today = date('Y-m-d');
								$tomorrow = date('Y-m-d', strtotime('tomorrow'));
								$fetch_records_sql = mysqli_query(
									$mysqli,
									"SELECT * FROM logs l
								INNER JOIN users u ON u.user_id = l.log_user_id
								WHERE u.user_id = '{$user_id}' AND
								l.log_date BETWEEN '{$today}' AND '{$tomorrow}' ORDER BY l.log_id DESC"
								);
								$cnt = 1;
								if (mysqli_num_rows($fetch_records_sql) > 0) {
									while ($return_results = mysqli_fetch_array($fetch_records_sql)) {
								?>
										<tr>
											<td><?php echo $cnt; ?></td>
											<td><?php echo $return_results['user_names']; ?></td>
											<td><?php echo $return_results['log_ip_address']; ?></td>
											<td><?php echo $return_results['log_device']; ?></td>
											<td><?php echo date('g:ia', strtotime($return_results['log_date'])); ?></td>
										</tr>
								<?php
										$cnt = $cnt + 1;
									}
								} ?>

						</tbody>
					</table>
				</div>
				<!-- /.card-body -->
			</div>
			<!-- /.card -->
		</div>
		<!-- /.col -->
	</div>

</section>