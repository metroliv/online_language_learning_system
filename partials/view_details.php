<?php
session_start();

require_once('../functions/reusableQuery.php');
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../helpers/notices.php');
require_once('../functions/notice_analytics.php');
require_once('../views/executive_notice.php'); 

// Fetch the ward ID and date from GET parameters
if (isset($_GET['wardId']) && isset($_GET['date'])) {
    $wardId = htmlspecialchars($_GET['wardId']);
    $date = htmlspecialchars($_GET['date']);

    // Fetch the ward name from the database
    $wardQuery = "SELECT ward_name FROM ward WHERE ward_id = '$wardId'";
    $wardResult = $mysqli->query($wardQuery);
    if ($wardResult->num_rows > 0) {
        $wardRow = $wardResult->fetch_assoc();
        $wardName = $wardRow['ward_name'];
    } else {
        echo "<h1>Invalid Ward ID.</h1>";
    
    }
} else {
    echo "<h1>Invalid request. Ward ID and Date are required.</h1>";

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include necessary headers -->
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
            <div class="content-header">
                <h1 class="text-center">Details for Ward: <?php echo htmlspecialchars($wardName); ?></h1>
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"> Approved Details </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../views/dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Approved Individual Reports</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-primary card-outline">
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Officer Name</th>
                                                <th>Stream</th>
                                                <th>Service</th>
                                                <th>Amount</th>
                                                <th>Location</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch approved reports
                                            $fetch_records_sql = mysqli_query(
                                                $mysqli,
                                                "SELECT * FROM revenue_collections c 
                                                INNER JOIN users u ON u.user_id = c.collection_user_id 
                                                WHERE c.collection_status = 'Approved' 
                                                AND c.collection_ward_id = '$wardId'"
                                            );

                                            if (mysqli_num_rows($fetch_records_sql) > 0) {
                                                $cnt = 1;
                                                while ($rows = mysqli_fetch_array($fetch_records_sql)) {
                                                    $collection_date = $rows['collection_date'];
                                                    if ($collection_date == '0000-00-00 00:00:00' || $collection_date == '0000-00-00') {
                                                        $collection_date = 'N/A';
                                                    } else {
                                                        $collection_date = date('Y-m-d H:i:s', strtotime($collection_date));
                                                    }
                                            ?>
                                                    <tr>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td>
                                                            <a href="revenue_collector_dashboard.php?user_id=<?php echo $rows['user_id']; ?>">
                                                                <?php echo htmlspecialchars($rows['user_names']); ?>
                                                            </a>

                                                        </td>
                                                        <td>ccs</td>
                                                        <td>agriculture</td>
                                                        <td>Ksh <?php echo number_format($rows['collection_amount'], 2); ?></td>
                                                        <td><?php echo htmlspecialchars($rows['collection_location']); ?></td>
                                                        <td><?php echo htmlspecialchars($collection_date); ?></td>
                                                        <td><?php echo htmlspecialchars($rows['collection_status']); ?></td>
                                                    </tr>
                                            <?php
                                                    $cnt++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='8'>No approved collections found for Ward: $wardId</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="control-sidebar control-sidebar-dark">
            </aside>
        </div>
        <?php include('../partials/scripts.php'); ?>
</body>

</html>