<?php

ini_set( 'display_errors', 1 );
require_once( "../../../../DbConnect.php" );

use DbConnect\DbConnect;

if( isset( $_POST['submit_profile_edit'] ) ) {

    $db = new DbConnect();

    $conn = $db->connect();

    $doctor_id = $_POST['doctor_id'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];

    $sql = "UPDATE doctors SET user_name = '$user_name', user_email = '$user_email', user_phone = $user_phone WHERE id = $doctor_id";

    
    $sql_exe = $conn->query( $sql );

    if( $sql_exe->execute() ) {
    ?>
    <script>alert( "profile Edited Successfully" )</script>
    <?php
        header( "Location: http://kidneyhealthmonitor.free.nf/doctors/pages/profile" );
    } else {
    ?>
    <script>alert( "Error Updating Profile" )</script>
    <?php        
        header( "Location: http://kidneyhealthmonitor.free.nf/doctors/pages/profile" );
    }

    
}