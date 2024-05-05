<?php

if( isset( $_COOKIE['user_id'] ) && !empty( $_COOKIE['user_id'] ) ) {

  header( "Location: http://kidneyhealthmonitor.free.nf/doctors/pages/profile");
} else {
?>

<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/c/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <!--<title> Login and Registration Form in HTML & CSS | CodingLab </title>-->
    <!--<link rel="stylesheet" href="http://kidneyhealthmonitor.free.nf/doctors/pages/login/style.css">-->
    <link rel="stylesheet" href="http://kidneyhealthmonitor.free.nf/doctors/pages/login/doctor_login.css">
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
<body>
  <div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
      <div class="front">
        <img src="Images/frontImg.jpg" alt="">
        <div class="text">
          <span class="text-1">SIGN UP <br> NOW</span>
          <span class="text-2">Let's get connected</span>
        </div>
      </div>
      <div class="back">
        <img class="backImg" src="Images/backImg.jpg" alt="">
        <div class="text">
          <span class="text-1">Complete miles of journey <br> with one step</span>
          <span class="text-2">Let's get started</span>
        </div>
      </div>
    </div>
    <div class="forms">
        <div class="form-content">
          <div class="login-form">
            <div class="title">Doctor Login</div>
          <form id="login-form">
            <div class="input-boxes">
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input type="text" name="login_email" placeholder="Enter your email" required>
              </div>
              <div class="input-box">
                <i class="fas fa-lock"></i>
                <input type="password" name="login_password" placeholder="Enter your password" required>
              </div>
              <div class="text"><a href="#">Forgot password?</a></div>
              <?php if( isset( $_GET['result'] ) && $_GET['result'] == 0 ) { ?>
                  <div><p style="color:red">Invalid User / Password</div>
              <?php } ?>
              <div class="button input-box">
                <input type="submit" value="Sumbit" name="doctor_login_btn">
              </div>
              <div class="text sign-up-text">Don't have an account? <label for="flip">Sigup now</label></div>
            </div>
        </form>
      </div>
        <div class="signup-form">
          <div class="title">Doctor Signup</div>
        <form id = "doctor-sign-up-form">
            <div class="input-boxes">
              <div class="input-box">
                <i class="fas fa-user"></i>
                <input type="text" name="name" placeholder="Enter your name" required>
              </div>
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input type="text" name="email" placeholder="Enter your email" required>
              </div>
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input type="text" name="phone" placeholder="Enter your phone" required>
              </div>
              <div class="input-box">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Enter your password" required>
              </div>
              <?php if( isset( $_GET['s_result'] ) && $_GET['s_result'] == 0 ) { ?>
              <div>
                <p>Error Creating Account</p>
              </div>
              <?php } ?>
              <div class="button input-box">
                <input type="submit" value="Sumbit" name="doctor_signup_btn">
              </div>
              <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
            </div>
      </form>
    </div>
    </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>

    $(document).ready( function() {

      var sign_up_form = document.getElementById("doctor-sign-up-form");

      sign_up_form.addEventListener( 'submit', function(e) {

        e.preventDefault();

        var user_name = this.name.value;
        var user_email = this.email.value;
        var user_phone = this.phone.value;
        var user_password = this.password.value;

        $.ajax({
          url:"handlers/signup_handler.php",
          method:"POST",
          dataType: "json",
          data: {
            "name": user_name,
            "email": user_email,
            "phone": user_phone,
            "password": user_password
          },
          success: function( response ) {

            if( response.status == 1 ) {

              alert( response.message );
              window.location.href = 'http://kidneyhealthmonitor.free.nf/doctors/pages/profile';
            } else {

              alert( response.message );
            }
          },
          error: function( xhr, status, error ) {

            console.log("XHR object:", xhr);
        console.log("Status:", status);
        console.log("Error:", error);
            alert( 'Error Creating User Account' );
          }
        });
        
      });

      var login_form = document.getElementById( "login-form" );
      login_form.addEventListener( 'submit', function(e) {

      e.preventDefault();

      var login_email = this.login_email.value;
      var login_password = this.login_password.value;

      $.ajax({
        url:"handlers/login_handler.php",
        method:"POST",
        dataType: "json",
        data: {
          "login_email": login_email,
          "login_password": login_password,
        },
        success: function( response ) {

          if( response.status == 1 ) {

            alert( response.message );
            window.location.href = 'http://kidneyhealthmonitor.free.nf/doctors/pages/profile';
          } else {

            alert( response.message );
          }
        },
        error: function( xhr, status, error ) {

          console.log("XHR object:", xhr);
      console.log("Status:", status);
      console.log("Error:", error);
          alert( 'Login Error!' );
        }
      });

      });
    });
    
    
    </script>
</body>
</html>
<?php } ?>
