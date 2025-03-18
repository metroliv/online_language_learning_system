<?php

// Start session and connect to the database
require_once('../config/config.php');
require_once('../helpers/auth.php');
require_once('../partials/chartplugins.php');
require_once('../functions/municipal_analytics.php');

?>

<!DOCTYPE html>
<html lang="en">

    <body class="hold-transition sidebar-mini layout-fixed">


    <main id="main" class="content-wrapper" style="margin-left: 0;">
          <!-- Page Title -->
          <div class="content-header">
              <div class="container-fluid">
                  <div class="row mb-2">
                      <div class="col-sm-6">
                          <h4 class="m-0"><?php 
						echo $municipal_name. ' Dashboard';						
						?>
                        </h4>
                      </div><!-- /.col -->
                      <div class="col-sm-6">
                          <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                              <li class="breadcrumb-item active">Municipal</li>
                          </ol>
                      </div><!-- /.col -->
                  </div><!-- /.row -->
              </div><!-- /.container-fluid -->
          </div><!-- End Page Title -->

          <section class="content">
              <div class="container-fluid">
                  <div class="row">
                     <!-- Target Card -->
                     <div class="col-lg-3 col-6">
                          <div class="small-box ">
                           
                              <div class="inner">
                                  <h6 class="text-danger">Target</h6>
                                  <p><h5>Ksh <?php echo number_format($total_monthly_target); ?></p>
                              </div>
                              <div class="icon">
                                  <i class="fas fa-gem"></i>
                              </div>
                              <a href="#" class="small-box-footer text-primary" style="background-color: #ffeb3b"><strong>This Month</strong></a>
                          </div>
                      </div><!-- End Target Card -->
                 
                      <!-- Collections Card -->
                      <div class="col-lg-3 col-6">
                          <div class="small-box">
                              <div class="inner">
                                  <h6 class="text-danger">Collections</h6>
                                  <p><h5>Ksh <?php echo number_format($total_monthly_collection); ?></h5></p>
                              </div>
                              <div class="icon">
                                  <i class="fas fa-shopping-cart"></i>
                              </div>
                              <a href="#" class="small-box-footer text-primary" style="background-color: #ffeb3b"><strong>This Month</strong></a>
                          </div>
                      </div><!-- End Collections Card -->
                     
                     
                      <!-- Performance Card -->
                      <div class="col-lg-3 col-6">
                          <div class="small-box">
                              <div class="inner">
                                  <h6 class="text-danger">Performance</h6>
                                  <p><h5><?php echo number_format($monthly_performance, 2); ?>%</h5></p>
                              </div>
                              <div class="icon">
                                  <i class="fas fa-chart-bar"></i>
                              </div>
                              <a href="#" class="small-box-footer text-primary" style="background-color: #ffeb3b"><strong>This Month</strong></a>
                          </div>
                      </div><!-- End Performance Card -->
                      
                      <!-- Champions Card -->
                      <div class="col-lg-3 col-6">
                          <div class="small-box">
                              <div class="inner">
                                  <h6 class="text-danger">Champions</h6>
                                  <div><small class="text-primary"><?php echo $best['ward_name']." : "; ?><span><?php echo number_format($best['performance_percentage'], 2); ?>%</span></small></div>
                                  <small class="text-primary"><?php echo $best_stream['stream_name']." : "; ?><span><?php echo " Ksh.". $best_stream['total_collection']?></span></small>
                              </div>
                              <div class="icon">
                                  <i class="fas fa-medal"></i>
                              </div>
                              <a href="#" class="small-box-footer text-primary" style="background-color: #ffeb3b"><strong>This Month</strong></a>
                          </div>
                      </div><!-- End Champions Card -->
                  </div>

                  <!-- Reports -->
                  <div class="row">
                      <div class="col-12">
                      <div class="card">
                      <div class="card-header ">
                      <h5 class="card-title text-primary">Wards | Stream Overall Performance</h5>
                      </div>
                      <div class="card-body">
                          <!-- Line Chart -->

                          <canvas id="wardPerformanceChart" width="400" height="200"></canvas>
                          <script>
                                var ctx = document.getElementById('wardPerformanceChart').getContext('2d');

                                // Prepare the datasets for each ward
                                var wardCollectionDatasets = [];

                                <?php foreach ($wards as $ward_name => $data): ?>
                                    wardCollectionDatasets.push({
                                        label: '<?php echo $ward_name; ?> - Collection',
                                        data: <?php echo json_encode(array_values($data['collection'])); ?>,
                                        borderColor: getRandomColor(),
                                        fill: false
                                    });
                                <?php endforeach; ?>

                                // Ensure all months are included, even if there are no collections
                                var allMonths = <?php echo json_encode($months); ?>;
                                var completeMonths = [
                                    "July", "August", "September", "October", "November", "December", 
                                    "January", "February", "March", "April", "May", "June"
                                ];

                                // Data for the chart
                                var chart = new Chart(ctx, {
                                    type: 'line',
                                    tension: 0.4,
                                    data: {
                                        labels: completeMonths, // X-axis labels (months)
                                       
                                        datasets: wardCollectionDatasets // Only collection datasets
                                    },
                                    options: {
                                        scales: {
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Month'
                                                },
                                                ticks: {
                                                    autoSkip: false // Ensure all months are shown
                                                }
                                            },
                                            y: {
                                                title: {
                                                    display: true,
                                                    text: 'Amount (Ksh)'
                                                },
                                                beginAtZero: true // Start the Y-axis at zero
                                            }
                                        },
                                        responsive: true
                                    }
                                });

                                // Function to generate random colors for each ward
                                function getRandomColor() {
                                    var letters = '0123456789ABCDEF';
                                    var color = '#';
                                    for (var i = 0; i < 6; i++) {
                                        color += letters[Math.floor(Math.random() * 16)];
                                    }
                                    return color;
                                }
                            </script>


                          <!-- End Line Chart -->
                      </div>
                   </div>
                  </div>
                  </div><!-- End Reports -->

                  <!-- Wards Monthly Performance Chart -->
                <div class="card ">
                    <div class="card-header">
                        <h4 class="card-title">Municipal Overal Performance</h4>
                    </div>
                    <div class="card-body">
                        <!-- Chart Container -->
                        <div style="position: relative; height: 500px;">  <!-- Set container height -->
                            <canvas id="trendChart" style="width: 100%; height: 100%;"></canvas>
                        </div>
                           <!-- Add Chart.js library -->
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const ctx = document.getElementById('trendChart').getContext('2d');

                                // Define all months from July to June
                                const months = ['July', 'August', 'September', 'October', 'November', 'December', 
                                                'January', 'February', 'March', 'April', 'May', 'June'];

                                // Manually defined data arrays (collection and target data)
                                const collectionData = <?php echo json_encode($collection_trend_data); ?>;
                                const targetData = <?php echo json_encode($target_trend_data); ?>;

                                const trendChart = new Chart(ctx, {
                                    type: 'bar',  // Choose 'bar' for bar chart
                                    data: {
                                        labels: months,  // X-axis labels (Months)
                                        datasets: [
                                            {
                                                label: 'Target',  // Label for Target data
                                                data: targetData,  // Target data
                                                backgroundColor: 'rgba(65, 105, 225, 0.7)',  // Royal blue for bar
                                                borderColor: 'rgba(65, 105, 225, 1)',  // Darker blue border
                                                borderWidth: 2,  // Bar border width
                                                barThickness: 20,  // Thickness of the bars
                                            },
                                            {
                                                label: 'Collection',  // Label for Collection data
                                                data: collectionData,  // Collection data
                                                backgroundColor: 'rgba(50, 205, 50, 0.7)',  // Lime green for bar
                                                borderColor: 'rgba(50, 205, 50, 1)',  // Darker green border
                                                borderWidth: 2,  // Bar border width
                                                barThickness: 20,  // Thickness of the bars
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,  // Make chart responsive
                                        maintainAspectRatio: false,  // Allow custom aspect ratio
                                        plugins: {
                                            legend: {
                                                position: 'top',  // Move the legend to the top
                                                labels: {
                                                    font: {
                                                        size: 14  // Increase font size for better readability
                                                    }
                                                }
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(tooltipItem) {  // Format tooltips as currency
                                                        return tooltipItem.dataset.label + ': Ksh ' + tooltipItem.raw.toLocaleString();
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Months',  // X-axis title
                                                    font: {
                                                        size: 16,  // Font size for axis title
                                                        weight: 'bold'  // Bold font
                                                    }
                                                },
                                                ticks: {
                                                    font: {
                                                        size: 12  // Increase font size for X-axis labels
                                                    }
                                                }
                                            },
                                            y: {
                                                beginAtZero: true,  // Start Y-axis from zero
                                                title: {
                                                    display: true,
                                                    text: 'Amount (Ksh)',  // Y-axis title
                                                    font: {
                                                        size: 16,  // Font size for axis title
                                                        weight: 'bold'  // Bold font
                                                    }
                                                },
                                                ticks: {
                                                    callback: function(value) {  // Format Y-axis as currency
                                                        return 'Ksh ' + value.toLocaleString();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            });
                        </script>
                        <?php include('../partials/chartplugins.php'); ?>
                    </div>
              </div><!-- /.container-fluid -->
          </section>

      </main>
    </body>
</html>
