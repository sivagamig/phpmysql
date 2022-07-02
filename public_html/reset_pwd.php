<?php 

if(isset($_REQUEST['token']) && !empty($_REQUEST['token']))
{
	$token = $_REQUEST['token'];
	include("connect.php");
	
	$result = mysqli_query($con,"select username from user where token='$token'");
	if(mysqli_num_rows($result)==1)
	{
		?>
			<html>
				<head>
					<title>Reset Password</title>
					<link rel="stylesheet" href="/styles.css" />
				</head>
				<body>
					<div class="container">
						<h1>Reset Password</h1>
						
						<?php 
							$errors = [];
							function filterData($data)
							{
								return addslashes(trim(strip_tags($data)));
							}
							
							if(isset($_POST['update'])){
								
								
								$npwd = (isset($_POST['npwd'])) ? filterData($_POST['npwd']) : '';
								$cnpwd = (isset($_POST['cnpwd'])) ? filterData($_POST['cnpwd']) : '';
								
								// password validation
								
								if($npwd === "")
								{
									$errors['npwd'] = "New Password Required";
								}
								else{
									
									$pattern = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/';
									if(!preg_match($pattern, $npwd))
									{
										$errors['npwd'] = "Password Must be a minimum of 8 characters,1 number,1 uppercase character,1 lowercase character";
									}
								}
								if($cnpwd === "")
								{
									$errors['cnpwd'] = "New Password Required";
								}
								
								if($npwd !== $cnpwd)
								{
									$errors['cnpwd'] = "New Password & confirm new password does not matched";
								}
								
								if(count($errors) === 0)
								{
									$hash_pwd = password_hash($npwd, PASSWORD_DEFAULT);
									mysqli_query($con,"update user set password='$hash_pwd' where token='$token'");
									if(mysqli_affected_rows($con)>0)
									{
										setcookie("success","Password updated successfully. Please Login",time()+3);
										header("Location: login.php");
										//header("Location: logout.php");
									}
									else
									{
										setcookie("error","Unable to update password, try again",time()+3);
										header("Location: login.php");
									}
								}
								
							} 
						?>
						
						<form method="POST" action="">
					
							<div class="formgroup">
								<label>New Password</label>
								<input type="password" name="npwd" value="<?php if(isset($_POST['npwd'])) echo $_POST['npwd']; ?>" class="formcontrol" />
								<small class="error"><?php if(isset($errors['npwd'])) echo $errors['npwd']; ?></small>
							</div>
							<div class="formgroup">
								<label>Confirm New Password</label>
								<input type="password" name="cnpwd" value="<?php if(isset($_POST['cnpwd'])) echo $_POST['cnpwd']; ?>" class="formcontrol" />
								<small class="error"><?php if(isset($errors['cnpwd'])) echo $errors['cnpwd']; ?></small>
							</div>
							<div class="formgroup">
								<input type="submit" name="update" value="Update" class="btn" />
							</div>
						</form>
					</div>
				</body>
			</html>
		<?php
	}
	else
	{
		echo "<p>Sorry! Unable to find your account</p>";
	}
	
	mysqli_close($con);
}
else
{
	echo "<p class='error'>Sorry! Unauthourised access....</p>";
}

?>