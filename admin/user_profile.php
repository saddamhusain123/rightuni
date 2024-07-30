<?php 
$page_title="User Profile";
$current_page="users";

include('includes/header.php'); 
include('includes/function.php');
include('language/language.php'); 

require("includes/check_availability.php");

$user_id=strip_tags(addslashes(trim($_GET['user_id'])));

if(!isset($_GET['user_id']) OR $user_id==''){
	header("Location: manage_users.php");
}

$user_qry="SELECT * FROM tbl_users WHERE `id`='$user_id'";
$user_result=mysqli_query($mysqli,$user_qry);

if(mysqli_num_rows($user_result)==0){
	header("Location: manage_users.php");
}

$user_row=mysqli_fetch_assoc($user_result);

$user_img='';

if($user_row['user_profile']!='' && file_exists('images/'.$user_row['user_profile'])){
	$user_img='images/'.$user_row['user_profile'];
}
else{
	$user_img='assets/images/user-icons.jpg';
}

	// Get last activelog
function getLastActiveLog($user_id){
	global $mysqli;

	$sql="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
	$res=mysqli_query($mysqli, $sql);

	if(mysqli_num_rows($res) == 0){
		echo 'no available';
	}
	else{

		$row=mysqli_fetch_assoc($res);
		return calculate_time_span($row['date_time'],true);	
	}
}

    // Update profile
if(isset($_POST['btn_submit']))
{

	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
	{
		$_SESSION['class']="warn";
		$_SESSION['msg']="invalid_email_format";
	}
	else{

		$email=cleanInput($_POST['email']);

		$sql="SELECT * FROM tbl_users WHERE `email` = '$email' AND `id` <> '".$user_id."'";

		$res=mysqli_query($mysqli, $sql);

		if(mysqli_num_rows($res) == 0){
			$data = array(
				'name'  =>  cleanInput($_POST['name']),
				'email'  =>  cleanInput($_POST['email']),
				'phone'  =>  cleanInput($_POST['phone'])
			);

			if($_POST['password']!="")
			{

				$password=md5(trim($_POST['password']));

				$data = array_merge($data, array("password"=>$password));
			}

			if($_FILES['profile_img']['name']!="")
			{

				if($user_row['profile_img']!="" OR !file_exists('images/'.$user_row['profile_img']))
				{
					unlink('images/'.$user_row['profile_img']);
				}

				$ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);

				$profile_img=rand(0,99999).'_'.date('dmYhis')."_user.".$ext;

                    //Main Image
				$tpath1='images/'.$profile_img;   

				if($ext!='png')  {
					$pic1=compress_image($_FILES["profile_img"]["tmp_name"], $tpath1, 80);
				}
				else{
					$tmp = $_FILES['profile_img']['tmp_name'];
					move_uploaded_file($tmp, $tpath1);
				}

				$data = array_merge($data, array("user_profile" => $profile_img));

			}

			$user_edit=Update('tbl_users', $data, "WHERE id = '".$user_id."'");

			$_SESSION['msg']="11";
		}
		else{
			$_SESSION['class']="warn";
			$_SESSION['msg']="email_exist";
		}
	}

	header("Location:user_profile.php?user_id=".$user_id);
	exit;
}
?>

<link rel="stylesheet" type="text/css" href="assets/css/stylish-tooltip.css">

<div class="row">
	<div class="col-lg-12">
		<?php
		if(isset($_GET['redirect'])){
			echo '<a href="'.$_GET['redirect'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
		}
		else{
			echo '<a href="manage_users.php"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
		}
		?>
		<div class="page_title_block user_dashboard_item" style="background-color: #333;border-radius:6px;0 1px 4px 0 rgba(0, 0, 0, 0.14);border-bottom:0">
			<div class="user_dashboard_mr_bottom">
				<div class="col-md-12 col-xs-12"> <br>
					<span class="badge badge-success badge-icon">
						<div class="user_profile_img">
							<?php 
							if($user_row['user_type']=='Google'){
								echo '<img src="assets/images/google-logo.png" style="top: 20px;left: 60px;" class="social_img">';
							}
							else if($user_row['user_type']=='Facebook'){
								echo '<img src="assets/images/facebook-icon.png" style="top: 20px;left: 60px;" class="social_img">';
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
					<span style="font-size: 14px;text-transform: lowercase;"><?php echo getLastActiveLog($user_id)?></span>
				</span>
				<br><br/>
			</div>
		</div>
	</div>

	<div class="card card-tab">
		<div class="card-header" style="overflow-x: auto;overflow-y: hidden;">
			<ul class="nav nav-tabs" role="tablist">
				<li role="dashboard" class="active"><a href="#edit_profile" aria-controls="edit_profile" role="tab" data-toggle="tab">Edit Profile</a></li>
				<li role="favourite_news"><a href="#favourite_news" aria-controls="favourite_news" role="tab" data-toggle="tab">Favourite News</a></li>
			</ul>
		</div>
		<div class="card-body no-padding tab-content">
			<div role="tabpanel" class="tab-pane active" id="edit_profile">
				<div class="row">
					<div class="col-md-12">
						<form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
							<div class="section">
								<div class="section-body">
									<div class="form-group">
										<label class="col-md-3 control-label">Name :-</label>
										<div class="col-md-6">
											<input type="text" name="name" id="name" value="<?=$user_row['name']?>" class="form-control" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Email :-</label>
										<div class="col-md-6">
											<input type="email" name="email" id="email" value="<?=$user_row['email']?>" class="form-control" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Password :-</label>
										<div class="col-md-6">
											<input type="password" name="password" id="password" value="" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Phone :-</label>
										<div class="col-md-6">
											<input type="text" name="phone" id="phone" value="<?=$user_row['phone']?>" class="form-control">
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-3 control-label">Profile Image :-
											<p class="control-label-help">(Recommended resolution: 100x100, 200x200) OR Squre image</p>
										</label>
										<div class="col-md-6">
											<div class="fileupload_block">
												<input type="file" name="profile_img" value="fileupload" accept=".png, .jpg, .jpeg, .svg, .gif" <?php echo (!isset($_GET['user_id'])) ? 'required="require"' : '' ?> id="fileupload">
												<div class="fileupload_img">
													<?php 
													$img_src="";
													if(!isset($_GET['user_id']) || $user_row['user_profile']==''){
														$img_src='assets/images/landscape.jpg';
													}else{
														$img_src='images/'.$user_row['user_profile'];
													}
													?>
													<img type="image" src="<?=$img_src?>" alt="image" style="width: 86px;height: 86px" />
												</div>   
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-9 col-md-offset-3">
											<button type="submit" name="btn_submit" class="btn btn-primary">Save</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="favourite_news">
				<div class="row">
					<div class="col-md-12">
						<table class="datatable table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Sr.</th>
									<th>Image</th>
									<th>News</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
								<?php

								$sql="SELECT tbl_news.`id`,tbl_news.`news_heading`,tbl_news.`news_featured_image`, tbl_favourite.`id` AS favourite_id, tbl_favourite.`created_at` AS favourite_date FROM tbl_news
								LEFT JOIN tbl_favourite ON tbl_news.`id`=tbl_favourite.`news_id`
								WHERE tbl_favourite.`user_id`='$user_id' ORDER BY tbl_favourite.`id` DESC";

								$res=mysqli_query($mysqli, $sql);
								$no=1;
								while ($row=mysqli_fetch_assoc($res)) {
									?>
									<tr>
										<td><?=$no;?></td>
										<td nowrap="">
											<?php 
											if(file_exists('images/'.$row['news_featured_image'])){
												?>
												<span class="mytooltip tooltip-effect-3">
													<span class="tooltip-item">
														<img src="images/<?php echo $row['news_featured_image'];?>" alt="no image" style="width: 60px;height: auto;border-radius: 5px">
													</span> 
													<span class="tooltip-content clearfix">
														<a href="images/<?php echo $row['news_featured_image'];?>" target="_blank"><img src="images/<?php echo $row['news_featured_image'];?>" alt="no image" /></a>
													</span>
												</span>
											<?php }else{
												?>
												<img src="" alt="no image" style="width: 60px;height: 60px;border-radius: 5px">
												<?php
											} ?>
										</td>
										<td title="<?=$row['news_heading']?>">
											<?php
											if(strlen($row['news_heading']) > 40){
												echo substr(stripslashes($row['news_heading']), 0, 40).'...';  
											}else{
												echo $row['news_heading'];
											}
											?>
										</td>
										<td><?=calculate_time_span($row['favourite_date'],true);?></td>
									</tr>
									<?php
									$no++;
								}
								mysqli_free_result($res);

								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
</div>

<?php 
include('includes/footer.php');
?>

<script type="text/javascript">

// Show active tab
$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
	localStorage.setItem('activeTab', $(e.target).attr('href'));
	document.title = $(this).text()+" | <?=APP_NAME?>";
});

var activeTab = localStorage.getItem('activeTab');
if(activeTab){
	$('.nav-tabs a[href="' + activeTab + '"]').tab('show');
}

  // For enable and disable
  $(".toggle_btn a").on("click",function(e){
  	e.preventDefault();
  	var _for=$(this).data("action");
  	var _id=$(this).data("id");
  	var _column=$(this).data("column");
  	var _table='tbl_news';

  	$.ajax({
  		type:'post',
  		url:'processData.php',
  		dataType:'json',
  		data:{id:_id,for_action:_for,column:_column,table:_table,'action':'toggle_status','tbl_id':'id'},
  		success:function(res){
  			console.log(res);
  			if(res.status=='1'){
  				location.reload();
  			}
  		}
  	});

  });

  // Preview news 
  $(".btn_preview").on("click",function(e){
  	e.preventDefault();
  	var _id=$(this).data("news");
  	var _title=$(this).data("title");
  	$("#newsPreview .modal-title").text(_title);
  	$("#newsPreview .modal-body").load("news_preview.php?news_id="+_id);
  	$("#newsPreview").modal("show");

  });

  // Filter form
  $(".filter").on("change",function(e){
  	$("#filterForm *").filter(":input").each(function(){
  		if ($(this).val() == '')
  			$(this).prop("disabled", true);
  	});
  	$("#filterForm").submit();
  });

  // for deletes
  $(document).on("click",".btn_delete_a", function(e){
  	e.preventDefault();

  	var _id=$(this).data("id");
  	var _table='tbl_news';
  	var _for_action='delete';

  	confirmDlg = duDialog('Are you sure?', 'All data will be removed which belong to this!', {
  		init: true,
  		dark: false, 
  		buttons: duDialog.OK_CANCEL,
  		okText: 'Proceed',
  		callbacks: {
  			okClick: function(e) {
  				$(".dlg-actions").find("button").attr("disabled",true);
  				$(".ok-action").html('<i class="fa fa-spinner fa-pulse"></i> Please wait..');
  				$.ajax({
  					type:'post',
  					url:'processData.php',
  					dataType:'json',
  					data:{'id':_id,'table':_table,'for_action':_for_action,'action':'multi_action'},
  					success:function(res){
  						location.reload();
  					}
  				});
  			} 
  		}
  	});
  	confirmDlg.show();
  });

//for multiple user action
$(document).on("click",".actions", function(e){
	e.preventDefault();

	var _ids = $.map($('.post_ids:checked'), function(c){return c.value; });
	var _action=$(this).data("action");

	if(_ids!='')
	{
		confirmDlg = duDialog('Action: '+$(this).text(), 'Do you really want to perform?', {
			init: true,
			dark: false, 
			buttons: duDialog.OK_CANCEL,
			okText: 'Proceed',
			callbacks: {
				okClick: function(e) {
					$(".dlg-actions").find("button").attr("disabled",true);
					$(".ok-action").html('<i class="fa fa-spinner fa-pulse"></i> Please wait..');
					var _table='tbl_news';

					$.ajax({
						type:'post',
						url:'processData.php',
						dataType:'json',
						data:{id:_ids,for_action:_action,table:_table,'action':'multi_action'},
						success:function(res){
							$('.notifyjs-corner').empty();
							if(res.status=='1'){
								location.reload();
							}
						}
					});

				} 
			}
		});
		confirmDlg.show();
	}
	else{
		infoDlg = duDialog('Opps!', 'No data selected', { init: true });
		infoDlg.show();
	}
});

 // Checkall inputs
 var totalItems=0;

 $("#checkall_input").click(function () {

 	totalItems=0;

 	$('input:checkbox').not(this).prop('checked', this.checked);
 	$.each($("input[name='post_ids[]']:checked"), function(){
 		totalItems=totalItems+1;
 	});

 	if($('input:checkbox').prop("checked") == true){
 		$('.notifyjs-corner').empty();
 		$.notify(
 			'Total '+totalItems+' item checked',
 			{ position:"top center",className: 'success'}
 			);
 	}
 	else if($('input:checkbox'). prop("checked") == false){
 		totalItems=0;
 		$('.notifyjs-corner').empty();
 	}
 });

 var noteOption = {
 	clickToHide : false,
 	autoHide : false,
 }

 $.notify.defaults(noteOption);

 $(".post_ids").click(function(e){

 	if($(this).prop("checked") == true){
 		totalItems=totalItems+1;
 	}
 	else if($(this). prop("checked") == false){
 		totalItems = totalItems-1;
 	}

 	if(totalItems==0){
 		$('.notifyjs-corner').empty();
 		exit();
 	}

 	$('.notifyjs-corner').empty();

 	$.notify(
 		'Total '+totalItems+' item checked',
 		{ position:"top center",className: 'success'}
 		);
 });

  // Profile iimage
  $("input[name='profile_img']").change(function() { 
  	var file=$(this);

  	if(file[0].files.length != 0){
  		if(isImage($(this).val())){
  			render_upload_image(this,$(this).next('.fileupload_img').find("img"));
  		}
  		else
  		{
  			$(this).val('');
  			$('.notifyjs-corner').empty();
  			$.notify(
  				'Only jpg/jpeg, png, gif and svg files are allowed!',
  				{ position:"top center",className: 'error'}
  				);
  		}
  	}
  });

</script>