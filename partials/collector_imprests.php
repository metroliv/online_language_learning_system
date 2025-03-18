<?php
session_start();

require_once('../functions/reusableQuery.php');
require_once('../functions/reciever_analytics.php');
require_once('../config/config.php');
require_once('../helpers/auth.php');
require_once('../helpers/users.php');
require_once('../partials/headn.php');

// Fetch the fiscal year dynamically from the database
$fyQuery = "SELECT DISTINCT imprest_fy FROM imprest ORDER BY imprest_fy DESC LIMIT 1";
$fyResult = mysqli_query($mysqli, $fyQuery);

if (mysqli_num_rows($fyResult) > 0) {
    $fyRow = mysqli_fetch_assoc($fyResult);
    $fy = $fyRow['imprest_fy'];
} else {
    echo "No fiscal year found.";
    exit;
}

// Fetch annual imprest amount and fiscal year
$imprestSql = "SELECT imprest_fy, imprest_amount FROM imprest WHERE imprest_fy = '$fy'";
$imprestResult = mysqli_query($mysqli, $imprestSql);

if (mysqli_num_rows($imprestResult) > 0) {
    $imprestRow = mysqli_fetch_assoc($imprestResult);
    $annualImprest = $imprestRow['imprest_amount'];
} else {
    echo "No records found for fiscal year $fy.";
    exit;
}

// Fetch all revenue collectors for each ward
$fetch_records_sql = mysqli_query(
    $mysqli,
    "SELECT u.user_id, u.user_names, u.user_ward_id
     FROM users u
     WHERE u.user_access_level = 'Revenue Collector'"
);

$collectorData = [];
while ($row = mysqli_fetch_assoc($fetch_records_sql)) {
    $collectorData[] = $row;
}

// Fetch annual target per ward
$targetSql = "SELECT streamtarget_ward_id, SUM(streamtarget_amount) AS ward_target
              FROM streamtarget
              WHERE streamtarget_fy = '$fy'
              GROUP BY streamtarget_ward_id";
$targetResult = mysqli_query($mysqli, $targetSql);

$wardTargets = [];
while ($targetRow = mysqli_fetch_assoc($targetResult)) {
    $wardTargets[$targetRow['streamtarget_ward_id']] = $targetRow['ward_target'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <?php include('../partials/headn.php'); ?>
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
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Imprest, <?php echo htmlspecialchars($fy); ?></h1>
                            <small>Department of Finance, Revenue Collection Tool</small>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Collector Imprests</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <!-- row  -->
                    <div class="row">
                        <!-- system users dashboard -->
                        <div class="col-lg-12">
                            <div class="card card-primary card-outline">
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Name</th>
                                                <th>Imprest Earned</th>
                                                <th>Disbursed Amount</th>
                                                <th>Due Imprest</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $cnt = 1;
                                        foreach ($collectorData as $collector) {
                                            $userId = $collector['user_id'];
                                            $wardId = $collector['user_ward_id'];

                                            // Get the number of collectors in the ward
                                            $collectorCountSql = "SELECT COUNT(*) AS collector_count
                                                                       FROM users
                                                                       WHERE user_access_level = 'Revenue Collector' AND user_ward_id = '$wardId'";
                                            $collectorCountResult = mysqli_query($mysqli, $collectorCountSql);
                                            $collectorCountRow = mysqli_fetch_assoc($collectorCountResult);
                                            $collectorNo = $collectorCountRow['collector_count'];

                                            $wardTarget = $wardTargets[$wardId] ?? 0;
                                            $collectorTarget = $collectorNo > 0 ? $wardTarget / $collectorNo : 0;
                                            $imprest = $collectorTarget > 0 ? $annualImprest / $collectorTarget : 0;

                                            // Calculate imprestEarned
                                            $collectionsSql = "SELECT SUM(collection_amount) FROM revenue_collections
                                                               WHERE collection_user_id = '$userId' AND collection_status = 'Approved'";
                                            $collectionsResult = mysqli_query($mysqli, $collectionsSql);
                                            $collectionsRow = mysqli_fetch_assoc($collectionsResult);
                                            $imprestEarned = $collectionsRow['SUM(collection_amount)'] * $imprest;

                                            // Calculate total disbursed amount
                                            $disbursedSql = "SELECT SUM(id.imprestdisbursement_amount) 
                                                             FROM imprestdisbursement id
                                                             INNER JOIN imprest i ON i.imprest_id = id.imprestdisbursement_imprest_id
                                                             WHERE i.imprest_fy = '$fy'
                                                             AND id.imprestdisbursement_user_id = '$userId'";
                                            $disbursedResult = mysqli_query($mysqli, $disbursedSql);
                                            $disbursedRow = mysqli_fetch_assoc($disbursedResult);
                                            $disbursedAmount = $disbursedRow['SUM(id.imprestdisbursement_amount)'] ?? 0;

                                            $dueImprest = $imprestEarned - $disbursedAmount;
                                        ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td>
                                                    <a href="#?id=<?php echo htmlspecialchars($userId); ?>">
                                                        <?php echo htmlspecialchars($collector['user_names']); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo "Ksh. " . number_format($imprestEarned, 0); ?></td>
                                                <td><?php echo "Ksh. " . number_format($disbursedAmount, 0); ?></td>
                                                <td><?php echo "Ksh. " . number_format($dueImprest, 0); ?></td>
                                            </tr>
                                        <?php
                                            $cnt++;
                                        }
                                        ?>
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
            <!-- /.content -->
            <!-- Add user modal -->
        </div>
        <!-- /.content-wrapper -->

        <?php include('../partials/footer.php'); ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <?php include('../partials/scriptn.php'); ?>

</body>
</html>
