<?php 
session_start();
//echo "token=====".$_SESSION['logintrue'];
if(isset($_SESSION['logintrue']) && !empty($_SESSION['logintrue']))
{
	include("connect.php");
//	echo "token".$_SESSION['logintrue'];
	$token = $_SESSION['logintrue'];
	$result = mysqli_query($con,"select * from user where token='$token'");
	$row = mysqli_fetch_assoc($result);
//	echo"siva". $row[username];
	?>
	<html>
		<head>
			<title>Welcome to <?php echo ucwords($row['username']); ?></title>
			<link rel="stylesheet"  href="/styles.css" />
		</head>
		<body>
			<div class="container">
				<?php include("menu.php");?>
				<h1>Welcome to <?php echo ucwords($row['username']); ?></h1>
			
			<table id="customers" border="1">
				<tr>
					<td>Id</td>
					<td><?php echo $row['id'];?></td>
				</tr>
				<tr>
					<td>Username</td>
					<td><?php echo $row['username'];?></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><?php echo $row['email'];?></td>
				</tr>
				<tr>
					<td>Mobile</td>
					<td><?php echo $row['mobile'];?></td>
				</tr>
				<tr>
					<td>State</td>
					<td><?php echo $row['state'];?></td>
				</tr>
				<tr>
					<td>Gender</td>
					<td><?php echo $row['gender'];?></td>
				</tr>
				<tr>
					<td>Loggedin Ip</td>
					<td><?php echo $_SERVER['REMOTE_ADDR'];?></td>
				</tr>
				<tr>
					<td>Account Created</td>
					<td><?php echo $row['created_at'];?></td>
				</tr>
			</table>
			</div>
		</body>
	</html>
	<?php
	mysqli_close($con);
}
else
{
	header("Location: login.php");
}
?>