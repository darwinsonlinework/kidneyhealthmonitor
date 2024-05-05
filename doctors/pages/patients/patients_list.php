<?php

//require_once ( "../header.html" );
//require_once ( "../layout.php" );

require_once ( "../../../DbConnect.php" );

use DbConnect\DbConnect;
//use PDO;

//echo phpinfo();
//die;


if( !empty( $_COOKIE['user_id'] ) && $_COOKIE['user_type'] == 'doctor' ) {
    
  $user_id = $_COOKIE['user_id'];
  $user_type = $_COOKIE['user_type'];

}

if( !empty( $user_id ) ) {


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
    <title>Chronic Kidney Disease - Patient List | Comprehensive Kidney Monitoring Tool | Dark Hand Coders</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://kidneyhealthmonitor.free.nf/assets/css/sidebar.css" rel="stylesheet">
    <link href="http://kidneyhealthmonitor.free.nf/assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.7/css/boxicons.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<!-- DataTables Buttons extension CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

    
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
                            <li class="breadcrumb-item"><b>Patient List</b></li>
                          </ol>
                        </nav>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-12">
                        <div class="col-lg-12">
                        <table>
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b>Doctor Name: </b><?php echo !empty($user_name) ? $user_name : '-'; ?></td>
                                    <td><b>Doctor Name: </b><?php echo !empty($user_name) ? $user_name : '-'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <!--
                            <div class="row">
                              <div class="col-sm-12">
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
                        `-->
                        <div class="row">
                        <div class="col-md-12 mb-12">

                        <table id="data-table" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>User Email</th>
                                    <th>User Phone</th>
                                    <th>Profile</th>
                                    <th>Reports</th>
                                    <th>Dialysis Sessions</th>
                                    <!-- Add more columns as needed -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table body content will be loaded dynamically -->
                            </tbody>
                        </table>

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

    <!-- DataTables -->
 <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Bootstrap 5 Integration -->
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Buttons JavaScript -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <!-- DataTables Buttons extension -->
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
  
    

  <script>

    var user_type = "<?php echo $user_type ?>"

    $('#data-table').DataTable({
            dom: 'Bfrtip',
            "ajax": {
                "url": "handlers/patients_list.php",
                "method": "POST",
                "dataSrc": function (response) {
                  
                // Return the DataTables data for DataTables
                return response.data;
            },
            "data":{ user_type : user_type },
            },
            "buttons": [
                    'excel'
                ],
            "columns": [
                { "data": "id" },
                { "data": "user_name" },
                { "data": "user_email" },
                { "data": "user_phone" },
                { "data": "id" },
                { "data": "id" },
                { "data": "id" },
                // Add more columns as needed
            ],
            columnDefs: [
            {
                targets: -3, // Target the last column
                render: function(data, type, row, meta) {
                  return '<a href="http://kidneyhealthmonitor.free.nf/doctors/pages/view?user_id='+data+'" class="btn btn-primary">View Profile</a>';
                }
            },
            {
                targets: -2, // Target the last column
                render: function(data, type, row, meta) {
                  return '<a href="http://kidneyhealthmonitor.free.nf/doctors/pages/reports/all.php?user_id='+data+'" class="btn btn-primary">View Reports</a>';
                }
            },
            {
                targets: -1, // Target the last column
                render: function(data, type, row, meta) {
                  return '<a href="http://kidneyhealthmonitor.free.nf/doctors/pages/dialysis/session_list.php?patient_id='+data+'" class="btn btn-primary">Dialysis List</a>';
                }
            }
        ],
            "order": [[1, 'desc']],
            "searching": true,
            "paging": true,
            "error": function(xhr, errorType, exception) {
                console.error("AJAX error: " + errorType + " - " + exception);
                // You can also display an alert here if you prefer
            }
        });
    /*
    // Generate random data for the last 5 days
    const height_data = {
        labels: <?php //echo json_encode( $last_5_dates ); ?>,
        datasets: [{
        label: 'Last 5 Day Heights',
        data: <?php //echo json_encode( $last_5_heights ); ?>,
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
        labels: <?php //echo json_encode( $last_5_dates ); ?>,
        datasets: [{
        label: 'Last 5 Day Heights',
        data: <?php //echo json_encode( $last_5_weights ); ?>,
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

<?php } else { header( 'Location: http://kidneyhealthmonitor.free.nf/doctors/pages/login' ); } ?>