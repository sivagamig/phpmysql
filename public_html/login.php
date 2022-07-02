<?php 
session_start();
//echo "token----".$_SESSION['logintrue'];

/*if(isset($_SESSION['logintrue']))
{
	header("Location: home.php");
}*/
include("connect.php");?>
<html>
	<head>
		<title>Login  Here</title>
		<link rel="stylesheet"  href="/styles.css" /> 
	</head>
	<body>
		<div class="container">
			<h1>Login Here</h1>
			<?php 
				$errors = [];
				
				
				
				function filterData($data)
				{
					return addslashes(trim(strip_tags($data)));
				}
				if(isset($_POST['login']))
				{
					
					$email = (isset($_POST['email'])) ? filterData($_POST['email']) : '';
					$pass = (isset($_POST['pass'])) ? filterData($_POST['pass']) : '';
					
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
					
					// password validation
					if($pass === "")
					{
						$errors['pass'] = "Password Required";
					}
					
					
					
					// inserting the adta
					if(count($errors)===0)
					{
						$result = mysqli_query($con,"select token, password, status from user where email='$email'");
						if(mysqli_num_rows($result)===1)
						{
							$row = mysqli_fetch_assoc($result);
							
							if(password_verify($pass,$row['password']))
							{
								if($row['status'] === "active")
								{
									$_SESSION['logintrue'] = $row['token'];
									header("Location:home.php");
								}
								else
								{
									echo "<p>Sorry, Unable to login, Please activate your account</p>";
								}
							}
							else
							{
								echo "<p>Password does not matched</p>";
							}
							
						}
						else
						{
							echo "<p>Sorry! Unable to find your account</p>";
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
					<label>Password</label>
					<input type="password" name="pass" class="formcontrol" value="<?php if(isset($_POST['pass'])) echo $_POST['pass'] ?>"/>
					<small class="error"><?php if(isset($errors['pass'])) echo $errors['pass']; ?></small>
				</div>
				
				<div class="formgroup">
					<input type="submit" name="login" value="Login" class="btn" />
					<a href='forgot.php'>Forgot Password ?</a> | <a href="register1.php">Create an Account</a>
					
				</div>
			</form>
		</div>
	</body>
</html>
<?php mysqli_close($con);?>