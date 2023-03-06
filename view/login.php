<!-- @author: Harsh Jaiswal  
@Purpose: This file html for the user and the all the ajax request for the           functionalities of admin
@Date:25 Feb 2023 -->
<?php
session_start();
$_SESSION['status']='login';

?>
<!DOCTYPE html>
<html>
<head>

	<title>Login</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
	<script>
		$(document).ready(function(e){
			e.preventDefault;
			//LOGIN
			$("#Login").click(function(){
			event.preventDefault();
			$.ajax({
				url: '../controller/core.php',
				type: 'POST',
				data: $('#LoginForm').serialize(),
				datatype : "json",
				success: function(response){
					result = JSON.parse(response);
                    console.log(result);

					if(result.success==1){
						if(result.usertype==1){
							$.ajax({
								url: '../routes/redirect_doctor_page.php',
								type:"POST",
								data:result,
								datatype : "json",
								success: function(data) {
									window.location = data;
								}
							});
						}
						else{$.ajax({
								url: '../routes/redirect_user_home.php',
								type:"POST",
								data:result,
								datatype : "json",
								success: function(data) {
									window.location = data;
								}
							});
						}	
					}		
					else{
					$('#LoginResult').html("Invalid login credentials ");
					}
				}
			});	
		});
	});
	</script>
	<h2>Login as</h2>
	<form id="LoginForm" method="post" >
        <!-- <label>
        <input type="radio" name="usertype" value="0" >patient</label>
        <label>

        <input type="radio" name="usertype" value="1" >Doctor<br><br></label>
        <label>     -->
		<label>Username/email:</label>
		<input type="email" name="email" required><br><br>
		<label>Password:</label>
		<input type="password" name="password" required><br><br>
	</form>


	<div >
		<button id="Login">Login</button>
		<div id="LoginResult"></div> 
	</div>
</body>
</html>

	