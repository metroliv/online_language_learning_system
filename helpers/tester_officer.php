<?php
require_once('../config/config.php'); // Include the config file for database connection

// Query to fetch officers (users), their targets, approved collected amounts, and ward names
$officersQuery = "
    SELECT 
        u.user_names AS officer_name,
        t.collectormonthlytarget_amount AS target_amount,
        COALESCE(SUM(r.collection_amount), 0) AS collected_amount,
        w.ward_name,
        t.collectormonthlytarget_month
    FROM 
        users u
    JOIN 
        collectormonthlytarget t ON u.user_id = t.collectormonthlytarget_user_id
    LEFT JOIN 
        revenue_collections r ON u.user_id = r.collection_user_id 
        AND r.collection_status = 'Approved'
        AND r.collection_fy = t.collectormonthlytarget_fy
        AND MONTH(r.collection_date) = t.collectormonthlytarget_month
    JOIN 
        ward w ON u.user_ward_id = w.ward_id
    WHERE 
        u.user_access_level IN ('Revenue Collector', 'Ward Administrator')
    GROUP BY 
        u.user_names, t.collectormonthlytarget_amount, w.ward_name, t.collectormonthlytarget_month
    ORDER BY 
        collected_amount DESC";

// Execute the query
$officersResult = $mysqli->query($officersQuery);

// Check if the query was successful
if ($officersResult->num_rows > 0) {
    $officersData = [];
    while ($row = $officersResult->fetch_assoc()) {
        // Output or store data for use in HTML/JavaScript
        $officersData[] = $row;
    }
} else {
    echo "No results found.";
}