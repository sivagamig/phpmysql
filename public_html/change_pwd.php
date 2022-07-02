<?php 
session_start();
if(isset($_SESSION['logintrue']) && !empty($_SESSION['logintrue']))
{
	include("connect.php");
	$token = $_SESSION['logintrue'];
	$result = mysqli_query($con,"select username, password from user where token='$token'");
	$row = mysqli_fetch_assoc($result);
	?>
	<html>
		<head>
			<title>Welcome to <?php echo ucwords($row['username']); ?></title>
			<link rel="stylesheet" href="/styles.css" />
		</head>
		<body>
			<div class="container">
				<?php include("menu.php");?>
				<h1>Change Password | <?php echo ucwords($row['username']); ?></h1>
				
				<?php 
				$errors = [];
				if(isset($_COOKIE['success']))
				{
					echo "<p>".$_COOKIE['success']."</p>";
				}
				
				if(isset($_COOKIE['error']))
				{
					echo "<p>".$_COOKIE['error']."</p>";
				}
				
				function filterData($data)
				{
					return addslashes(trim(strip_tags($data)));
				}
				
				if(isset($_POST['update'])){
					
					$opwd = (isset($_POST['opwd'])) ? filterData($_POST['opwd']) : '';
					$npwd = (isset($_POST['npwd'])) ? filterData($_POST['npwd']) : '';
					$cnpwd = (isset($_POST['cnpwd'])) ? filterData($_POST['cnpwd']) : '';
					
					// password validation
					
					if($opwd === "")
					{
						$errors['opwd'] = "Old Password Required";
					}
					if(!password_verify($opwd, $row['password']))
					{
						$errors['opwd'] = "Old Password does not matched with DB password";
					}
					
					
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
							setcookie("success","Password updated successfully",time()+3);
							header("Location: change_pwd.php");
							//header("Location: logout.php");
						}
						else
						{
							setcookie("error","Unable to update password, try again",time()+3);
							header("Location: change_pwd.php");
						}
					}
					
				} ?>
				
				<form method="POST" action="">
					<div class="formgroup">
						<label>Enter Old Password</label>
						<input type="password" name="opwd" value="<?php if(isset($_POST['opwd'])) echo $_POST['opwd']; ?>" class="formcontrol" />
						<small class="error"><?php if(isset($errors['opwd'])) echo $errors['opwd']; ?></small>
					</div>
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
	header("Location: login.php");
}