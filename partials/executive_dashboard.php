<?php
/*
 *   Crafted On Tue 08 Oct 2024
 *   Author Benjamin Wambua (Jayminwambu@gmail.com)
 * 
 *   www.makueni.go.ke
 *   info@makueni.go.k
 *
 *
 *   The Government of Makueni County Applications Development Section End User License Agreement
 *   Copyright (c) 2023 Government of Makueni County 
 *
 *
 *   1. GRANT OF LICENSE 
 *   GoMC Applications Development Section hereby grants to you (an individual) the revocable, personal, non-exclusive, and nontransferable right to
 *   install and activate this system on one computer solely for your official and non-commercial use,
 *   unless you have purchased a commercial license from GoMC Applications Development Section. Sharing this Software with other individuals, 
 *   or allowing other individuals to view the contents of this Software, is in violation of this license.
 *   You may not make the Software available on a network, or in any way provide the Software to multiple users
 *   unless you have first purchased at least a multi-user license from GoMC Applications Development Section
 *
 *   2. COPYRIGHT 
 *   The Software is owned by GoMC Applications Development Section and protected by copyright law and international copyright treaties. 
 *   You may not remove or conceal any proprietary notices, labels or marks from the Software.
 *
 *
 *   3. RESTRICTIONS ON USE
 *   You may not, and you may not permit others to
 *   (a) reverse engineer, decompile, decode, decrypt, disassemble, or in any way derive source code from, the Software;
 *   (b) modify, distribute, or create derivative works of the Software;
 *   (c) copy (other than one back-up copy), distribute, publicly display, transmit, sell, rent, lease or 
 *   otherwise exploit the Software. 
 *
 *
 *   4. TERM
 *   This License is effective until terminated. 
 *   You may terminate it at any time by destroying the Software, together with all copies thereof.
 *   This License will also terminate if you fail to comply with any term or condition of this Agreement.
 *   Upon such termination, you agree to destroy the Software, together with all copies thereof.
 *
 *
 *   5. NO OTHER WARRANTIES. 
 *   GoMC APPLICATIONS DEVELOPMENT SECTION DOES NOT WARRANT THAT THE SOFTWARE IS ERROR FREE. 
 *   GoMC APPLICATIONS DEVELOPMENT SECTION SOFTWARE DISCLAIMS ALL OTHER WARRANTIES WITH RESPECT TO THE SOFTWARE, 
 *   EITHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, 
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS. 
 *   SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES OR LIMITATIONS
 *   ON HOW LONG AN IMPLIED WARRANTY MAY LAST, OR THE EXCLUSION OR LIMITATION OF 
 *   INCIDENTAL OR CONSEQUENTIAL DAMAGES,
 *   SO THE ABOVE LIMITATIONS OR EXCLUSIONS MAY NOT APPLY TO YOU. 
 *   THIS WARRANTY GIVES YOU SPECIFIC LEGAL RIGHTS AND YOU MAY ALSO 
 *   HAVE OTHER RIGHTS WHICH VARY FROM JURISDICTION TO JURISDICTION.
 *
 *
 *   6. SEVERABILITY
 *   In the event of invalidity of any provision of this license, the parties agree that such invalidity shall not
 *   affect the validity of the remaining portions of this license.
 *
 *
 *   7. NO LIABILITY FOR CONSEQUENTIAL DAMAGES IN NO EVENT SHALL GoMC APPLICATIONS DEVELOPMENT SECTION OR ITS SUPPLIERS BE LIABLE TO YOU FOR ANY
 *   CONSEQUENTIAL, SPECIAL, INCIDENTAL OR INDIRECT DAMAGES OF ANY KIND ARISING OUT OF THE DELIVERY, PERFORMANCE OR 
 *   USE OF THE SOFTWARE, EVEN IF GoMC APPLICATIONS DEVELOPMENT SECTION HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES
 *   IN NO EVENT WILL GoMC APPLICATIONS DEVELOPMENT SECTION LIABILITY FOR ANY CLAIM, WHETHER IN CONTRACT 
 *   TORT OR ANY OTHER THEORY OF LIABILITY, EXCEED THE LICENSE FEE PAID BY YOU, IF ANY.
 *
 */

require_once('../config/config.php');
require_once('../functions/reusableQuery.php');
require_once('../helpers/cards_collection.php');
//require_once('../helpers/stream_perfomance_query.php');
require_once('../helpers/ward_perfomance_query.php');
require_once('../helpers/cards_collection.php');
require_once('../helpers/stream_perfomance_query.php');
require_once('../helpers/stream_comparison_query.php');
require_once('../helpers/monthly_comparison_query.php');
require_once('../helpers/stream_monthly_perfomance.php');
require_once('../functions/notice_analytics.php');

//require_once('../functions/notice_analytics.php');

// Fetch revenue officers
$officersQuery = "SELECT user_id, user_names FROM users WHERE user_access_level = 'Revenue Collector'";
$officersResult = $mysqli->query($officersQuery);

// Check for errors in the query
if (!$officersResult) {
    die('Error fetching officers: ' . $mysqli->error);
}

// Fetch streams
$streamsQuery = "SELECT stream_id, stream_name FROM revenue_streams";
$streamsResult = $mysqli->query($streamsQuery);

// Check for errors in the query
if (!$streamsResult) {
    die('Error fetching streams: ' . $mysqli->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('../partials/head.php'); ?>
    <title>Revenue Collection Reports</title>
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .text-left h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        .section-title {
            margin-top: 40px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }

        .section-title h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #444;
            padding-bottom: 10px;
        }

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

        .custom-card {
            background-color: rgba(0, 0, 0, 0.05);
            /* Light dark with transparency */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            /* Soft shadow */
            border-radius: 8px;
            /* Smooth rounded corners */
        }

        .custom-card h4 {
            color: black;
            /* Text color for main value */
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

        .chart-container {
            position: relative;
            height: 500px;
            /* Increased height */
            width: 100%;
            /* Full width */
        }

        .content-wrapper {
            padding: 20px;
            /* Ensure padding for content */
        }

        .card-section {
            border: 1px solid green;
            /* Light gray border */
            border-radius: 5px;
            /* Round corners */
            padding: 10px;
            /* Padding inside the section */
            background-color: #f9f9f9;
            /* Light background color for contrast */
            margin-bottom: 20px;
            /* Space below the section */
        }
    </style>
</head>

<body>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="wrapper">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="m-0 text-dark">
                        <?php echo $greeting . ', Welcome <span style="color: red;">' . $_SESSION['user_access_level'] . '</span>'; ?>
                    </h5>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="tabs-container">
        <!-- Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#monthlyTab">This Month Overview</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#annualTab">This FY Overview</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#comparisonTab">Comparative Overview</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Monthly Tab -->
            <div id="monthlyTab" class="tab-pane fade show active">
                <div class="row mt-4">
                    <!-- Monthly Target Card -->
                    <div class="col-lg-4 col-6">
                        <div class="small-box custom-card">
                            <h5 class="text-dark">Month Target</h5>
                            <div class="inner">
                                <h4>Ksh <?php echo number_format($monthlyRevenueTarget ?? 0); ?></h4>
                            </div>
                            <div class="icon">
                                <i class="fas fa-gem" style="color: grey; font-size: 70px; right: 15px;"></i>
                            </div>
                            <a href="#" class="small-box-footer text-primary"><?php echo date('F Y'); ?></a>
                        </div>
                    </div>

                    <!-- Monthly Revenue Collected Card -->
                    <div class="col-lg-4 col-6">
                        <div class="small-box custom-card">
                            <h5 class="text-dark">Revenue Collected</h5>
                            <div class="inner">
                                <h4>Ksh <?php echo number_format($monthlyRevenueCollected ?? 0); ?></h4>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart" style="color: grey; font-size: 65px; right: 15px;"></i>
                            </div>
                            <a href="#" class="small-box-footer text-primary"><?php echo date('F Y'); ?></a>
                        </div>
                    </div>

                    <!-- Monthly Performance Card -->
                    <div class="col-lg-4 col-6">
                        <div class="small-box custom-card">
                            <h5 class="text-dark">Performance</h5>
                            <div class="inner">
                                <h4><?php echo number_format($monthlyPercentageAchieved ?? 0, 2); ?>%</h4>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-bar" style="color: grey; font-size: 65px; right: 15px;"></i>
                            </div>
                            <a href="#" class="small-box-footer text-primary"><?php echo date('F Y'); ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Annual Tab -->
            <div id="annualTab" class="tab-pane fade">
                <div class="row mt-4">
                    <!-- Annual Target Card -->
                    <div class="col-lg-4 col-6">
                        <div class="small-box custom-card">
                            <h5 class="text-dark">Target</h5>
                            <div class="inner">
                                <h4>Ksh <?php echo number_format($revenueTarget ?? 0); ?></h4>
                            </div>
                            <div class="icon">
                                <i class="fas fa-gem" style="color: grey; font-size: 70px; right: 15px;"></i>
                            </div>
                            <a href="#" class="small-box-footer text-primary">FY_<?php echo $financialYear; ?></a>
                        </div>
                    </div>

                    <!-- Annual Revenue Collected Card -->
                    <div class="col-lg-4 col-6">
                        <div class="small-box custom-card">
                            <h5 class="text-dark">Revenue Collected</h5>
                            <div class="inner">
                                <h4>Ksh <?php echo number_format($totalRevenueCollected ?? 0); ?></h4>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart" style="color: grey; font-size: 65px; right: 15px;"></i>
                            </div>
                            <a href="#" class="small-box-footer text-primary">FY_<?php echo $financialYear; ?></a>
                        </div>
                    </div>

                    <!-- Annual Performance Card -->
                    <div class="col-lg-4 col-6">
                        <div class="small-box custom-card">
                            <h5 class="text-dark">Performance</h5>
                            <div class="inner">
                                <h4><?php echo number_format($percentageAchieved ?? 0, 2); ?>%</h4>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-bar" style="color: grey; font-size: 65px; right: 15px;"></i>
                            </div>
                            <a href="#" class="small-box-footer text-primary">FY_<?php echo $financialYear; ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comparison Tab (Placeholder for Future) -->
            <div id="comparisonTab" class="tab-pane fade">
                <div class="row mt-4">
                    <!-- Annual Target Card -->

                    <div class="col-lg-4 col-6">
                        <div class="small-box custom-card">
                            <h5 class="text-dark">Overal Performance</h5>
                            <div class="inner">
                                <small class="text-danger">Comparison Overview will be available here soon.</small>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-bar" style="color: grey; font-size: 65px; right: 15px;"></i>
                            </div>
                            <a href="#" class="small-box-footer text-primary"><?php echo 'FY_23/24 Vs 24/25' ?></a>
                        </div>
                    </div>


                    <!-- Annual Revenue Collected Card -->

                    <div class="col-lg-4 col-6">
                        <div class="small-box custom-card">
                            <h5 class="text-dark">Revenue Streams</h5>
                            <div class="inner">
                                <small class="text-danger">Comparison Overview will be available here soon.</small>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-bar" style="color: grey; font-size: 65px; right: 15px;"></i>
                            </div>
                            <a href="#" class="small-box-footer text-primary"><?php echo 'FY_23/24 Vs 24/25' ?></a>
                        </div>
                    </div>


                    <!-- Annual Performance Card -->
                    <div class="col-lg-4 col-6">
                        <div class="small-box custom-card">
                            <h5 class="text-dark">Wards</h5>
                            <div class="inner">
                                <small class="text-danger">Comparison Overview will be available here soon.</small>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-bar" style="color: grey; font-size: 65px; right: 15px;"></i>
                            </div>
                            <a href="#" class="small-box-footer text-primary"><?php echo 'FY_23/24 Vs 24/25' ?></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!--  Performance Visualization --------------------------------------------------------------------->

    <!-- Target vs Collection Monthly -->
    <div class="container-fluid mt-4">
        <!-- Tabbed Pane -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab"
                    aria-controls="overview" aria-selected="true">Overview</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="trends-tab" data-toggle="tab" href="#trends" role="tab" aria-controls="trends"
                    aria-selected="false">Trends</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="detailed-tab" data-toggle="tab" href="#detailed" role="tab"
                    aria-controls="detailed" aria-selected="false">Detailed View</a>
            </li>

        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <div class="row mt-4 justify-content-center">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header d-flex align-items-center" style="background-color: white;">
                                <!-- Title and Subtitle -->
                                <div>
                                    <h5 class="card-title">Overall County Performance </h5>
                                    <span><small class="text-primary"> [ Monthly Target Vs Collection Overview
                                            ]</small></span>
                                </div>
                                <!-- Options Menu (floated to the right) -->
                                <div class="ml-auto">
                                    <div class="dropdown">
                                        <button class="btn btn-sm-link text-dark" type="button" id="chartOptionsMenu"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            style="font-size: 1.0rem;">
                                            <i class="fas fa-ellipsis-h text-primary"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="chartOptionsMenu">
                                            <a class="dropdown-item" href="#" onclick="changeChartType('bar')">Bar
                                                Chart</a>
                                            <a class="dropdown-item" href="#" onclick="changeChartType('line')">Line
                                                Chart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="monthlyComparisonChart" style="width: 100%; height: 450px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trends Tab -->
            <div class="tab-pane fade" id="trends" role="tabpanel" aria-labelledby="trends-tab">
                <div class="row mb-4 justify-content-center">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header" style="background-color: #f5f5f5;">
                            </div>
                            <div class="card-body">
                                <div class="chart" style="position: relative; width: 100%;">
                                    <canvas id="revenueTrend" style="width: 100%; height: 450px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed View Tab -->
            <div class="tab-pane fade" id="detailed" role="tabpanel" aria-labelledby="detailed-tab">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-body">
                                <h5>Detailed View Content</h5>
                                <p>Content for the detailed comming soon</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


    <!-- JavaScript function to change chart type -->
    <script>
        function changeChartType(type) {
            // Change chart type logic here, e.g., updating chart type on the canvas with ID 'monthlyComparisonChart'
        }
    </script>

    <!-- Revenue Streams Overview Tabbed Pane -->
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h1 class="card-title">Revenue Streams Overview</h1>
            </div>
            <div class="card-body">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="streamTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="overall-tab" data-toggle="tab" href="#overall" role="tab"
                            aria-controls="overall" aria-selected="true">Overall Stream Performance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="monthly-tab" data-toggle="tab" href="#monthly" role="tab"
                            aria-controls="monthly" aria-selected="false">Monthly Stream Performance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="table-tab" data-toggle="tab" href="#table" role="tab"
                            aria-controls="table" aria-selected="false">Tabular stream Collections</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="streamTabContent">
                    <!-- Overall Stream Performance Tab Pane -->
                    <div class="tab-pane fade show active" id="overall" role="tabpanel" aria-labelledby="overall-tab">
                        <div class="row mb-4 justify-content-center">
                            <div class="col-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="background-color: #f5f5f5;">
                                    </div>
                                    <div class="card-body">
                                        <div class="chart" style="position: relative; width: 100%;">
                                            <canvas id="streamsComparisonChart"
                                                style="width: 100%; height: 600px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Stream Performance Tab Pane -->
                    <div class="tab-pane fade" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
                        <div class="row mb-4 justify-content-center">
                            <div class="col-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="background-color: #f5f5f5;">
                                    </div>
                                    <div class="card-body">
                                        <div class="chart">
                                            <canvas id="revenueChart" style="width: 100%; height: 400px;"></canvas>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="table" role="tabpanel" aria-labelledby="table-tab">
                        <div class="row mb-4 justify-content-center">
                            <div class="col-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="background-color: #f5f5f5;">
                                        <!-- Table header with embedded filter form -->
                                        <form method="POST" action="../helpers/stream_perfomance_query.php" target="filterFrame">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <select id="stream" name="stream" class="form-control" placeholder="Select Stream">
                                                        <option value="all">All Streams</option>
                                                        <?php
                                                        $streamsQuery = "SELECT stream_id, stream_name FROM revenue_streams";
                                                        $streamsResult = $mysqli->query($streamsQuery);
                                                        while ($stream = $streamsResult->fetch_assoc()) {
                                                            echo '<option value="' . $stream['stream_id'] . '">' . htmlspecialchars($stream['stream_name']) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="date" id="start_date" name="start_date" class="form-control"
                                                        value="<?php echo htmlspecialchars($startDate); ?>" placeholder="Start Date">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="date" id="end_date" name="end_date" class="form-control"
                                                        value="<?php echo htmlspecialchars($endDate); ?>" placeholder="End Date">
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-primary btn-sm form-control rounded-pill">
                                                        <i class="fas fa-filter"></i> Filter
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Stream Performance Table -->
                                    <div class="card-body">
                                    <table class="data_table table table-bordered table-striped" style="display:none;">
    <thead>
        <tr>
            <th>Stream</th>
            <th>Timeframe</th>
            <th>Collection</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Include the query logic
        include('../helpers/stream_perfomance_query.php');

        // Check if data is returned from the included file
        if (isset($streamPerformance) && is_array($streamPerformance) && !empty($streamPerformance)) {
            foreach ($streamPerformance as $performance) {
                echo '<tr>
                        <td>' . htmlspecialchars($performance['stream_name']) . '</td>
                        <td>' . htmlspecialchars($startDate) . ' to ' . htmlspecialchars($endDate) . '</td>
                        <td>' . number_format($performance['total_collected'], 0) . ' Ksh</td>
                        <td>
                            <a href="../views/view_details.php?stream_id=' . htmlspecialchars($performance['stream_id']) . '&start_date=' . urlencode($startDate) . '&end_date=' . urlencode($endDate) . '" class="btn btn-info btn-sm">View Details</a>
                        </td>
                    </tr>';
            }
        } else {
            echo '<tr>
                    <td colspan="4" class="text-center">No data available. Please refine your filter or check back later.</td>
                  </tr>';
        }
        ?>
    </tbody>
</table>


                                    </div>

                                    <!-- Hidden Iframe for Form Submission -->
                                    <iframe name="filterFrame" style="display:none;"></iframe>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Stream Monthly Performance Chart -->

    <!-- Ward Performance Chart -->


    <!-- Stream Performance Chart -->



    <!-- Stream Performance Table with Filter in Header -->
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h1 class="card-title">Wards Overview</h1>
            </div>
            <div class="card-body">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="wardTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="overall-tab" data-toggle="tab" href="#comparative" role="tab"
                            aria-controls="overall" aria-selected="true">Overall Ward Performance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="monthly-tab" data-toggle="tab" href="#month" role="tab"
                            aria-controls="monthly" aria-selected="false">Monthly Ward Performance</a>
                    </li>

                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="wardTabContent">
                    <!-- Overall Stream Performance Tab Pane -->
                    <div class="tab-pane fade show active" id="comparative" role="tabpanel"
                        aria-labelledby="overall-tab">
                        <div class="row mb-4 justify-content-center">
                            <div class="col-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="background-color: #f5f5f5;">
                                    </div>
                                    <div class="card-body">
                                        <div class="chart" style="position: relative; width: 100%;">
                                            <canvas id="performanceChart" style="width: 100%; height: 600px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Stream Performance Tab Pane -->
                    <div class="tab-pane fade" id="month" role="tabpanel" aria-labelledby="monthly-tab">
                        <div class="row mb-4 justify-content-center">
                            <div class="col-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="background-color: #f5f5f5;">
                                    </div>
                                    <div class="card-body">
                                        <div class="chart" style="position: relative; width: 100%;">
                                            <canvas id="performanceChart" style="width: 100%; height: 400px;"></canvas>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>


    <!-- /.container-fluid -->
    </div>
    <!-- ./wrapper -->
    <!-- jQuery -->
    <?php include('../partials/scriptn.php'); ?>
    <?php include('../partials/exec_scripts.php'); ?>
    <!-- Include the Change Password modal -->

    <script>
        document.querySelector('iframe[name="filterFrame"]').addEventListener('load', function() {
            const iframe = this;
            const tableBody = document.querySelector('.data_table tbody');

            // Check if iframe's content is available
            if (iframe.contentDocument && iframe.contentDocument.body) {
                // Replace the table body with iframe's content
                tableBody.innerHTML = iframe.contentDocument.body.innerHTML;

                // Show the table after data is loaded
                document.querySelector('.data_table').style.display = 'table';
            }
        });
    </script>
    <script>
        function changeChartType(type) {
            if (monthlyComparisonChart.config.type !== type) {
                monthlyComparisonChart.config.type = type;
                monthlyComparisonChart.update();
            }
        }
    </script>
    <script>
        // Pass PHP data to JavaScript
        const chartData = <?php echo json_encode($chartData); ?>;

        // Custom Chart.js plugin for rounded corners
        Chart.defaults.borderRadius = 10;

        // Render the chart
        const ctx1 = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: chartData.datasets
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#34495e',
                        titleColor: '#ecf0f1',
                        bodyColor: '#ecf0f1',
                        borderColor: '#34495e',
                        borderWidth: 1
                    },
                    legend: {
                        display: true,
                        labels: {
                            color: '#2c3e50',
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        type: 'category',
                        title: {
                            display: true,
                            text: 'Month',
                            color: '#34495e',
                            font: {
                                size: 14
                            }
                        },
                        grid: {
                            display: false // Hide gridlines for x-axis
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Collected Amount',
                            color: '#34495e',
                            font: {
                                size: 14
                            }
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString(); // Format as thousand separators
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 4,
                        backgroundColor: '#fff',
                        borderWidth: 2,
                        hoverRadius: 6
                    },
                    line: {
                        borderWidth: 3
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuad'
                }
            }
        });
    </script>
    <script>
        // JavaScript to render the chart
        document.addEventListener('DOMContentLoaded', function() {
            var ctxLine = document.getElementById('performanceChart').getContext('2d');
            var performanceChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($wards); ?>,
                    datasets: [{
                        label: 'Target Amount',
                        data: <?php echo json_encode($targets); ?>,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false
                    }, {
                        label: 'Collected Amount',
                        data: <?php echo json_encode($collected); ?>,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: '#e3e3e3'
                            }
                        },
                        x: {
                            grid: {
                                display: true,
                                color: '#e3e3e3'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: '#333'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    }
                }
            });
        });
    </script>
    <script>
        var ctx = document.getElementById('streamsComparisonChart').getContext('2d');
        var streamsComparisonChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($streams1); ?>,
                datasets: [{
                    label: 'Target Amount',
                    data: <?php echo json_encode($targets1); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Collected Amount',
                    data: <?php echo json_encode($collected1); ?>,
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
    <script>
        var ctx = document.getElementById('monthlyComparisonChart').getContext('2d');
        var monthlyComparisonChart = new Chart(ctx, {
            type: 'bar', // Bar chart for monthly performance
            data: {
                labels: <?php echo json_encode($monthNames); ?>,
                datasets: [{
                    label: 'Target Amount',
                    data: <?php echo json_encode($targets_monthly); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Collected Amount',
                    data: <?php echo json_encode($collected_monthly); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month' // Label for the x-axis
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Amount' // Label for the y-axis
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        // Fetch data from get_revenue_data.php and create the chart
        fetch('../views/collection_trend.php')
            .then(response => response.json())
            .then(data => {
                const labels = data.labels;
                const collectedData = data.data;

                // Debugging: check if data is correctly fetched
                console.log(labels);
                console.log(collectedData);

                // Set up the chart
                const ctx = document.getElementById("revenueTrend").getContext("2d");
                new Chart(ctx, {
                    type: 'line', // You can use 'bar', 'pie', etc.
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "Collected Amount",
                            data: collectedData,
                            borderColor: "rgba(75, 192, 192, 1)", // Line color
                            backgroundColor: "rgba(75, 192, 192, 0.2)", // Fill color
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                type: "time",
                                time: {
                                    unit: "day"
                                },
                                title: {
                                    display: true,
                                    text: "Date"
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: "Amount Collected"
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>

</body>

</html>