<?php
session_start();
require('../config/config.php');

// Fetch the stream ID from the query string
$stream_id = isset($_GET['stream_id']) ? intval($_GET['stream_id']) : 0;

// Initialize variables
$stream_name = '';
$total_collections = 0;
$total_targets = 0;
$target_achievement = 0;

// Validate stream_id
if ($stream_id <= 0) {
    die('Invalid stream ID.');
}

// Fetch stream details and performance data
$query = "
    SELECT
        rs.stream_name,
        COALESCE(SUM(rc.collection_amount), 0) AS total_collections,
        COALESCE(SUM(ct.collectortarget_amount), 0) AS total_targets
    FROM revenue_streams rs
    LEFT JOIN revenue_collections rc ON rs.stream_id = rc.collection_stream_id
        AND rc.collection_status = 'Approved'
    LEFT JOIN collectortarget ct ON rs.stream_id = ct.collectortarget_streamtarget_id
    WHERE rs.stream_id = ?
    GROUP BY rs.stream_name
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $stream_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $stream_name = $row['stream_name'];
    $total_collections = $row['total_collections'];
    $total_targets = $row['total_targets'];

    // Calculate target achievement
    $target_achievement = $total_targets > 0 ? ($total_collections / $total_targets) * 100 : 0;
} else {
    die('Stream not found.');
}

$stmt->close();

// Fetch monthly collections for the selected stream within the date range
$start_date = '';
$end_date = '';

if (isset($_GET['daterange'])) {
    list($start_date, $end_date) = explode(' - ', $_GET['daterange']);
    $start_date = date('Y-m-d', strtotime($start_date));
    $end_date = date('Y-m-d', strtotime($end_date));
} else {
    $start_date = date('Y-m-01'); // Default to the start of the current month
    $end_date = date('Y-m-t'); // Default to the end of the current month
}

$monthly_collections_query = "
    SELECT
        DATE_FORMAT(collection_date, '%Y-%m') AS month,
        COALESCE(SUM(collection_amount), 0) AS monthly_collections
    FROM revenue_collections
    WHERE collection_stream_id = ? 
        AND collection_status = 'Approved'
        AND collection_date BETWEEN ? AND ?
    GROUP BY month
    ORDER BY month DESC
";

$monthly_stmt = $mysqli->prepare($monthly_collections_query);
$monthly_stmt->bind_param('iss', $stream_id, $start_date, $end_date);
$monthly_stmt->execute();
$monthly_result = $monthly_stmt->get_result();

$months = [];
$monthly_collections = [];

while ($row = $monthly_result->fetch_assoc()) {
    $months[] = $row['month'];
    $monthly_collections[] = $row['monthly_collections'];
}

$monthly_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require('../partials/head.php'); ?>
    <title>Stream Performance Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-card {
            margin-bottom: 20px;
        }

        .achievement {
            font-size: 1.2em;
            color: #28a745;
        }
    </style>
</head>

<body>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Date Range Filter -->
                <div class="col-md-12 dashboard-card">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Filter by Date Range</h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="stream_performance_report.php">
                                <input type="hidden" name="stream_id" value="<?php echo htmlspecialchars($stream_id); ?>">
                                <div class="form-group">
                                    <label for="daterange">Select Date Range:</label>
                                    <div class="input-group">
                                        <input type="text" id="daterange" name="daterange" class="form-control" value="<?php echo isset($_GET['daterange']) ? htmlspecialchars($_GET['daterange']) : ''; ?>" autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Predefined Ranges</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-range="today">Today</a>
                                        <a class="dropdown-item" href="#" data-range="yesterday">Yesterday</a>
                                        <a class="dropdown-item" href="#" data-range="last7days">Last 7 Days</a>
                                        <a class="dropdown-item" href="#" data-range="last30days">Last 30 Days</a>
                                        <a class="dropdown-item" href="#" data-range="thismonth">This Month</a>
                                        <a class="dropdown-item" href="#" data-range="lastmonth">Last Month</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Stream Performance Report -->
                <div class="col-md-12 dashboard-card">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Stream Performance Report: <?php echo htmlspecialchars($stream_name); ?></h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Total Collections:</strong> <?php echo number_format($total_collections, 2); ?></p>
                            <p><strong>Total Targets:</strong> <?php echo number_format($total_targets, 2); ?></p>
                            <p><strong>Target Achievement:</strong> <span class="achievement"><?php echo number_format($target_achievement, 2); ?>%</span></p>
                        </div>
                    </div>
                </div>

                <!-- Monthly Collections Chart -->
                <div class="col-md-12 dashboard-card">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Monthly Collections</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="monthlyCollectionsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chart.js Script -->
    <script>
        var ctx = document.getElementById('monthlyCollectionsChart').getContext('2d');
        var monthlyCollectionsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Monthly Collections',
                    data: <?php echo json_encode($monthly_collections); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: true
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

        // Initialize Date Range Picker
        $(function() {
            $('#daterange').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                },
                ranges: {
                    'Today': [moment().startOf('day'), moment().endOf('day')],
                    'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                $(this).closest('form').submit();
            });

            // Handle dropdown range selection
            $('.dropdown-menu a').on('click', function() {
                var range = $(this).data('range');
                var start, end;

                switch (range) {
                    case 'today':
                        start = moment().startOf('day');
                        end = moment().endOf('day');
                        break;
                    case 'yesterday':
                        start = moment().subtract(1, 'days').startOf('day');
                        end = moment().subtract(1, 'days').endOf('day');
                        break;
                    case 'last7days':
                        start = moment().subtract(6, 'days');
                        end = moment();
                        break;
                    case 'last30days':
                        start = moment().subtract(29, 'days');
                        end = moment();
                        break;
                    case 'thismonth':
                        start = moment().startOf('month');
                        end = moment().endOf('month');
                        break;
                    case 'lastmonth':
                        start = moment().subtract(1, 'month').startOf('month');
                        end = moment().subtract(1, 'month').endOf('month');
                        break;
                    default:
                        start = moment().startOf('month');
                        end = moment().endOf('month');
                }

                $('#daterange').data('daterangepicker').setStartDate(start);
                $('#daterange').data('daterangepicker').setEndDate(end);
                $('#daterange').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                $('#daterange').closest('form').submit();
            });
        });
    </script>
</body>

</html>
