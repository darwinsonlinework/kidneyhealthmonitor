<?php

require_once "../../../../DbConnect.php";

use DbConnect\DbConnect;
//use Exception;
//use PDO;

if( !empty( isset( $_POST['name'] ) ) && !empty( isset( $_POST['email'] ) ) ) {

    try {
        $user_name= $_POST['name'];
        $user_email = $_POST['email'];
        $user_phone = $_POST['phone'];
        $password = $_POST['password'];

        $db = new DbConnect();
        $conn = $db->connect();

        $current_date = date( 'Y-m-d H:i:s' );

        $already_sql = "SELECT * FROM doctors WHERE user_email LIKE '%$user_email%' OR user_phone = $user_phone";
        $already_sql_exe = $conn->query( $already_sql );
        $already_sql_exe->execute();

        $already = $already_sql_exe->fetch( PDO::FETCH_ASSOC );

        if( empty( $already ) ) {

        $sql = "INSERT INTO doctors ( user_name, user_email, user_phone, password, user_type, paid, status, created_at ) VALUES ( :user_name, :user_email, :user_phone, :user_password, 'doctor', 0, 1, '" .$current_date . "' )";
        $sql_exe = $conn->prepare( $sql );
        
        $sql_exe->bindParam( ':user_name', $user_name );
        $sql_exe->bindParam( ':user_email', $user_email );
        $sql_exe->bindParam( ':user_phone', $user_phone );
        $sql_exe->bindParam( ':user_password', $password );
        
        $user_created = $sql_exe->execute();

        $doctor_id = $conn->lastInsertId();

        if( $doctor_id ) {

            setcookie('user_id', $doctor_id, time() + (30 * 24 * 60 * 60), '/');
            setcookie('user_type', 'doctor', time() + (30 * 24 * 60 * 60), '/');

            $response = [
                'status' => 1,
                'message' => 'Account Created Successfully'
            ];
        } else {

            $response = [
                'status' => 0
            ];
        }

        echo json_encode( $response );
        exit;

    } else {


        $response = [
            'status' => 0,
            'message' => 'User Phone / Email Already Present'
        ];

        echo json_encode( $response );
        exit;
        
    }

    } catch ( Exception $e ) {

        $response = [
            'status' => 0,
            'message' => 'Exception: ' . $e->getMessage()
        ];

        echo json_encode( $response );
        exit;
    }
}
?>
