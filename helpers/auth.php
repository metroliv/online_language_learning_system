<?php
/*
 *   Crafted On Sun Apr 07 2024
 *   Author Martin Mbithi (martin.mbithi@makueni.go.ke)
 * 
 *   www.makueni.go.ke
 *   info@makueni.go.ke
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

/* Login */
if (isset($_POST['Login'])) {
    $user_email = mysqli_real_escape_string($mysqli, $_POST['user_email']);
    $user_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['user_password'])));

    /* Process Login  */
    $login_sql = "SELECT * FROM users  WHERE user_email = '{$user_email}' AND user_password = '{$user_password}'";
    $res = mysqli_query($mysqli, $login_sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        /* Bind Session Variables */
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_name'] = $row['user_name'];
        $_SESSION['user_ward_id'] = $row['user_ward_id'];
        $_SESSION['user_access_level'] = $row['user_access_level'];
        $_SESSION['user_email'] = $row['user_email'];
        $_SESSION['user_names'] = $row['user_names'];
		$_SESSION['user_personal_number'] = $row['user_personal_number'];
        $_SESSION['user_phone_number'] = $row['user_phone_number'];

        $_SESSION['success'] = 'Login successful';


        /* Log This Operation */
        $log_sql = "INSERT INTO logs (log_user_id, log_user_type, log_date, log_ip_address, log_device) 
        VALUES ('{$_SESSION['user_id']}', '{$_SESSION['user_access_level']}', NOW(), '{$_SERVER['REMOTE_ADDR']}', '{$_SERVER['HTTP_USER_AGENT']}')";
        mysqli_query($mysqli, $log_sql);


        if ($row['user_change_password'] == '0') {
            $_SESSION['success'] = 'Logged in successfully';
            header('Location: dashboard');
            exit;
        } else {
            /* Force Password Reset If User Account Marked To Reset Password */
            $_SESSION['success'] = 'Please change your password to proceed';
            header('Location: force_password_reset');
            exit;
        }
    } else {
        $err = "Invalid login credentials";
    }
}



/* Force Password Reset If User Account Marked To Reset Password */
if (isset($_POST['Change_Password'])) {
    $user_id = mysqli_real_escape_string($mysqli, $_SESSION['user_id']);
    $new_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['new_password'])));
    $confirm_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['confirm_password'])));

	/* Persist */
	if ($new_password != $confirm_password) {
		$err = "Passwords does not match";
    } 
	else 
	{
		/** Persist */
		$update_password_sql = "UPDATE users SET user_password = '{$confirm_password}', user_change_password = '0' 
		WHERE user_id = '{$user_id}'";
		if (mysqli_query($mysqli, $update_password_sql)) {
			$_SESSION['success'] = 'Logged in successfully';
			header('Location: dashboard');
			exit;
		} else {
			$err = "Failed, please try again later";
		}
	}
}


/* Reset Password Step 1 */
if (isset($_POST['Reset_Password_Step_1'])) {
    $user_email = mysqli_real_escape_string($mysqli, $_POST['user_email']);
    $reset_token = mysqli_real_escape_string($mysqli, $tk);
    $reset_url = $url . $reset_token;

    /* Check If This Account Exists */
    $staff_check_sql = "SELECT * FROM users WHERE user_email = '{$user_email}'";
    $res = mysqli_query($mysqli, $staff_check_sql);
    if (mysqli_num_rows($res) > 0) {
        /*Persist Reset Token */
        $reset_token_sql = "UPDATE users SET user_password_reset_token = '{$reset_token}' WHERE user_email = '{$user_email}'";

        include('../mailers/reset_password.php');
        if (mysqli_query($mysqli, $reset_token_sql)) {
            $success = "Password reset instructions sent to your email";
        } else {
            $err = "Failed to reset account password, please try again later";
        }
    } else {
        $err = "Email does not exist";
    }
}


/* Reset Password Step 2 */
if (isset($_POST['Reset_Password_Step_2'])) {
    $reset_token = mysqli_real_escape_string($mysqli, $_GET['token']);
    $new_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['new_password'])));
    $confirm_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['confirm_password'])));

    /* Check If Passwords Match */
    if ($confirm_password != $new_password) {
        $err = "Failed, please try again";
    } else {

        /* Update staff password */
        $staff_check_sql = "SELECT * FROM users WHERE user_password_reset_token = '{$reset_token}'";
        $res = mysqli_query($mysqli, $staff_check_sql);
        if (mysqli_num_rows($res) > 0) {
            /* Update staff passwords */
            $update_password_sql = "UPDATE users SET user_password = '{$new_password}', user_password_reset_token = '' WHERE user_password_reset_token = '{$reset_token}'";
            if (mysqli_query($mysqli, $update_password_sql)) {
                $_SESSION['success'] = "Password reset successful, proceed to login";
                header('Location: login');
                exit;
            } else {
                $err = "Failed, please try again later";
            }
        }
    }
}

/** Force Password reset for all user */
if (isset($_POST['froce_reset_all'])) 
{
	// dd("hello");
    $user_change_pass = mysqli_real_escape_string($mysqli, '1');
    

	$fetch_records_sql = mysqli_query(
		$mysqli,
		"SELECT * FROM users"												
	);
	if (mysqli_num_rows($fetch_records_sql) > 0) {
		$cnt =  1;
		$res = 0;
		while ($rows = mysqli_fetch_array($fetch_records_sql)) {

			/** Persist */
			$update_password_sql = "UPDATE users SET user_change_password = '1' 
			WHERE user_id = '{$rows['user_id']}'";

			if (mysqli_query($mysqli, $update_password_sql)) {
				$res = 1;				
			} else {
				$res = 0;
			}
		}

		if($res){
			$success = "Force reset password succesful";
		}else{
			$err = "Failed, try again laiter";
		}
	}
	
	
}
