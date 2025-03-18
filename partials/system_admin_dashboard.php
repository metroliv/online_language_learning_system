<?php

use FontLib\Table\Type\head;

require('../config/config.php');
require('../functions/system_admin_analytics.php');
// include_once('../partials/headn.php');



?>

<head>
	<style>
		.row {
			display: flex;
			flex-wrap: wrap;
			gap: 0px;
		}

		.small-box {
			background: #f8f9fa;
			border-radius: 10px;
			padding: 15px;
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
			margin: 0;
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
			overflow: hidden;
			/* Ensures rounded corners are respected */
		}

		.table th,
		.table td {
			vertical-align: middle;
			/* Centers the content vertically */
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
						<?php echo $greeting . ', ' . $_SESSION['user_names'] ?>
					</h4>

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

	<?php
	$myAction = 'Dashboadfiters';
	include('../modals/filters.php')
		?>
	<!-- ./ filters -->

	<div class="container-fluid">

		<!-- widgets -->
		<div class="row">
			<div class="col-md-4 col-sm-6 col-12">
				<a href="revenue_target?month=<?php echo $months ?>&fy=<?php echo $fy ?>" class="text-dark">
					<div class="small-box">
						<div>
							<h5 class="text-danger">Targets</h5>
						</div>
						<div class="inner" style="min-height: 45px;">
							<h4>
								Ksh.
								<?php
								/** Target Amount */
								$query = "SELECT sum(info_amount) FROM collectortarget_info ci
                                  INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
                                  INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
                                  INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
                                  WHERE fy.fy_id = '{$fy}' AND ct.collectortarget_month IN ({$months})";
								$stmt = $mysqli->prepare($query);
								$stmt->execute();
								$stmt->bind_result($target);
								$stmt->fetch();
								$stmt->close();

								/** Deficit Amount */
								$query = "SELECT sum(info_defecit) FROM collectortarget_info ci
                                  INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
                                  INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
                                  INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
                                  WHERE fy.fy_id = '{$fy}' AND ct.collectortarget_month IN ({$months})";
								$stmt = $mysqli->prepare($query);
								$stmt->execute();
								$stmt->bind_result($deficit);
								$stmt->fetch();
								$stmt->close();

								$targetTotal = $target + $deficit;

								echo number_format($targetTotal > 0 ? $targetTotal : 0, 2);
								?>
							</h4>
						</div>
						<div class="icon">
							<i class="far fa-gem" style="color: gray; font-size: 50px; right: 15px;"></i>
						</div>
						<a href="#"
							class="small-box-footer d-flex justify-content-between align-items-center text-primary"
							style="background-color: #ffcd3d" data-toggle="modal" data-target="#filterDashboard">
							<span>
								<?php
								echo "";
								if ($annual) {
									echo $active_fy;
								} else {
									echo $fyMonths[$months] . ', 2024';
								}
								?>
							</span>
							<span class="dot-indicator"
								style="font-size: 14px; color: red;">&#8226;&#8226;&#8226;</span>
						</a>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="revenue_collected?month=<?php echo $months ?>&fy=<?php echo $fy ?>" class="text-dark">
					<div class="small-box">
						<div>
							<h5 class="text-danger">Collections</h5>
						</div>
						<div class="inner" style="min-height: 45px;">
							<h4>
								Ksh.
								<?php
								/** Total collections */
								$query = "SELECT SUM(collection_amount) FROM revenue_collections rc
                                  INNER JOIN financial_year fy ON rc.collections_fy = fy.fy_id
                                  WHERE collection_status = 'Approved' AND fy.fy_status = '1' 
                                  AND collections_month IN ({$months})";
								$stmt = $mysqli->prepare($query);
								$stmt->execute();
								$stmt->bind_result($collections_total);
								$stmt->fetch();
								$stmt->close();

								echo number_format($collections_total > 0 ? $collections_total : 0, 2);
								?>
							</h4>
						</div>
						<div class="icon">
							<i class="fas fa-shopping-cart" style="color: grey; font-size: 50px; right: 15px;"></i>
						</div>
						<a href="#"
							class="small-box-footer d-flex justify-content-between align-items-center text-primary"
							style="background-color: #ffcd3d" data-toggle="modal" data-target="#filterDashboard">
							<span>
								<!-- <?php echo $annual ? $fyYear . ' FY' : $fyMonths[$months] . ', ' . $fyYear; ?> -->
							</span>
							<span class="dot-indicator"
								style="font-size: 14px; color: red;">&#8226;&#8226;&#8226;</span>
						</a>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="target_achieved?month=<?php echo $months ?>&fy=<?php echo $fy ?>" class="text-dark">
					<div class="small-box">
						<div>
							<h5 class="text-danger">Target Achieved</h5>
						</div>
						<div class="inner" style="min-height: 45px;">
							<h4>
								<?php
								if ($targetTotal > 0) {
									$achievedPercent = $annual ? ($collections_total * 100) / $target : ($collections_total * 100) / $targetTotal;
									echo number_format($achievedPercent, 2) . '%';
								} else {
									echo '0%';
								}
								?>
							</h4>
						</div>
						<div class="icon">
							<i class="fas fa-chart-bar" style="color: grey; font-size: 50px; right: 15px;"></i>
						</div>
						<a href="#"
							class="small-box-footer d-flex justify-content-between align-items-center text-primary"
							style="background-color: #ffcd3d" data-toggle="modal" data-target="#filterDashboard">
							<span>
								<!-- <?php echo $annual ? $fyYear . ' FY' : $fyMonths[$months] . ', ' . $fyYear; ?> -->
							</span>
							<span class="dot-indicator"
								style="font-size: 14px; color: red;">&#8226;&#8226;&#8226;</span>
						</a>
					</div>
				</a>
			</div>
		</div>

		<!-- /.row -->

		<!-- Main row -->
		<!-- /.row (main row) -->
	</div>

	<!-- /.container-fluid -->

	<!----------------------Targets Table------------------------------------------------>

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title m-0"></h4>
					<div class="text-right">
						<small class="mt-2 text-danger">

							<?php echo '[ Today, ' . date('d M Y') . ' ]'; ?>

						</small>
					</div>
				</div>
				<!-- Tab Navigation -->
				<ul class="nav nav-tabs" id="targetTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="streamtarget-tab" data-toggle="tab" href="#streamtarget"
							role="tab">Annual Targets</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="quarterlytarget-tab" data-toggle="tab" href="#quarterlytarget"
							role="tab">Quarterly Targets</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="monthlytarget-tab" data-toggle="tab" href="#monthlytarget"
							role="tab">Monthly Targets</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="Collections-tab" data-toggle="tab" href="#collections"
							role="tab">Collections</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="authlogs-tab" data-toggle="tab" href="#authlogs"
							role="tab">Authentication Logs</a>
					</li>
				</ul>

				<!-- Tab Content -->
				<div class="tab-content" id="targetTabContent">
					<!-- Stream Targets Tab -->
					<div class="tab-pane fade show active" id="streamtarget" role="tabpanel">
						<?php include 'streamtarget_content.php'; ?>

					</div>

					<!-- Quarterly Targets Tab -->
					<div class="tab-pane fade" id="quarterlytarget" role="tabpanel">
						<?php include 'quarterlytarget_content.php'; ?>

					</div>

					<!-- Monthly Targets Tab -->
					<div class="tab-pane fade" id="monthlytarget" role="tabpanel">
						<?php include 'monthlytarget_content.php'; ?>

					</div>
					<div class="tab-pane fade" id="collections" role="tabpanel">
						<?php include 'collections_content.php'; ?>
					</div>

					<!-- Authentication Logs Tab -->
					<div class="tab-pane fade" id="authlogs" role="tabpanel">
						<?php include 'authlogs_content.php'; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!---------------------Update Annual Targets Modal----------------------------------------->

	<div class="modal fade" id="editStreamTargetModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="editStreamTargetForm" method="POST" action="../helpers/update_targets.php">
					<div class="modal-header">
						<h5 class="modal-title">Update Annual Targets</h5>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="streamtarget_id" id="editStreamTargetId">

						<div class="form-group">
							<label>Ward</label>
							<input type="text" name="streamtarget_ward" id="editStreamTargetWard" class="form-control"
								readonly>
						</div>

						<div class="form-group">
							<label>User</label>
							<input type="text" name="streamtarget_user" id="editStreamTargetUser" class="form-control"
								readonly>
						</div>

						<div class="form-group">
							<label>Stream</label>
							<input type="text" name="streamtarget_stream" id="editStreamTargetStream"
								class="form-control" readonly>
						</div>

						<div class="form-group">
							<label>Financial Year</label>
							<input type="text" name="streamtarget_fy" id="editStreamTargetFy" class="form-control"
								readonly>
						</div>

						<div class="form-group">
							<label>Amount</label>
							<input type="number" name="streamtarget_amount" id="editStreamTargetAmount"
								class="form-control" required step="0.01">
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Save changes</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!---------------------Update Quarterly Targets Modal----------------------------------------->
	<div class="modal fade" id="editQuarterlyTargetModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="editQuarterlyTargetForm" method="POST" action="../helpers/update_targets.php">
					<div class="modal-header">
						<h5 class="modal-title">Update Quarterly Target</h5>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="collectorquarterlytarget_id" id="editQuarterlyTargetId">

						<div class="form-group">
							<label>User</label>
							<input type="text" id="editQuarterlyTargetUser" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Quarter Number</label>
							<input type="text" id="editQuarterlyTargetQuarter" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Stream ID</label>
							<input type="text" id="editQuarterlyTargetStream" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Amount</label>
							<input type="number" name="collectorquarterlytarget_quarter_target"
								id="editQuarterlyTargetAmount" class="form-control" required step="0.01">
						</div>
						<div class="form-group">
							<label>Financial Year</label>
							<input type="text" id="editQuarterlyTargetFy" class="form-control" readonly>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Save changes</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!---------------------Update Monthly Targets Modal----------------------------------------->
	<div class="modal fade" id="editMonthlyTargetModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="editMonthlyTargetForm" method="POST" action="../helpers/update_targets.php">
					<div class="modal-header">
						<h5 class="modal-title">Update Monthly Target</h5>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="collectormonthlytarget_id" id="editMonthlyTargetId">

						<div class="form-group">
							<label>User</label>
							<input type="text" id="editMonthlyTargetUser" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Month</label>
							<input type="text" id="editMonthlyTargetMonth" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Stream ID</label>
							<input type="text" id="editMonthlyTargetStream" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Amount</label>
							<input type="number" name="collectormonthlytarget_fixed_amount" id="editMonthlyTargetAmount"
								class="form-control" required step="0.01">
						</div>
						<div class="form-group">
							<label>Financial Year</label>
							<input type="text" id="editMonthlyTargetFy" class="form-control" readonly>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Save changes</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>


	<script>
	



		$(document).ready(function () {
			// Initialize DataTables
			$('#streamTargetTable').DataTable({
				serverSide: true,
				ajax: {
					url: '../helpers/fetch_data.php',
					type: 'POST',
					data: { table: 'streamtarget' }
				},
				columns: [
					{ data: 'streamtarget_id', title: 'ID' },
					{ data: 'streamtarget_ward_id', title: 'Ward' },
					{ data: 'streamtarget_user_id', title: 'User' },
					{ data: 'streamtarget_stream_id', title: 'Stream' },
					{ data: 'streamtarget_amount', title: 'Amount' },
					{ data: 'streamtarget_fy', title: 'Financial Year' },
					{
						data: 'streamtarget_id',
						title: 'Action',
						render: function (data) {
							return `<button type='button' class='btn btn-primary editTargetBtn' data-id='${data}'>Edit</button>`;
						}
					}
				],
				scrollY: '400px',
				scrollCollapse: true,
				lengthMenu: [50, 100, 300],
				paging: true,
			});

			$('#quarterlyTargetTable').DataTable({
				serverSide: true,
				ajax: {
					url: '../helpers/fetch_data.php',
					type: 'POST',
					data: { table: 'collectorquarterlytarget' }
				},
				columns: [
					{ data: 'collectorquarterlytarget_id', title: 'ID' },
					{ data: 'collectorquarterlytarget_user_id', title: 'User' },
					{ data: 'collectorquarterlytarget_quarter_number', title: 'Quarter' },
					{ data: 'collectorquarterlytarget_stream_id', title: 'Stream' },
					{ data: 'collectorquarterlytarget_quarter_target', title: 'Quarter Target' },
					{ data: 'collectorquarterlytarget_quarter_collection', title: 'Quarter Collection' },
					{ data: 'collectorquarterlytarget_quarter_deficit', title: 'Quarter Deficit' },
					{ data: 'collectorquarterlytarget_fy', title: 'Financial Year' },
					{
						data: 'collectorquarterlytarget_id',
						title: 'Action',
						render: function (data) {
							return `<button type='button' class='btn btn-primary editQuarterlyTargetBtn' data-id='${data}'>Edit</button>`;
						}
					}
				],
				scrollY: '400px',
				scrollCollapse: true,
				lengthMenu: [50, 100, 250]
			});

			$('#monthlyTargetTable').DataTable({
				serverSide: true,
				ajax: {
					url: '../helpers/fetch_data.php',
					type: 'POST',
					data: { table: 'collectormonthlytarget' }
				},
				columns: [
					{ data: 'collectormonthlytarget_id', title: 'ID' },
					{ data: 'collectormonthlytarget_user_id', title: 'User' },
					{ data: 'collectormonthlytarget_month', title: 'Month' },
					{ data: 'collectormonthlytarget_stream_id', title: 'Stream' },
					{ data: 'collectormonthlytarget_fixed_amount', title: 'Fixed Target' },
					{ data: 'collectormonthlytarget_amount', title: 'Month Target' },
					{ data: 'collectormonthlytarget_deficit', title: 'Deficit' },
					{ data: 'collectormonthlytarget_fy', title: 'Financial Year' },
					{
						data: 'collectormonthlytarget_id',
						title: 'Action',
						render: function (data) {
							return `<button type='button' class='btn btn-primary editMonthlyTargetBtn' data-id='${data}'>Edit</button>`;
						}
					}
				],
				scrollY: '400px',
				scrollCollapse: true,
				lengthMenu: [100, 250, 500]
			});

			// Event delegation for dynamically generated buttons
			$(document).on('click', '.editTargetBtn', function () {
				const row = $(this).closest('tr');
				const id = row.find('td:eq(0)').text();
				const ward = row.find('td:eq(1)').text();
				const user = row.find('td:eq(2)').text();
				const stream = row.find('td:eq(3)').text();
				const amount = row.find('td:eq(4)').text();
				const fy = row.find('td:eq(5)').text();

				// Populate modal fields
				$('#editStreamTargetId').val(id);
				$('#editStreamTargetWard').val(ward);
				$('#editStreamTargetUser').val(user);
				$('#editStreamTargetStream').val(stream);
				$('#editStreamTargetAmount').val(amount);
				$('#editStreamTargetFy').val(fy);

				// Show the modal
				$('#editStreamTargetModal').modal('show');
			});

			$(document).on('click', '.editQuarterlyTargetBtn', function () {
				const row = $(this).closest('tr');
				const id = row.find('td:eq(0)').text();
				const user = row.find('td:eq(1)').text();
				const quarter = row.find('td:eq(2)').text();
				const stream = row.find('td:eq(3)').text();
				const amount = row.find('td:eq(4)').text();
				const fy = row.find('td:eq(7)').text();

				$('#editQuarterlyTargetId').val(id);
				$('#editQuarterlyTargetUser').val(user);
				$('#editQuarterlyTargetQuarter').val(quarter);
				$('#editQuarterlyTargetStream').val(stream);
				$('#editQuarterlyTargetAmount').val(amount);
				$('#editQuarterlyTargetFy').val(fy);

				$('#editQuarterlyTargetModal').modal('show');
			});

			$(document).on('click', '.editMonthlyTargetBtn', function () {
				const row = $(this).closest('tr');
				const id = row.find('td:eq(0)').text();
				const user = row.find('td:eq(1)').text();
				const month = row.find('td:eq(2)').text();
				const stream = row.find('td:eq(3)').text();
				const amount = row.find('td:eq(4)').text();
				const fy = row.find('td:eq(7)').text();

				$('#editMonthlyTargetId').val(id);
				$('#editMonthlyTargetUser').val(user);
				$('#editMonthlyTargetMonth').val(month);
				$('#editMonthlyTargetStream').val(stream);
				$('#editMonthlyTargetAmount').val(amount);
				$('#editMonthlyTargetFy').val(fy);

				$('#editMonthlyTargetModal').modal('show');
			});
		});
	</script>

</section>
<!-- /.content -->

</div>
<!-- /.content-wrapper -->
</section>