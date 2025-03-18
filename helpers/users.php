<?php
/*
 *   Crafted On Tue May 09 2023
 *   Author Bosco Mulwa (bosco.mulwa@makueni.go.ke)
 *   Author Alexander Musembi (alexander.musembi@makueni.go.ke)
 *   Author Martin Mbithi (martin.mbithi@makueni.go.ke)
 *   Author Faith Mumo (faith.mumo@makueni.go.ke)
 * 
 *   www.makueni.go.ke
 *   info@makueni.go.ke
 *
 *
 *   The Makueni County Government ICT, Education and Internship Department End User License Agreement
 *   Copyright (c) 2022 Makueni County Government
 *
 *
 *   1. GRANT OF LICENSE 
 *   Makueni County Government ICT, Education and Internship Department hereby grants to you (an individual) the revocable, personal, non-exclusive, and nontransferable right to
 *   install and activate this system on one computer solely for your official and non-commercial use,
 *   unless you have purchased a commercial license from Makueni County Government ICT. Sharing this Software with other individuals, 
 *   or allowing other individuals to view the contents of this Software, is in violation of this license.
 *   You may not make the Software available on a network, or in any way provide the Software to multiple users
 *   unless you have first purchased at least a multi-user license from Makueni County Government ICT, Education and Internship Department
 *
 *   2. COPYRIGHT 
 *   The Software is owned by Makueni County Government ICT, Education and Internship Department and protected by copyright law and international copyright treaties. 
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
 *   MAKUENI COUNTY GOVERNMENT ICT, EDUCATION AND INTERNSHIP DEPARTMENT  DOES NOT WARRANT THAT THE SOFTWARE IS ERROR FREE. 
 *   MAKUENI COUNTY GOVERNMENT ICT, EDUCATION AND INTERNSHIP DEPARTMENT SOFTWARE DISCLAIMS ALL OTHER WARRANTIES WITH RESPECT TO THE SOFTWARE, 
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
 *   7. NO LIABILITY FOR CONSEQUENTIAL DAMAGES IN NO EVENT SHALL MAKUENI COUNTY GOVERNMENT ICT, EDUCATION AND INTERNSHIP DEPARTMENT OR ITS SUPPLIERS BE LIABLE TO YOU FOR ANY
 *   CONSEQUENTIAL, SPECIAL, INCIDENTAL OR INDIRECT DAMAGES OF ANY KIND ARISING OUT OF THE DELIVERY, PERFORMANCE OR 
 *   USE OF THE SOFTWARE, EVEN IFMAKUENI COUNTY GOVERNMENT ICT, EDUCATION AND INTERNSHIP DEPARTMENT HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES
 *   IN NO EVENT WILL MAKUENI COUNTY GOVERNMENT ICT, EDUCATION AND INTERNSHIP DEPARTMENT  LIABILITY FOR ANY CLAIM, WHETHER IN CONTRACT 
 *   TORT OR ANY OTHER THEORY OF LIABILITY, EXCEED THE LICENSE FEE PAID BY YOU, IF ANY.
 */
 
require_once('../functions/reusableQuery.php');



/* Update Profile */
if (isset($_POST['UpdateProfile'])) {
	//dd($_POST);
	unset($_POST['UpdateProfile']);
	$res = updateDetails($_POST, 'users', 'user_id', $_POST['user_id']);
    // $user_name = mysqli_real_escape_string($mysqli, $_POST['user_names']);
    // $user_email = mysqli_real_escape_string($mysqli, $_POST['user_email']);
    // $user_ward_id = mysqli_real_escape_string($mysqli, $_POST['user_ward_id']);
    // $user_phone_number = mysqli_real_escape_string($mysqli, $_POST['user_phone_number']);
    // $user_access_level = mysqli_real_escape_string($mysqli, $_POST['user_access_level']);
    // $user_id = mysqli_real_escape_string($mysqli, $_SESSION['user_id']);

    // /* Persist */
    // $update_querry = "UPDATE users SET user_names = '{$user_name}', user_email = '{$user_email}', user_ward_id = '{$user_ward_id}', user_phone_number = '{$user_phone_number}', user_access_level = '{$user_access_level}'
    // WHERE user_id ='{$user_id}'";

    if ($res) {
        $success = "Profile updated";
    } else {
        $err = "Failed, please try again";
    }
}

/* Update Personal Password */
if (isset($_POST['UpdatePasswords'])) {
    $user_id = mysqli_real_escape_string($mysqli, $_SESSION['user_id']);
    $old_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['old_password'])));
    $new_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['new_password'])));
    $confirm_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['confirm_password'])));

    /* Check If Old Password  Match  */
    $sql = "SELECT * FROM  users WHERE user_id = '{$user_id}'";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if ($old_password != $row['user_password']) {
            $err =  "Please enter correct old password";
        } elseif ($new_password != $confirm_password) {
            $err = "Confirmation password does not match";
        } else {
            $update_querry = "UPDATE users SET  user_password = '{$confirm_password}' WHERE user_id ='{$user_id}'";
            if (mysqli_query($mysqli, $update_querry)) {
                $success = "Password updated";
            } else {
                $err = "Failed, please try again";
            }
        }
    }
}


/* Add User */
if (isset($_POST['Add_User'])) {
	// dd($_POST);
    $user_names = mysqli_real_escape_string($mysqli, $_POST['user_name']);
    $user_email = mysqli_real_escape_string($mysqli, $_POST['user_email']);
    $user_password =  sha1(md5(mysqli_real_escape_string($mysqli, 'Makueni102')));
    $user_phone_number = mysqli_real_escape_string($mysqli, $_POST['user_phone_number']);
    $user_access_level =   mysqli_real_escape_string($mysqli, $_POST['user_access_level']);
    $user_ward_id =   mysqli_real_escape_string($mysqli, $_POST['user_ward_id']);
    $user_personal_number =   mysqli_real_escape_string($mysqli, $_POST['user_personal_number']);

    /* Prevent Double Entries */
    $check_user = mysqli_query($mysqli, "SELECT * FROM users WHERE user_email = '{$user_email}'");
    if (mysqli_num_rows($check_user) > 0) {
        $err = "User Already Exists";
    } else {
        if (mysqli_query($mysqli, "INSERT INTO users (user_names, user_email, user_password, user_phone_number, user_access_level, user_ward_id, user_personal_number) 
            VALUES ('{$user_names}', '{$user_email}', '{$user_password}', '{$user_phone_number}', '{$user_access_level}', '{$user_ward_id}', '{$user_personal_number}')")) {
            $success = "User Added Successfully";
            /* Welcome Email */
			include('../mailers/bulk_sms/staff_welcome.php');
            include('../mailers/staff_welcome.php');
        } else {
            $err = "Error Adding User";
        }
    }
}


/* Update Users */
if (isset($_POST['Update_User'])) {
	//dd($_POST);

    $user_name = mysqli_real_escape_string($mysqli, $_POST['user_names']);
    $user_email = mysqli_real_escape_string($mysqli, $_POST['user_email']);
    $user_phone_number_number = mysqli_real_escape_string($mysqli, $_POST['user_phone_number_number']);
    $user_access_level = mysqli_real_escape_string($mysqli, $_POST['user_access_level']);
    $user_id = mysqli_real_escape_string($mysqli, $_POST['user_id']);
	$user_personal_number =   mysqli_real_escape_string($mysqli, $_POST['user_personal_number']);
	$user_ward_id =   mysqli_real_escape_string($mysqli, $_POST['user_ward_id']);



    /* Persist */
    $update_querry = "UPDATE users SET user_name = '{$user_name}', user_email = '{$user_email}', user_phone_number_number = '{$user_phone_number_number}', user_access_level = '{$user_access_level}', user_personal_number = '{$user_personal_number}' user_ward_id = '{$user_ward_id}'
    WHERE user_id ='{$user_id}'";
    if (mysqli_query($mysqli, $update_querry)) {
        $success = "User updated";
    } else {
        $err = "Failed, please try again";
    }
}

/* disable Users */
if (isset($_POST['Disable_User'])) {
    $user_id = mysqli_real_escape_string($mysqli, $_POST['user_id']);
	$success = "User disabled";
    // $delete_sql = "DELETE FROM users WHERE user_id = '{$user_id}'";
    // if (mysqli_query($mysqli, $delete_sql)) {
	// 	$success = "User deleted";
    // } else {
    //     $err = "Failed, please try again";
    // }
}


/* Reset passord to default - Makueni102 */
if (isset($_POST['Update_Officer_Password'])) {
	$user = selectOne('users', ['user_id'=>$_POST['user_id']]);
	$user_phone_number = $user['user_phone_number'];
	$user_email = $user['user_email'];
    $user_id = mysqli_real_escape_string($mysqli, $_POST['user_id']);
    $new_password = sha1(md5(mysqli_real_escape_string($mysqli, 'Makueni102')));
    $confirm_password = sha1(md5(mysqli_real_escape_string($mysqli, 'Makueni102')));

    /* Persist */
    if ($new_password != $confirm_password) {
        $err = "Passwords does not match";
    } else {

        $change_password_sql = "UPDATE users SET user_password = '{$confirm_password}', user_change_password='1'  WHERE user_id = '{$user_id}'";

        if (mysqli_query($mysqli, $change_password_sql)) {
            $success = "Password updated";
			 /* Reset Email */
			include('../mailers/force_password_reset.php');
			include('../mailers/bulk_sms/force_password_reset.php');
        } else {
            $err = "Failed, please try again";
        }
    }
}

/** Deactivate system user */
if (isset($_POST['deactivate_user'])) {
	
	$datas = [
		'user_active_status' => 0
	];
	
	$res= updateDetails($datas, 'users', 'user_id', $_POST['user_id']);
	if ($res) {
		$success = "User deactivated successfuly";
	}else{
		$err = "Error, Failed to deactivate";
	}
	
}

if(isset($_id['rstbtn']))
{
	dd("hello");
}
