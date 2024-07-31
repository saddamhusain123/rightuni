<?php 
$page_title = "Add College";

include("includes/header.php");
require("includes/function.php");
require("language/language.php");
require_once("thumbnail_images.class.php");

$user_id = $admin_id;

if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == 'admin') {
    $user_id = 0;
    $status = 1;
} else {
    $sql = "SELECT * FROM tbl_users WHERE `id` = '$user_id' AND `is_reporter` = 'true' AND `status` = '1'";
    $res = mysqli_query($mysqli, $sql);
    $row_user = mysqli_fetch_assoc($res);

    if ($row_user['auto_approve'] == 'true') {
        $status = 1;
    } else {
        $status = 0;
    }
}

$cat_qry = "SELECT * FROM courses WHERE deleted != 1 AND id != 1 ORDER BY name";
$cat_result = mysqli_query($mysqli, $cat_qry);

$state_qry = "SELECT * FROM states WHERE status = 1 ORDER BY name";
$State_result = mysqli_query($mysqli, $state_qry);

if (isset($_POST['submit'])) {

 
    if ($_POST['featured_image_url'] == '') {
        $ext = pathinfo($_FILES['news_featured_image']['name'], PATHINFO_EXTENSION);
        $news_featured_image = rand(0, 99999) . "_" . date('dmYhis') . "." . $ext;
        $tpath1 = 'images/' . $news_featured_image;

        if ($ext != 'png') {
            $pic1 = compress_image($_FILES["news_featured_image"]["tmp_name"], $tpath1, 80);
        } else {
            $tmp = $_FILES['news_featured_image']['tmp_name'];
            move_uploaded_file($tmp, $tpath1);
        }
    } else {
        $news_featured_image = trim($_POST['featured_image_url']);
    }

    // Function to generate URL-friendly slug
    function generateSlug($text) {
        $text = preg_replace('~[^\pL\d]+~u', '_', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '_');
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }

    $college_name = $_POST['name'];
    $college_slug = generateSlug($college_name);
    $desc = $_POST['news_description'];
    $status = 1;


    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keywords_json = $_POST['meta_keywords'];
    $meta_keywords_array = json_decode($meta_keywords_json, true); // Convert JSON string to PHP array

    // Extract values from the array and implode them into a comma-separated string
    $meta_keywords = '';
    if (is_array($meta_keywords_array)) {
        $meta_keywords = implode(', ', array_column($meta_keywords_array, 'value'));
    }

    // print_r($meta_description);exit;

   


    $qry = "INSERT INTO colleges(`name`, `slug`, `image`, `desc`, `status`, `meta_title`, `meta_keywords`, `meta_description` , `created_at`) VALUES ('" . addslashes($college_name) . "', '" . addslashes($college_slug) . "', '" . addslashes($news_featured_image) . "', '" . addslashes($desc) . "', '$status', '" . addslashes($meta_title) . "', '" . addslashes($meta_keywords) . "', '" . addslashes($meta_description) . "',NOW())";
    mysqli_query($mysqli, $qry);

    $last_id = mysqli_insert_id($mysqli);

    $cat_row = mysqli_fetch_array($cat_result);
       // Check if the form is submitted and the course_id is set
    if(isset($_POST['course_id'])) {
        // Retrieve the selected courses
        $selected_courses = $_POST['course_id'];
        
        // Get the college ID from the previous insert or form data
        $college_id = addslashes($last_id);
        
        // Iterate through the selected courses
        foreach($selected_courses as $course_id) {
            // Sanitize the course ID
            $course_id = addslashes($course_id);
            
            // Prepare the SQL insert query
            $sql = "INSERT INTO college_course_manage(`college_id`, `course_id`, `created_at`) 
                    VALUES ('$college_id', '$course_id', NOW())";
            
            // Execute the query
            mysqli_query($mysqli, $sql);
        }
        
        
    }

    $address = $_POST['address'];
    $state_id = $_POST['state_id'];
    $city = $_POST['city'];
    $program = $_POST['program'];
    $specialization = $_POST['specialization'];
    $admission_process = $_POST['admission_process'];
    $usp_of_college = $_POST['usp_of_college'];
    $affiliations = $_POST['affiliations'];
    $eligibility = $_POST['eligibility'];

 
   $sqldetails = "INSERT INTO college_details(`college_id`, `address`, `city`, `state_id`, `program`, `spacialization`, `admission_process`, `usp_of_college`, `affiliation`, `eligibility`, `status`,`created_at`) 
               VALUES (
                   '" . addslashes($last_id) . "', 
                   '" . addslashes($address) . "', 
                   '" . addslashes($city) . "', 
                   '" . addslashes($state_id) . "', 
                   '" . addslashes($program) . "', 
                   '" . addslashes($specialization) . "', 
                   '" . addslashes($admission_process) . "', 
                   '" . addslashes($usp_of_college) . "', 
                   '" . addslashes($affiliations) . "', 
                   '" . addslashes($eligibility) . "', 
                   '$status',
                   NOW()
               )";
   
                mysqli_query($mysqli, $sqldetails);


    
    $tution_fee = $_POST['tution_fee'];
    $tution_fee_state_quota = $_POST['tution_fee_state_quota'];
    $tution_fee_mgmt_quota = $_POST['tution_fee_mgmt_quota'];
    $other_fee_annual = $_POST['other_fee_annual'];
    $one_time_fee = $_POST['one_time_fee'];
    $refundable_fee = $_POST['refundable_fee'];
    $mess_fee_inr = $_POST['mess_fee_inr'];
    $hostel_fee_ac = $_POST['hostel_fee_ac'];
    $hostel_fee_non_ac = $_POST['hostel_fee_non_ac'];

    $feedetails = "INSERT INTO college_fees(`college_id`, `tution_fee`, `tution_fee_state_quota`, `tution_fee_mgmt_quota`, `other_fee_annual`, `one_time_fee`, `refundable_fee`, `hostel_fee_ac`, `hostel_fee_non_ac`, `mess_fee_inr`, `status`,`created_at`) 
               VALUES (
                   '" . addslashes($last_id) . "', 
                   '" . addslashes($tution_fee) . "', 
                   '" . addslashes($tution_fee_state_quota) . "', 
                   '" . addslashes($tution_fee_mgmt_quota) . "', 
                   '" . addslashes($other_fee_annual) . "', 
                   '" . addslashes($one_time_fee) . "', 
                   '" . addslashes($refundable_fee) . "', 
                   '" . addslashes($hostel_fee_ac) . "', 
                   '" . addslashes($hostel_fee_non_ac) . "', 
                   '" . addslashes($mess_fee_inr) . "', 
                   '$status',
                   NOW()
               )";
   
                mysqli_query($mysqli, $feedetails);


//     $meta_keyword = $_POST['meta_keyword'];
//     $meta_title = $_POST['meta_title'];
//     $meta_description = $_POST['meta_description'];
//     $module = ['colleges'];



//     $meta_details = "INSERT INTO meta_data('meta_title', 'meta_keywords', 'meta_description', 'module', 'module_id')
//             VALUES(
//               '" . addslashes($meta_title). "',
//               '" . addslashes($meta_keyword) . "',
//               '" . addslashes($meta_description). "',
//               '" . addslashes($module). "',
//               '" . addslashes($last_id). "'
//           )";

// mysqli_query($mysqli, $meta_details);



    $_SESSION['msg'] = "10";
    header("Location: colleges.php");
    exit;
}
?>


<style type="text/css">
  .select2-container--default.select2-container--focus .select2-selection--multiple{
    border:1px solid #999;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__rendered li{
    padding-top: 5px;
    padding-bottom: 5px;
  }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<div class="row">
  <div class="col-md-12">
  	<?php
    if(isset($_SERVER['HTTP_REFERER']))
    {
     echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
   }
   ?>
   <div class="card">
    <div class="page_title_block">
      <div class="col-md-5 col-xs-12">
        <div class="page_title"><?=$page_title?></div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="card-body mrg_bottom"> 
      <form action="" method="post" class="form form-horizontal" id="college_form" enctype="multipart/form-data">

        <div class="section">
          <div class="section-body">

            <div class="row form-group">
              
                <div class="col-md-6">
                  <label class="col-md-12 control-label">College Name :-</label><br/>
                  <input type="text" name="name" id="name" class="form-control">
                </div>
              <div class="col-md-6">
                <label class="col-md-12 control-label">Courses :-</label>
                <select name="course_id[]" id="course_id" class="select2" required multiple="multiple">
                  <?php
                  while($cat_row=mysqli_fetch_array($cat_result))
                  {
                    ?>                       
                    <option value="<?php echo $cat_row['id'];?>"><?php echo $cat_row['name'];?></option>                           
                    <?php
                  }
                  ?>
                </select>
              </div>
            </div>

               <!--  <div class="form-group">
              <label class="col-md-3 control-label">College Slug :-</label>
              <div class="col-md-6">
                <input type="text" name="news_url" id="news_url" class="form-control" required="required">
              </div>
            </div> -->

            <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Address :-</label>
                <input type="text" name="address" id="address" class="form-control" >
              </div>

              <div class="col-md-6">
                <label class="col-md-12 control-label">City :-</label>
                <input type="text" name="city" id="city" class="form-control" >
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">State :-</label>
                
                <select name="state_id" id="state_id" class="select2 form-control" required >
                  <option value="">Select State</option>
                  <?php

                  while($state_row=mysqli_fetch_array($State_result))
                  {
                    ?>                       
                    <option value="<?php echo $state_row['id'];?>"><?php echo $state_row['name'];?></option>                           
                    <?php
                  }
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="col-md-12 control-label">Program :-</label>
                <input type="text" name="program" id="program" class="form-control" >
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">USPs of the College :-</label>
                <input type="text" name="usp_of_college" id="usp_of_college" class="form-control" >
              </div>
              <div class="col-md-6">
                <label class="col-md-12 control-label">Affiliations  :-</label>
                <input type="text" name="affiliations" id="affiliations" class="form-control" >
              </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <label class="col-md-12 control-label">Featured Image :-
                    <p class="control-label-help">(Recommended resolution: 500x400, 600x500, 700x600 OR width greater than height)</p>
                  </label>
                  <div class="fileupload_block">
                    <input type="hidden" name="featured_image_url" value="">
                    <input type="file" name="news_featured_image" accept=".png, .jpg, .jpeg, .svg, .gif" value="" id="fileupload">
                    <div class="fileupload_img featured_image">
                      <img type="image" src="assets/images/landscape.jpg" style="width: 120px; height: 90px" alt="Featured image"/>
                    </div>
                  </div>
                </div>
              <div class="col-md-6">
                <label class="col-md-12 control-label">Gallery Image :-
                  <p class="control-label-help">(Recommended resolution: 500x400, 600x500, 700x600 OR width greater than height)</p>
                </label>
                <div class="fileupload_block">
                    <input type="hidden" name="featured_image_url" value="">
                  <input type="file" name="news_gallery_image[]" accept=".png, .jpg, .jpeg, .svg, .gif" value="" id="fileupload" multiple>
                  <div class="fileupload_img featured_image"><img type="image" src="assets/images/landscape.jpg" style="width: 120px;height: 90px" alt="Featured image" /></div> 
                </div>
              </div>
            </div>



            <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Eligibility  :-</label>
                <input type="text" name="eligibility" id="eligibility" class="form-control">
              </div>  
              <div class="col-md-6">
                <label class="col-md-12 control-label">Tution Fees :-</label>
                <input type="text" name="tution_fee" id="tution_fee" class="form-control" >
              </div>
            </div>
             <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Tution Fee State Quota  :-</label>
                <input type="text" name="tution_fee_state_quota" id="tution_fee_state_quota" class="form-control">
              </div>  
              <div class="col-md-6">
                <label class="col-md-12 control-label">Tution Fee Mgmt Quota :-</label>
                <input type="text" name="tution_fee_mgmt_quota" id="tution_fee_mgmt_quota" class="form-control" >
              </div>
            </div>
             <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Other Fee Annual  :-</label>
                <input type="text" name="other_fee_annual" id="other_fee_annual" class="form-control">
              </div>  
              <div class="col-md-6">
                <label class="col-md-12 control-label">One Time Fee :-</label>
                <input type="text" name="one_time_fee" id="one_time_fee" class="form-control" >
              </div>
            </div>
             <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Refundable Fee  :-</label>
                <input type="text" name="refundable_fee" id="refundable_fee" class="form-control">
              </div>  
             <div class="col-md-6">
                <label class="col-md-12 control-label">Mess Fee Inr :-</label>
                <input type="text" name="mess_fee_inr" id="mess_fee_inr" class="form-control" >
              </div>
            </div>
            </div>
             <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Hostel Fee Ac  :-</label>
                <input type="text" name="hostel_fee_ac" id="hostel_fee_ac" class="form-control">
              </div>  
              <div class="col-md-6">
                <label class="col-md-12 control-label">Hostel Fee Non Ac :-</label>
                <input type="text" name="hostel_fee_non_ac" id="hostel_fee_non_ac" class="form-control" >
              </div>
            </div>
             
             <div class="row">
               <div class="col-md-6">
                  <label class="col-md-12 control-label">Meta Title :-</label>
                  <input type="text" name="meta_title" id="meta_title" class="form-control">
                </div>
                <div class="col-md-6">
                  <label class="col-md-12 control-label">Meta Keywords :-</label>
                  <input type="text" name="meta_keywords" id="meta_keywords" class="form-control tagify-input" data-role="tagsinput">
                </div>
              </div>

            </div>
            <style type="text/css">
              /* Add this CSS to adjust the height of the Tagify input field */
              .tagify-input {
                height: 100px; /* Adjust the height as needed */
                overflow: auto; /* Ensure overflow is handled */
              }

            </style>


            
            <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Meta Description</label>
                <br/><br/>
                <textarea name="meta_description" id="meta_description" class="form-control"></textarea>
                <script>
                  CKEDITOR.replace( 'meta_description' ,{
                    filebrowserBrowseUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                    filebrowserUploadUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                    filebrowserImageBrowseUrl : 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=&akey=viaviweb'
                  });
                </script>
              </div>
              <div class="col-md-6">
                <label class="col-md-12 control-label">Description</label>
                <br/><br/>
                <textarea name="news_description" id="news_description" class="form-control"></textarea>
                <script>
                  CKEDITOR.replace( 'news_description' ,{
                    filebrowserBrowseUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                    filebrowserUploadUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                    filebrowserImageBrowseUrl : 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=&akey=viaviweb'
                  });
                </script>
              </div>

              
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Specialization :-</label><br/><br/>
                <textarea name="specialization" id="specialization" class="form-control"></textarea>
                
                <script>
                  CKEDITOR.replace( 'specialization' ,{
                    filebrowserBrowseUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                    filebrowserUploadUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                    filebrowserImageBrowseUrl : 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=&akey=viaviweb'
                  });
                </script>
              </div>
              <div class="col-md-6">
                <label class="col-md-12 control-label">Admission Process :-</label><br/><br/>
                <textarea name="admission_process" id="admission_process" class="form-control"></textarea>
                
                <script>
                  CKEDITOR.replace( 'admission_process' ,{
                    filebrowserBrowseUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                    filebrowserUploadUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                    filebrowserImageBrowseUrl : 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=&akey=viaviweb'
                  });
                </script>
              </div>
              </div>
            <br/>  
            <div class="form-group">
              <div class="col-md-9 col-md-offset-3">
                <button type="submit" name="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include("includes/footer.php");?> 

</div>


<script type="text/javascript">
	// Get youtube video size start
  $.extend({

    jYoutube: function(url, size){
      var ID = '';
      url = url.replace(/(>|<)/gi,'').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
      if(url[2] !== undefined) {
        ID = url[2].split(/[^0-9a-z_\-]/i);
        ID = ID[0];
      }
      else {
        ID = url;
      }

      if(size == "small"){
        return "http://img.youtube.com/vi/"+ID+"/2.jpg";
      }else {
        return "http://img.youtube.com/vi/"+ID+"/0.jpg";
      }
    }

  });

    // Get news types start
    $(document).ready(function(e) {
     $("#news_type").change(function(){
      var type=$(this).val();
      if(type=="video")
      {                 
        $("#youtube_url_display").show();
        $("#youtube_url_display").find("input").attr("required",true);
        $("#image_news").hide();

        if($("input[name='video_url']").val()!=''){
          var _video_img=$.jYoutube($("input[name='video_url']").val());
          $('.featured_image').find("img").attr('src',_video_img);
          $("input[name='featured_image_url']").val(_video_img);
        }
      }             
      else
      {   
        $("#image_news").show();   
        $("#youtube_url_display").find("input").attr("required",false);  
        $("#youtube_url_display").hide(); 
        $('.featured_image').find("img").attr('src','assets/images/landscape.jpg');
        $("input[name='featured_image_url']").val(''); 
      } 
    });

       // Get video url start
       $("input[name='video_url']").change(function(e){
        e.preventDefault();
        if($(this).val()!=''){
          var _video_img=$.jYoutube($(this).val());
          $('.featured_image').find("img").attr('src',_video_img);
          $("input[name='featured_image_url']").val(_video_img);  
        }
        else{
          $('.featured_image').find("img").attr('src','assets/images/landscape.jpg');
          $("input[name='featured_image_url']").val('');
        }
      });
       
     });
   </script>

   <script type="text/javascript">
  // Get featured image
  $("input[name='news_featured_image']").change(function() { 
    var file=$(this);

    if(file[0].files.length != 0){
      if(isImage($(this).val())){
        render_upload_image(this,$(this).next('.fileupload_img').find("img"));
        $("input[name='featured_image_url']").val('');
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
<script>
  document.getElementById('college_form').addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && event.target.tagName === 'INPUT') {
        event.preventDefault();
    }
});



</script>


<!-- Tagify CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
<!-- Tagify JS -->
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var input = document.querySelector('#meta_keywords');
        new Tagify(input, {
            delimiters: ",| ", // allow both comma and space as delimiters
            maxTags: Infinity, // no limit on the number of tags
            dropdown: {
                enabled: 0, // disable the dropdown by default for performance
                maxItems: 500, // max items to show in dropdown
            }
        });
    });
</script>
 <script>
            new Tagify(document.querySelector('#meta_keywords'), {
              whitelist: [],
              enforceWhitelist: false
            });
          </script>