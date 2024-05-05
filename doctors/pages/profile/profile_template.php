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

  $user_id = null;
  $user_type = null;
}

if( !empty( $user_id ) && !empty( $user_type ) ) {
?>
<?php
  date_default_timezone_set('Asia/Kolkatta'); // Set the default timezone to America/New_York

  
  
    $db = new DbConnect();

    $conn = $db->connect();

    if( $user_type == "user" ) {

      $sql = "SELECT * FROM users WHERE id = " . $user_id;
    } else if( $user_type == "nurse" ) {

      $sql = "SELECT * FROM nurses WHERE id = " . $user_id;
    } else if( $user_type == "doctor" ) {

    $sql = "SELECT * FROM doctors WHERE id = " . $user_id;
  }
  echo $sql;
    $sql_exe = $conn->query( $sql );
    $sql_exe->execute();

    $user_data = $sql_exe->fetch( PDO::FETCH_ASSOC );

    echo json_encode( $user_data );
    $user_name = $user_data['user_name'];
    $profile_pic = $user_data['profile_pic'];
    $user_email = $user_data['user_email'];
    $user_phone = $user_data['user_phone'];
    $plan = empty( $user_data['paid'] ) ? 'Free Plan' : 'Paid Plan';
    $user_status = $user_data['status'];
    $doctor_name = isset( $user_data['doctor_name'] ) ? $user_data['doctor_name'] : '';
    $doctor_contact = isset( $user_data['doctor_contact'] ) ? $user_data['doctor_contact'] : '';
    $created_at = $user_data['created_at']; 

    if( $user_type == 'user' ) {

    $sql2 = "SELECT * FROM user_basic_details WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
    $sql_exe2 = $conn->query( $sql2 );
    $sql_exe2->execute();

    $user_basic_details = $sql_exe2->fetch( PDO::FETCH_ASSOC );

    if( !empty( $user_basic_details ) ) {

      $height = $user_basic_details['height'];
    $weight = $user_basic_details['weight'];
    $allowed_water_intake = $user_basic_details['allowed_water_intake'];
    $dialysis_affirmation = $user_basic_details['dialysis_affirmation'];

    $dialysis_affirm = ( $dialysis_affirmation == 1 ) ? 'Yes' : 'No';

    $dialysis_frequency = $user_basic_details['dialysis_frequency'];

    if( $dialysis_frequency == 1 ) {

      $dialysis_frequency_affirm = "Daily";
    } else if( $dialysis_frequency == 2 ) {

      $dialysis_frequency_affirm = "2 Days Once";
    } else if( $dialysis_frequency == 3 ) {

      $dialysis_frequency_affirm = "3 Days Once";
    } else if( $dialysis_frequency == 4 ) {

      $dialysis_frequency_affirm = "Weekly Once";
    } else if( $dialysis_frequency == 100 ) {

      $dialysis_frequency_affirm = "No Dialysis";
    }
    }
    


    $yesterday = date('Y-m-d', strtotime('-1 day'));

    $yesterday_bp_sql = "SELECT SUM( systolic ) / COUNT( * ) AS yesterday_systolic FROM bp WHERE user_id = $user_id AND checked_date = '$yesterday'";
    $yesterday_bp_exe = $conn->query( $yesterday_bp_sql );
    $yesterday_bp_exe->execute();

    $yesterday_avg_bp = $yesterday_bp_exe->fetch( PDO::FETCH_COLUMN );

    $yesterday_urine_sql = "SELECT SUM( quantity ) / COUNT( * ) AS yesterday_urine FROM urine WHERE user_id = $user_id AND checked_date = '$yesterday'";
    $yesterday_urine_exe = $conn->query( $yesterday_urine_sql );
    $yesterday_urine_exe->execute();

    $yesterday_avg_urine = $yesterday_urine_exe->fetch( PDO::FETCH_COLUMN );

    
    $today = date('Y-m-d');

    $today_bp_sql = "SELECT SUM( systolic ) / COUNT( * ) AS today_bp FROM bp WHERE user_id = $user_id AND checked_date = '$today'";
    
    $today_bp_exe = $conn->query( $today_bp_sql );
    $today_bp_exe->execute();

    $today_avg_bp = $today_bp_exe->fetch( PDO::FETCH_COLUMN, 0 );

    
    $today_urine_sql = "SELECT SUM( quantity ) / COUNT( * ) AS today_urine FROM urine WHERE user_id = $user_id AND checked_date = '$today'";
    $today_urine_exe = $conn->query( $today_urine_sql );
    $today_urine_exe->execute();

    $today_avg_urine = $today_urine_exe->fetch( PDO::FETCH_COLUMN );

    //echo 'Avg U: ' . $today_avg_urine;
    //die;
    $line_chart_sql = "SELECT DATE(created_at) AS checked_date, height, `weight` FROM user_basic_details WHERE user_id = $user_id GROUP BY checked_date ORDER BY checked_date ASC LIMIT 5";
    $last_5_health = $conn->query( $line_chart_sql );
    $last_5_health->execute();

    $last_5_day_result = $last_5_health->fetchAll( PDO::FETCH_ASSOC );

    if( !empty( $last_5_day_result ) ) {

      foreach( $last_5_day_result AS $profile_data ) {

        $last_5_dates[] = $profile_data['checked_date'];
        $last_5_heights[] = $profile_data['height'];
        $last_5_weights[] = $profile_data['weight'];
      }
    }
  }

  echo 'came';
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
                              <?php if( $user_type == "doctor" ) { ?>
                            <a href="http://kidneyhealthmonitor.free.nf/doctors/pages/profile/edit.php">
                              <?php } else { ?>

                                <a href="http://kidneyhealthmonitor.free.nf/pages/profile/edit.php">
                              <?php } ?>
                                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary">Edit</button>
                            </a>
                            <form action="handlers/logout_handler.php" method="post">
                                <button type="submit" name="logout" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-primary ms-1">Logout</button>
                            </form>
                        </div>

                
                          </div>
                        </div>
                        <!--
                        <div class="card mb-4 mb-lg-0">
                          <div class="card-body p-0">
                            <ul class="list-group list-group-flush rounded-3">
                              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fas fa-globe fa-lg text-warning"></i>
                                <p class="mb-0">https://mdbootstrap.com</p>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-github fa-lg" style="color: #333333;"></i>
                                <p class="mb-0">mdbootstrap</p>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-twitter fa-lg" style="color: #55acee;"></i>
                                <p class="mb-0">@mdbootstrap</p>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-instagram fa-lg" style="color: #ac2bac;"></i>
                                <p class="mb-0">mdbootstrap</p>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-facebook-f fa-lg" style="color: #3b5998;"></i>
                                <p class="mb-0">mdbootstrap</p>
                              </li>
                            </ul>
                          </div>
                        </div>
                      -->

                     <!-- <div class="row">
                      <div class="col-lg-4">-->
                        <?php if( $user_type == "user" ) { ?>
                      <div class="card mb-12">
                          <div class="card-body">

                            <div class="row">
                                <div class="col-sm-9">
                                    <input type="hidden" id="user_id" name="user_id" class="form-control" value="<?php echo !empty( $user_id ) ? $user_id : '-'; ?>">
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-sm-9">
                                <p class="mb-0 heading">Height</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo !empty($height) ? $height : '-'; ?></p>
                              </div>
                            </div>
                            <hr>

                            <div class="row">
                            <div class="col-sm-9">
                                <p class="mb-0 heading">Weight</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo !empty($weight) ? $weight : '-'; ?></p>
                              </div>
                            </div>
                            <hr>

                            <div class="row">
                            <div class="col-sm-9">
                                <p class="mb-0 heading">Allowed Water Intake</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo !empty($allowed_water_intake) ? $allowed_water_intake : '-'; ?></p>
                              </div>
                            </div>
                            <hr>

                            <div class="row">
                            <div class="col-sm-9">
                                <p class="mb-0 heading">Dialysis</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo !empty( $dialysis_affirm ) ? $dialysis_affirm : '-'; ?></p>
                              </div>
                            </div>
                            <hr>

                            <div class="row">
                            <div class="col-sm-9">
                                <p class="mb-0 heading">Dialysis Frequency</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo !empty( $dialysis_frequency_affirm ) ? $dialysis_frequency_affirm : '-'; ?></p>
                              </div>
                            </div>
                            <hr>
                          </div>
                      </div>
                      <? } ?>
                      <!--</div>
                    </div>-->

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
                            <?php if( $user_type == "nurse" ) { ?>
                            <div class="row">
                              <div class="col-sm-3">
                                <p class="mb-0 heading">Doctor Name</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo !empty($doctor_name) ? $doctor_name : '-'; ?></p>
                              </div>
                            </div>
                            <hr>

                            <div class="row">
                              <div class="col-sm-3">
                                <p class="mb-0 heading">Doctor Contact</p>
                              </div>
                              <div class="col-sm-9">
                                <p class="text-muted mb-0 text"><?php echo !empty($doctor_contact) ? $doctor_contact : '-'; ?></p>
                              </div>
                            </div>
                            <hr>
                            <?php } ?>
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
                        <?php if( $user_type == "user" ) { ?>
                        <div class="row">
                          <div class="col-md-6 mb-4">
                            <div class="card mb-4 mb-md-0">
                              <div class="card-body">
                                <p class="mb-4"><span class="text-primary font-italic me-1">Assessment</span> Yesterday
                                </p>
                                <p class="mb-1" style="font-size: .77rem;">Average BP</p>
                                <div class="progress rounded" style="height: 5px;">
                                  <div class="progress-bar" role="progressbar" style="width: <?php echo !empty( $yesterday_avg_bp ) ? round( $yesterday_avg_bp ) / 120 * 100 : '0' ?>%" aria-valuenow="<?php echo !empty( $yesterday_avg_bp ) ? $yesterday_avg_bp : '0' ?>"
                                    aria-valuemin="0" aria-valuemax="200"></div>
                                </div>
                                <span><?php echo !empty($yesterday_avg_bp) ? round( $yesterday_avg_bp ) . '/200' : '0/200' ?></span>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Average Urine Output</p>
                                <div class="progress rounded" style="height: 5px;">
                                  <div class="progress-bar" role="progressbar" style="width: <?php echo !empty( $yesterday_avg_urine ) ? $yesterday_avg_urine : '0' ?>%" aria-valuenow="<?php echo !empty( $yesterday_avg_urine ) ? $yesterday_avg_urine : '0' ?>"
                                    aria-valuemin="0" aria-valuemax="3000"></div>
                                </div>
                                <span><?php echo !empty( $yesterday_avg_urine ) ? round( $yesterday_avg_urine ) . '/3000' : '0/3000' ?></span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 mb-4">
                            <div class="card mb-4 mb-md-0">
                              <div class="card-body">
                                <p class="mb-4"><span class="text-primary font-italic me-1">Assessment</span> Today
                                </p>
                                <p class="mb-1" style="font-size: .77rem;">Average BP</p>
                                <div class="progress rounded" style="height: 5px;">
                                  <div class="progress-bar" role="progressbar" style="width: <?php echo !empty( $today_avg_bp ) ? round( $today_avg_bp ) / 120 * 100 : '0' ?>%" aria-valuenow="<?php echo !empty( $today_avg_bp ) ? $today_avg_bp : '0' ?>"
                                    aria-valuemin="0" aria-valuemax="200"></div>
                                </div>
                                <span><?php echo !empty($today_avg_bp) ? round( $today_avg_bp ) . '/200' : '0/200' ?></span>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Average Urine Output</p>
                                <div class="progress rounded" style="height: 5px;">
                                  <div class="progress-bar" role="progressbar" style="width: <?php echo !empty( $today_avg_urine ) ? round( $today_avg_urine ) : '0' ?>%" aria-valuenow="<?php echo !empty( $today_avg_urine ) ? $today_avg_urine : '0' ?>"
                                    aria-valuemin="0" aria-valuemax="3000"></div>
                                </div>
                                <span><?php echo !empty( $today_avg_urine ) ? round( $today_avg_urine ) . '/3000' : '0/3000' ?></span>
                          
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">

                          <div class="col-sm-12 col-md-6">
                          <div class="card">
                                <div class="card-header">
                                <?php echo !empty( $last_5_heights ) ? "Last 5 Days Height" : "No Height Data" ?>
                                <canvas id="height_chart"></canvas>
                                </div>
                          </div>
                          </div>

                          <div class="col-sm-12 col-md-6">

                          <div class="card">
                                <div class="card-header">
                                <?php echo !empty( $last_5_weights ) ? "Last 5 Days Weight" : "No Weight Data" ?>
                                <canvas id="weight_chart"></canvas>
  
                              </div>
                          </div>

                          </div>
                          </div>
                          <?php } ?>
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


    

  <script>
    // Generate random data for the last 5 days
    const height_data = {
        labels: <?php echo json_encode( $last_5_dates ); ?>,
        datasets: [{
        label: 'Last 5 Day Heights',
        data: <?php echo json_encode( $last_5_heights ); ?>,
        backgroundColor: 'blue',
        borderColor: 'blue',
        borderWidth: 1
        }]
    };

    const height_data_config = {
        type: 'line',
        data: height_data,
        options: {
        scales: {
            y: {
            beginAtZero: true
            }
        }
        
        }
    };

    const weight_data = {
        labels: <?php echo json_encode( $last_5_dates ); ?>,
        datasets: [{
        label: 'Last 5 Day Heights',
        data: <?php echo json_encode( $last_5_weights ); ?>,
        backgroundColor: 'blue',
        borderColor: 'blue',
        borderWidth: 1
        }]
    };

    const weight_data_config = {
        type: 'line',
        data: weight_data,
        options: {
        scales: {
            y: {
            beginAtZero: true
            }
        }
        
        }
    };

    var height_chart = new Chart(document.getElementById('height_chart'), height_data_config );
    var weight_chart = new Chart(document.getElementById('weight_chart'), weight_data_config );
    /*
    // Create the chart
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.map(entry => entry.date),
        datasets: [
          {
            label: 'Height',
            data: data.map(entry => entry.height),
            fill: false,
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2
          },
          {
            label: 'Weight',
            data: data.map(entry => entry.weight),
            fill: false,
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
          }
        ]
      },
      options: options
    });
    */
  </script>
</body>

</html>

<?php } else { header( 'Location: http://kidneyhealthmonitor.free.nf/pages/login' ); } ?>