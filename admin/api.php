<?php 
include("includes/connection.php");
include("includes/function.php"); 	
include("language/app_language.php");
include("smtp_email.php");

error_reporting(0);

$file_path = getBaseUrl();

date_default_timezone_set("Asia/Kolkata");

$mysqli->set_charset('utf8mb4');

define("HOME_LIMIT",$settings_details['api_home_limit']);
define("PACKAGE_NAME",$settings_details['package_name']);

	// Get thumbs image
function get_thumb($filename,$thumb_size)
{	
	$file_path = getBaseUrl();
	return $thumb_path=$file_path.'thumb.php?src='.$filename.'&size='.$thumb_size;
}

	// For generate randome password
function generateRandomPassword($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

	// for varified of Purchase

if($settings_details['envato_buyer_name']=='' OR $settings_details['envato_purchase_code']=='' OR $settings_details['envato_purchased_status']==0) {  

	$set['ALL_IN_ONE_NEWS'][] =array('MSG' => 'Purchase code verification failed!','success'=>-1);	
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
}


	// For favourite news
function is_favourite($news_id, $user_id)
{
	global $mysqli;

	$sql_favourite="SELECT * FROM tbl_favourite WHERE `news_id`='$news_id' AND `user_id`='$user_id'";
	$res_favourite=mysqli_query($mysqli,$sql_favourite);
	
	if(mysqli_num_rows($res_favourite) > 0){
		return true;
	}
	else{
		return false;
	}
}

$get_method = checkSignSalt($_POST['data']);

 	// Get home start
if($get_method['method_name']=="get_home")	
{

	$user_id=$get_method['user_id'];

	$deleteViews="DELETE FROM tbl_views WHERE `views_at` < DATE_SUB(NOW(), INTERVAL 1 MONTH)";
	mysqli_query($mysqli,$deleteViews);

	$home_limit=HOME_LIMIT;

	$jsonObj0= array();	

	  	$cat_id=explode(',', $get_method['cat_id']);	// 2, 4	

	  	$start = date('Y-m-d',strtotime('today - 30 days'));
	  	$finish = date('Y-m-d',strtotime('today'));

	  	if($cat_id[0]!=''){
	  		$column='';
	  		foreach ($cat_id as $key => $value) {
	  			$column.='FIND_IN_SET('.$value.', tbl_news.`cat_id`) OR ';
	  		}

	  		$column=rtrim($column,'OR ');

	  		$query0="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
	  		LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
	  		LEFT JOIN tbl_views ON tbl_news.`id`= tbl_views.`news_id`
	  		WHERE ($column) AND tbl_news.`status`='1' AND tbl_category.`status`='1' AND tbl_views.`views_at` BETWEEN '$start' AND '$finish' GROUP BY tbl_views.`news_id` ORDER BY tbl_news.`total_views` DESC LIMIT $home_limit";

	  	}else{

	  		$query0="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
	  		LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
	  		LEFT JOIN tbl_views ON tbl_news.`id`= tbl_views.`news_id`
	  		WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' AND tbl_views.`views_at` BETWEEN '$start' AND '$finish' GROUP BY tbl_views.`news_id` ORDER BY tbl_news.`total_views` DESC LIMIT $home_limit";

	  	}

	  	$sql0 = mysqli_query($mysqli,$query0) or die(mysqli_error($mysqli));

	  	while($data0 = mysqli_fetch_assoc($sql0))
	  	{

	  		$row0['id'] = $data0['id'];
	  		$row0['news_type'] = $data0['news_type'];
	  		$row0['news_heading'] = stripslashes($data0['news_heading']);
	  		$row0['news_description'] = stripslashes($data0['news_description']);
	  		$row0['news_video_id'] = $data0['news_video_id'];
	  		$row0['news_video_url'] = $data0['news_video_url'];
	  		$row0['news_date'] = date('d-m-Y',$data0['news_date']);

	  		if(strpos($data0['news_featured_image'], $data0['news_video_id']) !== false){
	  			$row0['news_featured_image'] = $data0['news_featured_image'];
	  			$row0['news_featured_thumb'] = $data0['news_featured_image'];
	  		} else{
	  			$row0['news_featured_image'] = $file_path.'images/'.$data0['news_featured_image'];
	  			$row0['news_featured_thumb'] = get_thumb('images/'.$data0['news_featured_image'],'300x300');
	  		}

	  		$row0['total_views'] = $data0['total_views'];

	  		$row0['is_favourite'] = is_favourite($data0['id'],$user_id);

	  		$row0['share_link'] = $file_path.'view_news.php?news_id='.$data0['id'];

	  		$row0['cat_id'] = $data0['cat_id'];
	  		$row0['category_name'] = $data0['category_name'];

	  		array_push($jsonObj0,$row0);

	  	}

		// Get trending news
	  	$row['trending_news']=$jsonObj0;

	  	$jsonObj1= array();

	  	if($cat_id[0]!=''){
	  		$column='';
	  		foreach ($cat_id as $key => $value) {
	  			$column.='FIND_IN_SET('.$value.', tbl_news.`cat_id`) OR ';
	  		}

	  		$column=rtrim($column,'OR ');

	  		$query="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
	  		LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
	  		WHERE ($column) AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC LIMIT $home_limit";

	  	}else{

	  		$query="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
	  		LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
	  		WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC LIMIT $home_limit";

	  	}	

	  	$sql = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));

	  	while($data = mysqli_fetch_assoc($sql))
	  	{

	  		$row1['id'] = $data['id'];
	  		$row1['news_type'] = $data['news_type'];
	  		$row1['news_heading'] = stripslashes($data['news_heading']);
	  		$row1['news_description'] = stripslashes($data['news_description']);
	  		$row1['news_video_id'] = $data['news_video_id'];
	  		$row1['news_video_url'] = $data['news_video_url'];
	  		$row1['news_date'] = date('d-m-Y',$data['news_date']);

	  		if(strpos($data['news_featured_image'], $data['news_video_id']) !== false){
	  			$row1['news_featured_image'] = $data['news_featured_image'];
	  			$row1['news_featured_thumb'] = $data['news_featured_image'];
	  		} else{
	  			$row1['news_featured_image'] = $file_path.'images/'.$data['news_featured_image'];
	  			$row1['news_featured_thumb'] = get_thumb('images/'.$data['news_featured_image'],'300x300');
	  		}

	  		$row1['total_views'] = $data['total_views'];

	  		$row1['is_favourite'] = is_favourite($data['id'],$user_id);

	  		$row1['share_link'] = $file_path.'view_news.php?news_id='.$data['id'];

	  		$row1['cat_id'] = $data['cat_id'];
	  		$row1['category_name'] = $data['category_name'];

	  		array_push($jsonObj1,$row1);

	  	}

		// Get latest news
	  	$row['latest_news']=$jsonObj1;	

	  	$jsonObj_2= array();

	  	if($cat_id[0]!=''){
	  		$column='';
	  		foreach ($cat_id as $key => $value) {
	  			$column.='FIND_IN_SET('.$value.', tbl_news.`cat_id`) OR ';
	  		}

	  		$column=rtrim($column,'OR ');

	  		$query_all="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
	  		LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
	  		WHERE ($column) AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY RAND() DESC LIMIT $home_limit";

	  	}else{

	  		$query_all="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
	  		LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
	  		WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY RAND() DESC LIMIT $home_limit";

	  	}	

	  	$sql_all = mysqli_query($mysqli,$query_all) or die(mysqli_error($mysqli));

	  	while($data_all = mysqli_fetch_assoc($sql_all))
	  	{
	  		$row2['id'] = $data_all['id'];
	  		$row2['news_type'] = $data_all['news_type'];
	  		$row2['news_heading'] = stripslashes($data_all['news_heading']);
	  		$row2['news_description'] = stripslashes($data_all['news_description']);
	  		$row2['news_video_id'] = $data_all['news_video_id'];
	  		$row2['news_video_url'] = $data_all['news_video_url'];
	  		$row2['news_date'] = date('d-m-Y',$data_all['news_date']);

	  		if(strpos($data_all['news_featured_image'], $data_all['news_video_id']) !== false){
	  			$row2['news_featured_image'] = $data_all['news_featured_image'];
	  			$row2['news_featured_thumb'] = $data_all['news_featured_image'];
	  		} else{
	  			$row2['news_featured_image'] = $file_path.'images/'.$data_all['news_featured_image'];
	  			$row2['news_featured_thumb'] = get_thumb('images/'.$data_all['news_featured_image'],'300x300');
	  		}

	  		$row2['total_views'] = $data_all['total_views'];

	  		$row2['is_favourite'] = is_favourite($data_all['id'],$user_id);

	  		$row2['share_link'] = $file_path.'view_news.php?news_id='.$data_all['id'];

	  		$row2['cat_id'] = $data_all['cat_id'];
	  		$row2['category_name'] = $data_all['category_name'];

	  		array_push($jsonObj_2,$row2);
	  	}

		// Get top story news
	  	$row['top_story']=$jsonObj_2;

	  	$jsonObj_3= array();	

	  	$cat_order=API_CAT_ORDER_BY;

	  	if($cat_id[0]!=''){
	  		$column='';
	  		foreach ($cat_id as $key => $value) {
	  			$column.='FIND_IN_SET('.$value.', `cid`) OR ';
	  		}

	  		$column=rtrim($column,'OR ');

	  		$query="SELECT cid,category_name,category_image FROM tbl_category WHERE ($column) AND `status`='1' ORDER BY $cat_order DESC LIMIT $home_limit";

	  	}else{

	  		$query="SELECT cid,category_name,category_image FROM tbl_category WHERE `status`='1' ORDER BY $cat_order DESC LIMIT $home_limit";

	  	}


	  	$sql_all = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));

	  	while($data_all = mysqli_fetch_assoc($sql_all))
	  	{
	  		$row3['cid'] = $data_all['cid'];
	  		$row3['category_name'] = $data_all['category_name'];
	  		$row3['category_image'] = $file_path.'images/'.$data_all['category_image'];
	  		$row3['category_image_thumb'] = get_thumb('images/'.$data_all['category_image'],'300x300');

	  		array_push($jsonObj_3,$row3);
	  	}

		// Get category list
	  	$row['category']=$jsonObj_3;

	  	$set['ALL_IN_ONE_NEWS'] = $row;

	  	header( 'Content-Type: application/json; charset=utf-8' );
	  	echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	  	die();
	  }
	// Get home end 

	// Get category list start
	  else if ($get_method['method_name']=="get_category") {

	  	$jsonObj= array();	

	  	$cat_order=API_CAT_ORDER_BY;

	  	$query="SELECT cid,category_name,category_image FROM tbl_category WHERE `status`='1' ORDER BY ".$cat_order."";

	  	$sql = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));

	  	while($data = mysqli_fetch_assoc($sql))
	  	{
	  		$row['cid'] = $data['cid'];
	  		$row['category_name'] = $data['category_name'];
	  		$row['category_image'] = $file_path.'images/'.$data['category_image'];
	  		$row['category_image_thumb'] = get_thumb('images/'.$data['category_image'],'300x300');

	  		array_push($jsonObj,$row);

	  	}

	  	$set['ALL_IN_ONE_NEWS'] = $jsonObj;

	  	header( 'Content-Type: application/json; charset=utf-8' );
	  	echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	  	die();
	  }
	// Get category list end

	// Get news list start
	  else if ($get_method['method_name']=="get_news") {

	  	$post_order_by=API_CAT_POST_ORDER_BY;

		$cat_id=explode(',', $get_method['cat_id']);	// 2, 4	

		$user_id=$get_method['user_id'];

		if($cat_id[0]!=''){
			$column='';
			foreach ($cat_id as $key => $value) {
				$column.='FIND_IN_SET('.$value.', tbl_news.`cat_id`) OR ';
			}

			$column=rtrim($column,'OR ');

			$query_rec = "SELECT COUNT(*) as num FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE ($column) AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` $post_order_by";

		}else{

			$query_rec = "SELECT COUNT(*) as num FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE tbl_news.`cat_id`='$cat_id' AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` $post_order_by";
		}
		
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
		$page_limit=API_PAGE_LIMIT;

		$limit=($get_method['page']-1) * $page_limit;

		$jsonObj= array();	
		
		if($cat_id[0]!=''){
			$column='';
			foreach ($cat_id as $key => $value) {
				$column.='FIND_IN_SET('.$value.', tbl_news.`cat_id`) OR ';
			}

			$column=rtrim($column,'OR ');

			$query="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE ($column) AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` $post_order_by LIMIT $limit, $page_limit";

		}else{

			$query="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE tbl_news.`cat_id`='$cat_id' AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` $post_order_by LIMIT $limit, $page_limit";
			
		} 

		$sql = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
			$row['pagination_limit'] = $page_limit;
			$row['total_news'] = $total_pages['num'];
			$row['id'] = $data['id'];
			$row['news_type'] = $data['news_type'];
			$row['news_heading'] = stripslashes($data['news_heading']);
			$row['news_description'] = stripslashes($data['news_description']);
			$row['news_video_id'] = $data['news_video_id'];
			$row['news_video_url'] = $data['news_video_url'];
			$row['news_date'] = date('d-m-Y',$data['news_date']);

			if(strpos($data['news_featured_image'], $data['news_video_id']) !== false){
				$row['news_featured_image'] = $data['news_featured_image'];
				$row['news_featured_thumb'] = $data['news_featured_image'];
			} else{
				$row['news_featured_image'] = $file_path.'images/'.$data['news_featured_image'];
				$row['news_featured_thumb'] = get_thumb('images/'.$data['news_featured_image'],'300x300');
			}

			$row['total_views'] = $data['total_views'];

			$row['is_favourite'] = is_favourite($data['id'],$user_id);

			$row['share_link'] = $file_path.'view_news.php?news_id='.$data['id'];

			$row['cat_id'] = $data['cat_id'];
			$row['category_name'] = $data['category_name'];

			array_push($jsonObj,$row);

		}
		
		$set['ALL_IN_ONE_NEWS'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	// Get news list end

	// Get latest news list start
	else if($get_method['method_name']=="get_latest")
	{

		$page_limit=API_PAGE_LIMIT;

		$cat_id=explode(',', $get_method['cat_id']);	// 2, 4

		$user_id=$get_method['user_id'];

		if($cat_id[0]!=''){
			$column='';
			foreach ($cat_id as $key => $value) {
				$column.='FIND_IN_SET('.$value.', tbl_news.`cat_id`) OR ';
			}

			$column=rtrim($column,'OR ');

			$query_rec = "SELECT COUNT(*) as num FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`=tbl_category.`cid` 
			WHERE ($column) AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC";

		}else{

			$query_rec = "SELECT COUNT(*) as num FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`=tbl_category.`cid` 
			WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC";
			
		}

		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

		$limit=($get_method['page']-1) * $page_limit;

		$jsonObj= array();

		if($cat_id[0]!=''){
			$column='';
			foreach ($cat_id as $key => $value) {
				$column.='FIND_IN_SET('.$value.', tbl_news.`cat_id`) OR ';
			}

			$column=rtrim($column,'OR ');

			$query="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE ($column) AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC LIMIT $limit, $page_limit";

		}else{

			$query="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC LIMIT $limit, $page_limit";
		}	

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
			$row['pagination_limit'] = $page_limit;
			$row['total_news'] = $total_pages['num'];
			$row['id'] = $data['id'];
			$row['news_type'] = $data['news_type'];
			$row['news_heading'] = stripslashes($data['news_heading']);
			$row['news_description'] = stripslashes($data['news_description']);
			$row['news_video_id'] = $data['news_video_id'];
			$row['news_video_url'] = $data['news_video_url'];
			$row['news_date'] = date('d-m-Y',$data['news_date']);
			
			if(strpos($data['news_featured_image'], $data['news_video_id']) !== false){
				$row['news_featured_image'] = $data['news_featured_image'];
				$row['news_featured_thumb'] = $data['news_featured_image'];
			} else{
				$row['news_featured_image'] = $file_path.'images/'.$data['news_featured_image'];
				$row['news_featured_thumb'] = get_thumb('images/'.$data['news_featured_image'],'300x300');
			}

			$row['total_views'] = $data['total_views'];

			$row['is_favourite'] = is_favourite($data['id'],$user_id);

			$row['share_link'] = $file_path.'view_news.php?news_id='.$data['id'];

			$row['cat_id'] = $data['cat_id'];
			$row['category_name'] = $data['category_name'];

			array_push($jsonObj,$row);
			
		}
		
		$set['ALL_IN_ONE_NEWS'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	// Get latest news list start

	// Get latest category list start
	else if($get_method['method_name']=="get_category_latest")
	{
		$post_order_by=API_CAT_POST_ORDER_BY;

		$cat_id=explode(',', $get_method['cat_id']);	// 2, 4	

		$user_id=$get_method['user_id'];

		$page_limit=API_PAGE_LIMIT;

		if($cat_id[0]!=''){
			$column='';
			foreach ($cat_id as $key => $value) {
				$column.='FIND_IN_SET('.$value.', tbl_news.`cat_id`) OR ';
			}

			$column=rtrim($column,'OR ');

			$query_rec = "SELECT COUNT(*) as num FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`=tbl_category.`cid` 
			WHERE ($column) AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC";

		}else{

			$query_rec = "SELECT COUNT(*) as num FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`=tbl_category.`cid` 
			WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC";
			
		}

		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

		$limit=($get_method['page']-1) * $page_limit;

		$limit=($get_method['page']-1) * $page_limit;

		$jsonObj= array();	

		if($cat_id[0]!=''){
			$column='';
			foreach ($cat_id as $key => $value) {
				$column.='FIND_IN_SET('.$value.', tbl_news.`cat_id`) OR ';
			}

			$column=rtrim($column,'OR ');

			$query="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE ($column) AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC LIMIT $limit, $page_limit";

		}else{

			$query="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC LIMIT $limit, $page_limit";
		}

		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{

			$row['pagination_limit'] = $page_limit;
			$row['total_news'] = $total_pages['num'];
			$row['id'] = $data['id'];
			$row['news_type'] = $data['news_type'];
			$row['news_heading'] = stripslashes($data['news_heading']);
			$row['news_description'] = stripslashes($data['news_description']);
			$row['news_video_id'] = $data['news_video_id'];
			$row['news_video_url'] = $data['news_video_url'];
			$row['news_date'] = date('d-m-Y',$data['news_date']);
			
			if(strpos($data['news_featured_image'], $data['news_video_id']) !== false){
				$row['news_featured_image'] = $data['news_featured_image'];
				$row['news_featured_thumb'] = $data['news_featured_image'];
			} else{
				$row['news_featured_image'] = $file_path.'images/'.$data['news_featured_image'];
				$row['news_featured_thumb'] = get_thumb('images/'.$data['news_featured_image'],'300x300');
			}

			$row['total_views'] = $data['total_views'];

			$row['is_favourite'] = is_favourite($data['id'],$user_id);

			$row['share_link'] = $file_path.'view_news.php?news_id='.$data['id'];

			$row['cat_id'] = $data['cat_id'];
			$row['category_name'] = $data['category_name'];

			array_push($jsonObj,$row);
			
		}

		$set['ALL_IN_ONE_NEWS'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}
	// Get latest category list end 

	// Get video news list start
	else if($get_method['method_name']=="get_video_news")
	{
		$post_order_by=API_CAT_POST_ORDER_BY;

		$cat_id=explode(',', $get_method['cat_id']);	// 2, 4

		$user_id=$get_method['user_id'];	

		if($cat_id[0]!=''){
			$column='';
			foreach ($cat_id as $key => $value) {
				$column.='FIND_IN_SET('.$value.', tbl_news.`cat_id`) OR ';
			}

			$column=rtrim($column,'OR ');

			$query_rec = "SELECT COUNT(*) as num FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE ($column) AND tbl_news.`news_type`='video' AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC";

		}else{

			$query_rec = "SELECT COUNT(*) as num FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE tbl_news.`news_type`='video' AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` DESC";
			
		}
		
		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));
		
		$page_limit=API_PAGE_LIMIT;

		$limit=($get_method['page']-1) * $page_limit;

		$jsonObj= array();

		if($cat_id[0]!=''){
			$column='';
			foreach ($cat_id as $key => $value) {
				$column.='FIND_IN_SET('.$value.', tbl_news.`cat_id`) OR ';
			}

			$column=rtrim($column,'OR ');

			$query="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE ($column) AND tbl_news.`news_type`='video' AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` LIMIT $limit, $page_limit";

		}else{

			$query="SELECT tbl_news.*, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE tbl_news.`news_type`='video' AND tbl_news.`status`='1' AND tbl_category.`status`='1' ORDER BY tbl_news.`id` LIMIT $limit, $page_limit";
			
		}    

		$sql = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
			$row['pagination_limit'] = $page_limit;
			$row['total_news'] = $total_pages['num'];
			$row['id'] = $data['id'];
			$row['news_type'] = $data['news_type'];
			$row['news_heading'] = stripslashes($data['news_heading']);
			$row['news_description'] = stripslashes($data['news_description']);
			$row['news_video_id'] = $data['news_video_id'];
			$row['news_video_url'] = $data['news_video_url'];
			$row['news_date'] = date('d-m-Y',$data['news_date']);
			
			if(strpos($data['news_featured_image'], $data['news_video_id']) !== false){
				$row['news_featured_image'] = $data['news_featured_image'];
				$row['news_featured_thumb'] = $data['news_featured_image'];
			} else{
				$row['news_featured_image'] = $file_path.'images/'.$data['news_featured_image'];
				$row['news_featured_thumb'] = get_thumb('images/'.$data['news_featured_image'],'300x300');
			}

			$row['total_views'] = $data['total_views'];

			$row['is_favourite'] = is_favourite($data['id'],$user_id);

			$row['share_link'] = $file_path.'view_news.php?news_id='.$data['id'];

			$row['cat_id'] = $data['cat_id'];
			$row['category_name'] = $data['category_name'];

			array_push($jsonObj,$row);

		}
		
		$set['ALL_IN_ONE_NEWS'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();

	}
	// Get video news list end

	// Get single news start
	else if($get_method['method_name']=="get_single_news")
	{

		$jsonObj= array();

		$user_id=$get_method['user_id'];	
		$news_id=$get_method['news_id'];

		$query="SELECT tbl_news.*, tbl_category.`cid`, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
		LEFT JOIN tbl_category ON tbl_news.`cat_id` = tbl_category.`cid`
		WHERE tbl_category.`status`='1' AND tbl_news.`id`='$news_id'";
		$sql = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));

		while($data = mysqli_fetch_assoc($sql))
		{
			$row['id'] = $data['id'];
			$row['cat_id'] = $data['cat_id'];
			$row['news_type'] = $data['news_type'];
			$row['news_heading'] = stripslashes($data['news_heading']);
			$row['news_description'] = stripslashes($data['news_description']);
			$row['news_video_id'] = $data['news_video_id'];
			$row['news_video_url'] = $data['news_video_url'];
			$row['news_date'] = date('d-m-Y',$data['news_date']);
			
			if(strpos($data['news_featured_image'], $data['news_video_id']) !== false){
				$row['news_featured_image'] = $data['news_featured_image'];
				$row['news_featured_thumb'] = $data['news_featured_image'];
			} else{
				$row['news_featured_image'] = $file_path.'images/'.$data['news_featured_image'];
				$row['news_featured_thumb'] = get_thumb('images/'.$data['news_featured_image'],'300x300');
			}

			$row['total_views'] = $data['total_views'];

			$row['is_favourite'] = is_favourite($data['id'],$user_id);

			$row['share_link'] = $file_path.'view_news.php?news_id='.$news_id;

			$row['cid'] = $data['cid'];
			$row['category_name'] = $data['category_name'];

			// Relative news
			$sql_relatives="SELECT tbl_news.*, tbl_category.`cid`, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE tbl_category.`status`='1' AND tbl_news.`status`='1' AND tbl_news.`cat_id` IN ('".$data['cat_id']."') AND tbl_news.`id` <> '$news_id' LIMIT 5";

				$res_relative=mysqli_query($mysqli,$sql_relatives); 

				if(mysqli_num_rows($res_relative) > 0)
				{
					while ($data_relative=mysqli_fetch_array($res_relative)) {

						$row_relative['id'] = $data_relative['id'];
						$row_relative['cat_id'] = $data_relative['cat_id'];
						$row_relative['news_type'] = $data_relative['news_type'];
						$row_relative['news_heading'] = stripslashes($data_relative['news_heading']);
						$row_relative['news_description'] = stripslashes($data_relative['news_description']);
						$row_relative['news_video_id'] = $data_relative['news_video_id'];
						$row_relative['news_video_url'] = $data_relative['news_video_url'];
						$row_relative['news_date'] = date('d-m-Y',$data_relative['news_date']);

						if(strpos($data_relative['news_featured_image'], $data_relative['news_video_id']) !== false){
							$row_relative['news_featured_image'] = $data_relative['news_featured_image'];
							$row_relative['news_featured_thumb'] = $data_relative['news_featured_image'];
						} else{
							$row_relative['news_featured_image'] = $file_path.'images/'.$data_relative['news_featured_image'];
							$row_relative['news_featured_thumb'] = get_thumb('images/'.$data_relative['news_featured_image'],'300x300');
						}

						$row_relative['total_views'] = $data_relative['total_views'];

						$row_relative['is_favourite'] = is_favourite($data_relative['id'],$user_id);

						$row_relative['cid'] = $data_relative['cid'];
						$row_relative['category_name'] = $data_relative['category_name'];

						$row['relative_news'][]= $row_relative;

					}
				}
				else
				{
					$row['relative_news'][]= '';
				}

				mysqli_free_result($res_relative);

			//Gallery Images
				$qry1="SELECT * FROM tbl_news_gallery WHERE `news_id`='$news_id'";
				$result1=mysqli_query($mysqli,$qry1); 

				if($result1->num_rows > 0)
				{
					while($row_img=mysqli_fetch_array($result1)){
						$row1['image_id'] = $row_img['id'];
						$row1['image_name'] = $file_path.'images/'.$row_img['news_gallery_image'];
						$row['galley_image'][]= $row1;
					}
				}
				else
				{	
					$row['galley_image'][]= '';
				}

			//Comments
				$qry2="SELECT tbl_comments.*, tbl_users.`user_profile` FROM tbl_comments
				LEFT JOIN tbl_users
				ON tbl_comments.`user_id`=tbl_users.`id` 
				WHERE tbl_comments.`news_id`='$news_id' ORDER BY tbl_comments.`id` DESC LIMIT 5";
				$result2=mysqli_query($mysqli,$qry2); 

				if($result2->num_rows > 0)
				{
					while ($row_comments=mysqli_fetch_array($result2)) {

						$row2['comment_id'] = $row_comments['id'];
						$row2['news_id'] = $row_comments['news_id'];
						$row2['user_id'] = $row_comments['user_id'];
						$row2['user_name'] = $row_comments['user_name'];
						$row2['user_email'] = $row_comments['user_email'];
						$row2['user_profile'] = 'images/'.$row_comments['user_profile'];
						$row2['comment_text'] = $row_comments['comment_text'];
						$row2['comment_on'] = date('d M Y',$row_comments['comment_on']);
						$row['user_comments'][]= $row2;
					}
				}
				else
				{
					$row['user_comments'][]= '';
				}

				array_push($jsonObj,$row);

			}

			$view_qry=mysqli_query($mysqli,"UPDATE tbl_news SET total_views = total_views + 1 WHERE id = '$news_id'");

			$dataView = array(
				'news_id'  => $news_id,
				'views_at'  =>  date('Y-m-d')
			);		

			$qryView = Insert('tbl_views',$dataView);

			$set['ALL_IN_ONE_NEWS'] = $jsonObj;

			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
			die();
		}
	// Get single news end 

	// Get remove gallery image start
		else if($get_method['method_name']=="remove_gallery_img")
		{

			$jsonObj= array();

			$news_id=$get_method['news_id'];
			$image_id=$get_method['image_id'];

			$sql="SELECT * FROM tbl_news_gallery WHERE `id`='$image_id' AND `news_id`='$news_id'";

			$res=mysqli_query($mysqli, $sql);

			if(mysqli_num_rows($res) > 0){

				$row=mysqli_fetch_assoc($res);
				if(file_exists('images/'.$row['news_gallery_image'])){
					unlink('images/'.$row['news_gallery_image']);
				}

				$deleteSql="DELETE FROM tbl_news_gallery WHERE `id`='$image_id' AND `news_id`='$news_id'";
				mysqli_query($mysqli, $deleteSql);

				$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['img_remove_success'],'success'=>'1');

			}
			else{
				$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['no_data_msg'],'success'=>'0');	
			}

			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
			die();

		}
	// Get remove gallery image end

	// Get relative news start
		else if($get_method['method_name']=="get_relative_news")
		{

			$page_limit=API_PAGE_LIMIT;

			$news_id=trim($get_method['news_id']);
			$cat_id=trim($get_method['cat_id']);

			$user_id=$get_method['user_id'];

			$query_rec = "SELECT COUNT(*) as num FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' AND tbl_news.`cat_id` IN ('$cat_id') AND tbl_news.`id` <> '$news_id'";
			
			$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

			$limit=($get_method['page']-1) * $page_limit;

			$jsonObj= array();

			$query="SELECT tbl_news.*, tbl_category.`cid`, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' AND tbl_news.`cat_id` IN ('$cat_id') AND tbl_news.`id` <> '$news_id' LIMIT $limit, $page_limit";

			$sql = mysqli_query($mysqli,$query);

			if(mysqli_num_rows($sql) > 0){
				while($data = mysqli_fetch_assoc($sql))
				{
					$row['pagination_limit'] = $page_limit;
					$row['total_news'] = $total_pages['num'];
					$row['id'] = $data['id'];
					$row['cat_id'] = $data['cat_id'];
					$row['news_type'] = $data['news_type'];
					$row['news_heading'] = stripslashes($data['news_heading']);
					$row['news_description'] = stripslashes($data['news_description']);
					$row['news_video_id'] = $data['news_video_id'];
					$row['news_video_url'] = $data['news_video_url'];
					$row['news_date'] = date('d-m-Y',$data['news_date']);

					if(strpos($data['news_featured_image'], $data['news_video_id']) !== false){
						$row['news_featured_image'] = $data['news_featured_image'];
						$row['news_featured_thumb'] = $data['news_featured_image'];
					} else{
						$row['news_featured_image'] = $file_path.'images/'.$data['news_featured_image'];
						$row['news_featured_thumb'] = get_thumb('images/'.$data['news_featured_image'],'300x300');
					}

					$row['total_views'] = $data['total_views'];

					$row['is_favourite'] = is_favourite($data['id'],$user_id);

					$row['share_link'] = $file_path.'view_news.php?news_id='.$data['id'];

					$row['cid'] = $data['cid'];
					$row['category_name'] = $data['category_name'];

					array_push($jsonObj,$row);

				}

				$set['ALL_IN_ONE_NEWS'] = $jsonObj;
			}
			else
			{
				$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['search_result'],'success'=>'0');
			}

			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
			die();

		}
	// Get relative news end

	// Get search news start
		else if($get_method['method_name']=="get_search") {

			$page_limit=API_PAGE_LIMIT;

			$keyword=trim($get_method['search_text']);

			$user_id=$get_method['user_id'];

			$query_rec = "SELECT COUNT(*) as num FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' AND (tbl_news.`news_heading` LIKE '%$keyword%' OR tbl_news.`news_description` LIKE '%$keyword%' OR tbl_category.`category_name` LIKE '%$keyword%') ";
			
			$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

			$limit=($get_method['page']-1) * $page_limit;

			$jsonObj= array();	

			$query="SELECT tbl_news.*, tbl_category.`cid`, tbl_category.`category_name`, tbl_category.`category_image` FROM tbl_news
			LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
			WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' AND (tbl_news.`news_heading` LIKE '%$keyword%' OR tbl_news.`news_description` LIKE '%$keyword%' OR tbl_category.`category_name` LIKE '%$keyword%') LIMIT $limit, $page_limit";
			$sql = mysqli_query($mysqli,$query);

			if(mysqli_num_rows($sql) > 0){

				while($data = mysqli_fetch_assoc($sql))
				{
					$row['pagination_limit'] = $page_limit;
					$row['total_news'] = $total_pages['num'];
					$row['id'] = $data['id'];
					$row['cat_id'] = $data['cat_id'];
					$row['news_type'] = $data['news_type'];
					$row['news_heading'] = stripslashes($data['news_heading']);
					$row['news_description'] = stripslashes($data['news_description']);
					$row['news_video_id'] = $data['news_video_id'];
					$row['news_video_url'] = $data['news_video_url'];
					$row['news_date'] = date('d-m-Y',$data['news_date']);

					if(strpos($data['news_featured_image'], $data['news_video_id']) !== false){
						$row['news_featured_image'] = $data['news_featured_image'];
						$row['news_featured_thumb'] = $data['news_featured_image'];
					} else{
						$row['news_featured_image'] = $file_path.'images/'.$data['news_featured_image'];
						$row['news_featured_thumb'] = get_thumb('images/'.$data['news_featured_image'],'300x300');
					}

					$row['total_views'] = $data['total_views'];

					$row['is_favourite'] = is_favourite($data['id'],$user_id);

					$row['share_link'] = $file_path.'view_news.php?news_id='.$data['id'];

					$row['cid'] = $data['cid'];
					$row['category_name'] = $data['category_name'];

					array_push($jsonObj,$row);

				}

				$set['ALL_IN_ONE_NEWS'] = $jsonObj;
			}
			else
			{
				$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['search_result'],'success'=>'0');
			}

			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
			die();

		}
	// Get search news end

	// Get comments start
		else if ($get_method['method_name']=="get_comments") {

			$jsonObj= array();	
			$query_rec = "SELECT COUNT(*) as num FROM tbl_comments
			WHERE tbl_comments.`news_id`='".$get_method['news_id']."' ORDER BY tbl_comments.`id` DESC";
			$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

			$page_limit=50;
			
			$limit=($get_method['page']-1) * $page_limit;

			$jsonObj= array();	

			$query="SELECT tbl_comments.*, tbl_users.`user_profile` FROM tbl_comments
			LEFT JOIN tbl_users
			ON tbl_comments.`user_id`=tbl_users.`id`
			WHERE tbl_comments.`news_id`='".$get_method['news_id']."' ORDER BY tbl_comments.`id` DESC LIMIT $limit, $page_limit";

			$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

			while($data = mysqli_fetch_assoc($sql))
			{
				$row['pagination_limit'] = $page_limit;
				$row['total_news'] = $total_pages['num'];
				$row['comment_id'] = $data['id'];
				$row['news_id'] = $data['news_id'];
				$row['user_id'] = $data['user_id'];
				$row['user_name'] = $data['user_name'];
				$row['user_email'] = $data['user_email'];
				$row['user_profile'] = 'images/'.$data['user_profile'];
				$row['comment_text'] = $data['comment_text']; 
				$row['comment_on'] = date('d M Y',$data['comment_on']); 

				array_push($jsonObj,$row);

			}

			$set['ALL_IN_ONE_NEWS'] = $jsonObj;

			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
			die();

		}
	// Get comment end

	// Get channel start
		else if ($get_method['method_name']=="get_channel") {

		//echo $get_method['channel_id'];
			$sql1="SELECT * FROM tbl_channel WHERE id='1'";	
			$result1 = mysqli_query($mysqli,$sql1)or die(mysqli_error($mysqli));	

			$jsonObj= array();

			while ($row1 = mysqli_fetch_assoc($result1)) 
			{
				$row=array();
				$row['id'] = $row1['id'];
				$row['channel_name'] = $row1['channel_name'];
				$row['channel_type'] = $row1['channel_type'];
				$row['channel_url'] = $row1['channel_url'];
				$row['channel_description'] = $row1['channel_description'];
				$row['channel_logo'] = $row1['channel_logo'];					 
				array_push($jsonObj,$row);
			}

			$set['ALL_IN_ONE_NEWS'] = $jsonObj;				

			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
			die();
		}
	// Get channel end

	// Get add user comment start
		else if ($get_method['method_name']=="user_comment") {

			$qry = "SELECT * FROM tbl_users WHERE `id` = '".$get_method['user_id']."'"; 
			$result = mysqli_query($mysqli,$qry);
			$row = mysqli_fetch_assoc($result);

			$data = array(
				'news_id'  => $get_method['news_id'],
				'user_id'  => $get_method['user_id'],
				'user_name'  => $row['name'],				    
				'user_email'  =>  $row['email'],
				'comment_text'  =>  cleanInput($get_method['comment_text']),
				'comment_on'  =>  strtotime(date('d-m-Y h:i:s A'))
			);		

			$qry = Insert('tbl_comments',$data);

			$last_id=LastID('tbl_comments');

			$sql="SELECT tbl_comments.*, tbl_users.`user_profile` FROM tbl_comments LEFT JOIN tbl_users ON tbl_comments.`user_id`=tbl_users.`id` WHERE tbl_comments.`id`='$last_id'";

			$res=mysqli_query($mysqli,$sql);

			$row_comment=mysqli_fetch_assoc($res);

			$info['MSG'] = $app_lang['comment_success'];
			$info['success'] = '1';

			$info['comment_id'] = $row_comment['id'];
			$info['news_id'] = $row_comment['news_id'];
			$info['user_id'] = $row_comment['user_id'];
			$info['user_name'] = $row_comment['user_name'];
			$info['user_email'] = $row_comment['user_email'];
			$info['user_profile'] = $file_path.'images/'.$row_comment['user_profile'];
			$info['comment_text'] = $row_comment['comment_text']; 
			$info['comment_on'] = calculate_time_span($row_comment['comment_on'],true);		 

			$set['ALL_IN_ONE_NEWS'][]=$info;

			header( 'Content-Type: application/json; charset=utf-8');
			$json = json_encode($set);				
			echo $json;
			exit;
		}
	// Get add user comment end

	// Get remove user comments start
		else if($get_method['method_name']=="remove_comment")
		{
			$jsonObj= array();	
			$comment_id=$get_method['comment_id'];
			$news_id=$get_method['news_id'];

			Delete('tbl_comments','id='.$get_method['comment_id'].'');

			$info['success']="1";	
			$info['MSG']=$app_lang['comment_delete'];

			$sql="SELECT tbl_comments.*, tbl_users.`user_profile` FROM tbl_comments
			LEFT JOIN tbl_users
			ON tbl_comments.`user_id`=tbl_users.`id`
			WHERE tbl_comments.`news_id`='$news_id' ORDER BY tbl_comments.`id` DESC LIMIT 1 OFFSET 4";

			$res=mysqli_query($mysqli,$sql);
			$row=mysqli_fetch_assoc($res);

			$info['comment_id'] = $row['id'];
			$info['news_id'] = $row['news_id'];
			$info['user_id'] = $row['user_id'];
			$info['user_name'] = $row['user_name'];
			$info['user_email'] = $row['user_email'];
			$info['user_profile'] = $file_path.'images/'.$row_comment['user_profile'];
			$info['comment_text'] = $row_comment['comment_text']; 
			$info['comment_on'] = calculate_time_span($row_comment['comment_on'],true);		

			array_push($jsonObj,$info);

			$set['ALL_IN_ONE_NEWS'] = $jsonObj;

			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
			die();
		}
	// Get remove user comments end

	// Get add user report start
		else if($get_method['method_name']=="user_report")
		{
			$jsonObj= array();	
			$user_id=$get_method['user_id'];
			$news_id=$get_method['news_id'];
			$report=cleanInput($get_method['report']);

			$sql="SELECT * FROM tbl_reports WHERE `news_id`=$news_id AND `user_id`='$user_id'";
			$res=mysqli_query($mysqli,$sql);

			$info['success']="1";	

			if(mysqli_num_rows($res)==0){
				$data = array(
					'news_id' => $news_id,				    
					'user_id'  => $user_id,				    
					'report'  =>  $report,
					'report_on'  =>  strtotime(date('d-m-Y h:i:s A'))
				);

				$qry = Insert('tbl_reports',$data);	

				$info['MSG']=$app_lang['report_success'];
			}else{
				$info['MSG']=$app_lang['report_already'];
			}

			array_push($jsonObj,$info);

			$set['ALL_IN_ONE_NEWS'] = $jsonObj;

			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
			die();

		}
	// Get add user report end

	// Get app details start 
		else if($get_method['method_name']=="get_app_details")
		{

			$jsonObj= array();	

			$query="SELECT * FROM tbl_settings WHERE id='1'";
			$res = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));

			$data = mysqli_fetch_assoc($res);

			$sql="SELECT * FROM tbl_channel where id='1'";
			$result=mysqli_query($mysqli,$sql);
			$channel_row=mysqli_fetch_assoc($result);

			if($get_method['user_id']!=''){

				$sql1="SELECT * FROM tbl_users where id='".$get_method['user_id']."'";
				$res1=mysqli_query($mysqli,$sql1);
				$user_row=mysqli_fetch_assoc($res1);			

				$row['user_category'] = $user_row['category_id'];

			}else{
				$row['user_category'] = '';
			}

			$row['app_name'] = $data['app_name'];
			$row['app_logo'] = $file_path.'images/'.$data['app_logo'];
			$row['app_version'] = $data['app_version'];
			$row['app_author'] = $data['app_author'];
			$row['app_contact'] = $data['app_contact'];
			$row['app_email'] = $data['app_email'];
			$row['app_website'] = $data['app_website'];
			$row['app_description'] = $data['app_description'];
			$row['app_developed_by'] = $data['app_developed_by'];

			$row['app_privacy_policy'] = stripslashes($data['app_privacy_policy']);
			
			$row['app_terms_conditions'] = stripslashes($data['app_terms_conditions']);

			$row['package_name'] = $data['package_name'];
			if ($data['android_ad_network'] == 'admob' or $data['android_ad_network'] == 'facebook') {
				$row['publisher_id'] = $data['publisher_id'];
			} else {
				$row['publisher_id'] = '';
			}

			$row['interstital_ad'] = $data['interstital_ad'];
			$row['interstital_ad_type'] = $data['interstital_ad_type'];
			$row['interstital_ad_click'] = $data['interstital_ad_click'];

			if ($data['interstital_ad_type'] == 'facebook') {
				$row['interstital_ad_id'] = $data['interstital_facebook_id'];
			} else if ($data['interstital_ad_type'] == 'admob') {
				$row['interstital_ad_id'] = $data['interstital_ad_id'];
			}else if ($data['interstital_ad_type'] == 'applovins') {
				$row['interstital_ad_id'] = $data['interstitial_applovin_id'];
			} else if ($data['interstital_ad_type'] == 'wortise') {
				$row['interstital_ad_id'] = $data['interstitial_wortise_id'];
			} else if ($data['interstital_ad_type'] == 'startapp') {
				$row['interstital_ad_id'] = '';
			}

			

			$row['banner_ad'] = $data['banner_ad'];
			$row['banner_ad_type'] = $data['banner_ad_type'];

			if ($data['banner_ad_type'] == 'facebook') {
				$row['banner_ad_id'] = $data['banner_facebook_id'];
			} else if ($data['banner_ad_type'] == 'admob') {
				$row['banner_ad_id'] = $data['banner_ad_id'];
			} else if ($data['banner_ad_type'] == 'applovins') {
				$row['banner_ad_id'] = $data['banner_applovin_id'];
			} else if ($data['banner_ad_type'] == 'wortise') {
				$row['banner_ad_id'] = $data['banner_wortise_id'];
			} else if ($data['banner_ad_type'] == 'startapp') {
				$row['banner_ad_id'] = '';
			}

			$row['native_ad'] = $data['native_ad'];
			$row['native_ad_type'] = $data['native_ad_type'];

			if ($data['native_ad_type'] == 'facebook') {
				$row['native_ad_id'] = $data['native_facebook_id'];
			} else if ($data['native_ad_type'] == 'admob') {
				$row['native_ad_id'] = $data['native_ad_id'];
			} else if ($data['native_ad_type'] == 'applovins') {
				$row['native_ad_id'] = $data['native_applovin_id'];
			} else if($data['native_ad_type']=='wortise'){
				$row['native_ad_id'] = $data['native_wortise_id'];
			} else{
				$row['native_ad_id'] = '';
			}

			$row['native_position'] = $data['native_position'];
 
			if ($data['android_ad_network'] == 'startapp') {
				$row['wortise_app_id']='';
				 $row['startapp_app_id'] = $data['start_ads_id'];
			  } else if ($data['android_ad_network'] == 'wortise') {
				$row['wortise_app_id'] = $data['wortise_app_id'];
				 $row['startapp_app_id'] = '';
			  } else {
				 $row['startapp_app_id'] = '';
				$row['wortise_app_id']='';
			  }
			
			$row['channel_status'] = $channel_row['channel_status'];

			$row['app_update_status'] = $data['app_update_status'];
			$row['app_new_version'] = $data['app_new_version'];
			$row['app_update_desc'] = stripslashes($data['app_update_desc']);
			$row['app_redirect_url'] = $data['app_redirect_url'];
			$row['cancel_update_status'] = $data['cancel_update_status'];

			array_push($jsonObj,$row);

			$set['ALL_IN_ONE_NEWS'] = $jsonObj;

			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
			die();	
		}
	// Get app details end

	// Get user login start 
		else if($get_method['method_name']=="user_login")
		{

			$email= htmlentities(trim($get_method['email']));
			$password = htmlentities(trim($get_method['password']));
			$auth_id = htmlentities(trim($get_method['auth_id']));
			$user_type = htmlentities(trim($get_method['type']));

			if($user_type=='normal' OR $user_type=='Normal'){

			// simple login
				$qry = "SELECT * FROM tbl_users WHERE email = '$email' AND (`user_type`='Normal' OR `user_type`='normal') AND `id` <> 0"; 
				$result = mysqli_query($mysqli,$qry);
				$num_rows = mysqli_num_rows($result);

				if($num_rows > 0){
					$row = mysqli_fetch_assoc($result);

					if($row['status']==1){
						if($row['password']==md5($password)){

							$user_id=$row['id'];

							$sql_activity_log="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
							$res_activity_log=mysqli_query($mysqli, $sql_activity_log);

							if(mysqli_num_rows($res_activity_log) == 0){
	                        // insert active log

								$data_log = array(
									'user_id'  =>  $user_id,
									'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
								);

								$qry = Insert('tbl_active_log',$data_log);

							}
							else{
	                        // update active log
								$data_log = array(
									'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
								);

								$update=Update('tbl_active_log', $data_log, "WHERE user_id = '$user_id'");  
							}

							mysqli_free_result($res_activity_log);

							$is_reporter=false;

							if($row['is_reporter']=='true'){
								$is_reporter=true;	                    	
							}

							if($row['user_profile']!=''){
								$user_profile=$file_path.'images/'.$row['user_profile'];
							}
							else{
								$user_profile='';
							}

							$category_id=$row['category_id'];
							if(is_null($row['category_id'])){
								$category_id='';
							}

							$set['ALL_IN_ONE_NEWS'][]=array('user_id' => $row['id'], 'name'=>$row['name'], 'email'=>$row['email'], 'MSG' => $app_lang['login_success'], 'auth_id' => '', 'success'=>'1', 'is_reporter' => $is_reporter,'user_profile' => $user_profile, 'user_category' => $category_id);
						}
						else{
						// invalid password
							$set['ALL_IN_ONE_NEWS'][]=array('MSG' =>$app_lang['invalid_password'],'success'=>'0');
						}
					}
					else{
					// account is deactivated
						$set['ALL_IN_ONE_NEWS'][]=array('MSG' =>$app_lang['account_deactive'],'success'=>'0');
					}

				}
				else{
				// email not found
					$set['ALL_IN_ONE_NEWS'][]=array('MSG' =>$app_lang['email_not_found'],'success'=>'0');	
				}
			}
			else if($user_type=='google' OR $user_type=='Google'){

			// login with google

				$sql = "SELECT * FROM tbl_users WHERE (`email` = '$email' OR `auth_id`='$auth_id') AND (`user_type`='Google' OR `user_type`='google')";

				$res=mysqli_query($mysqli, $sql);

				if(mysqli_num_rows($res) > 0){
					$row = mysqli_fetch_assoc($res);

					if($row['status']==0){
						$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['account_deactive'],'success'=>'0');
					}	
					else
					{
						$user_id=$row['id'];

						$sql_activity_log="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
						$res_activity_log=mysqli_query($mysqli, $sql_activity_log);

						if(mysqli_num_rows($res_activity_log) == 0){
                        // insert active log

							$data_log = array(
								'user_id'  =>  $user_id,
								'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
							);

							$qry = Insert('tbl_active_log',$data_log);

						}
						else{
                        // update active log
							$data_log = array(
								'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
							);

							$update=Update('tbl_active_log', $data_log, "WHERE user_id = '$user_id'");  
						}

						mysqli_free_result($res_activity_log);

						$is_reporter=false;

						if($row['is_reporter']=='true'){
							$is_reporter=true;	                    	
						}

						if($row['user_profile']!=''){
							$user_profile=$file_path.'images/'.$row['user_profile'];
						}
						else{
							$user_profile='';
						}

						$category_id=$row['category_id'];
						if(is_null($row['category_id'])){
							$category_id='';
						}

						$set['ALL_IN_ONE_NEWS'][]=array('user_id' => $row['id'], 'name'=>$row['name'], 'email'=>$row['email'], 'MSG' => $app_lang['login_success'], 'auth_id' => $auth_id, 'success'=>'1', 'is_reporter' => $is_reporter,'user_profile' => $user_profile, 'user_category' => $category_id);

						$data = array(
							'auth_id'  =>  $auth_id
						);  

						$updatePlayerID=Update('tbl_users', $data, "WHERE `id` = '".$row['id']."'");
					}

				}
				else{
					$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['email_not_found'],'success'=>'0');
				}
			}
			else if($user_type=='facebook' OR $user_type=='Facebook'){

			// login with google

				$sql = "SELECT * FROM tbl_users WHERE (`email` = '$email' OR `auth_id`='$auth_id') AND (`user_type`='Facebook' OR `user_type`='facebook')";

				$res=mysqli_query($mysqli, $sql);

				if(mysqli_num_rows($res) > 0){
					$row = mysqli_fetch_assoc($res);

					if($row['status']==0){
						$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['account_deactive'],'success'=>'0');
					}	
					else
					{

						$user_id=$row['id'];

						$sql_activity_log="SELECT * FROM tbl_active_log WHERE `user_id`='$user_id'";
						$res_activity_log=mysqli_query($mysqli, $sql_activity_log);

						if(mysqli_num_rows($res_activity_log) == 0){
                        // insert active log

							$data_log = array(
								'user_id'  =>  $user_id,
								'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
							);

							$qry = Insert('tbl_active_log',$data_log);

						}
						else{
                        // update active log
							$data_log = array(
								'date_time'  =>  strtotime(date('d-m-Y h:i:s A'))
							);

							$update=Update('tbl_active_log', $data_log, "WHERE user_id = '$user_id'");  
						}

						mysqli_free_result($res_activity_log);

						$is_reporter=false;

						if($row['is_reporter']=='true'){
							$is_reporter=true;	                    	
						}

						if($row['user_profile']!=''){
							$user_profile=$file_path.'images/'.$row['user_profile'];
						}
						else{
							$user_profile='';
						}

						$category_id=$row['category_id'];
						if(is_null($row['category_id'])){
							$category_id='';
						}

						$set['ALL_IN_ONE_NEWS'][]=array('user_id' => $row['id'], 'name'=>$row['name'], 'email'=>$row['email'], 'MSG' => $app_lang['login_success'], 'auth_id' => $auth_id, 'success'=>'1', 'is_reporter' => $is_reporter,'user_profile' => $user_profile, 'user_category' => $category_id);

						$data = array(
							'auth_id'  =>  $auth_id
						);  

						$updatePlayerID=Update('tbl_users', $data, "WHERE `id` = '".$row['id']."'");
					}

				}
				else{
					$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['email_not_found'],'success'=>'0');
				}

			}
			else{
				$set['ALL_IN_ONE_NEWS'][]=array('success'=>'0', 'MSG' =>$app_lang['invalid_user_type']);
			}

			header( 'Content-Type: application/json; charset=utf-8' );
			echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
			die();
		}
	// Get user login end

	// Get user register start
		else if($get_method['method_name']=="user_register")
		{

  		$user_type=trim($get_method['type']); //Google, Normal, Facebook

  		$email=addslashes(trim($get_method['email']));
  		$auth_id=addslashes(trim($get_method['auth_id']));

  		$to = $get_method['email'];
  		$recipient_name=$get_method['name'];
		// subject

  		$subject = str_replace('###', APP_NAME, $app_lang['register_mail_lbl']);

  		if($user_type=='Google' || $user_type=='google'){
			// register with google

  			$sql="SELECT * FROM tbl_users WHERE (`email` = '$email' OR `auth_id`='$auth_id') AND `user_type`='Google'";
  			$res=mysqli_query($mysqli,$sql);
  			$num_rows = mysqli_num_rows($res);
  			$row = mysqli_fetch_assoc($res);

  			if($num_rows == 0)
  			{
    			// data is not available
  				$data = array(
  					'user_type'=>'Google',
  					'name'  => addslashes(trim($get_method['name'])),				    
  					'email'  =>  addslashes(trim($get_method['email'])),
  					'password'  =>  trim($get_method['password']),
  					'phone'  =>  addslashes(trim($get_method['phone'])),
  					'registered_on'  =>  strtotime(date('d-m-Y h:i:s A')), 
  					'status'  =>  '1'
  				);		

  				$qry = Insert('tbl_users',$data);

  				$user_id=mysqli_insert_id($mysqli);

  				$message='<div style="background-color: #eee;" align="center"><br />
  				<table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
  				<tbody>
  				<tr>
  				<td colspan="2" bgcolor="#FFFFFF" align="center" ><img src="'.$file_path.'images/'.APP_LOGO.'" alt="logo" /></td>
  				</tr>
  				<br>
  				<br>
  				<tr>
  				<td colspan="2" bgcolor="#FFFFFF" align="center" style="padding-top:25px;">
  				<img src="'.$file_path.'assets/images/thankyoudribble.gif" alt="header" auto-height="100" width="50%"/>
  				</td>
  				</tr>
  				<tr>
  				<td width="600" valign="top" bgcolor="#FFFFFF">
  				<table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
  				<tbody>
  				<tr>
  				<td valign="top">
  				<table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
  				<tbody>
  				<tr>
  				<td>
  				<p style="color: #717171; font-size: 24px; margin-top:0px; margin:0 auto; text-align:center;"><strong>'.$app_lang['welcome_lbl'].', '.addslashes(trim($get_method['name'])).'</strong></p>
  				<br>
  				<p style="color:#15791c; font-size:18px; line-height:32px;font-weight:500;margin-bottom:30px; margin:0 auto; text-align:center;">'.$app_lang['google_register_msg'].'<br /></p>
  				<br/>
  				<p style="color:#999; font-size:17px; line-height:32px;font-weight:500;">'.$app_lang['thank_you_lbl'].' '.APP_NAME.'</p>
  				</td>
  				</tr>
  				</tbody>
  				</table>
  				</td>
  				</tr>
  				</tbody>
  				</table>
  				</td>
  				</tr>
  				<tr>
  				<td style="color: #262626; padding: 20px 0; font-size: 18px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">'.$app_lang['email_copyright'].' '.APP_NAME.'.</td>
  				</tr>
  				</tbody>
  				</table>
  				</div>';

  				$set['ALL_IN_ONE_NEWS'][]=array('user_id' => strval($user_id),'name'=>$get_method['name'],'email'=>$get_method['email'], 'success'=>'1', 'MSG' =>'', 'auth_id' => $auth_id, 'is_reporter' => false,'user_category' => '');
  			}
  			else{
  				$data = array(
  					'auth_id'  =>  $auth_id,
  				); 

  				$update=Update('tbl_users', $data, "WHERE id = '".$row['id']."'");

  				if($row['status']==0)
  				{
  					$set['ALL_IN_ONE_NEWS'][]=array('MSG' =>$app_lang['account_deactive'],'success'=>'0');
  				}	
  				else
  				{
  					$is_reporter=false;

  					if($row['is_reporter']=='true'){
  						$is_reporter=true;	                    	
  					}

  					if($row['user_profile']!=''){
  						$user_profile=$file_path.'images/'.$row['user_profile'];
  					}
  					else{
  						$user_profile='';
  					}

  					$category_id=$row['category_id'];
  					if(is_null($row['category_id'])){
  						$category_id='';
  					}

  					$set['ALL_IN_ONE_NEWS'][]=array('user_id' => $row['id'], 'name'=>$row['name'], 'email'=>$row['email'], 'MSG' => $app_lang['login_success'], 'auth_id' => $auth_id, 'success'=>'1', 'is_reporter' => $is_reporter,'user_profile' => $user_profile, 'user_category' => $category_id);
  				}
  			}

  		}
  		else if($user_type=='Facebook' || $user_type=='facebook'){
			// register with facebook

  			$sql="SELECT * FROM tbl_users WHERE (`email` = '$email' OR `auth_id`='$auth_id') AND `user_type`='Facebook'";
  			$res=mysqli_query($mysqli,$sql);
  			$num_rows = mysqli_num_rows($res);
  			$row = mysqli_fetch_assoc($res);

  			if($num_rows == 0)
  			{
    			// data is not available
  				$data = array(
  					'user_type'=>'Facebook',
  					'name'  => addslashes(trim($get_method['name'])),				    
  					'email'  =>  addslashes(trim($get_method['email'])),
  					'password'  =>  trim($get_method['password']),
  					'phone'  =>  addslashes(trim($get_method['phone'])),
  					'registered_on'  =>  strtotime(date('d-m-Y h:i:s A')), 
  					'status'  =>  '1'
  				);		

  				$qry = Insert('tbl_users',$data);

  				$user_id=mysqli_insert_id($mysqli);

  				$message='<div style="background-color: #eee;" align="center"><br />
  				<table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
  				<tbody>
  				<tr>
  				<td colspan="2" bgcolor="#FFFFFF" align="center" ><img src="'.$file_path.'images/'.APP_LOGO.'" alt="logo" /></td>
  				</tr>
  				<br>
  				<br>
  				<tr>
  				<td colspan="2" bgcolor="#FFFFFF" align="center" style="padding-top:25px;">
  				<img src="'.$file_path.'assets/images/thankyoudribble.gif" alt="header" auto-height="100" width="50%"/>
  				</td>
  				</tr>
  				<tr>
  				<td width="600" valign="top" bgcolor="#FFFFFF">
  				<table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
  				<tbody>
  				<tr>
  				<td valign="top">
  				<table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
  				<tbody>
  				<tr>
  				<td>
  				<p style="color: #717171; font-size: 24px; margin-top:0px; margin:0 auto; text-align:center;"><strong>'.$app_lang['welcome_lbl'].', '.addslashes(trim($get_method['name'])).'</strong></p>
  				<br>
  				<p style="color:#15791c; font-size:18px; line-height:32px;font-weight:500;margin-bottom:30px; margin:0 auto; text-align:center;">'.$app_lang['facebook_register_msg'].'<br /></p>
  				<br/>
  				<p style="color:#999; font-size:17px; line-height:32px;font-weight:500;">'.$app_lang['thank_you_lbl'].' '.APP_NAME.'</p>
  				</td>
  				</tr>
  				</tbody>
  				</table>
  				</td>
  				</tr>
  				</tbody>
  				</table>
  				</td>
  				</tr>
  				<tr>
  				<td style="color: #262626; padding: 20px 0; font-size: 18px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">'.$app_lang['email_copyright'].' '.APP_NAME.'.</td>
  				</tr>
  				</tbody>
  				</table>
  				</div>';

  				$set['ALL_IN_ONE_NEWS'][]=array('user_id' => strval($user_id),'name'=>$get_method['name'],'email'=>$get_method['email'], 'success'=>'1', 'MSG' =>'', 'auth_id' => $auth_id, 'is_reporter' => false,'user_category' => '');
  			}
  			else{
  				$data = array(
  					'auth_id'  =>  $auth_id,
  				); 

  				$update=Update('tbl_users', $data, "WHERE id = '".$row['id']."'");

  				if($row['status']==0)
  				{
  					$set['ALL_IN_ONE_NEWS'][]=array('MSG' =>$app_lang['account_deactive'],'success'=>'0');
  				}	
  				else
  				{
  					$is_reporter=false;

  					if($row['is_reporter']=='true'){
  						$is_reporter=true;	                    	
  					}

  					if($row['user_profile']!=''){
  						$user_profile=$file_path.'images/'.$row['user_profile'];
  					}
  					else{
  						$user_profile='';
  					}

  					$category_id=$row['category_id'];
  					if(is_null($row['category_id'])){
  						$category_id='';
  					}

  					$set['ALL_IN_ONE_NEWS'][]=array('user_id' => $row['id'], 'name'=>$row['name'], 'email'=>$row['email'], 'MSG' => $app_lang['login_success'], 'auth_id' => $auth_id, 'success'=>'1', 'is_reporter' => $is_reporter,'user_profile' => $user_profile, 'user_category' => $category_id);
  				}
  			}

  		}
  		else{
			// for normal registration

  			$sql = "SELECT * FROM tbl_users WHERE email = '$email'"; 
  			$result = mysqli_query($mysqli, $sql);
  			$row = mysqli_fetch_assoc($result);

  			if (!filter_var($get_method['email'], FILTER_VALIDATE_EMAIL)) 
  			{
  				$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['invalid_email_format'],'success'=>'0');
  			}
  			else if($row['email']!="")
  			{
  				$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['email_exist'],'success'=>'0');
  			}
  			else
  			{	
  				if($_FILES['profile_img']['name']!=''){
  					$ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);

  					$user_profile=rand(0,99999)."_user.".$ext;

			        //Main Image
  					$tpath1='images/'.$user_profile;   

  					if($ext!='png')  {
  						$pic1=compress_image($_FILES["profile_img"]["tmp_name"], $tpath1, 80);
  					}
  					else{
  						$tmp = $_FILES['profile_img']['tmp_name'];
  						move_uploaded_file($tmp, $tpath1);
  					}
  				}
  				else{
  					$user_profile='';
  				}

  				$data = array(
  					'user_type'=>'Normal',											 
  					'name'  => addslashes(trim($get_method['name'])),				    
  					'email'  =>  addslashes(trim($get_method['email'])),
  					'password'  =>  md5(trim($get_method['password'])),
  					'phone'  =>  addslashes(trim($get_method['phone'])),
  					'registered_on'  =>  strtotime(date('d-m-Y h:i:s A')), 
  					'user_profile' => $user_profile,
  					'status'  =>  '1'
  				);		

  				$qry = Insert('tbl_users',$data);

  				$message='<div style="background-color: #eee;" align="center"><br />
  				<table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
  				<tbody>
  				<tr>
  				<td colspan="2" bgcolor="#FFFFFF" align="center" ><img src="'.$file_path.'images/'.APP_LOGO.'" alt="logo" /></td>
  				</tr>
  				<br>
  				<br>
  				<tr>
  				<td colspan="2" bgcolor="#FFFFFF" align="center" style="padding-top:25px;">
  				<img src="'.$file_path.'assets/images/thankyoudribble.gif" alt="header" auto-height="100" width="50%"/>
  				</td>
  				</tr>
  				<tr>
  				<td width="600" valign="top" bgcolor="#FFFFFF">
  				<table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
  				<tbody>
  				<tr>
  				<td valign="top">
  				<table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
  				<tbody>
  				<tr>
  				<td>
  				<p style="color: #717171; font-size: 24px; margin-top:0px; margin:0 auto; text-align:center;"><strong>'.$app_lang['welcome_lbl'].', '.addslashes(trim($get_method['name'])).'</strong></p>
  				<br>
  				<p style="color:#15791c; font-size:18px; line-height:32px;font-weight:500;margin-bottom:30px; margin:0 auto; text-align:center;">'.$app_lang['normal_register_msg'].'<br /></p>
  				<br/>
  				<p style="color:#999; font-size:17px; line-height:32px;font-weight:500;">'.$app_lang['thank_you_lbl'].' '.APP_NAME.'</p>
  				</td>
  				</tr>
  				</tbody>
  				</table>
  				</td>
  				</tr>
  				</tbody>
  				</table>
  				</td>
  				</tr>
  				<tr>
  				<td style="color: #262626; padding: 20px 0; font-size: 18px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">'.$app_lang['email_copyright'].' '.APP_NAME.'.</td>
  				</tr>
  				</tbody>
  				</table>
  				</div>';

  				$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['register_success'],'success'=>'1');
  			}

  		}

  		send_email($to,$recipient_name,$subject,$message);

  		header( 'Content-Type: application/json; charset=utf-8' );
  		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
  		die();
  	}
  	// Get user register end

  	// Get user profile start
  	else if($get_method['method_name']=="user_profile")
  	{
  		$jsonObj= array();	

  		$user_id=$get_method['user_id'];

  		$qry = "SELECT * FROM tbl_users WHERE id = '$user_id'"; 

  		$result = mysqli_query($mysqli,$qry);

  		$row = mysqli_fetch_assoc($result);	

  		$data['success']="1";
  		$data['user_id'] = $row['id'];
  		$data['name'] = $row['name'];
  		$data['email'] = $row['email'];
  		$data['phone'] = ($row['phone']!='') ? $row['phone'] : '';
  		$data['user_profile'] = ($row['user_profile']!='') ? $file_path.'images/'.$row['user_profile'] : '';
  		$data['is_reporter'] = $row['is_reporter'];

  		array_push($jsonObj,$data);

  		$set['ALL_IN_ONE_NEWS'] = $jsonObj;

  		header( 'Content-Type: application/json; charset=utf-8' );
  		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
  		die();
  	}
	// Get user profile end

	// Get edit user profile start
  	else if($get_method['method_name']=="edit_profile")
  	{
  		$jsonObj= array();	

  		$qry = "SELECT * FROM tbl_users WHERE `id` = '".$get_method['user_id']."'"; 
  		$result = mysqli_query($mysqli,$qry);
  		$row = mysqli_fetch_assoc($result);

  		$profile_img=$row['profile_img'];

  		if (!filter_var($get_method['email'], FILTER_VALIDATE_EMAIL)) 
  		{
  			$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['invalid_email_format'],'success'=>'0');

  			header( 'Content-Type: application/json; charset=utf-8' );
  			$json = json_encode($set);
  			echo $json;
  			exit;
  		}
  		else if($row['email']==$get_method['email'] AND $row['id']!=$get_method['user_id'])
  		{
  			$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['email_exist'],'success'=>'0');

  			header( 'Content-Type: application/json; charset=utf-8' );
  			$json = json_encode($set);
  			echo $json;
  			exit;
  		}else{


  			$data = array(
  				'name'  =>  cleanInput($get_method['name']),
  				'email'  =>  trim($get_method['email']),
  				'phone'  =>  cleanInput($get_method['phone']),
  			);


  			if(isset($_FILES['profile_img']) && $_FILES['profile_img']['name']!="")
  			{

  				if($row['profile_img']!="" OR !file_exists('images/'.$row['profile_img']))
  				{
  					unlink('images/'.$row['profile_img']);
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

  			if($get_method['password']!=""){
  				$data = array_merge($data, array("password" => md5(trim($get_method['password']))));
  			}

  		}

  		if($profile_img!=''){
  			$user_profile=$file_path.'images/'.$profile_img;
  		}
  		else{
  			$user_profile='';
  		}

  		$user_edit=Update('tbl_users', $data, "WHERE id = '".$get_method['user_id']."'");

  		$data1 = array(
  			'user_name'  =>  $get_method['name'],
  			'user_email'  =>  $get_method['email']
  		);

  		$data_edit=Update('tbl_users', $data1, "WHERE user_id = '".$get_method['user_id']."'");

  		$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['update_success'],'success'=>'1', 'user_profile' => $user_profile);

  		header( 'Content-Type: application/json; charset=utf-8' );
  		$json = json_encode($set);
  		echo $json;
  		exit;
  	}
	// Get edit user profile end

	// Get forgot password start
  	else if($get_method['method_name']=="forgot_pass")
  	{	 
  		$qry = "SELECT * FROM tbl_users WHERE email = '".$get_method['email']."' AND `user_type`='Normal' AND `id` <> 0"; 
  		$result = mysqli_query($mysqli,$qry);
  		$row = mysqli_fetch_assoc($result);

  		if($row['email']!="")
  		{
  			$password=generateRandomPassword(7);

  			$new_password=md5($password);

  			$to = $row['email'];
  			$recipient_name=$row['name'];
			// subject
  			$subject = str_replace('###', APP_NAME, $app_lang['forgot_password_sub_lbl']);

  			$message='<div style="background-color: #f9f9f9;" align="center"><br />
  			<table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
  			<tbody>
  			<tr>
  			<td colspan="2" bgcolor="#FFFFFF" align="center"><img src="'.$file_path.'images/'.APP_LOGO.'" alt="header" /></td>
  			</tr>
  			<tr>
  			<td width="600" valign="top" bgcolor="#FFFFFF"><br>
  			<table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
  			<tbody>
  			<tr>
  			<td valign="top"><table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
  			<tbody>
  			<tr>
  			<td><p style="color: #262626; font-size: 24px; margin-top:0px;"><strong>'.$app_lang['dear_lbl'].' '.$row['name'].'</strong></p>
  			<p style="color:#262626; font-size:20px; line-height:32px;font-weight:500;"><br>'.$app_lang['your_password_lbl'].' '.$password.'</p>


  			<p style="color:#262626; font-size:17px; line-height:32px;font-weight:500;margin-bottom:30px;">'.$app_lang['thank_you_lbl'].' '.APP_NAME.'</p>

  			</td>
  			</tr>
  			</tbody>
  			</table></td>
  			</tr>

  			</tbody>
  			</table></td>
  			</tr>
  			<tr>
  			<td style="color: #262626; padding: 20px 0; font-size: 18px; border-top:5px solid #52bfd3;" colspan="2" align="center" bgcolor="#ffffff">'.$app_lang['email_copyright'].' '.APP_NAME.'.</td>
  			</tr>
  			</tbody>
  			</table>
  			</div>';

  			send_email($to,$recipient_name,$subject,$message);

  			$sql="UPDATE tbl_users SET `password`='$new_password' WHERE `id`='".$row['id']."'";
  			mysqli_query($mysqli,$sql);

  			$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['password_sent_mail'],'success'=>'1');
  		}
  		else
  		{  	 

  			$set['ALL_IN_ONE_NEWS'][]=array('MSG' => $app_lang['email_not_found'],'success'=>'0');

  		}

  		header( 'Content-Type: application/json; charset=utf-8' );
  		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE));
  		die();
  	}
	// Get forgot password end

	// Get save category start
  	else if($get_method['method_name']=="save_category")
  	{
  		$jsonObj= array();	

  		$user_id=$get_method['user_id'];
  		$cat_id=$get_method['cat_id'];

  		$data = array(
  			'category_id'  =>  $cat_id
  		);

  		$user_edit=Update('tbl_users', $data, "WHERE id = '".$user_id."'");

  		$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['category_save'],'success'=>'1');

  		header( 'Content-Type: application/json; charset=utf-8' );
  		$json = json_encode($set);
  		echo $json;
  		exit;

  	}
	// Get save category end

	// Get upload news start
  	else if($get_method['method_name']=="upload_news")
  	{
  		$jsonObj= array();

  		$user_id=$get_method['user_id'];

  		$sql="SELECT * FROM tbl_users WHERE `id`='$user_id' AND `is_reporter` = 'true' AND `status`='1'";

  		$res=mysqli_query($mysqli, $sql);

  		if(mysqli_num_rows($res) > 0)
  		{
  			$row_user=mysqli_fetch_assoc($res);

  			$status=0;

  			if($row_user['auto_approve']=='true'){
  				$status=1;
  			}

  			if($get_method['news_type']=='video')
  			{
  				$video_url=$get_method['video_url'];

  				preg_match_all("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $video_url, $matches); 
  				$video_id=  $matches[0][0];
  			}
  			else
  			{
  				$video_id='';
  				$video_url ='';
  			}

  			$ext = pathinfo($_FILES['news_featured_image']['name'], PATHINFO_EXTENSION);

  			$news_featured_image=rand(0,99999)."_".date('dmYhis').".".$ext;

            //Main Image
  			$tpath1='images/'.$news_featured_image;   

  			if($ext!='png')  {
  				$pic1=compress_image($_FILES["news_featured_image"]["tmp_name"], $tpath1, 80);
  			}
  			else{
  				$tmp = $_FILES['news_featured_image']['tmp_name'];
  				move_uploaded_file($tmp, $tpath1);
  			}

  			$data = array( 
  				'user_id'  =>  $user_id,
  				'cat_id'  =>  $get_method['cat_id'],
  				'news_type'  =>  $get_method['news_type'],
  				'news_heading'  =>  addslashes($get_method['news_heading']),
  				'news_description'  =>  addslashes($get_method['news_description']),
  				'news_date'  =>  strtotime($get_method['news_date']),
  				'news_featured_image'  =>  $news_featured_image,
  				'news_video_id'  =>  $video_id,
  				'news_video_url'  =>  $video_url,
  				'status' => $status
  			);		

  			$qry = Insert('tbl_news',$data);	

  			$news_id=mysqli_insert_id($mysqli);

  			$size_sum = array_sum($_FILES['news_gallery_image']['size']);

  			if($size_sum > 0)
  			{ 
  				for ($i = 0; $i < count($_FILES['news_gallery_image']['name']); $i++) 
  				{
  					$ext = pathinfo($_FILES['news_gallery_image']['name'][$i], PATHINFO_EXTENSION);

  					$news_gallery_image=rand(0,99999).''.$news_id."_".date('dmYhis')."_gallery.".$ext;

	                //Main Image
  					$tpath1='images/'.$news_gallery_image;   

  					if($ext!='png')  {
  						$pic1=compress_image($_FILES["news_gallery_image"]["tmp_name"][$i], $tpath1, 80);
  					}
  					else{
  						$tmp = $_FILES['news_gallery_image']['tmp_name'][$i];
  						move_uploaded_file($tmp, $tpath1);
  					}

  					$data1 = array(
  						'news_id'=>$news_id,
  						'news_gallery_image'  => $news_gallery_image
  					);      

  					$qry1 = Insert('tbl_news_gallery',$data1);
  				}
  			}

  			if($status==1)
  				$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['news_upload'],'success'=>'1');
  			else
  				$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['news_upload_wait'],'success'=>'1');
  		}
  		else{
  			$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['news_upload_error'],'success'=>'0');
  		}

  		header( 'Content-Type: application/json; charset=utf-8' );
  		$json = json_encode($set);
  		echo $json;
  		exit;

  	}
	// Get upload news end

	// Get edit upload news start
  	else if($get_method['method_name']=="edit_uploaded_news")
  	{
  		$jsonObj= array();

  		$user_id=$get_method['user_id'];
  		$news_id=$get_method['news_id'];

  		$sql="SELECT * FROM tbl_users WHERE `id`='$user_id' AND `is_reporter` = 'true' AND `status`='1'";

  		$res=mysqli_query($mysqli, $sql);

  		if(mysqli_num_rows($res) > 0)
  		{
  			$row_user=mysqli_fetch_assoc($res);

  			$status=0;

  			if($row_user['auto_approve']=='true'){
  				$status=1;
  			}

  			$qry="SELECT * FROM tbl_news WHERE `id`='$news_id'";
  			$result=mysqli_query($mysqli,$qry);
  			$row=mysqli_fetch_assoc($result);

  			$news_featured_image=$row['news_featured_image'];

  			if($get_method['news_type']=='video')
  			{
  				$video_url=$get_method['video_url'];

  				preg_match_all("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $video_url, $matches); 
  				$video_id=  $matches[0][0];
  			}
  			else
  			{
  				$video_id='';
  				$video_url ='';
  			}

  			if(isset($_FILES['news_featured_image']) && $_FILES['news_featured_image']['name']!='')
  			{
  				unlink('images/'.$row['news_featured_image']);

  				$ext = pathinfo($_FILES['news_featured_image']['name'], PATHINFO_EXTENSION);

  				$news_featured_image=rand(0,99999)."_".date('dmYhis').".".$ext;

				//Main Image
  				$tpath1='images/'.$news_featured_image;   

  				if($ext!='png')  {
  					$pic1=compress_image($_FILES["news_featured_image"]["tmp_name"], $tpath1, 80);
  				}
  				else{
  					$tmp = $_FILES['news_featured_image']['tmp_name'];
  					move_uploaded_file($tmp, $tpath1);
  				}
  			}

  			$data = array( 
  				'user_id'  =>  $user_id,
  				'cat_id'  =>  $get_method['cat_id'],
  				'news_type'  =>  $get_method['news_type'],
  				'news_heading'  =>  addslashes($get_method['news_heading']),
  				'news_description'  =>  addslashes($get_method['news_description']),
  				'news_date'  =>  strtotime($get_method['news_date']),
  				'news_featured_image'  =>  $news_featured_image,
  				'news_video_id'  =>  $video_id,
  				'news_video_url'  =>  $video_url
  			);		

  			$updated=Update('tbl_news', $data, "WHERE id = '".$news_id."'");

  			$size_sum = array_sum($_FILES['news_gallery_image']['size']);

  			if($size_sum > 0)
  			{ 
  				for ($i = 0; $i < count($_FILES['news_gallery_image']['name']); $i++) 
  				{
  					$ext = pathinfo($_FILES['news_gallery_image']['name'][$i], PATHINFO_EXTENSION);

  					$news_gallery_image=rand(0,99999).''.$news_id."_".date('dmYhis')."_gallery.".$ext;

	                //Main Image
  					$tpath1='images/'.$news_gallery_image;   

  					if($ext!='png')  {
  						$pic1=compress_image($_FILES["news_gallery_image"]["tmp_name"][$i], $tpath1, 80);
  					}
  					else{
  						$tmp = $_FILES['news_gallery_image']['tmp_name'][$i];
  						move_uploaded_file($tmp, $tpath1);
  					}

  					$data1 = array(
  						'news_id'=>$news_id,
  						'news_gallery_image'  => $news_gallery_image
  					);      

  					$qry1 = Insert('tbl_news_gallery',$data1);
  				}
  			}

  			$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['news_updated'],'success'=>'1');
  		}
  		else{
  			$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['news_upload_error'],'success'=>'0');
  		}

  		header( 'Content-Type: application/json; charset=utf-8' );
  		$json = json_encode($set);
  		echo $json;
  		exit;
  	}
	// Get edit upload news end

	// Get reporter request start
  	else if($get_method['method_name']=="reporter_request")
  	{
  		$jsonObj= array();	

  		$user_id=$get_method['user_id'];

  		$email=$get_method['email'];
  		$password=md5($get_method['password']);

  		if($user_id!=0){
  			if(user_info($user_id,'status')!='0' OR user_info($user_id,'status')!=''){
  				if(user_info($user_id,'is_reporter')=='true'){
  					$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['request_reporter_err'],'success'=>'0');
  				}
  				else{

  					$sql="SELECT * FROM tbl_request_reporter WHERE `user_id`='".$get_method['user_id']."'";
  					$res=mysqli_query($mysqli, $sql);

  					if(user_info($user_id,'user_type')!='Normal'){
						// update email and password
  						$data_update = array(
  							'email'  =>  $email,
  							'password'  =>  $password
  						);

  						$update=Update('tbl_users', $data_update, "WHERE id = '$user_id'");
  					}

  					if(mysqli_num_rows($res) == 0){
  						$data = array( 
  							'user_id'  =>  cleanInput($get_method['user_id']),
  							'request_on'  =>  strtotime(date('d-m-Y h:i:s A')), 
  						);

  						$qry = Insert('tbl_request_reporter',$data);

  						$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['request_reporter_success'],'success'=>'1');
  					}
  					else{

  						$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['request_reporter_already'],'success'=>'0');
  					}

  				}
  			}
  			else{
  				$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['account_deactive'],'success'=>'0');
  			}
  		}
  		else{
  			$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['login_err'],'success'=>'0');
  		}

  		header( 'Content-Type: application/json; charset=utf-8' );
  		$json = json_encode($set);
  		echo $json;
  		exit;
  	}
	// Get reporter request end

	// Get user uploaded news start
  	else if($get_method['method_name']=="users_uploaded_news")
  	{
  		$jsonObj= array();
  		$user_id=$get_method['user_id'];

  		$page_limit=API_PAGE_LIMIT;

  		$query_rec = "SELECT COUNT(*) as num FROM tbl_news
  		LEFT JOIN tbl_category ON tbl_news.`cat_id`=tbl_category.`cid` 
  		WHERE tbl_category.`status`='1' AND tbl_news.`user_id`='$user_id' ORDER BY tbl_news.`id` DESC";

  		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

  		$limit=($get_method['page']-1) * $page_limit;

  		$jsonObj= array();

  		$query="SELECT tbl_news.*, tbl_category.`category_name` FROM tbl_news
  		LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
  		WHERE tbl_category.`status`='1' AND tbl_news.`user_id`='$user_id' ORDER BY tbl_news.`id` DESC LIMIT $limit, $page_limit";

  		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

  		while($data = mysqli_fetch_assoc($sql))
  		{
  			$row['pagination_limit'] = $page_limit;
  			$row['total_news'] = $total_pages['num'];
  			$row['id'] = $data['id'];
  			$row['news_type'] = $data['news_type'];
  			$row['news_heading'] = stripslashes($data['news_heading']);
  			$row['news_description'] = stripslashes($data['news_description']);
  			$row['news_video_id'] = $data['news_video_id'];
  			$row['news_video_url'] = $data['news_video_url'];
  			$row['news_date'] = date('d-m-Y',$data['news_date']);

  			if(strpos($data['news_featured_image'], $data['news_video_id']) !== false){
  				$row['news_featured_image'] = $data['news_featured_image'];
  				$row['news_featured_thumb'] = $data['news_featured_image'];
  			} else{
  				$row['news_featured_image'] = $file_path.'images/'.$data['news_featured_image'];
  				$row['news_featured_thumb'] = get_thumb('images/'.$data['news_featured_image'],'300x300');
  			}

  			$row['total_views'] = $data['total_views'];

  			$row['is_approved'] = ($data['status']) ? 'true' : 'false';

  			$row['is_favourite'] = is_favourite($data['id'],$user_id);

  			$row['share_link'] = $file_path.'view_news.php?news_id='.$data['id'];

  			$row['cat_id'] = $data['cat_id'];
  			$row['category_name'] = $data['category_name'];

  			array_push($jsonObj,$row);

  		}

  		$set['ALL_IN_ONE_NEWS'] = $jsonObj;

  		header( 'Content-Type: application/json; charset=utf-8' );
  		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
  		die();
  	}
	// Get user uploaded news end

	// Get delete news start
  	else if($get_method['method_name']=="delete_news")
  	{
  		$jsonObj= array();	

  		$news_id=$get_method['news_id'];
  		$user_id=$get_method['user_id'];

  		$sql="SELECT * FROM tbl_news WHERE `id`='$news_id' AND `user_id`='$user_id'";
  		$res=mysqli_query($mysqli, $sql);

  		if(mysqli_num_rows($res) > 0){

  			$row=mysqli_fetch_assoc($res);

  			if($row['news_featured_image']!="" AND file_exists('images/'.$row['news_featured_image']))
  			{
  				unlink('images/'.$row['news_featured_image']);
  				unlink('images/thumbs/'.$row['news_featured_image']);
  			}

  			$sql_gallery="SELECT * FROM tbl_news_gallery WHERE `news_id` = '".$news_id."'";
  			$res_gallery=mysqli_query($mysqli, $sql_gallery);

  			while ($row_gallery=mysqli_fetch_assoc($res_gallery)) {
  				if(file_exists('images/'.$row_gallery['news_gallery_image']));{
  					unlink('images/'.$row_gallery['news_gallery_image']);
  				}
  			}

  			mysqli_free_result($res_gallery);

  			Delete('tbl_news_gallery','news_id='.$news_id);

  			$deleteSql="DELETE FROM tbl_comments WHERE `news_id` IN ($news_id)";
  			mysqli_query($mysqli, $deleteSql);

  			$deleteSql="DELETE FROM tbl_reports WHERE `news_id` IN ($news_id)";
  			mysqli_query($mysqli, $deleteSql);

  			$deleteSql="DELETE FROM tbl_views WHERE `news_id` IN ($news_id)";
  			mysqli_query($mysqli, $deleteSql);

  			$deleteSql="DELETE FROM tbl_news WHERE `id` IN ($news_id)";
  			mysqli_query($mysqli, $deleteSql);

  			$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['news_deleted'],'success'=>'1');
  		}
  		else{
  			$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['news_data_not_found'],'success'=>'0');	
  		}

  		header( 'Content-Type: application/json; charset=utf-8' );
  		$json = json_encode($set);
  		echo $json;
  		exit;
  	}
	// Get delete news end

	// Get favourite news start
  	else if($get_method['method_name']=="favourite_news")
  	{
  		$jsonObj= array();	

  		$news_id=$get_method['news_id'];
  		$user_id=$get_method['user_id'];

  		$sql="SELECT * FROM tbl_favourite WHERE `news_id`='$news_id' AND `user_id`='$user_id'";
  		$res=mysqli_query($mysqli, $sql);

  		if(mysqli_num_rows($res) == 0){

  			$data = array(
  				'news_id'  =>  $news_id,
  				'user_id'  =>  $user_id,
  				'created_at'  =>  strtotime(date('d-m-Y h:i:s A')), 
  			);

  			$qry = Insert('tbl_favourite',$data);

  			$share_link = $file_path.'view_news.php?news_id='.$news_id;

  			$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['favourite_success'],'share_link' => $share_link ,'success'=>'1');
  		}
  		else{
  			$deleteSql="DELETE FROM tbl_favourite WHERE `news_id`='$news_id' AND `user_id`='$user_id'";

  			if(mysqli_query($mysqli, $deleteSql)){
  				$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['favourite_remove_success'],'success'=>'0');	
  			}
  			else{
  				$set['ALL_IN_ONE_NEWS'][]=array('MSG'=>$app_lang['favourite_remove_error'],'success'=>'1');
  			}
  		}

  		header( 'Content-Type: application/json; charset=utf-8' );
  		$json = json_encode($set);
  		echo $json;
  		exit;
  	}
	// Get favourite news end

	// Get favourite news list start
  	else if($get_method['method_name']=="users_favourite_news")
  	{
  		$jsonObj= array();
  		$user_id=$get_method['user_id'];

  		$page_limit=API_PAGE_LIMIT;

  		$query_rec = "SELECT COUNT(*) as num FROM tbl_news
  		LEFT JOIN tbl_favourite ON tbl_news.`id`= tbl_favourite.`news_id`
  		LEFT JOIN tbl_category ON tbl_news.`cat_id`=tbl_category.`cid` 
  		WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' AND tbl_favourite.`user_id`='$user_id' ORDER BY tbl_favourite.`id` DESC";

  		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

  		$limit=($get_method['page']-1) * $page_limit;

  		$jsonObj= array();

  		$query="SELECT tbl_news.*, tbl_category.`category_name` FROM tbl_news
  		LEFT JOIN tbl_favourite ON tbl_news.`id`= tbl_favourite.`news_id` 
  		LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
  		WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' AND tbl_favourite.`user_id`='$user_id' ORDER BY tbl_favourite.`id` DESC LIMIT $limit, $page_limit";

  		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

  		while($data = mysqli_fetch_assoc($sql))
  		{
  			$row['pagination_limit'] = $page_limit;
  			$row['total_news'] = $total_pages['num'];
  			$row['id'] = $data['id'];
  			$row['news_type'] = $data['news_type'];
  			$row['news_heading'] = stripslashes($data['news_heading']);
  			$row['news_description'] = stripslashes($data['news_description']);
  			$row['news_video_id'] = $data['news_video_id'];
  			$row['news_video_url'] = $data['news_video_url'];
  			$row['news_date'] = date('d-m-Y',$data['news_date']);

  			if(strpos($data['news_featured_image'], $data['news_video_id']) !== false){
  				$row['news_featured_image'] = $data['news_featured_image'];
  				$row['news_featured_thumb'] = $data['news_featured_image'];
  			} else{
  				$row['news_featured_image'] = $file_path.'images/'.$data['news_featured_image'];
  				$row['news_featured_thumb'] = get_thumb('images/'.$data['news_featured_image'],'300x300');
  			}

  			$row['total_views'] = $data['total_views'];

  			$row['is_favourite'] = is_favourite($data['id'],$user_id);

  			$row['share_link'] = $file_path.'view_news.php?news_id='.$data['id'];

  			$row['cat_id'] = $data['cat_id'];
  			$row['category_name'] = $data['category_name'];

  			array_push($jsonObj,$row);

  		}

  		$set['ALL_IN_ONE_NEWS'] = $jsonObj;

  		header( 'Content-Type: application/json; charset=utf-8' );
  		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
  		die();
  	}
	// Get favourite news list end

	// Get recent news start
  	else if($get_method['method_name']=="get_recent_news")
  	{
  		$jsonObj= array();

  		$page_limit=API_PAGE_LIMIT;

  		$ids=$get_method['news_ids'];

  		$user_id=$get_method['user_id'];

  		$query_rec = "SELECT COUNT(*) as num FROM tbl_news
  		LEFT JOIN tbl_category ON tbl_news.`cat_id`=tbl_category.`cid` 
  		WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' AND tbl_news.`id` IN ($ids) ORDER BY tbl_news.`id` DESC";

  		$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query_rec));

  		$limit=($get_method['page']-1) * $page_limit;

  		$jsonObj= array();

  		$query="SELECT * FROM tbl_news
  		LEFT JOIN tbl_category ON tbl_news.`cat_id`= tbl_category.`cid` 
  		WHERE tbl_news.`status`='1' AND tbl_category.`status`='1' AND tbl_news.`id` IN ($ids) ORDER BY tbl_news.`id` DESC LIMIT $limit, $page_limit";

  		$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));

  		while($data = mysqli_fetch_assoc($sql))
  		{
  			$row['pagination_limit'] = $page_limit;
  			$row['total_news'] = $total_pages['num'];
  			$row['id'] = $data['id'];
  			$row['news_type'] = $data['news_type'];
  			$row['news_heading'] = stripslashes($data['news_heading']);
  			$row['news_description'] = stripslashes($data['news_description']);
  			$row['news_video_id'] = $data['news_video_id'];
  			$row['news_video_url'] = $data['news_video_url'];
  			$row['news_date'] = date('d-m-Y',$data['news_date']);

  			if(strpos($data['news_featured_image'], $data['news_video_id']) !== false){
  				$row['news_featured_image'] = $data['news_featured_image'];
  				$row['news_featured_thumb'] = $data['news_featured_image'];
  			} else{
  				$row['news_featured_image'] = $file_path.'images/'.$data['news_featured_image'];
  				$row['news_featured_thumb'] = get_thumb('images/'.$data['news_featured_image'],'300x300');
  			}

  			$row['total_views'] = $data['total_views'];

  			$row['is_favourite'] = is_favourite($data['id'],$user_id);

  			$row['share_link'] = $file_path.'view_news.php?news_id='.$data['id'];

  			$row['cat_id'] = $data['cat_id'];
  			$row['category_name'] = $data['category_name'];

  			array_push($jsonObj,$row);

  		}

  		$set['ALL_IN_ONE_NEWS'] = $jsonObj;

  		header( 'Content-Type: application/json; charset=utf-8' );
  		echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
  		die();
  	}
	// Get recent news end
  	else
  	{
  		$get_method = checkSignSalt($_POST['data']);
  	}	

  ?>