<?php 
class Employee
{
	private $con = null;
	function __construct()
	{
		$this->con =mysqli_connect("153.92.7.101","u624541055_sivasql","Siva123$","u624541055_register");
	}
	function __destruct()
	{
		mysqli_close($this->con);
	}
	function getAllEmployee()
	{
		$result = mysqli_query($this->con,"select id,username,email,mobile,gender,state from user");
		$data = [];
		if(mysqli_num_rows($result)>0)
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$data[] = $row;
			}
			
			return $data;
		}
		else
		{
			return $data;
		}
	}
	
	
	function getEmpInfo($id)
	{
		
		$result = mysqli_query($this->con,"select id,username,email,mobile,gender,state from user where id=$id");
		$data = [];
		if(mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_assoc($result);
			return $row;
		}
		else
		{
			return $data;
		}
	}
}

if(isset($_REQUEST['token']) && !empty($_REQUEST['token']))
{
	$token = $_REQUEST['token'];
	$obj = new Employee();
	
	
	if($token == "all")
	{
		echo json_encode($obj->getAllEmployee());
	}
	
	else if($token == "individual")
	{
		if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			echo json_encode($obj->getEmpInfo($id));
		}
		else
		{
			echo json_encode(["error"=>"Sorry! Invalid URL parameter"]);
		}
	}
	else
	{
		echo json_encode(["error"=>"Sorry! Invalid URL parameter"]);
	}
}
?>
