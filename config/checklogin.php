<?php
session_start();

function check_login()
{
	if ((strlen($_SESSION['user_id']) == 0)) {
		$host = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = "../";
		$_SESSION["user_id"] = "";
		header("Location: http://$host$uri/$extra");
	}
}

$user_id = mysqli_real_escape_string($mysqli, $_SESSION['user_id']);
$user_access_level = mysqli_real_escape_string($mysqli, $_SESSION['user_access_level']);
global $user_access_level, $user_id;

/* Invoke IT */
check_login();