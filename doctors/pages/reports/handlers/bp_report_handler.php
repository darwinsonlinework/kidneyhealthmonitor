<?php

require_once("../../../../DbConnect.php");

use DbConnect\DbConnect;


if( ( isset( $_COOKIE['user_id'] ) && !empty( $_COOKIE['user_id'] ) ) &&( isset( $_COOKIE['user_type']) && $_COOKIE['user_type'] == 'doctor' ) ) {

    $user_id = $_COOKIE['user_id'];

    if( isset( $_POST['user_id'] ) ) {

        $patient_id = $_POST['user_id'];
    }
    
} else {

    header( "Location: http://kidneyhealthmonitr.free.nf/nurses/pages/login" );
}

if( !empty( $patient_id ) ) {
    
$db = new DbConnect();
$conn = $db->connect();

// DataTable parameters
$start = $_POST['start'] ?? 0; // Start index for pagination
$length = $_POST['length'] ?? 10; // Number of records per page
$search = $_POST['search']['value'] ?? ''; // Search keyword
$order_column = $_POST['order'][0]['column'] ?? 0; // Column index for sorting
$order_dir = $_POST['order'][0]['dir'] ?? 'DESC'; // Sorting direction
$from_date = $_POST['from_date'] ?? '';
$to_date = $_POST['from_date'] ?? '';

/*
if( !empty( $from_date ) && !empty( $to_date ) ) {

    $date_filter = " AND checked_date BETWEEN $from_date AND $to_date ";
} else {

    $date_filter = "";
}*/

// Columns mapping
$columns = array(
    0 => 'id',
    1 => 'checked_date',
    2 => 'checked_time',
    3 => 'systolic',
    4 => 'diastolic'
    // Add more columns if needed
);

// Construct SQL query
$sql = "SELECT * FROM bp WHERE 1=1 AND user_id = $patient_id ";
if (!empty($search)) {
    $sql .= " AND (checked_date LIKE '%$search%' OR checked_time LIKE '%$search%' OR systolic LIKE '%$search%' OR diastolic LIKE '%$search%')";
}
//$sql .= $date_filter;
$sql .= " ORDER BY " . $columns[$order_column] . " " . $order_dir;
$sql .= " LIMIT $start, $length";

// Execute SQL query
$result = $conn->query($sql);

// Format data
$data = array();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $row;
}

// Get total records without filtering
$total_records = $conn->query("SELECT COUNT(*) FROM bp")->fetchColumn();

// Get total records after filtering
$total_filtered = $conn->query("SELECT COUNT(*) FROM bp WHERE 1=1")->fetchColumn();

if( !empty( $data ) ) {

$high_bp = $normal_bp = $low_bp = [];

//echo 'DDD: . ' . json_encode( $data );

foreach( $data AS $bp ) {

    //echo PHP_EOL . 'Item : ' . json_encode( $bp );
    if( $bp['systolic'] > 120 ) {

        $high_bp[] = $bp['systolic'];
    } else if( $bp['systolic'] <= 120 && $bp['systolic'] >= 90 ) {

        $normal_bp[] = $bp['systolic'];
    } else {

        $low_bp[] = $bp['systolic'];
    }
}

$total_displayed = count( $data );

$high_bp_count = count( $high_bp );

$high_bp_percentage = number_format( ($high_bp_count / $total_displayed) * 100, 2 );

$normal_bp_count = count( $normal_bp );
$normal_bp_percentage = number_format( ($normal_bp_count / $total_displayed) * 100, 2 );

$low_bp_count = count( $low_bp );
$low_bp_percentage = number_format( ($low_bp_count / $total_displayed) * 100, 2 );

$percentages = [

    "high_bp_percentage" => $high_bp_percentage,
    "normal_bp_percentage" => $normal_bp_percentage,
    "low_bp_percentage" => $low_bp_percentage
];
} else {
    $percentages = [

        "high_bp_percentage" => 0,
        "normal_bp_percentage" => 0,
        "low_bp_percentage" => 0
    ];
}
// Prepare JSON response

if( !empty( $doctor_id ) ) {

    $response = array(
        "draw" => intval($_POST['draw'] ?? 1), // Draw counter
        "recordsTotal" => intval($total_records), // Total records
        "recordsFiltered" => intval($total_filtered), // Total records after filtering
        "data" => $data, // Data
    );
} else {

    $response = array(
        "draw" => intval($_POST['draw'] ?? 1), // Draw counter
        "recordsTotal" => intval($total_records), // Total records
        "recordsFiltered" => intval($total_filtered), // Total records after filtering
        "data" => $data, // Data
        'percentages' => $percentages
    );
}


// Return JSON response
echo json_encode($response);

} else {

    header( "Location: http://kidneyhealthmonitor.free.nf/pages/login" );
}
?>
