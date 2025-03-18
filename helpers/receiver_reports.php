<?php
/*
 *   Crafted On Wed 31/7/24
 *   Author stephen ndunda (ndundastevn@gmail.com)
 *  
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
require_once('../config/checklogin.php');


/* Step 1: filter reports */
if (isset($_POST['filterReports'])) 
{	
	//dd($_POST);
// Redirect to a new page
header("Location: All_reports?status=" . $_POST['report_status'] . "&date=" . $_POST['daterange'] . "&fy=" . $_POST['fy']);
exit(); // Always call exit after a redirect
		
		
}

/* Step 1: Approve Collection */
if (isset($_POST['ApproveCollection'])) 
{
	$id = $_POST['collection_id'];
	unset($_POST['ApproveCollection']);
	unset($_POST['collection_id']);
	$_POST['collection_status'] = 'Approved';
	
	$status = updateDetails($_POST, 'revenue_collections', 'collection_id', $id);
	
	if ($status){
	$success = "Approved Successful";
	} else {
	$err = "Failed, try agin laiter";
	}
}

/* Step 2: Decline Collection */
if (isset($_POST['DeclineCollection'])) 
{
	unset($_POST['DeclineCollection']);
	$id = $_POST['collection_id'];
	unset($_POST['collection_id']);
	$_POST['collection_status'] = 'Declined';
	
	$status = updateDetails($_POST, 'revenue_collections', 'collection_id', $id);
	
	if ($status){
	$success = "Declined Successful";
	} else {
	$err = "Failed, try agin laiter";
	}
}


/* Update Collection */
// if (isset($_POST['UpdateCollection'])) {
//     $collection_id = mysqli_real_escape_string($mysqli, $_POST['collection_id']);
//     $collection_service_id  = mysqli_real_escape_string($mysqli, $_POST['collection_service_id']);
//     $collection_amount = str_replace(',', '', mysqli_real_escape_string($mysqli, $_POST['collection_amount']));
//     $collection_date = mysqli_real_escape_string($mysqli, $_POST['collection_date']);
//     $collection_location = mysqli_real_escape_string($mysqli, $_POST['collection_location']);
//     $collection_comment = mysqli_real_escape_string($mysqli, $_POST['collection_comment']);

//     if (mysqli_query($mysqli, "UPDATE revenue_collections SET collection_service_id = '{$collection_service_id}', collection_amount = '{$collection_amount}',
//     collection_date = '{$collection_date}', collection_location = '{$collection_location}', collection_comment = '{$collection_comment}'
//     WHERE collection_id = '{$collection_id}'")) {
//         $success = "Revenue collection updated";
//     } else {
//         $err = "Error Adding Collection";
//     }
// }

