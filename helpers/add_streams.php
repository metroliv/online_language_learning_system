<?php
/*
 *   Crafted On Mon May 06 2024
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

 
/* Add streams */

if (isset($_POST['Add_streams'])) {
    $stream_name = mysqli_real_escape_string($mysqli, $_POST['stream_name']);
    /*$stream_name = mysqli_real_escape_string($mysqli, $_POST['stream_name']);
    
    /* Prevent Double Entries */
    $check_stream = mysqli_query($mysqli, "SELECT * FROM revenue_streams WHERE 'stream_name' = '{$stream_name}'");
    if (mysqli_num_rows($check_stream) > 0) {
        $err = "Stream Already Exists";
    } else {
        if (mysqli_query($mysqli, "INSERT INTO revenue_streams (stream_name) 
            VALUES ('{$stream_name}')")) {
            $success = "Stream Added Successfully";
        } else {
            $err = "Error Adding Stream";
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_streams'])) {
    // Retrieve form data
   // $stream_id = $mysqli->real_escape_string($_POST['stream_id']);
    $stream_name = $mysqli->real_escape_string($_POST['stream_name']);

    // Prevent double entries and update the record
    $query = "UPDATE revenue_streams SET stream_name = '$stream_name' WHERE stream = '$stream_name'";

    if ($mysqli->query($query) === TRUE) {
        // Redirect to stream.php after successful update
        header('Location: /Revenue_Reporting_Tool/views/stream.php');
        exit();
    }else {
        echo "Error updating record: " . $mysqli->error;
    }
}


require_once('../config/config.php');

// Update Revenue Stream
if (isset($_POST['update_stream'])) {
    $stream_id = mysqli_real_escape_string($mysqli, $_POST['stream_id']);
    $stream_name = mysqli_real_escape_string($mysqli, $_POST['stream_name']);

    // Update query
    $update_query = "UPDATE revenue_streams SET stream_name='$stream_name' WHERE stream_id='$stream_id'";
    if (mysqli_query($mysqli, $update_query)) {
        $_SESSION['success'] = "Revenue Stream updated successfully.";
    } else {
        $_SESSION['error'] = "Error updating Revenue Stream.";
    }

    header('Location: ../views/stream.php'); // Redirect back to the revenue streams page
    exit();
}

// Deactivate Revenue Stream
if (isset($_POST['deactivate_stream'])) {
    $stream_id = mysqli_real_escape_string($mysqli, $_POST['stream_id']);

    // Deactivate query
    $deactivate_query = "UPDATE revenue_streams SET status='inactive' WHERE stream_id='$stream_id'";
    if (mysqli_query($mysqli, $deactivate_query)) {
        $_SESSION['success'] = "Revenue Stream deactivated successfully.";
    } else {
        $_SESSION['error'] = "Error deactivating Revenue Stream.";
    }

    header('Location: ../views/stream.php'); // Redirect back to the revenue streams page
    exit();
}


//edit a stream
/*
if (isset($_POST['update_stream'])) {
    $stream_name = mysqli_real_escape_string($mysqli, $_POST['stream_name']);
    
    if (mysqli_query($mysqli, "UPDATE stream_name SET stream_name = '{$stream_name}'")){
        $success = "Revenue collection updated";
    } else {
        $err = "Error Adding Collection";
    }
}

/*
if (isset($_POST['update_stream'])) {
    //$stream_id = mysqli_real_escape_string($mysqli, $_POST['stream_id']);
    

    // Update the revenue stream
    $query = "UPDATE revenue_streams SET stream_name = '{$stream_name}' WHERE stream_id = '{$stream_id}'";
    if (mysqli_query($mysqli, $query)) {
        header('Location: /Revenue_Reporting_Tool/views/stream.php');
        $sucess = "Stream updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($mysqli);
    }
}
//Deactivate a stream

if (isset($_POST['deactivate_stream'])) {
    $stream_id = mysqli_real_escape_string($mysqli, $_POST['stream_id']);

    // Delete the revenue stream
    $query = "DELETE FROM revenue_streams WHERE stream_id = '{$stream_id}'";
    if (mysqli_query($mysqli, $query)) {
        header("Location:stream.php?success=Stream deactivated successfully");
    } else {
        echo "Error deleting record: " . mysqli_error($mysqli);
    }
*/