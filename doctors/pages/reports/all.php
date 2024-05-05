<?php

require_once( "../../../DbConnect.php" );

use DbConnect\DbConnect;

$doctor_id = NULL;

if( isset( $_COOKIE['user_id'] ) && !empty( $_COOKIE['user_id'] ) && $_COOKIE['user_type'] == "doctor" ) {

    $user_id = $_COOKIE['user_id'];

    if( isset( $_GET['user_id'] ) && !empty( $_GET['user_id'] ) ) {

        $u_user_id = $_GET['user_id'];

    } else {

        echo 'Please proveide valid Patient Id to see report';
        die;
    }
} else {

    header( "Location: http://kidneyhealthmonitor.free.nf/doctors/pages/login" );
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dark Hand Coders | Kidney Health Monitor | BP Readings</title>
    
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include( '../layouts/sidebar.php' ); ?>
            <!-- Content -->
            <div class="col main-content">
                <!-- Header -->
                <?php include( '../layouts/header.php' ); ?>
                <!-- Body -->
                <div class="content">
                <div class="container">
                    <h2 class="mt-5">BP Readings</h2>
                        <table id="bp-data-table" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Checked Date</th>
                                    <th>Checked Time</th>
                                    <th>Systolic</th>
                                    <th>Diastolic</th>
                                    <!-- Add more columns as needed -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table body content will be loaded dynamically -->
                            </tbody>
                        </table>
                    <!-- Add download link for Excel file -->
                    <!--<a href="export.php" class="btn btn-primary">Download as Excel</a> -->
                </div>

                <div class="container">
                    <h2 class="mb-5">urine Readings</h2>

                    <table id="urine-data-table" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Checked Date</th>
                                    <th>Checked Time</th>
                                    <th>Quantity</th>
                                    <!-- Add more columns as needed -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table body content will be loaded dynamically -->
                            </tbody>
                        </table>
                </div>

                <div class="container">

                    <h2 class="mb-5">Blood Tests</h2>

                    <table id="blood-test-data-table" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Checked Date</th>
                                        <th>Checked Time</th>
                                        <th>Creatine</th>
                                        <th>Urea</th>
                                        <th>Phosphorous</th>
                                        <!-- Add more columns as needed -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Table body content will be loaded dynamically -->
                                </tbody>
                            </table>

                    </div>
                
                <div class="container">
                    <div class="row">
                        <div id="chart-container" class="col-sm-12">
                        <canvas id="myChart" class="report-pie-chart" width="150 !important" height="150 !important"></canvas>
                        </div>
                    </div>
                </div>
                </div>
                <!-- Footer -->
                <?php include( '../layouts/footer.php' ); ?>
            </div>
        </div>
    </div>



<!-- Bootstrap Bundle with Popper -->
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>-->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <!-- DataTables Buttons extension -->
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>

    

    
    <script>

        
    </script>
    <script>
        $(document).ready(function() {

            var u_user_id = <?php echo $u_user_id ?>

            function destroy_chart(chart_id) {
            
                var canvas = document.getElementById(chart_id);

                console.log( canvas );
            if (canvas && canvas != null ) {
                // Remove the canvas element from the DOM
                canvas.parentNode.removeChild(canvas);
                //canvas.parentNode.innerHTML = '';
            }
        }


            function createPieChart(data) {

                console.dir( 'DDD Dir: ' + data );
                // Create pie chart
                var canva_present = document.getElementById( "myChart" );

                console.log( 'Alreadyy: ' + canva_present );
                if( canva_present == '' || canva_present == null ) {

                    var canvas = document.createElement('canvas');
                canvas.id = 'myChart';
                canvas.class= 'report-pie-chart';
                canvas.style = 'style="display: block; box-sizing: border-box; height: 1296px; width: 1296px;'
                canvas.width="2592";
                canvas.height="2592";

                // Append canvas to a container element in the DOM
                document.getElementById('chart-container').appendChild(canvas);
                }
                

                var ctx = document.getElementById('myChart').getContext('2d');
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.map(function(item) { return item.label; }),
                        datasets: [{
                            data: data.map(function(item) { return item.value; }),
                            backgroundColor: ["#ff6384", "#36a2eb", "#ffce56"] // Example colors
                        }]
                    },
                    options: {
                        responsive: true,
                        legend: {
                            position: 'right',
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var dataset = data.datasets[tooltipItem.datasetIndex];
                                    var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                        return previousValue + currentValue;
                                    });
                                    var currentValue = dataset.data[tooltipItem.index];
                                    var percentage = Math.floor(((currentValue / total) * 100) + 0.5);  
                                    return percentage + "%";
                                }
                            }
                        }
                    }
                });
                }

                $('#blood-test-data-table').DataTable({
            
            dom: 'Bfrtip',
            "ajax": {
                "url": "handlers/blood_test_report_handler.php",
                "method": "POST",
                "data": { user_id : u_user_id },
                "Content-Type": "json",
                "success": function( response ) {

                    console.dir( 'Urine: ' + JSON.stringify( response) );
                }
                /*
                "dataSrc": function (response) {
                // Access percentage values
                var highPercentage = response.percentages.high_bp_percentage;
                var normalPercentage = response.percentages.normal_bp_percentage;
                var lowPercentage = response.percentages.low_bp_percentage;

                // Update or use the percentage values as needed
                console.log("High BP Percentage: " + highPercentage);
                console.log("Normal BP Percentage: " + normalPercentage);
                console.log("Low BP Percentage: " + lowPercentage);

                // Call function to create pie chart
                var pieChartData = [
                    { label: "High BP", value: highPercentage },
                    { label: "Normal BP", value: normalPercentage },
                    { label: "Low BP", value: lowPercentage }
                ];
                createPieChart(pieChartData);

                // Return the DataTables data for DataTables
                return response.data;
            }
            */
            
            },
            "buttons": [
                    'excel'
                ],
            "columns": [
                { "data": "id" },
                { "data": "checked_date" },
                { "data": "checked_time" },
                { "data": "creatine" },
                { "data": "urea" },
                { "data": "phosphorous" }
                // Add more columns as needed
            ],
            "order": [[1, 'desc']],
            "searching": true,
            "paging": true,
            "error": function(xhr, errorType, exception) {
                console.error("AJAX error: " + errorType + " - " + exception);
                // You can also display an alert here if you prefer
            }
        });


            $('#bp-data-table').DataTable({


            dom: 'Bfrtip',
            "ajax": {
                "url": "handlers/bp_report_handler.php",
                "method": "POST",
                "data": { user_id : u_user_id },
                "Content-Type": "json"
            },
            "buttons": [
                    'excel'
                ],
            "columns": [
                { "data": "id" },
                { "data": "checked_date" },
                { "data": "checked_time" },
                { "data": "systolic" },
                { "data": "diastolic" }
                // Add more columns as needed
            ],
            "order": [[1, 'desc']],
            "searching": true,
            "paging": true,
            "error": function(xhr, errorType, exception) {
                console.error("AJAX error: " + errorType + " - " + exception);
                // You can also display an alert here if you prefer
                alert( exception );
            },
            success: function(response) {
            console.log("Response from backend:", response); // Log the response
            }
        });

        /* Urine table */

        $('#urine-data-table').DataTable({

        dom: 'Bfrtip',
        "ajax": {
            
            "url": "handlers/urine_report_handler.php",
            "method": "POST",
            "data": { user_id : u_user_id },
            "Content-Type": "json"
        },
        "buttons": [
                'excel'
            ],
        "columns": [
            { "data": "id" },
            { "data": "checked_date" },
            { "data": "checked_time" },
            { "data": "quantity" }
            // Add more columns as needed
        ],
        "order": [[1, 'desc']],
        "searching": true,
        "paging": true,
        "error": function(xhr, errorType, exception) {
            console.error("AJAX error: " + errorType + " - " + exception);
            // You can also display an alert here if you prefer
        }
        });

        /** Blood test Table */


    });

    </script>
    
 </body>
</html>