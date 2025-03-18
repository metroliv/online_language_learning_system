<?php
require_once('../config/config.php');
require_once('../functions/reusableQuery.php');
require_once('../helpers/cards_collection.php');
require_once('../helpers/stream_perfomance_query.php');
require_once('../helpers/ward_perfomance_query.php');
require_once('../partials/head.php');
require_once('../helpers/cards_collection.php');
require_once('../helpers/stream_perfomance_query.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('../partials/head.php'); ?>
    <title>Revenue Collection Reports</title>
    <style>
        .chart-container {
            position: relative;
            height: 500px;
            width: 100%;
        }

        .content-wrapper {
            padding: 20px;
        }
    </style>
    <!-- Add Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <small>Welcome to Department of Finance Revenue Collection Tool</small>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <!-- Info Boxes -->
                <div class="col-md-3 col-sm-6 col-12">
                    <a href="monthly_revenue_target" class="text-dark">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="far fa-flag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Monthly Revenue Target</span>
                                <!--<span class="info-box-number"><?php echo number_format($monthlyRevenueTarget); ?> Ksh</span>-->
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <a href="monthly_revenue_collected" class="text-dark">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Monthly Revenue Collected</span>
                                <!--<span class="info-box-number"><?php echo number_format($monthlyRevenueCollected); ?> Ksh</span>-->
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <a href="monthly_target_achieved" class="text-dark">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-percent"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">% Monthly Target Achieved</span>
                                <!-- <span class="info-box-number"><?php echo number_format($monthlyPercentageAchieved, 0); ?>%</span>-->
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- First Row: Monthly and Weekly Comparison Charts -->
            <div class="row">
                <!-- Monthly Comparison Chart -->
                <div class="col-md-6">
                    <h2>Monthly Comparison for August</h2>
                    <div class="chart-container">
                        <canvas id="monthlyComparisonChart"></canvas>
                    </div>
                </div>

                <!-- Weekly Comparison Chart -->
                <div class="col-md-6">
                    <h2>Weekly Comparison for Week 47</h2>
                    <div class="chart-container">
                        <canvas id="weeklyComparisonChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Spacer Row (Optional) -->
            <div class="row mt-2"> <!-- Use mt-2 for smaller spacing -->
                <div class="col-md-12">
                    <!-- This row will ensure spacing without affecting the layout -->
                </div>
            </div>

            <!-- Second Row: Performance Comparison Chart -->
            <div class="row">
                <div class="col-md-12">
                    <h2>Performance Comparison for Various Streams</h2>
                    <div class="chart-container">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <h2>Monthly Performance Comparison</h2>
                    <div class="chart-container">
                        <canvas id="monthlyPerformanceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Wards Performance Comparison Chart -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h2>Wards Performance Comparison</h2>
                    <div class="chart-container">
                        <canvas id="wardsPerformanceChart"></canvas>
                    </div>
                </div>
            </div>
        </di>
    </div>

    <!-- Chart.js code for generating the charts -->
    <script>
        // Data for Monthly Comparison (August)
        const monthlyComparisonData = {
            labels: ['FY 22/23', 'FY 23/24', 'FY 24/25'],
            datasets: [{
                label: 'Revenue Collected in August (Ksh)',
                data: [46, 50, 55], // Replace with actual values
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Configuring the bar chart for Monthly Comparison
        const monthlyComparisonConfig = {
            type: 'bar',
            data: monthlyComparisonData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Revenue Collected (Ksh)'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Revenue Comparison for August (Financial Years)'
                    }
                }
            }
        };

        // Render the Monthly Comparison Chart
        const monthlyComparisonChart = new Chart(
            document.getElementById('monthlyComparisonChart'),
            monthlyComparisonConfig
        );

        // Data for Weekly Comparison (Week 47)
        const weeklyComparisonData = {
            labels: ['F1', 'F2', 'F3'],
            datasets: [{
                label: 'Revenue Collected in Week 47 (Ksh)',
                data: [48, 42, 40], // Replace with actual values
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(255, 205, 86, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 205, 86, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Configuring the bar chart for Weekly Comparison
        const weeklyComparisonConfig = {
            type: 'bar',
            data: weeklyComparisonData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Revenue Collected (Ksh)'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Revenue Comparison for Week 47'
                    }
                }
            }
        };

        // Render the Weekly Comparison Chart
        const weeklyComparisonChart = new Chart(
            document.getElementById('weeklyComparisonChart'),
            weeklyComparisonConfig
        );

        // Data for Performance Comparison (Target vs Collected Amounts)
        const performanceData = {
            labels: ['Stream A', 'Stream B', 'Stream C'], // Replace with actual stream names
            datasets: [{
                    label: 'Target Amount (T)',
                    data: [100, 200, 150], // Replace with actual target amounts
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Collected Amount (C)',
                    data: [80, 180, 120], // Replace with actual collected amounts
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        };

        // Configuring the bar chart for Performance Comparison
        const performanceConfig = {
            type: 'bar',
            data: performanceData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount (Ksh)'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Comparison of Target vs Collected Amounts'
                    },
                    legend: {
                        position: 'top'
                    }
                }
            }
        };

        // Render the Performance Comparison Chart
        const performanceChart = new Chart(
            document.getElementById('performanceChart'),
            performanceConfig
        );
    // Data for Monthly Performance Comparison
    const monthlyPerformanceData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [
            {
                label: 'Target Amount',
                data: [30, 40, 35, 50, 60, 45, 55, 70, 65, 75, 85, 90], // Replace with actual target amounts
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            },
            {
                label: 'Collected Amount',
                data: [28, 38, 33, 48, 58, 43, 53, 68, 63, 72, 83, 88], // Replace with actual collected amounts
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }
        ]
    };

    // Configuring the bar chart for Monthly Performance Comparison
    const monthlyPerformanceConfig = {
        type: 'bar',
        data: monthlyPerformanceData,
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount (Ksh)'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Performance Comparison'
                }
            }
        }
    };

    // Render the Monthly Performance Comparison Chart
    const monthlyPerformanceChart = new Chart(
        document.getElementById('monthlyPerformanceChart'),
        monthlyPerformanceConfig
    );

    // Data for Wards Performance Comparison
    const wardsPerformanceData = {
        labels: ['Ward 1', 'Ward 2', 'Ward 3'], // Replace with actual wards
        datasets: [
            {
                label: 'Target Amount',
                data: [300, 400, 350], // Replace with actual target amounts for each ward
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Collected Amount',
                data: [280, 380, 330], // Replace with actual collected amounts for each ward
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }
        ]
    };

    // Configuring the bar chart for Wards Performance Comparison
    const wardsPerformanceConfig = {
        type: 'bar',
        data: wardsPerformanceData,
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Ward'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount (Ksh)'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Wards Performance Comparison'
                }
            }
        }
    };

    // Render the Wards Performance Comparison Chart
    const wardsPerformanceChart = new Chart(
        document.getElementById('wardsPerformanceChart'),
        wardsPerformanceConfig
    );
    </script>

</body>

</html>