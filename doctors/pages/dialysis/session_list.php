<?php

ini_set( 'display_errors', 1 );

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

if( !empty( $user_id ) && !empty( $user_type ) ) {

    $patient_id = $_GET['patient_id'];

    if( !empty( $patient_id ) ) {

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
                            <li class="breadcrumb-item"><b>Dialysis Session List</b></li>
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
                        <div class="row">
                        <div class="col-md-12 mb-12">

                        <table id="data-table" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Before Weight</th>
                                    <th>After Weight</th>
                                    <th>BP 1</th>
                                    <th>BP 2</th>
                                    <th>BP 3</th>
                                    <th>Action</th>
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

    var user_type = "<?php echo $user_type ?>";
    var patient_id = "<?php echo $patient_id ?>";

    $('#data-table').DataTable({
            dom: 'Bfrtip',
            "ajax": {
                "url": "handlers/session_list_handler.php",
                "method": "POST",
                "dataSrc": function (response) {
                  
                // Return the DataTables data for DataTables
                return response.data;
            },
            "data":{ user_type : user_type, patient_id : patient_id },
            },
            "buttons": [
                    'excel'
                ],
            "columns": [
                { "data": "checked_date" },
                { "data": "checked_time" },
                { "data": "before_weight" },
                { "data": "after_weight" },
                { "data": "bp_data_1" },
                { "data": "bp_data_2" },
                { "data": "bp_data_3" },
                { "data": "id" }
                // Add more columns as needed
            ],
            columnDefs: [
            {
                targets: -1, // Target the last column
                render: function(data, type, row, meta) {
                  return '<a href="http://kidneyhealthmonitor.free.nf/nurses/pages/settings/complete_dialysis.php?edit='+data+'" class="btn btn-primary">Edit</a>';
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
  </script>
</body>

</html>

<?php } } else { header( 'Location: http://kidneyhealthmonitor.free.nf/nurses/pages/login' ); } ?>