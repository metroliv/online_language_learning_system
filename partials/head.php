<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Online Language Learning System</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../public/images/small-logo.png" type="image/x-icon">
    <link rel="icon" href="../public/img/image.png" type="image/x-icon">

    <!-- SEO Meta Tags -->
    <meta name="description" content="Learn different languages through interactive lessons, quizzes, and exercises.">
    <meta name="keywords" content="language learning, online courses, quizzes, interactive exercises, education">
    <meta name="author" content="Your System Name">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/plugins/fontawesome-free/css/all.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme & UI Styling -->
    <link rel="stylesheet" href="../public/css/adminlte.min.css">
    <link rel="stylesheet" href="../public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../public/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.css">
    
    <!-- Plugins & Components -->
    <link rel="stylesheet" href="../public/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="../public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../public/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="../public/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="../public/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <link rel="stylesheet" href="../public/plugins/bs-stepper/css/bs-stepper.min.css">
    <link rel="stylesheet" href="../public/plugins/dropzone/min/dropzone.min.css">
    <link rel="stylesheet" href="../public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Moment.js (Required for Date Range Picker) -->
    <script src="../public/plugins/moment/moment.min.js"></script>

    <!-- AdminLTE Scripts -->
    <script src="https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/chart.js/Chart.min.js"></script>
    <script src="https://adminlte.io/themes/v3/dist/js/adminlte.min.js"></script>
    

    <?php
    /* Alert Session Via Alerts */
    if (isset($_SESSION['success'])) {
        $success = $_SESSION['success'];
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['err'])) {
        $err = $_SESSION['err'];
        unset($_SESSION['err']);
    }
    ?>
</head>
