<?php
session_start();
require('../config/config.php');

// Fetch top-performing streams with approved collections
$query = "
    SELECT
        rs.stream_id,
        rs.stream_name,
        COALESCE(SUM(approved_collections.total_collections), 0) AS total_collections,
        COALESCE(SUM(targets.total_targets), 0) AS total_targets,
        ROW_NUMBER() OVER (ORDER BY COALESCE(SUM(approved_collections.total_collections), 0) DESC) AS stream_rank
    FROM revenue_streams rs
    LEFT JOIN (
        SELECT
            collection_stream_id,
            SUM(collection_amount) AS total_collections
        FROM revenue_collections
        WHERE collection_status = 'Approved'
        GROUP BY collection_stream_id
    ) AS approved_collections ON rs.stream_id = approved_collections.collection_stream_id
    LEFT JOIN (
        SELECT
            st.streamtarget_stream_id,
            SUM(st.streamtarget_amount) AS total_targets
        FROM streamtarget st
        GROUP BY st.streamtarget_stream_id
    ) AS targets ON rs.stream_id = targets.streamtarget_stream_id
    GROUP BY rs.stream_name
    ORDER BY stream_rank;
";


$stmt = $mysqli->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$streams = [];
$collections = [];
$targets = [];
$stream_ids = [];

while ($row = $result->fetch_assoc()) {
    $streams[] = $row['stream_name'];
    $collections[] = $row['total_collections'];
    $targets[] = $row['total_targets'];
    $stream_ids[] = $row['stream_id'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require('../partials/head.php'); ?>
    <title>Top Performing Streams</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-card {
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Include Sidebar -->
        <?php include('../partials/executive_sidenav.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Top Performing Streams</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../views/dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Top Performing Streams</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Top Performing Streams List -->
                        <div class="col-md-12 dashboard-card">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Top Performing Streams</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <?php foreach ($streams as $index => $stream_name) : ?>
                                            <li class="list-group-item">
                                                <a href="#?stream_id=<?php echo urlencode($stream_ids[$index]); ?>">
                                                    <?php echo htmlspecialchars($stream_name); ?> - Collections: <?php echo number_format($collections[$index], 2); ?> - Targets: <?php echo number_format($targets[$index], 2); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Top Performing Streams Chart -->
                        <div class="col-md-12 dashboard-card">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Top Performing Streams (Chart)</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="streamsChart"></canvas>
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
        <?php include('../partials/scriptn.php'); ?>
    </div>
    <!-- ./wrapper -->

    <!-- Chart.js Script -->
    <script>
        var ctx = document.getElementById('streamsChart').getContext('2d');
        var streamsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($streams); ?>,
                datasets: [{
                    label: 'Total Collections',
                    data: <?php echo json_encode($collections); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Total Targets',
                    data: <?php echo json_encode($targets); ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
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

</html>
