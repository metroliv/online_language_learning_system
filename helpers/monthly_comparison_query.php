<?php
/*
 *	Crafted On Mon Sept 27 2024
 *   Author Benjamin Wambua (jayminwambu@gmail.com)
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
require_once('../config/config.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch the active fy_id for the current financial year
$fyQuery = "SELECT fy_id FROM financial_year WHERE fy_status = 1 LIMIT 1";
$fyStmt = $mysqli->prepare($fyQuery);
$fyStmt->execute();
$fyResult = $fyStmt->get_result();

if ($fyRow = $fyResult->fetch_assoc()) {
    $financialYearId = $fyRow['fy_id']; // Active financial year ID
} else {
    die("No active financial year found.");
}

// Fetch monthly collected amounts for the active financial year
$collectedQuery = "
    SELECT 
        DATE_FORMAT(rc.collection_date, '%Y-%m') AS month,
        SUM(rc.collection_amount) AS total_collected
    FROM revenue_collections rc
    WHERE rc.collection_status = 'Approved' AND rc.collections_fy = ?
    GROUP BY month
";
$collectedStmt = $mysqli->prepare($collectedQuery);
$collectedStmt->bind_param("i", $financialYearId);
$collectedStmt->execute();
$collectedResult = $collectedStmt->get_result();

// Fetch monthly targets for the active financial year
$targetQuery = "
    SELECT 
        DATE_FORMAT(cm.collectormonthlytarget_month, '%Y-%m') AS month,
        SUM(cm.collectormonthlytarget_fixed_amount) AS total_target
    FROM collectormonthlytarget cm
    WHERE cm.collectormonthlytarget_fy = ?
    GROUP BY month
";
$targetStmt = $mysqli->prepare($targetQuery);
$targetStmt->bind_param("i", $financialYearId); // Use financial year ID to match the table field
$targetStmt->execute();
$targetResult = $targetStmt->get_result();

// Combine collected and target data
$collectedData = [];
while ($row = $collectedResult->fetch_assoc()) {
    $collectedData[$row['month']] = $row['total_collected'];
}

$targetData = [];
while ($row = $targetResult->fetch_assoc()) {
    $targetData[$row['month']] = $row['total_target'];
}

// Prepare data for chart
$months = array_unique(array_merge(array_keys($collectedData), array_keys($targetData)));
sort($months); // Optional: sort months in chronological order

$targets_monthly = array_map(function($month) use ($targetData) { return $targetData[$month] ?? 0; }, $months);
$collected_monthly = array_map(function($month) use ($collectedData) { return $collectedData[$month] ?? 0; }, $months);

// Convert month codes to names
function monthName($monthCode) {
    $dateTime = DateTime::createFromFormat('Y-m', $monthCode);
    return $dateTime ? $dateTime->format('F Y') : $monthCode;
}

$monthNames = array_map('monthName', $months);

// Optionally, you can print the result arrays for debugging purposes
// print_r($monthNames);
// print_r($targets_monthly);
// print_r($collected_monthly);
