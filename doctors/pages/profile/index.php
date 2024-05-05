<?php

//require_once ( "../header.html" );
//require_once ( "../layout.php" );

require_once ( "../../../DbConnect.php" );

use DbConnect\DbConnect;
//use PDO;

//echo phpinfo();
//die;
if( isset( $_COOKIE['user_id'] ) &&  !empty( $_COOKIE['user_id'] ) || isset( $_COOKIE['user_type'] ) &&  !empty( $_COOKIE['user_type'] ) ) {

  $user_id = $_COOKIE['user_id'];
  $user_type = $_COOKIE['user_type'];

} else {

  header( 'Location: http://kidneyhealthmonitor.free.nf/doctors/pages/login' );
}

if( !empty( $user_id ) && $user_type == "doctor" ) {
?>
<?php

  date_default_timezone_set('Asia/Kolkatta'); // Set the default timezone to America/New_York
  
    $db = new DbConnect();

    $conn = $db->connect();

    $sql = "SELECT * FROM doctors WHERE id = " . $user_id;
    $sql_exe = $conn->query( $sql );
    $sql_exe->execute();

    $user_data = $sql_exe->fetch( PDO::FETCH_ASSOC );

    $user_name = $user_data['user_name'];
    $profile_pic = $user_data['profile_pic'];
    $user_email = $user_data['user_email'];
    $user_phone = $user_data['user_phone'];
    $plan = empty( $user_data['paid'] ) ? 'Free Plan' : 'Paid Plan';
    $user_status = $user_data['status'];
    $created_at = $user_data['created_at']; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chronic Kidney Disease - Profile | Comprehensive Kidney Monitoring Tool | Dark Hand Coders</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://kidneyhealthmonitor.free.nf/assets/css/sidebar.css" rel="stylesheet">
    <link href="http://kidneyhealthmonitor.free.nf/assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.7/css/boxicons.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
</head>

<body class="profile-body">
    <div class="container-fluid profile-container">
        <div class="row">
            <!-- Sidebar -->
            <?php include( "../layouts/sidebar.php" ); ?>
            <!-- Content -->
            <div class="col main-content">
                <!-- Header -->
                <?php include( "../layouts/header.php" ); ?>
                <!-- Body -->
                <div class="content">
                <section style="background-color: #eee;">
                  <div class="container py-2">
                    <div class="row">
                      <div class="col">
                        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                          <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><b>Profile</b></li>
                          </ol>
                        </nav>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-4">
                        <div class="card mb-4">
                          <div class="card-body text-center">
                          <img id="profile-pic" src="<?php echo !empty($profile_pic) ? $profile_pic : 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp'; ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                              
                              <h5 class="my-3"><?php echo !empty($user_name) ? $user_name : 'Username'; ?></h5>
                            <p class="text-muted mb-1 text"><b class="heading">Member Since: </b><?php echo !empty( $created_at ) ? date( 'Y-m-d', strtotime( $created_at ) ) : "-" ?></p>
                            <!--<div class="d-flex justify-content-center mb-2">
                              <a href="http://kidneyhealthmonitor.free.nf/pages/profile/edit.php"><button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary">Edit</button></a>
                              <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-primary ms-1">Logout</button>
                            </div>-->

                            <div class="d-flex justify-content-center mb-2">
                              <a href="http://kidneyhealthmonitor.free.nf/doctors/pages/profile/edit.php">
                                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary">Edit</button>
                            </a>
                            <form action="../login/handlers/logout_handler.php" method="post">
                                <button type="submit" name="logout" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-primary ms-1">Logout</button>
                            </form>
                        </div>

                
                          </div>
                        </div>

                      </div>
                      <div class="col-lg-8">
                        <div class="card mb-4">
                          <div class="card-body">
                            <div class="row">
                              <div class="col-sm-3">
                                <p class="mb-0 heading">Full Name</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo !empty($user_name) ? $user_name : '-'; ?></p>
                              </div>
                            </div>
                            <hr>
                            <div class="row">
                              <div class="col-sm-3">
                                <p class="mb-0 heading">Email</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo !empty($user_email) ? $user_email : '-'; ?></p>
                              </div>
                            </div>
                            <hr>
                            <div class="row">
                              <div class="col-sm-3">
                                <p class="mb-0 heading">Phone</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo !empty($user_phone) ? $user_phone : '-'; ?></p>
                              </div>
                            </div>
                            <hr>
                            <div class="row">
                              <div class="col-sm-3">
                                <p class="mb-0 heading">Status</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo ( !empty( $user_status ) && $user_status == 1 ) ? 'Active' : 'In-Active'; ?></p>
                              </div>
                            </div>
                            <hr>
                            <div class="row">
                              <div class="col-sm-3">
                                <p class="mb-0 heading">Plan</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo $plan; ?><?php echo empty($user_data['paid']) ? "<span><a href='http://kidneyhealthmonitor.free.nf/pages/plans/plans.php' style='text-decoration: none'> Buy Plan</a></span>" : ""; ?></p>
                              </div>
                            </div>

                          </div>
                        </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </section>
                
        </div>
    </div>
    </div>
                
  </div>
  <div>
              <!-- Footer -->
              <?php include( "../layouts/footer.php" ); ?>
            </div>
            

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="http://kidneyhealthmonitor.free.nf/assets/js/sidebar.js"></script>

</body>

</html>

<?php } else {

header( 'Location: http://kidneyhealthmonitor.free.nf/doctors/pages/login' );
} ?>