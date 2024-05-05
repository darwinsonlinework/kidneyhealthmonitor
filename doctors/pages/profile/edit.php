<?php

//require_once ( "../header.html" );
//require_once ( "../layout.php" );

require_once ( "../../../DbConnect.php" );

use DbConnect\DbConnect;
//use PDO;

//echo phpinfo();
//die;

if( isset( $_COOKIE['user_id'] ) && isset( $_COOKIE['user_type'] ) && $_COOKIE['user_type'] == "doctor" ) {

  $user_id = $_COOKIE['user_id'];

} else {

  $user_id = null;
}

if( isset( $user_id ) && !empty( $user_id ) ) {

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
     
        
    </head>
    
    <body class="profile-body">
        <div class="container-fluid profile-container">
            <div class="row">
                <!-- Sidebar -->
                <?php include( "../layouts/sidebar.php" ); ?>
                <!-- Content -->
                <div class="col main-content">
                    <!-- Header -->
                    <?php include( "../pages/layouts/header.php" ); ?>
                    <!-- Body -->
                    <div class="content">
                    <section style="background-color: #eee;">
                      <div class="container py-2">
                        <div class="row">
                          <div class="col">
                            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                              <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><b>Profile Edit</b></li>
                              </ol>
                            </nav>
                          </div>
                        </div>
    
                        <div class="row">
                          <div class="col-lg-4">
                            
                          <div class="card mb-4">
                          <div class="card-body text-center">
                          <img id="profile-pic" src="<?php echo !empty($profile_pic) ? $profile_pic : 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp'; ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">       
                              <form method="POST" enctype="multipart/form-data">
                                  <input type="file" name="file" id="file-input">
                                  <input type="submit" value="Upload" name="profile_pic_submit">
                              </form>
                              
                              <?php
                              if (isset($_POST['profile_pic_submit'])) {
                                if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
                                    $tempFilePath = $_FILES['file']['tmp_name'];
                                    $newFilePath = '../../assets/images/doctors/profile_pics/' . $_FILES['file']['name']; // Adjust the destination folder as needed
                                    if (move_uploaded_file($tempFilePath, $newFilePath)) {
                                        $profile_pic = $newFilePath; // Update $profile_pic with the new file path
                                        $profile_pic_sql = "UPDATE doctors SET profile_pic = '$profile_pic' WHERE id = $$doctor_id";
                                        $profile_pic_exe = $conn->query($profile_pic_sql);
                                        if ($profile_pic_exe) {
                                            echo '<p>Profile picture uploaded successfully.</p>';
                                            echo '<script>document.getElementById("profile-pic").src = "' . $newFilePath . '";</script>';
                                            header("Location: http://kidneyhealthmonitor.free.nf/pages/profile/edit.php");
                                        } else {
                                            echo 'Failed to update profile picture in database.';
                                        }
                                    } else {
                                        echo 'Failed to move uploaded file.';
                                    }
                                } else {
                                    echo 'Error uploading file.';
                                }
                            }
                            
                              ?>
    
                            <!--  <script>
                                  document.getElementById('file-input').addEventListener('change', function(event) {
                                      const file = event.target.files[0];
                                      if (file) {
                                          const reader = new FileReader();
                                          reader.onload = function(e) {
                                              document.getElementById('profile-pic').src = e.target.result;
                                          }
                                          reader.readAsDataURL(file);
                                      }
                                  });
                              </script>
                                -->
                              <h5 class="my-3"><?php echo !empty($user_name) ? $user_name : 'Username'; ?></h5>
                              <p class="text-muted mb-1"><b>Member Since: </b><?php echo !empty( $created_at ) ? date( 'Y-m-d', strtotime( $created_at ) ) : "-" ?></p>
                            <div class="d-flex justify-content-center mb-2">
                            </div>
                          </div>
                        </div>
                            
                            </div>
                      <div class="col-lg-8">
                        <div class="card mb-4">
                          <div class="card-body">

                          <form id="profile-edit-form" action="handlers/edit_handler.php" method="POST">

<div class="row">
    <div class="col-sm-9">
    </div>
</div>
<div id="profile-edit-form" class="row">

<input type="hidden" id="doctor_id" name="doctor_id" class="form-control" value="<?php echo !empty($user_id) ? $user_id : ''; ?>">
    <div class="col-sm-3">
        <label for="user_name">Full Name</label>
    </div>
    <div class="col-sm-9">
        <input type="text" id="user_name" name="user_name" class="form-control" value="<?php echo !empty($user_name) ? $user_name : '-'; ?>">
    </div>
</div>
<hr>

<div class="row">
    <div class="col-sm-3">
        <label for="user_email">Email</label>
    </div>
    <div class="col-sm-9">
        <input type="text" id="user_email" name="user_email" class="form-control" value="<?php echo !empty($user_email) ? $user_email : '-'; ?>" required>
    </div>
</div>
<hr>

<div class="row">
    <div class="col-sm-3">
        <label for="user_phone">Phone <small>(optional)</small></label>
    </div>
    <div class="col-sm-9">
        <input type="text" id="user_phone" name="user_phone" class="form-control" value="<?php echo !empty($user_phone) ? $user_phone : ''; ?>">
    </div>
</div>
<hr>

<br>

<div class="row">
    <div class="col-sm-3">
        <input type="submit" id="submit_profile_edit" name="submit_profile_edit" class="form-control primary" value="Update Profile">
    </div>
</div>

</form>
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

<?php } else { header( 'Location: http://kidneyhealthmonitor.free.nf/doctors/pages/login' ); } ?>