<?php 
session_start();
if(isset($_SESSION['logintrue']) && !empty($_SESSION['logintrue']))
{
	$token = $_SESSION['logintrue'];
	include("connect.php");
	$result = mysqli_query($con,"select username,mobile,state,gender from user where token='$token'");
	$row = mysqli_fetch_assoc($result);
	?>
	<html>
		<head>
			<title>Edit Profile | <?php echo ucwords($row['username']);?></title>
			<link rel="stylesheet" href="/styles.css" />
		</head>
		<body>
			<div class="container">
				<?php 
					include("menu.php");
				?>
				<h1>Edit profile | <?php echo ucwords($row['username']);?></h1>
				
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
				
				if(isset($_POST['update']))
				{
					$uname = (isset($_POST['uname'])) ? filterData($_POST['uname']) : '';
					$mobile = (isset($_POST['mobile'])) ? filterData($_POST['mobile']) : '';
					$gender = (isset($_POST['gender'])) ? filterData($_POST['gender']) : '';
					$state = (isset($_POST['state'])) ? filterData($_POST['state']) : '';
					
					if($uname === "")
					{
						$errors['uname'] = "Username is Required";
					}
					else{
						if(strlen($uname) <= 4 || strlen($uname) >=20)
						{
							$errors['uname'] = "Username should between 4 and 20 chars";
						}
					}
					
					if($gender === "")
					{
						$errors['gender'] = "Please select gender";
					}
					if($state === "")
					{
						$errors['state'] = "Please select state";
					}
					if($mobile === "")
				{
					$errors['mobile'] = "Mobile Required";
				}
				else{
					if(!filter_var($mobile, FILTER_VALIDATE_INT))
					{
						$errors['mobile']="Mobile should contains digits only";
					}
					if(strlen($mobile) !== 10)
					{
						$errors['mobile']="Mobile should contains 10 digits only";
					}
				}
					
					if(count($errors) === 0)
					{
						mysqli_query($con,"update user set username='$uname',mobile='$mobile', gender='$gender', state='$state' where token='$token'");
						if(mysqli_affected_rows($con)>0)
						{
							setcookie("success","Profile updated successfully",time()+3);
							header("Location: edit.php");
						}
						else{
							setcookie("error","Sorry! Unable to update profile, try again",time()+3);
							header("Location: edit.php");
						}
					}
				}
				
				?>
				
				<form method="POST" action="">
				<div class="formgroup">
					<label>Username</label>
					<input type="text" name="uname" class="formcontrol" value="<?php if(isset($_POST['uname'])) echo $_POST['uname'] ?><?php if(!isset($_POST['uname'])) echo $row['username']; ?>"/>
					<small class="error"><?php if(isset($errors['uname'])) echo $errors['uname']; ?></small>
				</div>
				
				<div class="formgroup">
					<label>Mobile</label>
					<input type="text" name="mobile" class="formcontrol" value="<?php if(isset($_POST['mobile'])) echo $_POST['mobile'] ?><?php if(!isset($_POST['mobile'])) echo $row['mobile']; ?>"/>
					<small class="error"><?php if(isset($errors['mobile'])) echo $errors['mobile']; ?></small>
				</div>
				<div class="formgroup">
					<label>Gender</label>
					<label><input type="radio" name="gender" value="Male"  <?php if(isset($_POST['gender'])) if($_POST['gender'] === "Male") echo "Checked" ?> <?php if($row['gender'] === "Male") echo "Checked";?> />Male</label>
					<label><input type="radio" name="gender" value="Female"  <?php if(isset($_POST['gender'])) if($_POST['gender'] === "Female") echo "Checked" ?> <?php if($row['gender'] === "Female") echo "Checked";?> />Female</label>
					<small class="error"><?php if(isset($errors['gender'])) echo $errors['gender']; ?></small>
				</div>
				<div class="formgroup">
					<label>State</label>
					<select name="state" class="formcontrol">
						<option value="">--Select State--</option>
						
						<option <?php if(isset($_POST['state'])) if($_POST['state'] === "Warwickshire") echo "selected"; ?>  <?php if($row['state'] === "Warwickshire") echo "selected";?> value="Warwickshire">Warwickshire</option>
						<option <?php if(isset($_POST['state'])) if($_POST['state'] === "Leicestershire") echo "selected"; ?> <?php if($row['state'] === "LeicestershirWarwickshiree") echo "selected";?> value="Leicestershire">Leicestershire</option>
						
						<option <?php if(isset($_POST['state'])) if($_POST['state'] === "Buckinghamshire") echo "selected"; ?> <?php if($row['state'] === "Buckinghamshire") echo "selected";?> value="Buckinghamshire">Buckinghamshire</option>
					</select>
					<small class="error"><?php if(isset($errors['state'])) echo $errors['state']; ?></small>
				</div>
				<div class="formgroup">
					<input type="submit" name="update" value="Update" class="btn" />
				</div>
			</form>
			</div>
		</body>
	</html>
	<?php
	mysqli_close($con);
}
else
{
	header("location: login.php");
}
?>