<?php 
session_start();
if(isset($_SESSION['logintrue']) && !empty($_SESSION['logintrue']))
{
	include("connect.php");
	$token = $_SESSION['logintrue'];
	$result = mysqli_query($con,"select username,profile_pic from user where token='$token'");
	$row = mysqli_fetch_assoc($result);
	?>
	<html>
		<head>
			<title>Welcome to <?php echo ucwords($row['username']); ?></title>
			<link rel="stylesheet" href="styles.css" />
		</head>
		<body>
			<div class="container">
				<?php include("menu.php");?>
				<h1>Upload Avatar | <?php echo ucwords($row['username']); ?></h1>
				
				<?php  
					if($row['profile_pic'] !== "")
					{
						?>
							<img src="profiles/<?php echo $row['profile_pic'];?>" height="100" width="100" />
						<?php
					}
				?>
				
				<?php 
				if(isset($_COOKIE['success']))
				{
					echo "<p>".$_COOKIE['success']."</p>";
				}
				if(isset($_COOKIE['error']))
				{
					echo "<p>".$_COOKIE['error']."</p>";
				}
				
				if(isset($_POST['update']))
				{
					if(is_uploaded_file($_FILES['avatar']['tmp_name']))
					{
						$filename = $_FILES['avatar']['name'];
						$filetype = $_FILES['avatar']['type'];
						$filesize = $_FILES['avatar']['size'];
						$tmppath = $_FILES['avatar']['tmp_name'];
						$allowed = ['png','jpg','jpeg','gif'];
						$ext = pathinfo($filename,PATHINFO_EXTENSION);
						if(in_array($ext,$allowed))
						{
							if(file_exists("profiles/".$filename))
							{
								$str = substr(str_shuffle("qwertyuioplkjhgfdsazxcvbnm".time()),5,16);
								$filename = $str."_".$filename;
							}
							
							if(move_uploaded_file($tmppath, "profiles/".$filename))
							{
								mysqli_query($con,"update user set profile_pic='$filename' where token='$token'");
								if(mysqli_affected_rows($con) > 0)
								{
									setcookie("success","Avatar uploaded successfully", time()+3);
									header("Location:avatar.php");
								}
								else
								{
									setcookie("error","Sorry! Unable to update avatar", time()+3);
									header("Location:avatar.php");
								}
							}
							else
							{
								echo "<p>Sorry! Unable to upload a file</p>";
							}
							
						}
						else
						{
							echo "<p class='error'>Please select an valid image to upload</p>";
						}
					}
					else
					{
						echo "<p class='error'>Please select an image to upload</p>";
					}
				}
				?>
				
				<form method="POST" action="" enctype="multipart/form-data">
					<div class="formgroup">
						<label>Please select an Image to upload</label>
						<input type="file" name="avatar" class="formcontrol" style="padding: 6px;" />
						<small>Note: Please upload .jpg, .png, .jpeg files only</small>
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