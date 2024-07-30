<?php $page_title = "View comment";

	include('includes/header.php'); 
	include('includes/function.php');
	include('language/language.php');

	//require("includes/check_availability.php");

	// Get thumb img
	function get_thumb($filename,$thumb_size)
	{
		
		$file_path = getBaseUrl();

		return $thumb_path=$file_path.'thumb.php?src='.$filename.'&size='.$thumb_size;
	}

 	$id=trim($_GET['news_id']);

	$sql="SELECT * FROM tbl_news
			LEFT JOIN tbl_category
			ON tbl_news.`cat_id`=tbl_category.`cid` 
			WHERE tbl_news.`id`='$id'";

	$res=mysqli_query($mysqli,$sql);
	$row=mysqli_fetch_assoc($res);

	if(isset($_SESSION['login_type']) AND $_SESSION['login_type']=='reporter' AND $row['user_id']!=$admin_id){
		header('Location: reporter_dashboard.php');
	}

 	$sql1="SELECT tbl_comments.*, tbl_users.`name`,tbl_users.`user_profile` FROM tbl_comments, tbl_users WHERE tbl_comments.`news_id`='$id' and tbl_users.`id`=tbl_comments.`user_id` ORDER BY tbl_comments.`comment_on` DESC";
	$res_comment=mysqli_query($mysqli, $sql1) or die(mysqli_error($mysqli));
	$arr_dates=array();
	$i=0;
	while($comment=mysqli_fetch_assoc($res_comment)){
		$dates=date('d M Y',$comment['comment_on']);
		$arr_dates[$dates][$i++]=$comment;
	}

?>
<style>
.app-messaging ul.chat li .message{
	padding: 5px 10px 15px 5px;
	min-height: 60px
}
.app-messaging ul.chat li .message span.comment-text-item{
	margin-top:8px;
	display:inline-block;
}
.app-messaging .messaging {
    transform: translate(0, 0);
}
@media (max-width: 767px) {
.app-messaging .heading .title{
	font-size:14px !important;
}	
}
</style>

<div class="app-messaging-container">
	<?php
      	if(isset($_SERVER['HTTP_REFERER']))
      	{
      		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
      	}
     ?>
     <br>
	<div class="app-messaging" id="collapseMessaging">
	<div class="messaging">
		<div class="heading">
			<div class="title" style="font-size: 16px">
				
				<?php 
					if(isset($_SESSION['login_type']) AND $_SESSION['login_type']=='reporter'){
				?>
					<a class="btn-back" href="<?=(!isset($_GET['redirect']) OR $_GET['redirect']=='') ? 'manage_reporter_news.php' : $_GET['redirect']?>">
						<i class="fa fa-angle-left" aria-hidden="true"></i>
					</a>
				<?php }else{?> 
					<a class="btn-back" href="<?=(!isset($_GET['redirect']) OR $_GET['redirect']=='') ? 'manage_comments.php' : $_GET['redirect']?>">
						<i class="fa fa-angle-left" aria-hidden="true"></i>
					</a>
				<?php } ?>
				<strong>Title</strong>&nbsp;&nbsp;<?=stripslashes($row['news_heading'])?>
			</div>
			<div class="action"></div>
		</div>
		<ul class="chat" style="flex: unset;height: 500px;">
		<?php 
		if(!empty($arr_dates))
		{
			foreach ($arr_dates as $key => $val) {
			?>
			<li class="line">
				<div class="title"><?=$key?></div>
			</li>
			<?php 
			foreach ($val as $key1 => $value) {

				$img='';
				if(!file_exists('images/'.$value['user_profile']) || $value['user_profile']==''){
					$img='user-icons.jpg';
				}else{
					$img=$value['user_profile'];
				}
				
			?>
			<li class="<?=$value['id']?>" style="padding-right: 20px">

			<div class="message">
			<img src="<?=get_thumb('images/'.$img,'50x50')?>" style="width: 50px;float: left;margin-right: 10px;border-radius: 50%;box-shadow: 0px 0px 2px 1px #ccc">
			<span style="color: #000;font-weight: 600"><?=$value['name']?></span>
			<br/>
			<span class="comment-text-item">
			<?=$value['comment_text']?>	
			</span>
			</div>
			<div class="info" style="clear: both;">
			<div class="datetime">
			<?=calculate_time_span($value['comment_on'],true)?>
			<a href="javascript:void(0)" class="btn_delete" data-id="<?=$value['id']?>" style="color: red;text-decoration: none;" data-table="tbl_comments" data-column="id"><i class="fa fa-trash"></i> Delete</a>
			</div>
			</div>
			</li>
			<?php } // end of inner foreach
			}	// end of main foreach
		}	// end of if
		else{
		?>
		<div class="jumbotron" style="width: 100%; text-align: center;">
		<h3>Sorry !</h3> 
		<p>No comments available</p> 
		</div>
		<?php
		} 
		?>
		</ul>
	</div>
</div>
</div>


<?php 
include('includes/footer.php');
?> 
