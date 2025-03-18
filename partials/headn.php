<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../public/images/small-logo.png" type="image/x-icon">
    <link rel="icon" href="../public/img/small-logo.png" type="image/x-icon">
    <title>Government of Makueni County | Revenue Collection Reporting Tool</title>
    <!-- Favicon -->
    <!-- SEO -->
       <!-- Google Fonts -->
	   <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel=" stylesheet" href="../public/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../public/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/adminlte.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="../public/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../public/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="../public/plugins/summernote/summernote-bs4.min.css">
    <!-- Swal -->
    <link rel="stylesheet" href="../public/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="../public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="../public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="../public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

	 
  <!-- daterange picker -->
  <link rel="stylesheet" href="../public/plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="../public/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">  
  <!-- Select2 -->
  <link rel="stylesheet" href="../public/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="../public/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="../public/plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="../public/plugins/dropzone/min/dropzone.min.css">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/adminlte.min.css"> <!-- Include your custom CSS file -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php
    /* Alert Sesion Via Alerts */
    if (isset($_SESSION['success'])) {
        $success = $_SESSION['success'];
        unset($_SESSION['success']);
    }
    /* Alert Sesion Via Alerts */
    if (isset($_SESSION['err'])) {
        $err = $_SESSION['err'];
        unset($_SESSION['err']);
    }

	//Redirect user if they are not loged in 
	// if(empty($_SESSION['user_id']))
	// {
	// 	header("Location: login");
	// 	exit(); 	
	// }
    ?>
   
</head>