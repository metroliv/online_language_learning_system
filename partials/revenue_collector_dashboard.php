<?php
// Start session and connect to the database

require_once('../config/config.php');
require_once('../helpers/auth.php');
require_once('../partials/headn.php');
require_once('../functions/collector_target_analytics.php');
//require_once('../helpers/cards_collection.php');
require_once('../helpers/target.php');

//dd($_SESSION['user_ward_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Collector Dashboard</title>
        <link rel="stylesheet" href="../public/css/adminlte.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <!-- DataTable CSS -->
        <link rel="stylesheet" href="../public/dist/css/adminlte.min.css">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Load jQuery first -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <!-- Then load DataTables -->
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

<body>
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
                        <h5>
                            <?php
                            echo "<br/>";
                            // Fetch the active fiscal year (fy_id)
                            $fyQuery = "SELECT fy_id, fy_year FROM financial_year WHERE fy_status = 1 LIMIT 1";
                            $fyStmt = $mysqli->prepare($fyQuery);
                            $fyStmt->execute();
                            $fyResult = $fyStmt->get_result();

                            if ($fyRow = $fyResult->fetch_assoc()) {
                                $financialYearId = $fyRow['fy_id'];
                                $fyYear = $fyRow['fy_year']; // Retrieve fiscal year string for display
                            
                                if ($annual) {
                                    echo $fyYear . ' FY';
                                } else {
                                    echo $fyMonths[$months] . ', ' . $fyYear;
                                }
                            } else {
                                die("No active financial year found.");
                            }
                            ?>
                        </h5>

                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="dashboard"><?php echo $ward_name['ward_name'] ?></a>
                            </li>
                            <li class="breadcrumb-item active">Dashboard</li>
                            </li>
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
        include('../modals/filters.php') ?>
        <!-- ./ filters -->

        <div class="row">
            <!-- Date Range Filter -->

        </div>

        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->

            <!-- /.row -->
            <div class="row">

                <div class="col-md-3 col-sm-6 col-12">
                    <a href="revenue_target_per_stream?id=<?php echo $_SESSION['user_id'] ?>&month=<?php echo $months ?>&fy=<?php echo $active_fy_id ?>"
                        class="text-dark">
                        <div class="small-box">
                            <div>
                                <h5 class="text-danger">My Target</h5>
                            </div>
                            <div class="inner" style="min-height: 50px;">
                                <h4>Ksh <?php
                                /** target amount */
                                $query = "SELECT sum(info_amount) FROM collectortarget_info ci
                                        INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
                                        INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
                                        INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
                                        WHERE fy.fy_id = '{$fy}'
                                        AND ct.collectortarget_user_id = '{$_SESSION['user_id']}'
                                        AND ct.collectortarget_month IN ({$months})";
                                $stmt = $mysqli->prepare($query);
                                $stmt->execute();
                                $stmt->bind_result($target);
                                $stmt->fetch();
                                $stmt->close();

                                /** deficit amount */
                                $query = "SELECT sum(info_defecit) FROM collectortarget_info ci
                                        INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
                                        INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
                                        INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
                                        WHERE fy.fy_id = '{$fy}'
                                        AND ct.collectortarget_user_id = '{$_SESSION['user_id']}'
                                        AND ct.collectortarget_month IN ({$months})";
                                $stmt = $mysqli->prepare($query);
                                $stmt->execute();
                                $stmt->bind_result($defecit);
                                $stmt->fetch();
                                $stmt->close();

                                $targetTotal = $target + $defecit;
                                if ($targetTotal > 0) {
                                    echo number_format($targetTotal);
                                } else {
                                    echo '0';
                                }
                                ?></h4>
                            </div>
                            <div class="icon">
                                <i class="fas fa-gem" style="color: grey; font-size: 50px; right: 15px;"></i>
                            </div>
                            <a href="#"
                                class="small-box-footer d-flex justify-content-between align-items-center text-primary"
                                style="background-color: #ffdd00" data-toggle="modal" data-target="#filterDashboard">
                                <span><?php
                                if ($annual) {
                                    echo $fyYear . ' FY';
                                } else {
                                    echo $fyMonths[$months] . ', ' . $fyYear;
                                }
                                ?></span>
                                <span class="dot-indicator"
                                    style="font-size: 14px; color: red;">&#8226;&#8226;&#8226;</span>
                            </a>
                        </div>
                        <!-- /.small-box -->
                    </a>
                </div>


                <div class="col-md-3 col-sm-6 col-12">
                    <a href="#" class="text-dark">
                        <div class="small-box">
                            <div>
                                <h5 class="text-danger">Collections</h5>
                            </div>
                            <div class="inner" style="min-height: 50px;">
                                <h4>Ksh <?php
                                /** Total collections */
                                $query = "SELECT SUM(collection_amount) FROM revenue_collections rc
                                      INNER JOIN financial_year fy ON rc.collections_fy = fy.fy_id
                                      WHERE collection_user_id = '{$_SESSION['user_id']}'
                                      AND collection_status = 'Approved' 
                                      AND fy.fy_status = '1'
                                      AND collections_month IN ({$months})";
                                $stmt = $mysqli->prepare($query);
                                $stmt->execute();
                                $stmt->bind_result($collections_total);
                                $stmt->fetch();
                                $stmt->close();
                                if ($collections_total > 0) {
                                    echo number_format($collections_total);
                                } else {
                                    echo '0';
                                }
                                ?></h4>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart" style="color: grey; font-size: 50px; right: 15px;"></i>
                            </div>
                            <a href="#"
                                class="small-box-footer d-flex justify-content-between align-items-center text-primary"
                                style="background-color: #ffdd00" data-toggle="modal" data-target="#filterDashboard">
                                <span>
                                    <?php
                                    if ($annual) {
                                        echo $fyYear . ' FY';
                                    } else {
                                        echo $fyMonths[$months] . ', ' . $fyYear;
                                    }
                                    ?>
                                </span>
                                <span class="dot-indicator"
                                    style="font-size: 14px; color: red;">&#8226;&#8226;&#8226;</span>
                            </a>
                        </div>
                        <!-- /.small-box -->
                    </a>
                </div>
                <!-- /.col -->

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="small-box">
                        <h5 class="text-danger">Performance</h5>
                        <div class="inner" style="min-height: 40px;"> <!-- Added min-height -->
                            <h5>
                                <?php
                                if ($targetTotal) {
                                    echo number_format(($collections_total * 100) / $targetTotal, 2) . '%';
                                } else {
                                    echo '0%';
                                }
                                ?>
                            </h5>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-bar"
                                style="color: grey; font-size: 50px; right: 15px; margin-bottom: 0px;"></i>
                        </div>
                        <a href="#"
                            class="small-box-footer d-flex justify-content-between align-items-center text-primary"
                            style="background-color: #ffdd00" data-toggle="modal" data-target="#filterDashboard">
                            <span>
                                <?php
                                if ($annual) {
                                    echo $fyYear . ' FY';
                                } else {
                                    echo $fyMonths[$months] . ', ' . $fyYear;
                                }
                                ?>
                            </span>
                            <span class="dot-indicator"
                                style="font-size: 14px; color: red;">&#8226;&#8226;&#8226;</span>
                        </a>
                    </div>
                    <!-- /.small-box -->
                </div>
                <!-- /.col -->

                <div class="col-md-3 col-sm-6 col-12">
                    <a href="imprest" class="text-dark">
                        <div class="small-box">
                            <div>
                                <h5 class="text-danger">Imprest Earned</h5>
                            </div>
                            <div class="inner" style="min-height: 45px;">
                                <h4>
                                    Ksh
                                    <?php
                                    // Initialize variables for the calculations
                                    $total_imprest_earned = 0;

                                    // Fetch user data
                                    $stmt = $mysqli->prepare("SELECT * FROM users WHERE user_id = ?");
                                    $stmt->bind_param("i", $_SESSION['user_id']);
                                    $stmt->execute();
                                    $fetch_records_sql = $stmt->get_result();

                                    if ($fetch_records_sql && mysqli_num_rows($fetch_records_sql) > 0) {
                                        while ($rows = mysqli_fetch_array($fetch_records_sql)) {
                                            // Target amount
                                            $query = "
                                SELECT SUM(info_amount) 
                                FROM collectortarget_info ci
                                INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
                                INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
                                INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
                                WHERE fy.fy_id = ?
                                AND ct.collectortarget_user_id = ?
                            ";
                                            $stmtTarget = $mysqli->prepare($query);
                                            $stmtTarget->bind_param("ii", $fy, $rows['user_id']);
                                            $stmtTarget->execute();
                                            $stmtTarget->bind_result($target);
                                            $stmtTarget->fetch();
                                            $stmtTarget->close();

                                            // Collections
                                            $query = "
                                SELECT SUM(collection_amount) 
                                FROM revenue_collections rc
                                INNER JOIN financial_year fy ON rc.collections_fy = fy.fy_id
                                WHERE rc.collection_user_id = ?
                                AND collection_status = 'Approved' 
                                AND fy.fy_status = '1'
                            ";
                                            $stmtCollections = $mysqli->prepare($query);
                                            $stmtCollections->bind_param("i", $rows['user_id']);
                                            $stmtCollections->execute();
                                            $stmtCollections->bind_result($stream_collections);
                                            $stmtCollections->fetch();
                                            $stmtCollections->close();

                                            // Calculate Achieved Percentage
                                            if ($target > 0) {
                                                $achievedPercentage = ($stream_collections * 100) / $target;
                                            } else {
                                                $achievedPercentage = 0; // Default to 0% if no target
                                            }

                                            // Imprest
                                            $query = "
                                SELECT imprest_amount, imprest_retainer 
                                FROM imprest i
                                INNER JOIN financial_year fy ON fy.fy_id = i.imprest_fyr
                                WHERE fy.fy_id = ?
                            ";
                                            $stmtImprest = $mysqli->prepare($query);
                                            $stmtImprest->bind_param("i", $fy);
                                            $stmtImprest->execute();
                                            $stmtImprest->bind_result($annual_imprest, $retainer);
                                            $stmtImprest->fetch();
                                            $stmtImprest->close();

                                            // Calculate the earned imprest
                                            if ($annual_imprest > 0) {
                                                $imprest_earned = (($annual_imprest * $achievedPercentage) / 100) + $retainer;
                                            } else {
                                                $imprest_earned = $retainer;
                                            }

                                            // Add to the total imprest earned
                                            $total_imprest_earned += $imprest_earned;
                                        }
                                    }
                                    echo number_format($total_imprest_earned, 2);
                                    ?>
                                </h4>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file-invoice-dollar"
                                    style="color: grey; font-size: 50px; right: 15px;"></i>
                            </div>
                            <a href="#"
                                class="small-box-footer d-flex justify-content-between align-items-center text-primary"
                                style="background-color: #ffdd00" data-toggle="modal" data-target="#filterDashboard">
                                <span>
                                    <?php
                                    if ($annual) {
                                        echo $fyYear . ' FY';
                                    } else {
                                        echo $fyMonths[$months] . ', ' . $fyYear;
                                    }
                                    ?>
                                </span>
                                <span class="dot-indicator"
                                    style="font-size: 14px; color: red;">&#8226;&#8226;&#8226;</span>
                            </a>
                        </div>
                        <!-- /.small-box -->
                    </a>
                </div>


                <!-- /.col -->
            </div>
            <!-- /.row -->

            <?php include('../views/chart2.php'); ?>

            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background-color: #ffdd00;">
                        <h5 class="card-title">Stream Tracker</h5>
                        <div class="card-tools">
                            <a class="btn btn-tool" href="#" data-bs-toggle="dropdown"><i class="fas fa-filter"
                                    style="font-size: 14px; color: red;"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">This Month</a>
                                <a class="dropdown-item" href="#">This Year</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="streamTrackerTable" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Stream</th>
                                    <th>Target</th>
                                    <th>Collected</th>
                                    <th>Tracker</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $fetch_records_sql = mysqli_query($mysqli, "SELECT * FROM revenue_streams ORDER BY stream_name DESC");
                                if (mysqli_num_rows($fetch_records_sql) > 0) {
                                    $cnt = 1;
                                    while ($rows = mysqli_fetch_array($fetch_records_sql)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $rows['stream_name']; ?></td>
                                            <td>
                                                <?php
                                                // Calculate target
                                                $query = "SELECT sum(info_amount) FROM collectortarget_info ci
                                              INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
                                              INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
                                              INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
                                              WHERE fy.fy_id = '{$fy}'
                                              AND ct.collectortarget_user_id = '{$_SESSION['user_id']}'
                                              AND rs.stream_id = '{$rows['stream_id']}'
                                              AND ct.collectortarget_month IN ({$months})";
                                                $stmt = $mysqli->prepare($query);
                                                $stmt->execute();
                                                $stmt->bind_result($targett);
                                                $stmt->fetch();
                                                $stmt->close();

                                                /** Deficit amount */
                                                $query = "SELECT sum(info_defecit) FROM collectortarget_info ci
                                              INNER JOIN collectortarget ct ON ct.collectortarget_id = ci.info_collectortarget_id
                                              INNER JOIN revenue_streams rs ON rs.stream_id = ci.info_stream_id
                                              INNER JOIN financial_year fy ON fy.fy_id = ct.collectortarget_fy
                                              WHERE fy.fy_id = '{$fy}'
                                              AND ct.collectortarget_user_id = '{$_SESSION['user_id']}'
                                              AND ct.collectortarget_month IN ({$months})
                                              AND rs.stream_id = '{$rows['stream_id']}'";
                                                $stmt = $mysqli->prepare($query);
                                                $stmt->execute();
                                                $stmt->bind_result($defecitt);
                                                $stmt->fetch();
                                                $stmt->close();

                                                $targetTotall = $targett + $defecitt;
                                                echo 'Ksh. ' . number_format($targetTotall > 0 ? $targetTotall : 0);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                /** Total monthly collections */
                                                $query = "SELECT SUM(collection_amount) FROM revenue_collections
                                              WHERE collection_user_id = '{$_SESSION['user_id']}'
                                              AND collection_stream_id = '{$rows['stream_id']}'
                                              AND collections_month IN ({$months})
                                              AND collection_status = 'Approved'";
                                                $stmt = $mysqli->prepare($query);
                                                $stmt->execute();
                                                $stmt->bind_result($my_collections);
                                                $stmt->fetch();
                                                $stmt->close();
                                                $collections = $my_collections ? $my_collections : 0;
                                                echo "Ksh " . number_format($collections);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                // Calculate percentage of achievement
                                                $achivedstrPerc = ($collections > 0 && $targetTotall > 0) ? ($collections / $targetTotall) * 100 : 0;

                                                // Define color codes based on performance
                                                if ($achivedstrPerc == 0) {
                                                    $trackerStatus = 'Off track';
                                                    $badgeColor = 'bg-danger'; // Red
                                                } elseif ($achivedstrPerc > 100) {
                                                    $trackerStatus = 'Excellent';
                                                    $badgeColor = 'bg-success'; // Green
                                                } elseif ($achivedstrPerc >= 80) {
                                                    $trackerStatus = 'Good';
                                                    $badgeColor = 'bg-primary'; // Blue
                                                } elseif ($achivedstrPerc >= 60) {
                                                    $trackerStatus = 'Fair';
                                                    $badgeColor = 'bg-warning'; // Yellow
                                                } elseif ($achivedstrPerc >= 1) {
                                                    $trackerStatus = 'Poor';
                                                    $badgeColor = 'bg-orange'; // Orange (custom color)
                                                } else {
                                                    $trackerStatus = 'Off track';
                                                    $badgeColor = 'bg-danger'; // Red
                                                }
                                                ?>
                                                <span
                                                    class="badge <?php echo $badgeColor; ?>"><?php echo $trackerStatus; ?></span>
                                            </td>
                                            <td><?php echo number_format($achivedstrPerc, 2); ?>%</td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>


                    </div>

                    <script>
                        $(document).ready(function () {
                            $('#streamTrackerTable').DataTable({
                                "scrollY": "400px", // Set the height of the scrollable area
                                "scrollCollapse": true, // Allow the table to reduce in height if there are fewer records
                                "paging": false, // Enable pagination
                                "pageLength": 10, // Set the number of rows per page
                                "lengthMenu": [5, 10, 15, "All"], // Length menu options
                                "searching": true, // Enable searching
                                "ordering": true, // Enable ordering
                                "info": true, // Show information about the table
                                "language": {
                                    "search": "_INPUT_",
                                    "searchPlaceholder": "Search streams...",
                                    "lengthMenu": "Show _MENU_ entries",
                                },
                                "order": [
                                    [5, 'desc']
                                ], // Order by percentage descending
                                "columnDefs": [{
                                    "orderable": false,
                                    "targets": [4]
                                } // Disable sorting on Tracker column
                                ]
                            });
                        });
                    </script>

                </div>
            </div>
            <?php include('../partials/script.php'); ?>
    </section>
</body>