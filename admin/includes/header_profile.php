<?php 
	
	$current_page="reporter";
	include('includes/header.php'); 
	include('includes/function.php');
	include('language/language.php'); 

	$reporter_id=strip_tags(addslashes(trim($_GET['reporter_id'])));

	if(isset($_SESSION['login_type']) AND $_SESSION['login_type']=='admin'){

		if(!isset($_GET['reporter_id']) OR $reporter_id==''){
			$_SESSION['class']="warn";
        	$_SESSION['msg']="26";
			header("Location: manage_reporter.php");
		}

	    $user_qry="SELECT * FROM users WHERE id='$reporter_id'";
	    $user_result=mysqli_query($mysqli,$user_qry);

	    if(mysqli_num_rows($user_result)==0){
	    	$_SESSION['class']="warn";
        	$_SESSION['msg']="26";
	    	header("Location: manage_reporter.php");
	    }
	}
	else{
		if(!isset($_GET['reporter_id']) OR $reporter_id==''){
			$_SESSION['class']="warn";
        	$_SESSION['msg']="26";
			header("Location: home.php");
		}

	    $user_qry="SELECT * FROM users WHERE id='$reporter_id'";
	    $user_result=mysqli_query($mysqli,$user_qry);

	    if(mysqli_num_rows($user_result)==0){

	    	$_SESSION['class']="warn";
        	$_SESSION['msg']="26";

	    	header("Location: home.php");
	    }
	}

    $user_row=mysqli_fetch_assoc($user_result);

    $user_img='';

	if($user_row['user_profile']!='' && file_exists('images/'.$user_row['user_profile'])){
		$user_img='images/'.$user_row['user_profile'];
	}
	else{
		$user_img='assets/images/user-icons.jpg';
	}

	function getLastActiveLog($reporter_id){
    	global $mysqli;

    	$sql="SELECT * FROM active_log WHERE `user_id`='$reporter_id'";
        $res=mysqli_query($mysqli, $sql);

        if(mysqli_num_rows($res) == 0){
        	echo 'no available';
        }
        else{

        	$row=mysqli_fetch_assoc($res);
			return calculate_time_span($row['date_time'],true);	
        }
    }
?>

<!-- <link rel="stylesheet" type="text/css" href="assets/css/stylish-tooltip.css"> -->

<div class="row">
	<div class="col-lg-12">
		<?php
			if(isset($_GET['redirect'])){
	          echo '<a href="'.$_GET['redirect'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
	        }
			else if(isset($_SESSION['login_type']) AND $_SESSION['login_type']=='admin'){
	          	echo '<a href="manage_reporter.php"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
	        }
	        else{
	         	echo '<a href="home.php"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
	        }
		?>
		<div class="page_title_block user_dashboard_item" style="background-color: #333;border-radius:6px;0 1px 4px 0 rgba(0, 0, 0, 0.14);border-bottom:0">
		<div class="user_dashboard_mr_bottom">
		  <div class="col-md-12 col-xs-12"> <br>
			<span class="badge badge-success badge-icon">
			  <div class="user_profile_img">
			  
			   <?php 
				  if($user_row['user_type']=='Google'){
					echo '<img src="assets/images/google-logo.png" style="width: 16px;height: 16px;position: absolute;top: 25px;z-index: 1;left: 70px;">';
				  }
				  else if($user_row['user_type']=='Facebook'){
					echo '<img src="assets/images/facebook-icon.png" style="width: 16px;height: 16px;position: absolute;top: 25px;z-index: 1;left: 70px;">';
				  }
				?>
				<img type="image" src="<?php echo $user_img;?>" alt="image" style=""/>          

			  </div>
			  <span style="font-size: 14px;"><?php echo $user_row['name'];?>				
			  </span>
			</span>  
			<span class="badge badge-success badge-icon">
			<i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
			<span style="font-size: 14px;text-transform: lowercase;"><?php echo $user_row['email'];?></span>
			</span> 
			<span class="badge badge-success badge-icon">
			  <strong style="font-size: 14px;">Registered At:</strong>
			  <span style="font-size: 14px;"><?php echo date('d-m-Y',$user_row['registered_on']);?></span>
			</span>
			<span class="badge badge-success badge-icon">
			  <strong style="font-size: 14px;">Last Activity On:</strong>
			  <span style="font-size: 14px;text-transform: lowercase;"><?php echo getLastActiveLog($reporter_id)?></span>
			</span>
		  </div>
		</div>
	</div>
	<div class="card card-tab">
		<div class="card-header" style="overflow-x: auto;overflow-y: hidden;">
			<ul class="nav nav-tabs">
				<li role="dashboard" <?=($currentFile=='reporter_profile.php') ? 'class="active"' : '' ?>>
					<a href="reporter_profile.php?reporter_id=<?=$reporter_id?>" name="Edit Profile">Edit Profile</a>
				</li>
				<li role="uploaded_news" <?=($currentFile=='uploaded_news.php') ? 'class="active"' : '' ?>>
					<a href="uploaded_news.php?reporter_id=<?=$reporter_id?>" name="Uploaded News">Uploaded News</a>
				</li>
				<li role="favourite_news" <?=($currentFile=='favourite_news.php') ? 'class="active"' : '' ?>>
					<a href="favourite_news.php?reporter_id=<?=$reporter_id?>" name="Favourite News">Favourite News</a>
				</li>
			</ul>
		</div>