<?php 

//require_once ( "../../DbConnect.php" );
//require_once( "../../DbConnect.php" );

ini_set( 'display_errors', 0 );
//error_reporting(E_ALL & ~E_WARNING);

use DbConnect\DbConnect;

$db = new DbConnect();

    $conn = $db->connect();

if( isset( $_COOKIE['user_id'] ) && $_COOKIE['user_type'] == "doctor" ) {

  $user_id = $_COOKIE['user_id'];
  $user_type = $_COOKIE['user_type'];

  $sql = "SELECT profile_pic FROM doctors WHERE id = " . $user_id;

    $sql_exe = $conn->query( $sql );
    $sql_exe->execute();

    $profile_pic = $sql_exe->fetch( PDO::FETCH_COLUMN );
}

if( ( isset( $user_id ) && !empty( $user_id ) ) ) {


  date_default_timezone_set('Asia/Kolkatta'); // Set the default timezone to America/New_York

 
 ?>
<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <h1>Kidney Health Monitor</h1>

            </div>
            <div class="col-md-4 d-none d-md-block">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle header-profile-pic-btn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo !empty($profile_pic) ? $profile_pic : 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp'; ?>" class="rounded-circle img-fluid" width="40px" alt="profile Pic">
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="http://kidneyhealthmonitor.free.nf/doctors/pages/profile">Profile</a></li>
                        
                        <li><a class="dropdown-item" href="http://kidneyhealthmonitor.free.nf/doctors/pages/login/handlers/logout_handler.php">Logout</a></li>
                        <!-- Add more dropdown items as needed -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } else {

    header( "Location: http://kidneyhealthmonitor.free.nf/pages/login" );
}
?>
