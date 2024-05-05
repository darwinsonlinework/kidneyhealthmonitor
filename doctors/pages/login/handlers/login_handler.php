<?php

ini_set( "display_errors", 0 );

require_once "../../../../DbConnect.php";

use DbConnect\DbConnect;


//require_once( "http://kidneyhealthmonitor.free.nf/DbConnect.php" );

//require_once( "http://kidneyhealthmonitor.free.nf/DbConnect.php" );

//require_once( "http://kidneyhealthmonitor.free.nf/DbConnect.php" );
//use DoctorDbConnect\DoctorDbConnect;
///echo json_encode( 'ccc;' ) ; 

if( isset( $_POST['login_email'] ) && isset($_POST['login_password']) ) {

//if( isset( $_GET['doctor_login_btn'] )  && isset( $_GET['login_email'] ) && isset($_GET['login_password']) ) {

    try {
        $email = $_POST['login_email'];//$_GET['login_email'];//
        $password = $_POST[  'login_password'];//$_GET['login_password']; //$_POST['login_password'];
    
        $db = new DbConnect();
        $conn = $db->connect();
        
        $sql = "SELECT * FROM doctors WHERE user_email= :email AND password = :password AND status = 1";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $response = [];
        
        if (!empty($result)) {
            // Setting the "user_id" cookie
            setcookie("user_id", $result['id'], time() + (30 * 24 * 60 * 60), "/");
            setcookie("user_type", 'doctor', time() + (30 * 24 * 60 * 60), "/");
            
            $response = [
                'status' => 1,
                'message' => 'Logged In Sccessfully'
            ];
            // Redirect to the dashboard
            //header("Location: http://kidneyhealthmonitor.free.nf/pages/dashboard.php");
        } else {

            $response = [
                'status' => 0,
                'message' => 'Wrong Email / Password'
            ];
            // Redirect back to the login page with an error message
            //header("Location: http://kidneyhealthmonitor.free.nf/pages/login?result=0");
        }
        
        echo json_encode( $response );
        exit;
    } catch (PDOException $e) {
        // Handle database errors
        //echo "Error: " . $e->getMessage();

        $response = [
            'status' => 0
        ];

        echo json_encode( $response );
        exit;
    }
}