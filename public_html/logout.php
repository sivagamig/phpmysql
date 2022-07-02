<?php 
session_start();
if(isset($_SESSION['logintrue']) && !empty($_SESSION['logintrue']))
{
	session_unset();
	session_destroy();
	header("Location:login.php");
}
else
{
	header("Location:login.php");
}