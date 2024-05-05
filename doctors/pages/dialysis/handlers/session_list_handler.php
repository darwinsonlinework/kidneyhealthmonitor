<?php

ini_set( 'display_errors', 1 );

require_once("../../../../DbConnect.php");

use DbConnect\DbConnect;


if( isset( $_COOKIE['user_id'] ) && !empty( $_COOKIE['user_id'] ) && $_COOKIE['user_type'] == 'doctor' ) {

    $user_id = $_COOKIE['user_id'];
    $user_type = $_COOKIE['user_type'];
}

if( !empty( $user_id ) && !empty( $user_type ) ) {

    if( isset( $_POST['patient_id'] ) && !empty( $_POST['patient_id'] ) ) {

        $patient_id = $_POST['patient_id'];


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
    0 => 'checked_date',
    1 => 'checked_time',
    2 => 'before_weight',
    3 => 'after_weight',
    4 => 'bp_data_1',
    5 => 'bp_data_2',
    6 => 'bp_data_3'
    // Add more columns if needed
);


    $sql = "SELECT * FROM complete_dialysis WHERE 1=1 AND user_id = $patient_id";

if (!empty($search)) {
    $sql .= " AND ( checked_date LIKE '%$search%' OR bp_data_1 LIKE '%$search%' OR bp_data_1 LIKE '%$search%' OR bp_data_2 LIKE '%$search%' OR bp_data_3 LIKE '%$search%' OR before_weight LIKE '%$search%' OR after_weight LIKE '%$search%' )";
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


$response = array(
    "draw" => intval($_POST['draw'] ?? 1), // Draw counter
    "recordsTotal" => intval($total_records), // Total records
    "recordsFiltered" => intval($total_filtered), // Total records after filtering
    "data" => $data
);

// Return JSON response
echo json_encode($response);
    }
} else {

    header( "Location: http://kidneyhealthmonitr.free.nf/doctors/pages/login" );
}
?>
