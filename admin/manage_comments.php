<?php 
$page_title="Manage Comments";

include('includes/header.php'); 

include('includes/function.php');
include('language/language.php');  

require("includes/check_availability.php");


		// Get news info
function news_info($news_id,$param='news_heading')
{
	global $mysqli;

	$query="SELECT * FROM tbl_news WHERE tbl_news.`id`='".$news_id."'";

	$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));
	$row=mysqli_fetch_assoc($sql);

	return stripslashes($row[$param]);
}

	// Get total comments
function total_comments($news_id)
{
	global $mysqli;

	$query="SELECT COUNT(*) AS total_comments FROM tbl_comments WHERE `news_id`='$news_id'";
	$sql = mysqli_query($mysqli,$query) or die(mysqli_error());
	$row=mysqli_fetch_assoc($sql);
	return stripslashes($row['total_comments']);
}

	// Get comments list
$users_qry="SELECT id, news_id, user_id, max(comment_on) AS comment_on FROM tbl_comments GROUP BY tbl_comments.`news_id` ORDER BY tbl_comments.`id` DESC";  

$users_result=mysqli_query($mysqli,$users_qry);

?>

<link rel="stylesheet" type="text/css" href="assets/css/stylish-tooltip.css">

<div class="row">
	<div class="col-xs-12">
		<?php
		if(isset($_SERVER['HTTP_REFERER']))
		{
			echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
		}
		?>
		<div class="card mrg_bottom">
			<div class="page_title_block">
				<div class="col-md-5 col-xs-12">
					<div class="page_title"><?=$page_title?></div>
				</div>
				<div class="col-md-7">
					<button class="btn btn-danger btn_cust btn_delete_all comment-btn-item"><i class="fa fa-trash"></i> Delete All</button>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-12 mrg-top">          	
				<table class="datatable table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:40px">
								<div class="checkbox" style="margin: 0px">
									<input type="checkbox" name="checkall" id="checkall_input" value="">
									<label for="checkall_input"></label>
								</div>
							</th>
							<th></th>
							<th>News</th>
							<th>Total Comments</th>	 
							<th>Last Comment</th>	 
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i=0;
						while($users_row=mysqli_fetch_array($users_result))
						{	 
							?>
							<tr class="<?=$users_row['news_id']?>">
								<td> 
									<div class="checkbox">
										<input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $users_row['news_id']; ?>" class="post_ids">
										<label for="checkbox<?php echo $i;?>"></label>
									</div>
								</td>
								<td nowrap="">
									<?php 
									if(file_exists('images/'.news_info($users_row['news_id'],'news_featured_image'))){
										?>
										<span class="mytooltip tooltip-effect-3">
											<span class="tooltip-item">
												<img src="images/<?php echo news_info($users_row['news_id'],'news_featured_image');?>" alt="no image" style="width: 60px;height: auto;border-radius: 5px">
											</span> 
											<span class="tooltip-content clearfix">
												<a href="images/<?php echo news_info($users_row['news_id'],'news_featured_image');?>" target="_blank"><img src="images/<?php echo news_info($users_row['news_id'],'news_featured_image');?>" alt="no image" /></a>
											</span>
										</span>
									<?php }else{
										?>
										<img src="" alt="no image" style="width: 60px;height: 60px;border-radius: 5px">
										<?php
									} ?>
								</td>
								<td title="<?=news_info($users_row['news_id'])?>">
									<?php
									if(strlen(news_info($users_row['news_id'])) > 28){
										echo substr(stripslashes(news_info($users_row['news_id'])), 0, 28).'...';  
									}else{
										echo news_info($users_row['news_id']);
									}
									?>
								</td>
								<td>
									<a href="view_comments.php?news_id=<?=$users_row['news_id']?>"><?php echo total_comments($users_row['news_id']);?> Comments</a>
								</td>
								<td>
									<?=calculate_time_span($users_row['comment_on'],true);?>
								</td>
								<td> 
									<a href="javascript:void(0)" data-id="<?php echo $users_row['news_id'];?>" class="btn btn-danger btn_delete btn_cust" data-toggle="tooltip" data-tooltip="Delete" data-column='news_id' data-table="tbl_comments"><i class="fa fa-trash"></i></a>
								</td>
							</tr>
							<?php $i++; } ?>
						</tbody>
					</table>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>     

	<?php include('includes/footer.php');?>

	<script type="text/javascript">
		$(".btn_delete_all").click(function(e){
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

							$.ajax({
								type:'post',
								url:'processData.php',
								dataType:'json',
								data:{ids:_ids,'action':'removeAllComment'},
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


//checkall inputs
var totalItems=0;

{$("#checkall_input").click(function () {
	
	totalItems=0;
	
	$("input[name='post_ids[]']").prop('checked', this.checked);
	
	$.each($("input[name='post_ids[]']:checked"), function(){
		totalItems=totalItems+1;
	});
	
	
	if($("input[name='post_ids[]']").prop("checked") == true){
		$('.notifyjs-corner').empty();
		$.notify(
			'Total '+totalItems+' item checked',
			{ position:"top center",className: 'success'}
			);
	}
	else if($("input[name='post_ids[]']").prop("checked") == false){
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
		exit;
	}
	
	$('.notifyjs-corner').empty();
	
	$.notify(
		'Total '+totalItems+' item checked',
		{ position:"top center",className: 'success'}
		);
});
}
</script>                  