<?php include("connect.php");?>
<html>
	<head>
		<title>Register Here</title>
		<link rel="stylesheet" href="/styles.css" />
	</head>
	<body>
		<div class="container">
			<h1>Register Here</h1>
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
				if(isset($_POST['register1']))
				{
					$uname = (isset($_POST['uname'])) ? filterData($_POST['uname']) : '';
					$email = (isset($_POST['email'])) ? filterData($_POST['email']) : '';
					$pass = (isset($_POST['pass'])) ? filterData($_POST['pass']) : '';
					$cpass = (isset($_POST['cpass'])) ? filterData($_POST['cpass']) : '';
					$mobile = (isset($_POST['mobile'])) ? filterData($_POST['mobile']) : '';
					$gender = (isset($_POST['gender'])) ? filterData($_POST['gender']) : '';
					$state = (isset($_POST['state'])) ? filterData($_POST['state']) : '';
					
					$hashpass = password_hash($pass,PASSWORD_DEFAULT);
					$ip = $_SERVER['REMOTE_ADDR'];
					$token = md5(str_shuffle($uname.$email.$mobile.time()));
					
					// username validation
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
					// password validation
					if($pass === "")
					{
						$errors['pass'] = "Password Required";
					}
					else{
						
						$pattern = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/';
						if(!preg_match($pattern, $pass))
						{
							$errors['pass'] = "Password Must be a minimum of 8 characters,1 number,1 uppercase character,1 lowercase character";
						}
					}
					if($cpass === "")
					{
						$errors['cpass'] = "Confirm Password Required";
					}
					
					if($pass !== $cpass)
					{
						$errors['cpass'] = "Password does not macthed";
					}
					
					if($gender === "")
					{
						$errors['gender'] = "Please select gender";
					}
					
					if($state === "")
					{
						$errors['state'] = "Please select County";
					}
					
					
					$data = mysqli_query($con,"select email from user where email='$email'");
					if(mysqli_num_rows($data)===1)
					{
					    $errors['email'] = "Email is already taken";
					}
					
					// inserting the adta
					if(count($errors)===0)
					{
						mysqli_query($con,"INSERT INTO user(username,email,password,mobile,gender,state,ip,token) VALUES('$uname','$email','$hashpass','$mobile','$gender','$state','$ip','$token')");
						if(mysqli_affected_rows($con)>0)
						{
							// send an email to the registered user
							
							$subject = "Account Activation Process";
							$message = "Hi ".$uname.", <br> Thanks for creating an account withus, please click the below link to activate your account<br /> 
							<a href='https://sivagami.co.uk/activate.php?token=".$token."' target='_blank'>Click Here</a><br /><br/> Thanks<br>Team";
							$headers = 'Content-Type:text/html'."\r\n".'From: webmaster@example.com';
							
							if(mail($email,$subject,$message,$headers))
							{
								header("location:register1.php");
								setcookie("success","Account created successfully, Please activate your account",time()+3);
							}
							else
							{
								header("location:register1.php");
								setcookie("error","Account created successfully, Unable to sent an email, Contact admin",time()+3);
							}
							
						}
						else
						{
							header("location:register1.php");
							setcookie("error","Sorry! Unable to create an account",time()+3);
						}
					}
					
					
					
					
					
				}
			?>
			<form method="POST" action="">
				<div class="formgroup">
					<label>Username</label>
					<input type="text" name="uname" class="formcontrol" value="<?php if(isset($_POST['uname'])) echo $_POST['uname'] ?>"/>
					<small class="error"><?php if(isset($errors['uname'])) echo $errors['uname']; ?></small>
				</div>
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
					<label>Confirm Password</label>
					<input type="password" name="cpass" class="formcontrol" value="<?php if(isset($_POST['cpass'])) echo $_POST['cpass'] ?>"/>
					<small class="error"><?php if(isset($errors['cpass'])) echo $errors['cpass']; ?></small>
				</div>
				<div class="formgroup">
					<label>Mobile</label>
					<input type="text" name="mobile" class="formcontrol" value="<?php if(isset($_POST['mobile'])) echo $_POST['mobile'] ?>"/>
					<small class="error"><?php if(isset($errors['mobile'])) echo $errors['mobile']; ?></small>
				</div>
				<div class="formgroup">
					<label>Gender</label>
					<label><input type="radio" name="gender" value="Male"  <?php if(isset($_POST['gender'])) if($_POST['gender'] === "Male") echo "Checked" ?> />Male</label>
					<label><input type="radio" name="gender" value="Female"  <?php if(isset($_POST['gender'])) if($_POST['gender'] === "Female") echo "Checked" ?> />Female</label>
					<small class="error"><?php if(isset($errors['gender'])) echo $errors['gender']; ?></small>
				</div>
				<div class="formgroup">
					<label>County</label>
					<select name="state" class="formcontrol">
						<option value="">--Select County--</option>
						
					 <?php 
                      $json_data = file_get_contents("county.json");
                      $response_data = json_decode($json_data,true);
                         $count = count($response_data);
                      for($i=0;$i<$count;$i++){									
					?>			
                    <option value=<?php echo $response_data [$i]["name"];?>> <?php echo $response_data [$i]["name"];?> </option>
					<?php
                } 
				?>
					</select>
					<small class="error"><?php if(isset($errors['state'])) echo $errors['state']; ?></small>
				</div>
				<div class="formgroup">
					<input type="submit" name="register1" value="Register" class="btn" />
				</div>
			</form>
		</div>
	</body>
</html>
<?php mysqli_close($con);?>