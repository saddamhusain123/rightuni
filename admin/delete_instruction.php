<?php 
	
	$page_title="Delete Account Instruction";
	include("includes/connection.php");
?>

<!DOCTYPE html>
<html> 
<head>
	<meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width"> 
	<title><?php echo (isset($page_title)) ? $page_title.' | ' : '' ?><?php echo APP_NAME;?></title>
	<style type="text/css">
		body{
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; padding:1em;
		}
		.header{
			border-bottom: 2px solid #000;
			box-shadow: 0px 5px 10px 0px #ddd;
			padding: 10px 20px;
		}
		.header img{
			width: 80px;height: 80px;float: left;margin-right: 20px
		}
		.header h1{
			padding: 23px;margin: 0px
		}
		@media screen and (max-width: 768px) {

			.header img{
				width: 60px;
				height: 60px;
			}
			.header > h1 {
				font-size: 20px;
			}
		}
	</style>
</head> 
<body>

<div class="header">
	<img src="images/<?php echo APP_LOGO;?>" alt="app logo"/>
	<h1><?php echo APP_NAME;?></h1>	
</div>

<div style="clear: both;"></div>

<h2>To delete your account your have to contact the Admin</h2>

<p>Send email to below email address with your name, email and reason for deletation</p>
<p><strong>Email Address: </strong>matjar.info@gmail.com</p>

</body>
</html>