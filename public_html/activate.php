<?php 

if(isset($_REQUEST['token']) && !empty($_REQUEST['token']))
{
	include("connect.php");
	$token = $_REQUEST['token'];
//	echo "token".$token;
	$result = mysqli_query($con,"select username,status from user where token='$token'");
//	echo mysqli_error($con);
	if(mysqli_num_rows($result)==1)
	{
		$row = mysqli_fetch_assoc($result);
		
		  //  echo $row['status'];
		if($row['status'] === "inactive")
		
		{
		    
			mysqli_query($con,"update user set status ='active' where token='$token'");
			if(mysqli_affected_rows($con)>0)
			{
				echo "<p><h1>Account activated successfully. please <a href='login.php'>login</a></h1> </p>";
			}
			else
			{
				echo "<p><h1>Sorry! Unable to activate your account. please contact admin</h1></p>";
			}
		}
		else if($row['status'] === "active"){
			echo "<p><h1>Your account is already activated. please <a href='login.php'>login</a></h1></p>";
		}
	}
	else
	{
		echo "<p><h1>Sorry! Unable to find your account</h1></p>";
	}
	
	
	mysqli_close($con);
}
else
{
	echo "<p>UnAuthourised Access...</p>";
}


?>