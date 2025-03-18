<?php
require('../config/config.php');
include_once('../functions/reciever_analytics.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward Receiver Dashboard</title>
    <link rel="stylesheet" href="../public/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> <!-- DataTable CSS -->
    <link rel="stylesheet" href="../public/dist/css/adminlte.min.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Load jQuery first -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> <!-- Then load DataTables -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js library -->
</head>

  <style>
        
        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 0px;
        }

        .small-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .small-box:hover {
            transform: translateY(-5px);
        }

        .small-box .inner h6 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .small-box .inner p {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 1;
        }

        .small-box .icon {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 2.5rem;
            opacity: 0.3;
        }

        .small-box-footer {
            display: block;
            padding: 10px;
            text-align: center;
            background: #007bff;
            color: white;
            font-weight: 600;
            border-radius: 0 0 10px 10px;
        }

        .small-box-footer:hover {
            background: #0056b3;
        }
        .table {
              border-radius: 0.5rem;
              overflow: hidden; /* Ensures rounded corners are respected */
          }
          .table th, .table td {
              vertical-align: middle; /* Centers the content vertically */
          }
    </style>
</head>

<!-- Main content -->
<section class="content">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h4 class="m-0 text-dark">
						<?php 
						echo $ward_name['ward_name'] . ' Ward';						
						?>
					</h4>
					<h6 class="text-danger"><?php
								echo "<br/>";
								if ($annual) {
									echo '2024/2025 FY';
								} else {
									echo $fyMonths[$months] . ', 2024';		
								}
								?>
					</h6>
									
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
	<!-- <div class="container-fluid">
		<div class="text-right">
			<a data-toggle="modal" data-target="#filterDashboard"><button type="button" class="btn btn-outline-success btn-sm">Filter</button></a>
		</div>
	</div> -->
	<?php 
	$myAction = "Dashboadfiters";
	include('../modals/filters.php')
	?>
	<!-- ./ filters -->

	<!-- <?php
		// Define a variable for the fiscal year display
		if ($annual) {
			$fiscalYearDisplay = '2024/2025 FY';
		} else {
			$fiscalYearDisplay = $fyMonths[$months] . ', 2024';
		}

		// Use the variable wherever needed
		
		?> -->


	<!-- widgets -->
	<div class="container-fluid mt-1">
			<div class="row" style="border: 2px solid white; padding: 10px;"> <!-- Blue border around the row -->
			<div class="col-12 col-md-4 col-lg-4">
				<a href="ward_revenue_target" class="text-dark">
					<div class="small-box" style="border: 2px solid blue;"> <!-- Green border around each card -->
						<h5 class="text-danger">Target</h5>
						<div class="inner">
							<p>
								<h4>
									<?php
									/** target amount */
									$query = "SELECT sum(info_amount) FROM collectortarget_info ci
									INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
									INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
									INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
									WHERE fy.fy_id = '{$fy}'
									AND ct.collectortarget_ward_id = '{$_SESSION['user_ward_id']}'
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
									AND ct.collectortarget_ward_id = '{$_SESSION['user_ward_id']}'
									AND ct.collectortarget_month IN ({$months})
									";
									$stmt = $mysqli->prepare($query);
									$stmt->execute();
									$stmt->bind_result($defecit);
									$stmt->fetch();
									$stmt->close();

									$targetTotal = $target + $defecit;
									if ($targetTotal > 0) {
										echo 'Ksh. ' . number_format($targetTotal);
									} else {
										echo 'Ksh. 0';
									}
									?>
								</h4>
							</p>
						</div>
						<div class="icon">
							<i class="fas fa-gem" style="color: grey; font-size: 60px; right: 15px;"></i>
						</div>
						<a href="#" class="small-box-footer d-flex justify-content-between align-items-center text-primary" style="background-color: #ffcd3d" data-toggle="modal" data-target="#filterDashboard">
							<span><?php echo $fiscalYearDisplay; ?></span>
							<span class="dot-indicator" style="font-size: 14px; color: red;">&#8226;&#8226;&#8226;</span>
						</a>
					</div>
				</a>
			</div>

			<!-- Repeat for other cards -->
			<div class="col-12 col-md-4 col-lg-4">
				<a href="ward_collections" class="text-dark">
					<div class="small-box" style="border: 2px solid blue;"> <!-- Green border around card -->
						<h5 class="text-danger">Collections</h5>
						<div class="inner">
							<p>
								<h4>
									<?php
									
								/** Total collections */
								$user_id = $_SESSION['user_id'];
														$query = "
									SELECT SUM(rc.collection_amount) 
									FROM revenue_collections rc
									INNER JOIN financial_year fy ON rc.collections_fy = fy.fy_id
									WHERE rc.collection_user_id = ?
									AND rc.collection_status = 'Approved'
									AND fy.fy_status = '1'
									AND rc.collections_month IN ($months)
								";

								$stmt = $mysqli->prepare($query);
								$stmt->bind_param('i', $user_id); // Bind user ID as an integer
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
								</h4>
							</p>
						</div>
						<div class="icon">
							<i class="fas fa-shopping-cart" style="color: grey; font-size: 60px; right: 15px;"></i>
						</div>
						<a href="#" class="small-box-footer d-flex justify-content-between align-items-center text-primary" style="background-color: #ffcd3d" data-toggle="modal" data-target="#filterDashboard">
							<span><?php echo $fiscalYearDisplay; ?></span>
							<span class="dot-indicator" style="font-size: 14px; color: red;">&#8226;&#8226;&#8226;</span>
						</a>
					</div>
				</a>
			</div>

			<div class="col-12 col-md-4 col-lg-4">
				<a href="ward_performance" class="text-dark">
					<div class="small-box" style="border: 2px solid blue;"> <!-- Green border around card -->
						<h5 class="text-danger">Target Achieved</h5>
						<div class="inner">
							<p>
								<h4>
									<?php
									if ($targetTotal) {
										echo number_format(($collections_total * 100) / $targetTotal, 2) . '%';
									} else {
										echo '0%';
									}
									?>
								</h4>
							</p>
						</div>
						<div class="icon">
							<i class="fas fa-chart-bar" style="color: grey; font-size: 60px; right: 15px;"></i>
						</div>
						<a href="#" class="small-box-footer d-flex justify-content-between align-items-center text-primary" style="background-color: #ffcd3d" data-toggle="modal" data-target="#filterDashboard">
							<span><?php echo $fiscalYearDisplay; ?></span>
							<span class="dot-indicator" style="font-size: 14px; color: red;">&#8226;&#8226;&#8226;</span>
						</a>
					</div>
				</a>
			</div>
		</div>

	</div>
	<!-- ./widgets -->
</section>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				
				<!-- LINE CHART -->
				<div class="card card-info d-none">
					<div class="card-header">
						<h3 class="card-title">Line Chart</h3>

						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
							<button type="button" class="btn btn-tool" data-card-widget="remove">
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>
					<div class="card-body" style="min-height: 450px;">
						<div class="chart">
							<canvas id="lineChart" style="min-height: 450px; height: 450px; max-height: 600px; max-width: 100%;"></canvas>
						</div>
					</div>

					<!-- /.card-body -->
				</div>
				<!-- /.card -->

				<!-- BAR CHART -->
				<div class="card">
					<div class="card-header text-primary">
						<h3 class="card-title">Ward Stream_Performance</h3>

						<div class="card-tools">
							<button type="button" class="btn btn-tool text-primary" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
							<button type="button" class="btn btn-tool text-danger" data-card-widget="remove">
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>
					<div class="card-body">
						<div class="chart">
							<canvas id="barChart" style="min-height: 400px; height: 400px; max-height: 400px; max-width: 100%;"></canvas>
						</div>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->

				<!-- STACKED BAR CHART -->
				<div class="card card-success d-none">
					<div class="card-header">
						<h3 class="card-title">Stacked Bar Chart</h3>

						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
							<button type="button" class="btn btn-tool" data-card-widget="remove">
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>
					<div class="card-body" style="min-height: 350px;">
						<div class="chart">
							<canvas id="lineChart" style="min-height: 450px; height: 450px; max-height: 600px; max-width: 100%;"></canvas>
						</div>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->

			</div>
			<!-- /.col (RIGHT) -->
			<div class="col-md-12">
				<!-- AREA CHART -->
				<div class="card card-primary d-none">
					<div class="card-header">
						<h3 class="card-title">Area Chart</h3>

						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
							<button type="button" class="btn btn-tool" data-card-widget="remove">
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>
					<div class="card-body">
						<div class="chart">
							<canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
						</div>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->

				<!-- DONUT CHART -->
				<div class="card card-danger d-none">
					<div class="card-header">
						<h3 class="card-title">Stream performance</h3>

						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
							<button type="button" class="btn btn-tool" data-card-widget="remove">
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>
					<div class="card-body">
						<canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->


			</div>
			<!-- /.col (LEFT) -->

			<div class="col-md-12">
				<!-- PIE CHART -->
				<div class="card">
					<div class="card-header text-primary">
						<h3 class="card-title">Staff perfomance</h3>

						<div class="card-tools">
							<button type="button" class="btn btn-tool text-primary" data-card-widget="collapse">
								<i class="fas fa-minus"></i>
							</button>
							<button type="button" class="btn btn-tool text-danger" data-card-widget="remove">
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>
					<div class="card-body">
						<canvas id="pieChart" style="min-height: 400px; height: 400px; max-height: 400px; max-width: 100%;"></canvas>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->

			</div>

		</div>
		<!-- /.row -->
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->