<?php
// Start session if needed
session_start();

// Enable error reporting for troubleshooting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the necessary files
require_once('../config/config.php');

// Query to Fetch User IDs, Names, Ward Names, and Total Approved Collection Amount
$query_officers = "
    SELECT 
        u.user_id, 
        u.user_names, 
        w.ward_name,
        SUM(c.collection_amount) AS total_collected
    FROM revenue_collections c
    JOIN users u ON c.collection_user_id = u.user_id
    JOIN ward w ON u.user_ward_id = w.ward_id
    WHERE c.collection_status = 'Approved'
    GROUP BY u.user_id, u.user_names, w.ward_name
    ORDER BY total_collected DESC
    LIMIT 5;
";

// Execute the query
$result_officers = $mysqli->query($query_officers);

// Fetch data for the chart
$officers = [];
$amounts = [];
$officer_ids = [];

while ($row = $result_officers->fetch_assoc()) {
    $officers[] = $row['user_names'];
    $amounts[] = $row['total_collected'];
    $officer_ids[] = $row['user_id'];
}
?>

<head>
    <?php require_once('../partials/head.php'); ?>
    <title>Top Performing Officers</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-card {
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
       
        <!-- Include Header -->
       

        <!-- Include Sidebar -->
        <?php include('../partials/executive_sidenav.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Top Performing Officers</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../views/dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Top Performing Officers</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Top Performing Officers List -->
                        <div class="col-md-12 dashboard-card">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Top Performing Officers</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <?php foreach ($officer_ids as $index => $officer_id) : ?>
                                            <li class="list-group-item">
                                                <a href="#?officer_id=<?php echo urlencode($officer_id); ?>">
                                                    <?php echo htmlspecialchars($officers[$index]); ?> - <?php echo number_format($amounts[$index], 2); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Top Performing Officers Chart -->
                        <div class="col-md-12 dashboard-card">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Top Performing Officers (Chart)</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="officersChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Include Footer -->
        <?php include('../partials/footer.php'); ?>
    </div>
    <!-- ./wrapper -->
    <?php include('../partials/scriptn.php'); ?>
    <!-- Chart.js Script -->
    <script>
        var ctx = document.getElementById('officersChart').getContext('2d');
        var officersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($officers); ?>,
                datasets: [{
                    label: 'Total Collection Amount',
                    data: <?php echo json_encode($amounts); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
