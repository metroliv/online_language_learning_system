<?php
session_start();
require_once('../config/config.php');
require_once('../partials/head.php');
// Fetch form data
$ward = $_POST['ward'];
$duration = $_POST['duration'];
$revenue_officer = $_POST['revenue_officer'];
$stream = $_POST['stream'];

// Build the base query
$query = "
    SELECT 
        w.ward_name,
        u.user_names,
        s.stream_name,
        rc.collection_date,
        rc.collection_amount,
        rc.collection_location,
        rc.collection_comment
    FROM 
        revenue_collections rc
    JOIN 
        ward w ON rc.collection_ward_id = w.ward_id
    JOIN 
        users u ON rc.collection_user_id = u.user_id
    JOIN 
        revenue_services rs ON rc.collection_service_id = rs.service_id
    JOIN 
        revenue_streams s ON rs.service_stream_id = s.stream_id
    WHERE 
        rc.collection_status = 'Approved'
";

// Add filters based on user inputs
if ($ward != 'all') {
    $query .= " AND rc.collection_ward_id = '$ward'";
}
if ($revenue_officer != 'all') {
    $query .= " AND rc.collection_user_id = '$revenue_officer'";
}
if ($stream != 'all') {
    $query .= " AND rs.service_stream_id = '$stream'";
}

// Add duration filter
$today = date('Y-m-d');
switch ($duration) {
    case 'daily':
        $query .= " AND DATE(rc.collection_date) = '$today'";
        break;
    case 'weekly':
        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d', strtotime('sunday this week'));
        $query .= " AND rc.collection_date BETWEEN '$start_date' AND '$end_date'";
        break;
    case 'monthly':
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        $query .= " AND rc.collection_date BETWEEN '$start_date' AND '$end_date'";
        break;
}

// Execute the query
$result = $mysqli->query($query);

// Check for results and display them

?>

<body>
    <div class="wrapper">
        <!-- Main Sidebar Container -->
        <?php include('../partials/executive_sidenav.php'); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
         <div class="container">

                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"> Approved Details </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../views/dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Revenue Collection Reports</li>
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

                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Ward Name</th>
                                                <th>Revenue Officer</th>
                                                <th>Stream</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Location</th>
                                                <th>Comment</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "
                                            SELECT 
                                                w.ward_name,
                                                u.user_names,
                                                s.stream_name,
                                                rc.collection_date,
                                                rc.collection_amount,
                                                rc.collection_location,
                                                rc.collection_comment
                                            FROM 
                                                revenue_collections rc
                                            JOIN 
                                                ward w ON rc.collection_ward_id = w.ward_id
                                            JOIN 
                                                users u ON rc.collection_user_id = u.user_id
                                            JOIN 
                                                revenue_services rs ON rc.collection_service_id = rs.service_id
                                            JOIN 
                                                revenue_streams s ON rs.service_stream_id = s.stream_id
                                            WHERE 
                                                rc.collection_status = 'Approved'
                                        ";
                                        
                                        // Add filters based on user inputs
                                        if ($ward != 'all') {
                                            $query .= " AND rc.collection_ward_id = '$ward'";
                                        }
                                        if ($revenue_officer != 'all') {
                                            $query .= " AND rc.collection_user_id = '$revenue_officer'";
                                        }
                                        if ($stream != 'all') {
                                            $query .= " AND rs.service_stream_id = '$stream'";
                                        }
                                        
                                        // Add duration filter
                                        $today = date('Y-m-d');
                                        switch ($duration) {
                                            case 'daily':
                                                $query .= " AND DATE(rc.collection_date) = '$today'";
                                                break;
                                            case 'weekly':
                                                $start_date = date('Y-m-d', strtotime('monday this week'));
                                                $end_date = date('Y-m-d', strtotime('sunday this week'));
                                                $query .= " AND rc.collection_date BETWEEN '$start_date' AND '$end_date'";
                                                break;
                                            case 'monthly':
                                                $start_date = date('Y-m-01');
                                                $end_date = date('Y-m-t');
                                                $query .= " AND rc.collection_date BETWEEN '$start_date' AND '$end_date'";
                                                break;
                                        }
                                        
                                        // Execute the query
                                        $result = $mysqli->query($query);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row['ward_name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['user_names']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['stream_name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['collection_date']) . "</td>";
                                                    echo "<td>Ksh " . number_format($row['collection_amount'], 2) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['collection_location']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['collection_comment']) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='7'>No collections found.</td></tr>";
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
    <?php include('../partials/scripts.php'); ?>

</body>

</html>