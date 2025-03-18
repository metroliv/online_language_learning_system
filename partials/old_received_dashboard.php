<?php 
// require_once('../functions/ward_reciever_analytics.php');
// require_once('../functions/reusableQuery.php');
//$revenue_targets = selectOne('revenue_targets', ['target_ward_id'=>22]);

// $revenue_target = $revenue_targets['target_amount'];
// $total_revenue_collected = 800000;
// $target_achieved = '80%';
// $pending_approvals = 22;

// dd($revenue_target);
require('../config/config.php');

?>

<!-- Main content -->
<section class="content">
	<!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">

                        <?php echo $ward_name['ward_name'].' 2024/2025 FY' ?>
                        <?php $greeting . ', ' . $_SESSION['user_names'] ?>
                    </h1>
                    <small>Department of Finance Revenue Collection Tool</small>
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

	<div class="container-fluid">
		<!-- Small boxes (Stat box) -->
		<!-- /.row -->
		<div class="row">
          <div class="col-md-3 col-sm-6 col-12">
		  	<a href="ward_revenue_target" class="text-dark">
				<div class="info-box">
				<span class="info-box-icon bg-info"><i class="far fa-flag"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Target</span>
					<span class="info-box-number"><?php echo 'Ksh'.' '.floor($wardAnnualTarget); ?></span>
				</div>
				<!-- /.info-box-content -->
				</div>
			</a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
			<a href="ward_revenue_target" class="text-dark">
				<div class="info-box">
				<span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Collections</span>
					<span class="info-box-number">Ksh <?php echo floor($annual_collections); ?></span>
				</div>
				<!-- /.info-box-content -->
				</div>
			</a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
			<a href="ward_revenue_target" class="text-dark">
				<div class="info-box">
				<span class="info-box-icon bg-warning"><i class="fas fa-percent"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Target Achieved</span>
					<span class="info-box-number"><?php echo ceil($percentage_annual); ?> %</span>
				</div>
				<!-- /.info-box-content -->
				</div>
			</a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
			<a href="pending_approvals" class="text-dark">
				<div class="info-box">
				<span class="info-box-icon bg-success"><i class="fas fa-pause"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Commision due</span>
					<span class="info-box-number"><?php echo $pending_approvals; ?></span>
				</div>
				<!-- /.info-box-content -->
				</div>
			</a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->          
       </div>
        <!-- /.row -->
	</div>

</section>

<!-- Main content -->
 <section class="content">
 	<div class="content">                
		<div class="container">
			<!-- row  -->
			<div class="row">
				<!-- system users dashboard -->
				<div class="col-12 col-sm-6 col-lg-6">
					<div class="card card-primary card-outline">
	
						<!-- /.card-header -->
						<div class="card-body">
							<table id="example1" class="table table-bordered table-striped">
								<thead>                  
									<tr>
										<th style="width: 10px">#</th>
										<th>name</th>
										<th>Target</th>																		
										<th>Target Achieved</th>																				
									</tr>
								</thead>
								<tbody>
								<?php
								$fetch_records_sql = mysqli_query(
									$mysqli,

										"SELECT * FROM users u 
									WHERE user_access_level = 'Revenue Collector'
									AND user_ward_id = '{$_SESSION['user_ward_id']}'
									"
									// JOIN collectortarget ct ON u.user_id = ct.collectortarget_user_id
									// AND	ct.collectortarget_ward_id = 2}'
									// "SELECT * FROM collectortarget  ct 
									// INNER JOIN streamtarget st ON ct.collectortarget_streamtarget_id  = st.streamtarget_id 
									// INNER JOIN revenue_streams rs ON rs.stream_id = st.streamtarget_stream_id 
									// INNER JOIN users s ON ct.collectortarget_user_id = s.user_id 
									// WHERE st.streamtarget_ward_id = 's.user_ward_id'
									// "	
								);
								if (mysqli_num_rows($fetch_records_sql) > 0) {
									$cnt =  1;
									while ($rows = mysqli_fetch_array($fetch_records_sql)) {
								?>
										<tr>
											<td>
												<?php echo $cnt; ?>
											</td>
											
												<td>
													<a href="staff_targets?id=<?php echo $rows['user_id']; ?>">
														<?php echo $rows['user_names']; ?>
													</a>
												</td>							
											                                               
											<td>
												<?php 
													/** Total annual target */
													$query = "SELECT collectortarget_amount FROM collectortarget 
													WHERE collectortarget_user_id = '{$rows['user_id']}'";
													$stmt = $mysqli->prepare($query);
													$stmt->execute();
													$stmt->bind_result($mytargets);
													$stmt->fetch();
													$stmt->close();

													echo $mytargets; 														
												?>
											</td>                                                 
											<td>
												<?php 
												/** Total annual collections */
												$query = "SELECT SUM(collection_amount) FROM revenue_collections 
												WHERE collection_user_id = '{$rows['user_id']}' 
												AND collection_status = 'Approved'
												";
												$stmt = $mysqli->prepare($query);
												$stmt->execute();
												$stmt->bind_result($mycollections);
												$stmt->fetch();
												$stmt->close();
												$percenttarget = ($mycollections*100)/$mytargets;
												echo $percenttarget.'%';													
												?>
											</td>                                                   
											
											
										</tr>
								<?php
										$cnt = $cnt + 1;
										// include('../modals/users.php');
									}
								} ?>
							</tbody>
																			
								</tbody>										
							</table>
						</div>
						<!-- /.card-body -->
					</div>
					<!-- /.card -->
				</div>

				<div class="col-12 col-sm-6 col-lg-6">
					<!-- DONUT CHART -->
					<div class="card card-success">
						<div class="card-header">
							<h3 class="card-title">Staff Performance</h3>

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

			</div>
			<!-- /.row -->
		</div>
	</div>
 </section>

 <!-- charts hidden -->
<section class="content d-none">
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
              <div class="card-body">
                <div class="chart">
                  <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- BAR CHART -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Staff collection per stream</h3>

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
                  <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
              <div class="card-body">
                <div class="chart">
                  <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col (left) -->
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
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Staff performance</h3>

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

            <!-- PIE CHART -->
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Staff performance</h3>

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
                <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->




	 <!--  -->
	<!--  -->
	<?php 
// require_once('../functions/ward_reciever_analytics.php');
// require_once('../functions/reusableQuery.php');
//$revenue_targets = selectOne('revenue_targets', ['target_ward_id'=>22]);

// $revenue_target = $revenue_targets['target_amount'];
// $total_revenue_collected = 800000;
// $target_achieved = '80%';
// $pending_approvals = 22;

// dd($revenue_target);
require('../config/config.php');

?>

<!-- Main content -->
<section class="content">
	<!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">

					<?php echo $ward_name['ward_name'].' Ward,'.' '.date('F Y'); ?>
					<?php $greeting . ', ' . $_SESSION['user_names'] ?>
                    </h1>
                    <small>Department of Finance Revenue Collection Tool</small>
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

	<div class="container-fluid">
		<!-- Small boxes (Stat box) -->
		<!-- /.row -->
		<div class="row">
          <div class="col-md-3 col-sm-6 col-12">
		  	<a href="ward_revenue_target" class="text-dark">
				<div class="info-box">
				<span class="info-box-icon bg-info"><i class="far fa-flag"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Target</span>
					<span class="info-box-number"><?php echo 'Ksh'.' '.floor($wardMonthlyTarget); ?></span>
				</div>
				<!-- /.info-box-content -->
				</div>
			</a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
			<a href="ward_revenue_target" class="text-dark">
				<div class="info-box">
				<span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Collections</span>
					<span class="info-box-number">Ksh <?php echo floor($monthly_collections); ?></span>
				</div>
				<!-- /.info-box-content -->
				</div>
			</a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
			<a href="ward_revenue_target" class="text-dark">
				<div class="info-box">
				<span class="info-box-icon bg-warning"><i class="fas fa-percent"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Target Achieved</span>
					<span class="info-box-number"><?php echo ceil($percentage_target); ?> %</span>
				</div>
				<!-- /.info-box-content -->
				</div>
			</a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
			<a href="pending_approvals" class="text-dark">
				<div class="info-box">
				<span class="info-box-icon bg-success"><i class="fas fa-pause"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Commision due</span>
					<span class="info-box-number"><?php echo 'KSH'; ?></span>
				</div>
				<!-- /.info-box-content -->
				</div>
			</a>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->          
       </div>
        <!-- /.row -->
	</div>

</section>

<!-- Main content -->
 <section class="content">
 	<div class="content">                
		<div class="container">
			<!-- row  -->
			<div class="row">
                        <!-- system users dashboard -->
                        <div class="col-lg-12">
							<div class="card card-primary card-outline">
			
								<!-- /.card-header -->
								<div class="card-body">
									<table id="example1" class="table table-bordered table-striped">
										<thead>                  
											<tr>
												<th style="width: 10px">#</th>
												<th>name</th>
												<th>Target</th>												
												<th>Collections</th>												
												<th>Target Achieved</th>																				
												<th>Commision Due</th>																				
											</tr>
										</thead>
										<tbody>
										<?php
                                        $fetch_records_sql = mysqli_query(
                                            $mysqli,

                                             "SELECT * FROM users u 
											WHERE user_access_level = 'Revenue Collector'
											AND user_ward_id = '{$_SESSION['user_ward_id']}'
											"
											// JOIN collectortarget ct ON u.user_id = ct.collectortarget_user_id
											// AND	ct.collectortarget_ward_id = 2}'
                                            // "SELECT * FROM collectortarget  ct 
											// INNER JOIN streamtarget st ON ct.collectortarget_streamtarget_id  = st.streamtarget_id 
											// INNER JOIN revenue_streams rs ON rs.stream_id = st.streamtarget_stream_id 
											// INNER JOIN users s ON ct.collectortarget_user_id = s.user_id 
											// WHERE st.streamtarget_ward_id = 's.user_ward_id'
											// "	
                                        );
                                        if (mysqli_num_rows($fetch_records_sql) > 0) {
                                            $cnt =  1;
                                            while ($rows = mysqli_fetch_array($fetch_records_sql)) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $cnt; ?>
                                                    </td>
													
														<td>
															<a href="staff_month_target?id=<?php echo $rows['user_id'] ?>">
																<?php echo $rows['user_names']; ?>
															</a>
														</td>
													
													<td>
														<?php 
														/** Total Monthly Target collections */
														$query = "SELECT SUM(collectortarget_amount ) FROM collectortarget 
														WHERE collectortarget_user_id = '{$rows['user_id']}'														
														";
														$stmt = $mysqli->prepare($query);
														$stmt->execute();
														$stmt->bind_result($annualTargets);
														$stmt->fetch();
														$stmt->close();
														if($annualTargets>0){
															$monthTarget = $annualTargets/12;
														}else{
															$monthTarget = 0;
														}
														echo floor($monthTarget);
														?>
													</td>                                                 
													<td>
														<?php 
															/** Total monthly collections */
															$query = "SELECT SUM(collection_amount) FROM revenue_collections															
															WHERE collection_user_id = '{$rows['user_id']}'
															AND MONTH(collection_date) = MONTH(CURRENT_DATE)
															AND YEAR(collection_date) = YEAR(CURRENT_DATE);
															";
															$stmt = $mysqli->prepare($query);
															$stmt->execute();
															$stmt->bind_result($monthCollections);
															$stmt->fetch();
															$stmt->close();
															echo $monthCollections;														
														?>
													</td>                                                 
                                                    <td>
														<?php 
														$achiedPercentage = ($monthCollections*100);
														$monthpernt = $achiedPercentage/$monthTarget;	
														echo $monthpernt													
														?>
                                                    </td>                                                   
                                                    <td>
														<?php 
														$monthCom = ($monthpernt*14000)/100;
														echo 'KSH '.$monthCom;
														?>
												
                                                    </td>
                                                   
                                                </tr>
                                        <?php
                                                $cnt = $cnt + 1;
                                                // include('../modals/users.php');
                                            }
                                        } ?>
                                    </tbody>
																					
										</tbody>										
									</table>
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
                        </div>
                    </div>
                    <!-- /.row -->
		</div>
	</div>
 </section>

 
	
            
	
            