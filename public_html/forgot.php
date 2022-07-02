<?php include("connect.php");?>
<html>
	<head>
		<title>Forgot Password</title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<div class="container">
			<h1>Forgot Password</h1>
			
			<?php 
			if(isset($_COOKIE['success']))
			{
				echo "<p>".$_COOKIE['success']."</p>";
			}
			
			if(isset($_COOKIE['error']))
			{
				echo "<p>".$_COOKIE['error']."</p>";
			}
			$errors = [];
			function filterData($data)
			{
				return addslashes(trim(strip_tags($data)));
			}
			if(isset($_POST['submit']))
			{
				$email = (isset($_POST['email'])) ? filterData($_POST['email']) : '';
				
				//Email Validation
				if($email === "")
				{
					$errors['email'] = "Email Required";
				}
				else 
				{
					if(!filter_var($email, FILTER_VALIDATE_EMAIL))
					{
						$errors['email'] = "Enter a valid email";
					}
				}
				
				if(count($errors) === 0)
				{
					$result = mysqli_query($con, "select username,token from user where email='$email'");
					if(mysqli_num_rows($result)===1)
					{
						$row = mysqli_fetch_assoc($result);
						$token = $row['token'];
						$to = $email;
						$subjects = "Reset password Request";
						$message = "Hi ".ucwords($row['username']).", <br><br> Your reset password request has been received. please click the below link to reset your password.<br><br>
						<a href='https://sivagami.co.uk/reset_pwd.php?token=".$token."' target='_blank'>Reset Password</a><br><br>Thanks<br>Team";
						$headers = 'Content-Type:text/html'."\r\n".'From: webmaster@example.com';
						
						
						if(mail($to, $subject, $message, $headers))
						{
							setcookie("success","Thanks, Reset Password link sent to your registered email. please check", time()+3);
							header("Location: forgot.php");
						}
						else
						{
							setcookie("success","Sorry! Unable to sent an email, contact admin", time()+3);
							header("Location: forgot.php");
						}
						
					}
					else
					{
						echo "<p class='error'>Sorry! Unbale to find your account</p>";
					}
				}
			}
			?>
			
			<form method="POST" action="">
			
				<div class="formgroup">
					<label>Email</label>
					<input type="text" name="email" class="formcontrol" value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>"/>
					<small class="error"><?php if(isset($errors['email'])) echo $errors['email']; ?></small>
				</div>
				
				
				<div class="formgroup">
					<input type="submit" name="submit" value="Submit" class="btn" />
					<a href='login.php'>Login </a> | <a href="register.php">Create an Account</a>
					
				</div>
			</form>
		</div>
	</body>
</html>
<?php mysqli_close($con);?>