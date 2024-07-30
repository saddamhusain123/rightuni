<?php 
include("includes/connection.php");
error_reporting(0);

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	 // set time for session timeout
$currentTime = time() + 25200;
$expired = 7200;

if($username=="")
{
	$_SESSION['msg']="1"; 
	header( "Location:index.php");
	exit;		 
}
else if($password=="")
{
	$_SESSION['msg']="2"; 
	header( "Location:index.php");
	exit;		 
}	 
else
{
	
	$sql="SELECT * FROM admin WHERE (`username` = '$username' OR `email`='$username')";
	
	$result=mysqli_query($mysqli,$sql);		
	
	if(mysqli_num_rows($result) > 0)
	{ 
		$row=mysqli_fetch_assoc($result);
		
		if (password_verify($password, $row['password'])) {
			$_SESSION['id']=$row['id'];
			$_SESSION['admin_name']=$row['username'];
			$_SESSION['login_type']='admin';
			$_SESSION['class']='success';
			$_SESSION['msg']="25";
			$_SESSION['timeout'] = $currentTime + $expired;

			$_SESSION['msg']="28"; 
			header( "Location:home.php");
			exit;
		}
		else{
			$_SESSION['msg']="23"; 
			header( "Location:index.php");
			exit;
		}
	}
	else
	{
		$_SESSION['msg']="22"; 
		header( "Location:index.php");
		exit;
	}
}

?> 