<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('../functions/reusableQuery.php');
require_once('../config/config.php');
require_once('../helpers/auth.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('../partials/head.php'); ?>
    <!-- Add daterangepicker CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <style>
        /* Make sure the chart container is properly sized */
        #chartContainer {
            height: 200px;
            /* Container height */
            width: 100%;
            position: relative;
        }

        /* Set the canvas to fully fill the container */
        #growthChart {
            height: 100% !important;
            width: 100% !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <?php include('../partials/preloader.php'); ?>

        <!-- Navbar -->
        <?php include('../partials/header.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include('../partials/executive_sidenav.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Revenue Growth Rate</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../views/dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Revenue Growth Rate</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div><!-- /.content-header -->

            <!-- Main Content -->
            <section class="content">
                <div class="container mt-5">
                    <h2>Revenue Growth Rate</h2>
                    <!-- Period Selector -->
                    <div class="form-group">
                        <label for="dateRange">Select Date Range:</label>
                        <input type="text" id="dateRange" class="form-control" />
                    </div>

                    <!-- Chart Container -->
                    <canvas id="growthChart" style="width: 100%; height: 400px;"></canvas>
                </div><!-- /.container -->
                <div id="chartContainer">
                    <canvas id="growthChart"></canvas>
                </div>
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <!-- Include Footer -->
        <?php include('../partials/footer.php'); ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
    </div><!-- /.wrapper -->

    <!-- Include jQuery and other scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize date range picker
        $('#dateRange').daterangepicker({
            opens: 'left',
            startDate: moment().startOf('year').format('YYYY-MM-DD'), // Start of current year
            endDate: moment().format('YYYY-MM-DD'), // Current date
            locale: {
                format: 'YYYY-MM-DD'
            }
        }, function(start, end) {
            fetchChartData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });

        // Function to fetch chart data based on the selected date range
        async function fetchChartData(startDate, endDate) {
            console.log('Fetching data for:', startDate, 'to', endDate); // Debugging log

            try {
                const response = await fetch(`../helpers/performance_analysis.php?start_date=${startDate}&end_date=${endDate}`);
                const data = await response.json();
                console.log('Fetched Data:', data); // Debugging log

                if (Array.isArray(data) && data.length > 0) {
                    const {
                        labels,
                        values
                    } = prepareChartData(data);
                    drawChart(labels, values);
                } else {
                    console.warn('No data available for the selected date range.');
                    drawChart([], []); // Clear the chart if no data
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // Convert data to chart format
        function prepareChartData(data) {
            const labels = data.map(item => item.period);
            const values = data.map(item => item.total_revenue);
            return {
                labels,
                values
            };
        }

        // Variable to hold the chart instance
        let chartInstance = null;

        // Draw the chart with reduced height
        function drawChart(labels, data) {
            const ctx = document.getElementById('growthChart').getContext('2d');

            // Destroy the existing chart instance to ensure the new one renders correctly
            if (chartInstance) {
                chartInstance.destroy();
            }

            // Create a new chart with adjusted settings
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Revenue',
                        data: data,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true, // Maintain the aspect ratio
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat().format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Period'
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Total Revenue'
                            },
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 2]
                            }
                        }
                    }
                }
            });
        }

        // Initial chart load on page load
        document.addEventListener('DOMContentLoaded', () => {
            const startDate = moment().startOf('year').format('YYYY-MM-DD');
            const endDate = moment().format('YYYY-MM-DD');
            fetchChartData(startDate, endDate);
        });
    </script>


</body>

</html>