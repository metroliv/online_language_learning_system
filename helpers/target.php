<?php
/*
 *	Crafted On Fri April 19 2024
 *   Author stephen Ndunda (ndundastevn@gmail.com)
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
 *
 */
require_once('../functions/reusableQuery.php');
// require('../config/config.php');



/** Search collections */
if(isset($_POST['seachCollections'])){
	// Redirect to another page
	header('Location: admin_collections?='.$_POST['year'].'-'.$_POST['month'].'-'.$_POST['day']);
	exit();
}

/** dashboard filterrs */
if (isset($_POST['Dashboadfiters'])) 
{	
	header("Location: dashboard?month=" . $_POST['month'] . "&fy=" . $_POST['fy']);
	exit(); 		
}

/** ward target filterrs */
if (isset($_POST['wardRevenueTarget'])) 
{	
	header("Location: ward_revenue_target?month=" . $_POST['month'] . "&fy=" . $_POST['fy']);
	exit(); 		
}
/** ward collections filterrs */
if (isset($_POST['ward_collections'])) 
{	
	header("Location: ward_collections?month=" . $_POST['month'] . "&fy=" . $_POST['fy']);
	exit(); 		
}
/** ward performance filterrs */
if (isset($_POST['wardPerformance'])) 
{	
	header("Location: ward_performance?month=" . $_POST['month'] . "&fy=" . $_POST['fy']);
	exit(); 		
}

/** Tsrget filterrs */
if (isset($_POST['filterTargets'])) 
{	
	header("Location: revenue_target?month=" . $_POST['month'] . "&fy=" . $_POST['fy']);
	exit(); 		
}

/** Collections filterrs */
if (isset($_POST['Collectionsfiters'])) 
{	
	header("Location: revenue_collected?month=" . $_POST['month'] . "&fy=" . $_POST['fy']);
	exit(); 		
}

/** performance filters */
if (isset($_POST['Perfomancefiters'])) 
{	
	header("Location: target_achieved?month=" . $_POST['month'] . "&fy=" . $_POST['fy']);
	exit(); 		
}

/** performance filters */
if (isset($_POST['staffAnnualTarget'])) 
{	
	header("Location: staff_annual_target?month=" . $_POST['month'] . "&fy=" . $_POST['fy']);
	exit(); 		
}

/** performance filters */
if (isset($_POST['ward_revenue_target_stream'])) 
{	
	header("Location: ward_revenue_target_stream?month=" . $_POST['month'] . "&fy=" . $_POST['fy']);
	exit(); 		
}

/** staff collections filters */
if (isset($_POST['staff_collections'])) 
{	
	header("Location: staff_collections?month=" . $_POST['month'] . "&fy=" . $_POST['fy']);
	exit(); 		
}


/** staff performance filters */
if (isset($_POST['staff_performance'])) 
{	
	header("Location: staff_performance?month=" . $_POST['month'] . "&fy=" . $_POST['fy']);
	exit(); 		
}


/** STEP 1: Saving Targets */
if(isset($_POST['addTarget'])){
	//dd($_POST);
    $res;   

    $streams = selectMany('revenue_streams');
    foreach ($streams as $key => $stream) {
        $amount = str_replace(",", "", $_POST[$stream['stream_id']]); // Retrieve the entered amount
        $targetdata = [
            'streamtarget_ward_id'=> $_POST['target_ward_id'],
            'streamtarget_fy'=> $_POST['target_financialYear'],
            'streamtarget_user_id'=> $_POST['target_officer_id'], // Add officer field here
            'streamtarget_amount'=> $amount,
            'streamtarget_stream_id'=> $stream['stream_id'],         
        ];

        // Check if target already exists
        $check_if_data_exists = selectOne('streamtarget', [
            'streamtarget_fy'=> $_POST['target_financialYear'], 
            'streamtarget_stream_id'=> $stream['stream_id'],
            'streamtarget_user_id'=> $_POST['target_officer_id'] // Include officer in the check
        ]);

        // Save data if it doesn't exist
        if(empty($check_if_data_exists)){
			
           $res = saveData($targetdata, 'streamtarget');
        } else {
            $res = 0;
        }
    }

    if ($res){
        $success = "Target set Successful";
    } else {
        $err = "Failed, try again later";
    }

}

/** STEP 1: Updating Targets */
if(isset($_POST['UpdateTarget'])){
	unset($_POST['UpdateTarget']);
	$id = $_POST['target_id'];
	unset($_POST['target_id']);
	//dd($_POST);
	
	$status = updateDetails($_POST, 'revenue_targets', 'target_id', $id);
	
	if ($status){
	$success = "Target Updated Successful";
	} else {
	$err = "Failed, try agin later";
	}
}


?>


