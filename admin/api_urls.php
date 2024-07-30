<?php 
$page_title="Api Urls";

include("includes/header.php");
include("includes/function.php");

require("includes/check_availability.php");

$file_path = getBaseUrl().'api.php';

?>
<div class="row">
	<div class="col-sm-12 col-xs-12">
		<?php
	      	if(isset($_SERVER['HTTP_REFERER']))
	      	{
	      		echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
	      	}
      	 ?>
		<div class="card">
			<div class="card-header">
				<?=$page_title?>
			</div>
			<div class="card-body no-padding">
				<pre>
					<code class="html">
						<br><b>API URL</b>&nbsp; <?php echo $file_path;?>    
						<br><b>Home</b> (Method: get_home) (Param: cat_id, user_id)
						<br><b>Category List</b> (Method: get_category)
						<br><b>News by Category ID</b> (Method: get_news) (Param: cat_id, user_id, page)
						<br><b>Latest News</b> (Method: get_latest) (Param: user_id, page)
						<br><b>Latest News by Category</b> (Method: get_category_latest) (Param: cat_id (2,3), user_id, page)
						<br><b>Video News</b> (Method: get_video_news) (Param: cat_id, user_id, page)
						<br><b>Single News</b> (Method: get_single_news) (Param: news_id, user_id)
						<br><b>Relative News</b> (Method: get_relative_news) (Param: news_id, cat_id, user_id, page)
						<br><b>Search News</b> (Method: get_search) (Param: search_text, user_id, page)
						<br><b>News Comment</b> (Method: get_comments) (Param: news_id, page)
						<br><b>User Comment</b> (Method: user_comment) (Param: news_id, user_id, comment_text)
						<br><b>Delete Comment</b> (Method: remove_comment) (Param: comment_id)
						<br><b>User Report</b> (Method: user_report) (Param: news_id, user_id, report)
						<br><b>Save Categories By Users</b> (Method: save_category) (Param: user_id, cat_id)
						<br><b>Channel</b> (Method: get_channel)
						<br><b>User Register</b>(Method: user_register) (Parameter: name, email, password, phone, auth_id, type(Normal, Google, Facebook)) (File: profile_img(Optional))
						<br><b>User Login</b>(Method: user_login) (Parameter: email, password, auth_id, type[Normal, Google, Facebook])
						<br><b>User Profile</b> (Method: user_profile) (Param: user_id)
						<br><b>Edit Profile</b> (Method: edit_profile) (Param: user_id, name, email, password, phone) (File: profile_img(Optional))
						<br><b>Upload news</b> (Method: upload_news) (Param: user_id, news_type[video/image], cat_id[1,2,3], video_url, news_heading, news_description, news_date[dd-mm-YYYY]) (File: news_featured_image, news_gallery_image[])
						<br><b>Edit uploaded news</b> (Method: edit_uploaded_news) (Param: news_id, user_id, news_type[video/image], cat_id[1,2,3], video_url, news_heading, news_description, news_date[dd-mm-YYYY]) (File: news_featured_image, news_gallery_image[])
						<br><b>Delete News</b> (Method: delete_news) (Param: news_id, user_id)
						<br><b>Remove Gallery Image</b> (Method: remove_gallery_img) (Param: news_id, image_id)
						<br><b>User's Uploaded News</b> (Method: users_uploaded_news) (Param: user_id, page)
						<br><b>Favourite News</b> (Method: favourite_news) (Param: news_id, user_id)
						<br><b>User's Favourite News</b> (Method: users_favourite_news) (Param: user_id, page)
						<br><b>Recent Favourite News</b> (Method: get_recent_news) (Param: news_ids [1,2,3]) (Param: user_id, page)
						<br><b>Request for reporter</b> (Method: reporter_request) (Param: user_id, email, password)
						<br><b>Forgot Password</b> (Method: forgot_pass) (Param: email)
						<br><b>App Details</b>(Method: get_app_details) (Param: user_id)
					</code> 
				</pre>

			</div>
		</div>
	</div>
</div>
<br/>
<div class="clearfix"></div>

<?php include("includes/footer.php");?>