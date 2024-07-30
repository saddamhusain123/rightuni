<?php 
require("includes/connection.php");
require("includes/function.php");
require("language/language.php");
include("language/app_language.php");

include("smtp_email.php");

$file_path = getBaseUrl();

error_reporting(0);

	// Get user info
function get_user_info($user_id,$field_name) 
{
	global $mysqli;

	$qry_user="SELECT * FROM users WHERE id='".$user_id."'";
	$query1=mysqli_query($mysqli,$qry_user);
	$row_user = mysqli_fetch_array($query1);

	$num_rows1 = mysqli_num_rows($query1);

	if ($num_rows1 > 0)
	{     
		return $row_user[$field_name];
	}
	else
	{
		return "";
	}
}

$response=array();

	// get total comments
function total_comments($news_id)
{
	global $mysqli;

	$query="SELECT COUNT(*) AS total_comments FROM comments WHERE `news_id`='$news_id'";
	$sql = mysqli_query($mysqli,$query)or die(mysqli_error($mysqli));
	$row=mysqli_fetch_assoc($sql);
	return stripslashes($row['total_comments']);
}

	// Send notification
function send_notification($fields){

	$fields = json_encode($fields);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic '.ONESIGNAL_REST_KEY));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	$notify_res = curl_exec($ch);

	curl_close($ch);

	return $notify_res;
}

$_SESSION['class']="success";

switch ($_POST['action']) {
	case 'toggle_status':
	$table_nm = $_POST['table'];

	$sql_schema="SHOW COLUMNS FROM $table_nm";
	$res_schema=mysqli_query($mysqli, $sql_schema);
	$row_schema=mysqli_fetch_array($res_schema);

	$id = $_POST['id'];
	$for_action = $_POST['for_action'];
	$column = $_POST['column'];
	$tbl_id = $row_schema[0];

			// Enable & disable status
	if($for_action=='enable'){
		$data = array($column  =>  '1');
		$edit_status=Update($table_nm, $data, "WHERE $tbl_id = '$id'");
		$_SESSION['msg']="13";

		if($table_nm=='news'){

			$sql_news="SELECT * FROM news WHERE `id` IN ($id) AND `user_id` <> '0'";
			$res_news=mysqli_query($mysqli, $sql_news);

			$content = array("en" => "Your news has been approved by admin.");

			while ($row=mysqli_fetch_assoc($res_news)) {

				$user_id=$row['user_id'];

				$fields = array(
					'app_id' => ONESIGNAL_APP_ID,
					'included_segments' => array('Subscribed Users'), 
					'data' => array("foo" => "bar","type"=>'news',"post_id"=>$row['id'],"title"=>addslashes($row['news_heading']),"external_link"=>false),
					'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $user_id)),
					'headings'=> array("en" => APP_NAME),
					'contents' => $content
				);

				send_notification($fields);

			}

			mysqli_free_result($res_news);
		}


	}else{
		$data = array($column  =>  '0');
		$edit_status=Update($table_nm, $data, "WHERE $tbl_id = '$id'");
		$_SESSION['msg']="14";
	}

	$response['status']=1;
	$response['action']=$for_action;
	echo json_encode($response);
	break;

		// Get remove all comments	
	case 'removeAllComment':

	$ids=implode(',', $_POST['ids']);

	$sqlDelete="DELETE FROM comments WHERE `news_id` IN ($ids)";

	if(mysqli_query($mysqli, $sqlDelete)){
		$response['status']=1;	
	}
	else{
		$response['status']=0;
	}

	$response['status']=1;
	$_SESSION['msg']="12";	
	echo json_encode($response);
	break;	

		// Get remove all comment	
	case 'removeComment':

	$news_id=$_POST['news_id'];

	Delete('comments','news_id='.$news_id);

	$response['status']=1;
	$response['msg']=$client_lang['12'];
	echo json_encode($response);
	break;

		// Get view remove comment 	
	case 'viewComment':

	$id=$_POST['id'];

	Delete('comments','id='.$id);

	$response['status']=1;
	$response['msg']=$client_lang['12'];
	echo json_encode($response);
	break;



		// Get remove all reports	
	case 'removeAllReports':

	$ids=implode(",", $_POST['ids']);

	$sqlDelete="DELETE FROM reports WHERE `news_id` IN ($ids)";

	if(mysqli_query($mysqli, $sqlDelete)){
		$response['status']=1;	
	}
	else{
		$response['status']=0;
	}

	$response['status']=1;	
	$_SESSION['msg']="12";
	echo json_encode($response);

	break;

		// Get remove gallery image	
	case 'remove_gallery_img':
	$id=$_POST['id'];

	$img=$_POST['img'];

	if(file_exists('images/'.$img)){
		unlink('images/'.$img);
	}

	Delete('news_gallery','id='.$id);

	$response['status']=1;
	$response['msg']=$client_lang['img_remove_msg'];
	echo json_encode($response);
	break;

		// For mutli action perform	
	case 'multi_action':

	$action=$_POST['for_action'];
	
	$table=$_POST['table'];

	if(is_array($_POST['id']))
		$ids=implode(",", $_POST['id']);
	else
		$ids=$_POST['id'];

	if($action=='enable'){

		$sql="UPDATE $table SET `status`='1' WHERE `id` IN ($ids)";
		mysqli_query($mysqli, $sql);
		$_SESSION['msg']="13";

		if($table=='news'){

			$sql_news="SELECT * FROM news WHERE `id` IN ($ids) AND `user_id` <> '0'";
			$res_news=mysqli_query($mysqli, $sql_news);

			$content = array("en" => "Your news has been approved by admin.");

			while ($row=mysqli_fetch_assoc($res_news)) {

				$user_id=$row['user_id'];

				$fields = array(
					'app_id' => ONESIGNAL_APP_ID,                                       
					'included_segments' => array('Subscribed Users'), 
					'data' => array("foo" => "bar","type"=>'news',"post_id"=>$row['id'],"title"=>addslashes($row['news_heading']),"external_link"=>false),
					'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $user_id)),
					'headings'=> array("en" => APP_NAME),
					'contents' => $content
				);

				send_notification($fields);

			}

			mysqli_free_result($res_news);
		}


	}
	else if($action=='disable'){
		$sql="UPDATE $table SET `status`='0' WHERE `id` IN ($ids)";
		if(mysqli_query($mysqli, $sql)){
			$_SESSION['msg']="14";
		}
	}
	else if($action=='delete'){

		if($table=='users'){

			$deleteSql="DELETE FROM comments WHERE `user_id` IN ($ids)";
			mysqli_query($mysqli, $deleteSql);

			$deleteSql="DELETE FROM reports WHERE `user_id` IN ($ids)";
			mysqli_query($mysqli, $deleteSql);

			$deleteSql="DELETE FROM active_log WHERE `user_id` IN ($ids)";
			mysqli_query($mysqli, $deleteSql);

			

			$deleteSql="DELETE FROM favourite WHERE `user_id` IN ($ids)";
			mysqli_query($mysqli, $deleteSql);

			$deleteSql="DELETE FROM request_reporter WHERE `user_id` IN ($ids)";
			mysqli_query($mysqli, $deleteSql);

			$sql="SELECT * FROM news WHERE `user_id` IN ($ids) AND `user_id` <> 0";
			$res=mysqli_query($mysqli, $sql);

			while ($row=mysqli_fetch_assoc($res)){

				if($row['news_featured_image']!="" AND file_exists('images/'.$row['news_featured_image']))
				{
					unlink('images/'.$row['news_featured_image']);
					unlink('images/thumbs/'.$row['news_featured_image']);
				}

				$sql_gallery="SELECT * FROM news_gallery WHERE `news_id` = '".$row['id']."'";
				$res_gallery=mysqli_query($mysqli, $sql_gallery);

				while ($row_gallery=mysqli_fetch_assoc($res_gallery)) {
					if(file_exists('images/'.$row_gallery['news_gallery_image']));{
						unlink('images/'.$row_gallery['news_gallery_image']);
					}
				}

				mysqli_free_result($res_gallery);

				Delete('news_gallery','news_id='.$row['id']);

				Delete('comments','news_id='.$row['id']);
				Delete('reports','news_id='.$row['id']);
				Delete('views','news_id='.$row['id']);

			}

			$deleteSql="DELETE FROM news WHERE `user_id` IN ($ids)";
			mysqli_query($mysqli, $deleteSql);

			mysqli_free_result($res);

			$sql="SELECT * FROM users WHERE `id` IN ($ids)";
			$res=mysqli_query($mysqli, $sql);

			while ($row=mysqli_fetch_assoc($res)) {
				if($row['user_profile']!="" AND file_exists('images/'.$row['user_profile']))
				{
					unlink('images/'.$row['user_profile']);
				}
			}

			mysqli_free_result($res);

			$deleteSql="DELETE FROM $table WHERE `id` IN ($ids)";
			mysqli_query($mysqli, $deleteSql);
		}
		else if($table=='colleges') {

	    $sql = "SELECT * FROM colleges WHERE `id` IN ($ids)";
	    $res = mysqli_query($mysqli, $sql);

	    while ($row = mysqli_fetch_assoc($res)) {

	        if($row['news_featured_image'] != "" AND file_exists('images/'.$row['news_featured_image'])) {
	            unlink('images/'.$row['news_featured_image']);
	            unlink('images/thumbs/'.$row['news_featured_image']);
	        }

	        $sql_gallery = "SELECT * FROM college_gallery WHERE `college_id` = '".$row['id']."'";
	        $res_gallery = mysqli_query($mysqli, $sql_gallery);

	        while ($row_gallery = mysqli_fetch_assoc($res_gallery)) {
	            if(file_exists('images/'.$row_gallery['news_gallery_image'])) {
	                unlink('images/'.$row_gallery['news_gallery_image']);
	            }
	        }

	        mysqli_free_result($res_gallery);

	        // Instead of deleting from college_gallery, set deleted to 1
	        $updateSql = "UPDATE college_gallery SET `deleted` = 1 WHERE `college_id` = ".$row['id'];
	        mysqli_query($mysqli, $updateSql);
	    }

	  
	    $updateSql = "UPDATE college_details SET `deleted` = 1 WHERE `college_id` IN ($ids)";
	    mysqli_query($mysqli, $updateSql);

	   
	    $updateSql = "UPDATE college_fees SET `deleted` = 1 WHERE `college_id` IN ($ids)";
	    mysqli_query($mysqli, $updateSql);

	   
	    $updateSql = "UPDATE college_course_manage SET `deleted` = 1 WHERE `college_id` IN ($ids)";
	    mysqli_query($mysqli, $updateSql);

	    
	    $updateSql = "UPDATE $table SET `deleted` = 1 WHERE `id` IN ($ids)";
	    mysqli_query($mysqli, $updateSql);
	}

else if($table=='blogs'){

	$sql="SELECT * FROM blogs WHERE `id` IN ($ids)";
	$res=mysqli_query($mysqli, $sql);
	
	$deleteSql="UPDATE $table SET deleted = 1 WHERE id IN ($ids)";
	mysqli_query($mysqli, $deleteSql);
}
		else if($table=='category'){

			$sql="SELECT * FROM news WHERE `cat_id` = '$ids'";
			$res=mysqli_query($mysqli, $sql);
			while ($row=mysqli_fetch_assoc($res)){

				if($row['news_featured_image']!="" AND file_exists('images/'.$row['news_featured_image']))
				{
					unlink('images/'.$row['news_featured_image']);
					unlink('images/thumbs/'.$row['news_featured_image']);
				}

				$sql_gallery="SELECT * FROM news_gallery WHERE `news_id` = '".$row['id']."'";
				$res_gallery=mysqli_query($mysqli, $sql_gallery);

				while ($row_gallery=mysqli_fetch_assoc($res_gallery)) {
					if(file_exists('images/'.$row_gallery['news_gallery_image']));{
						unlink('images/'.$row_gallery['news_gallery_image']);
					}
				}

				mysqli_free_result($res_gallery);

				Delete('news_gallery','news_id='.$row['id']);

				$deleteSql="DELETE FROM comments WHERE `news_id`='".$row['id']."'";
				mysqli_query($mysqli, $deleteSql);

				$deleteSql="DELETE FROM reports WHERE `news_id`='".$row['id']."'";
				mysqli_query($mysqli, $deleteSql);

				$deleteSql="DELETE FROM views WHERE `news_id`='".$row['id']."'";
				mysqli_query($mysqli, $deleteSql);

			}

			$deleteSql="DELETE FROM news WHERE `cat_id` = '$ids'";
			mysqli_query($mysqli, $deleteSql);

			$updateNews="UPDATE news SET `cat_id` = CAST(`cat_id` AS UNSIGNED) & ~POW($ids, FIND_IN_SET('$ids', `cat_id`)) WHERE FIND_IN_SET('$ids', `cat_id`)";

			mysqli_query($mysqli, $updateNews);

			$sql="SELECT * FROM category WHERE `cid` IN ($ids)";
			$res=mysqli_query($mysqli, $sql);
			while ($row=mysqli_fetch_assoc($res)) {
				if($row['category_image']!="" AND file_exists('images/'.$row['category_image']))
				{
					unlink('images/'.$row['category_image']);
					unlink('images/thumbs/'.$row['category_image']);
				}
			}

			$deleteSql="DELETE FROM $table WHERE `cid` IN ($ids)";
			mysqli_query($mysqli, $deleteSql);
		}
		else if($table=='courses'){

			$sql="SELECT * FROM courses WHERE `id` = '$ids'";
			$res=mysqli_query($mysqli, $sql);
			
			$deleteSql="UPDATE $table SET deleted = 1 WHERE id IN ($ids)";
			mysqli_query($mysqli, $deleteSql);
		}
		

		$_SESSION['msg']="12";
	}
	else if($action=='approve_request'){

		$sql="SELECT * FROM request_reporter WHERE `id` IN ($ids)";
		$res=mysqli_query($mysqli, $sql);

		$subject = str_replace('###', APP_NAME, $app_lang['register_mail_lbl']);

		while ($row=mysqli_fetch_assoc($res)) {

			$user_id=$row['user_id'];

			$content = array("en" => "Your reporter request has been approved by admin.");

			$sql_update="UPDATE users SET `status`='1', WHERE `id`='$user_id'";
			mysqli_query($mysqli, $sql_update);

			$fields = array(
				'app_id' => ONESIGNAL_APP_ID,                                       
				'included_segments' => array('Subscribed Users'), 
				'data' => array("foo" => "bar","type" => "","external_link"=>false),
				'filters' => array(array('field' => 'tag', 'key' => 'user_id', 'relation' => '=', 'value' => $user_id)),
				'headings'=> array("en" => APP_NAME),
				'contents' => $content
			);

			send_notification($fields);

			$email=get_user_info($user_id, 'email');

			if($email!=''){

				$name=get_user_info($user_id, 'name');

				$message='<div style="background-color: #f9f9f9;" align="center"><br />
				<table style="font-family: OpenSans,sans-serif; color: #666666;" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
				<tbody>
				<tr>
				<td colspan="2" bgcolor="#FFFFFF" align="center"><img src="'.getBaseUrl().'images/'.APP_LOGO.'" alt="header" /></td>
				</tr>
				<tr>
				<td width="600" valign="top" bgcolor="#FFFFFF"><br>
				<table style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; padding: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
				<tbody>
				<tr>
				<td valign="top"><table border="0" align="left" cellpadding="0" cellspacing="0" style="font-family:OpenSans,sans-serif; color: #666666; font-size: 10px; width:100%;">
				<tbody>
				<tr>
				<td>
				<p style="color: #262626; font-size: 24px; margin-top:0px;"><strong>'.$app_lang['dear_lbl'].' '.$name.'</strong></p>
				<p style="color:#15791c; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px; margin:0 auto; text-align:center;">'.$app_lang['reporter_req_msg'].'<br /></p>
				<p style="color:#15791c; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px; margin:0 auto; text-align:center;">'.$app_lang['reporter_req_msg2'].'<br /></p>
				<p style="color:#15791c; font-size:20px; line-height:32px;font-weight:500;margin-bottom:30px; margin:0 auto; text-align:center;">'.getBaseUrl().'<br /></p>
				<br/>
				<p style="color:#999; font-size:18px; line-height:32px;font-weight:500;">'.$app_lang['thank_you_lbl'].' '.APP_NAME.'</p>
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

				send_email($email,$name,$subject,$message);
			}

		}

		$approved_on=strtotime(date('d-m-Y h:i:s A'));

		$sql_update="UPDATE request_reporter SET `status`='1', `is_seen`='1', `approved_on`='$approved_on' WHERE `id` IN ($ids)";
		mysqli_query($mysqli, $sql_update);

		$_SESSION['class']="success";
		$_SESSION['msg']="27";

	}

	$response['status']=1;	
	echo json_encode($response);
	break;

		// Get notifyrequest 	
	case 'notifyRequest':

	$sql = "SELECT * FROM request_reporter WHERE `is_seen`='0' ORDER BY `id` DESC";
	$qry = mysqli_query($mysqli, $sql);
	$info=array();
	while ($row = mysqli_fetch_assoc($qry)) {

		$data='<li>
		<a href="manage_reporter_request.php">
		<span class="badge badge-success pull-right">'.date('d M, Y',$row['request_on']).'</span>
		<div class="message">
		<div class="content">
		<div class="title">New reporter request is arrive</div>
		<div class="description"><strong>By:</strong> '.user_info($row['user_id'],'name').' </div>
		</div>
		</div>
		</a>
		</li>';

		array_push($info, $data);
	}

	$count=mysqli_num_rows($qry);

	$response['status']=1;
	$response['count']=$count;

	if($count!=0){
		$response['content']=$info;	
	}
	else{

		$data='<li>
		<p style="text-align: center;font-size: 16px;padding: 10px;color: #333;font-weight: 400;">Sorry ! no data</p>
		</li>';

		array_push($info, $data);

		$response['content']=$info;
	}

	echo json_encode($response);
	break;

		// Check smtp mail 
	case 'check_smtp':
	{
		$to = trim($_POST['email']);
		$recipient_name='Check User';

		$subject = '[IMPORTANT] '.APP_NAME.' Check SMTP Configuration';

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
		<td>
		<p style="color: #262626; font-size: 24px; margin-top:0px;">Hi, '.$_SESSION['admin_name'].'</p>
		<p style="color: #262626; font-size: 18px; margin-top:0px;">This is the demo mail to check SMTP Configuration. </p>
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


		send_email($to,$recipient_name,$subject,$message, true);
	}
	break;

	default:
			# code...
	break;
}

?>



