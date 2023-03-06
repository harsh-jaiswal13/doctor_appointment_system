<?php
session_start();
$_SESSION['status']=NULL;
?>

<html>
<head>
  <title>Sign Up Form</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>

  $(document).ready(function() {

      $('#signup-form-doctor').hide();  
      $('#signup-form-patients').hide();  

      $(document).on("click", "#signup_as_patients", function(){
        $('#signup-form-patients').show();  
        $('#signup-form-doctor').hide();
      });

      $(document).on("click", "#signup_as_doctor", function(){
        $('#signup-form-patients').hide();  
        $('#signup-form-doctor').show();
      });
  


      // MAKE AJAX REQUEST FOR SIGN UP THE USER
      $('#signup-form-doctor').submit(function() {
        event.preventDefault();
        var ds={
          data:$('#signup-form-doctor').serialize(),
          usertype:"doctor"
        };

        $.ajax({
              url: '../controller/core.php',
              type: 'post',
              data: ds,
              datatype : "json",
              success: function(response) {
                console.log($('#signup-form-doctor').serialize());
                console.log(typeof(response));
                result = JSON.parse(response);

                if (result.success == 'TRUE') {
                  document.getElementById("signup-form").reset();
                  alert('Thank you for signing up!');
                } 
                else {
                  $('#error').html(result.message);
                }
              }
            });
        });
              
        
        $('#signup-form-patients').submit(function() {
        event.preventDefault();

        var ds={
          data:$('#signup-form-patients').serialize(),
          usertype:"patient"
        };  

          $.ajax({
                url: '../controller/core.php',
                type: 'post',
                data: ds,
                datatype : "json",
                success: function(response) {
                  console.log($('#signup-form-doctor').serialize());
                  console.log(typeof(response));
                  result = JSON.parse(response);
                  if (result.success == 'TRUE') {
                    document.getElementById("signup-form").reset();
                    alert('Thank you for signing up!');
                  } 
                  else {
                    $('#error').html(result.message);
                  }
                }
            });
        });
});
              
     
        
        
  </script>
</head>
<body>

<h1>Sign Up Form</h1>

<button id="signup_as_patients" type="button">signup as patient</button>
<button id="signup_as_doctor" type="button">signup as doctor</button>



<form id="signup-form-doctor"  method="post">


    <label for="name">fname:</label>
    <input type="text" id="fname" name="fname"><br>
    
    <label for="name">lname:</label>
    <input type="text" id="lname" name="lname"><br>
    
    <label for="speciality">speciality</label>
    <input type="text" id="speciality" name="speciality"><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br>
    
    <label for="confirm-password">Confirm Password:</label>
    <input type="password" id="confirm-password" name="confirm_password"><br>
    
    <label for="contact">contact</label>
    <input type="text" id="contact" name="contact"><br>
    
    </div>
       <input type="submit" value="Sign Up">
  </form>

<form id="signup-form-patients" method="post">
  <label for="fname">First Name:</label>
  <input type="text" id="p_fname" name="fname"><br>

  <label for="lname">Last Name:</label>
  <input type="text" id="p_lname" name="lname"><br>

  <label for="email">Email:</label>
  <input type="email" id="p_email" name="email"><br>

  <label for="password">Password:</label>
  <input type="password" id="p_password" name="password"><br>

  <label for="age">Age:</label>
  <input type="number" id="p_age" name="age"><br>

  <label for="contact">Contact:</label>
  <input type="tel" id="p_contact" name="contact"><br>

  <input type="submit" value="Submit">
</form>


  <a href="../view/login.php">login</a>

  <div id="error"></div>
</body>
</html>
