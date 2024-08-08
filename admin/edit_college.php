<?php 
$page_title = "Edit College";

include("includes/header.php");
require("includes/function.php");
require("language/language.php");
require_once("thumbnail_images.class.php");

$user_id = $admin_id;

if (isset($_SESSION['login_type']) && $_SESSION['login_type'] == 'admin') {
    $user_id = 0;
    $status = 1;
} else {
    $sql = "SELECT * FROM tbl_users WHERE `id` = ? AND `is_reporter` = 'true' AND `status` = '1'";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row_user = $res->fetch_assoc();
    $status = ($row_user['auto_approve'] == 'true') ? 1 : 0;
}

$cid = $_GET['id'];

// Fetch existing college data
$old_data = "SELECT colleges.*, college_details.address, college_details.city, college_details.program, college_details.spacialization, 
             college_details.admission_process, college_details.usp_of_college, college_details.affiliation, college_details.eligibility, 
             college_details.state_id, states.name AS state_name, 
             college_fees.tution_fee, college_fees.tution_fee_state_quota, college_fees.tution_fee_mgmt_quota, college_fees.other_fee_annual, 
             college_fees.one_time_fee, college_fees.refundable_fee, college_fees.hostel_fee_ac, college_fees.hostel_fee_non_ac, 
             college_fees.mess_fee_inr
             FROM colleges
             JOIN college_details ON colleges.id = college_details.college_id
             JOIN states ON college_details.state_id = states.id
             LEFT JOIN college_fees ON colleges.id = college_fees.college_id
             WHERE colleges.id = ?";
$stmt = $mysqli->prepare($old_data);
$stmt->bind_param("i", $cid);
$stmt->execute();
$old_data_result = $stmt->get_result();
$row_college = $old_data_result->fetch_assoc();

// Fetch selected courses
$course_old_data = "SELECT courses.name AS course_name, courses.id AS course_id
                    FROM colleges
                    LEFT JOIN college_course_manage ON colleges.id = college_course_manage.college_id
                    JOIN courses ON college_course_manage.course_id = courses.id
                    WHERE colleges.id = ?";
$stmt = $mysqli->prepare($course_old_data);
$stmt->bind_param("i", $cid);
$stmt->execute();
$course_old_data_result = $stmt->get_result();
$selected_courses = [];
while ($course_row = $course_old_data_result->fetch_assoc()) {
    $selected_courses[$course_row['course_id']] = $course_row['course_name'];
}
$row_college['old_courses'] = $selected_courses;

$college_gallery = "SELECT * FROM college_gallery WHERE college_id = '$cid' AND deleted != 1";
$gallery_result = $mysqli->query($college_gallery);

$images = [];

if ($gallery_result) {
    while ($row = $gallery_result->fetch_assoc()) {
        // Assuming the column for image URL is named 'image_url'
        if (!empty($row['image'])) {
            $images[] = $row['image'];
        }
    }
} else {
    echo "Query failed: " . $mysqli->error;
}

// Now $images contains all the image URLs or paths
// echo "<pre>"; print_r($images); exit;



// print_r($row_college1);exit;
// Fetch courses and states for selection
$cat_qry = "SELECT * FROM courses WHERE deleted != 1 AND id != 1 ORDER BY name";
$cat_result = $mysqli->query($cat_qry);

$state_qry = "SELECT * FROM states WHERE status = 1 ORDER BY name";
$State_result = $mysqli->query($state_qry);

if (isset($_POST['submit'])) {
    // Handle file upload
    $news_featured_image = $row_college['image']; // Preserve existing image

    if ($_FILES['news_featured_image']['error'] == UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['news_featured_image']['name'], PATHINFO_EXTENSION);
        $news_featured_image = rand(0, 99999) . "_" . date('dmYhis') . "." . $ext;
        $tpath1 = 'images/' . $news_featured_image;

        if ($ext != 'png') {
            compress_image($_FILES["news_featured_image"]["tmp_name"], $tpath1, 80);
        } else {
            $tmp = $_FILES['news_featured_image']['tmp_name'];
            move_uploaded_file($tmp, $tpath1);
        }
    }

    // Function to generate slug
    function generateSlug($text) {
        $text = preg_replace('~[^\pL\d]+~u', '_', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '_');
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }

    // Prepare data for update
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

    // Update college information
    $qry = "UPDATE colleges SET `name` = ?, `slug` = ?, `image` = ?, `desc` = ?, `status` = ?, `meta_title` =  ?, `meta_keywords` = ?, `meta_description` = ?, `updated_at` = NOW() WHERE `id` = ?";
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param("ssssisssi", $college_name, $college_slug, $news_featured_image, $desc, $status, $meta_title, $meta_keywords, $meta_description, $cid);
    if (!$stmt->execute()) {
        echo "Error updating college: " . $stmt->error;
        exit;
    }

    // Update courses
    if (isset($_POST['course_id'])) {
        $selected_courses = $_POST['course_id'];
        // Clear existing courses
        $delete_sql = "DELETE FROM college_course_manage WHERE college_id = ?";
        $stmt = $mysqli->prepare($delete_sql);
        $stmt->bind_param("i", $cid);
        $stmt->execute();

        foreach ($selected_courses as $course_id) {
            $sql = "INSERT INTO college_course_manage(`college_id`, `course_id`, `created_at`) VALUES (?, ?, NOW())";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ii", $cid, $course_id);
            if (!$stmt->execute()) {
                echo "Error adding course: " . $stmt->error;
                exit;
            }
        }
    }

    // Update college details
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state_id = $_POST['state_id'];
    $program = $_POST['program'];
    $specialization = $_POST['specialization'];
    $admission_process = $_POST['admission_process'];
    $usp_of_college = $_POST['usp_of_college'];
    $affiliations = $_POST['affiliations'];
    $eligibility = $_POST['eligibility'];

    $sqldetails = "UPDATE college_details SET `address` = ?, `city` = ?, `state_id` = ?, `program` = ?, `spacialization` = ?, 
                   `admission_process` = ?, `usp_of_college` = ?, `affiliation` = ?, `eligibility` = ?, `status` = ?, `created_at` = NOW() 
                   WHERE `college_id` = ?";
    $stmt = $mysqli->prepare($sqldetails);
    $stmt->bind_param("ssissssssii", $address, $city, $state_id, $program, $specialization, $admission_process, $usp_of_college, $affiliations, $eligibility, $status, $cid);
    if (!$stmt->execute()) {
        echo "Error updating college details: " . $stmt->error;
        exit;
    }

    // Update fee details
    $tution_fee = $_POST['tution_fee'];
    $tution_fee_state_quota = $_POST['tution_fee_state_quota'];
    $tution_fee_mgmt_quota = $_POST['tution_fee_mgmt_quota'];
    $other_fee_annual = $_POST['other_fee_annual'];
    $one_time_fee = $_POST['one_time_fee'];
    $refundable_fee = $_POST['refundable_fee'];
    $mess_fee_inr = $_POST['mess_fee_inr'];
    $hostel_fee_ac = $_POST['hostel_fee_ac'];
    $hostel_fee_non_ac = $_POST['hostel_fee_non_ac'];

    $feedetails = "UPDATE college_fees SET `tution_fee` = ?, `tution_fee_state_quota` = ?, `tution_fee_mgmt_quota` = ?, 
                   `other_fee_annual` = ?, `one_time_fee` = ?, `refundable_fee` = ?, `hostel_fee_ac` = ?, 
                   `hostel_fee_non_ac` = ?, `mess_fee_inr` = ?, `status` = ?, `created_at` = NOW() 
                   WHERE `college_id` = ?";
    $stmt = $mysqli->prepare($feedetails);
    $stmt->bind_param("sssssssssii", $tution_fee, $tution_fee_state_quota, $tution_fee_mgmt_quota, $other_fee_annual, $one_time_fee, $refundable_fee, $hostel_fee_ac, $hostel_fee_non_ac, $mess_fee_inr, $status, $cid);
    if (!$stmt->execute()) {
        echo "Error updating college fees: " . $stmt->error;
        exit;
    }



 if (isset($_FILES['college_gallery_image'])) {
    // Get the college ID from the previous insert or form data
    

    // Directory where files will be stored
    $upload_directory = 'images/college_gallery/'; // Ensure this directory exists and is writable

    // Iterate through each file
    $total_files = count($_FILES['college_gallery_image']['name']);
    for ($i = 0; $i < $total_files; $i++) {
        $file_name = $_FILES['college_gallery_image']['name'][$i];
        $file_tmp = $_FILES['college_gallery_image']['tmp_name'][$i];
        $file_type = $_FILES['college_gallery_image']['type'][$i];
        $file_error = $_FILES['college_gallery_image']['error'][$i];
        $file_size = $_FILES['college_gallery_image']['size'][$i];

        if ($file_error === UPLOAD_ERR_OK) {
            // Sanitize file name
            $file_name = mysqli_real_escape_string($mysqli, $file_name);
            
            // Move the file to the desired directory
            $target_file = $upload_directory . basename($file_name);
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Prepare and execute the SQL insert query
                $sql = "INSERT INTO college_gallery(`college_id`, `image`, `created_at`) 
                        VALUES ('$cid', '$file_name', NOW())";
                mysqli_query($mysqli, $sql);
            } else {
                echo "Failed to move uploaded file: " . htmlspecialchars($file_name);
            }
        } else {
            echo "File upload error: " . $file_error;
        }
    }
}




if (isset($_POST['removed_files'])) {
    // Sanitize and process the list of removed files
    $removed_files = explode(',', $_POST['removed_files']);
    
    // Prepare and execute a statement for each removed file
    foreach ($removed_files as $file_name) {
        // Trim any extra whitespace and sanitize the file name
        $file_name = trim($file_name);
        $file_name = mysqli_real_escape_string($mysqli, $file_name);

        // Construct the SQL query to mark the file as deleted
        $sql90 = "UPDATE college_gallery SET deleted = 1 WHERE image = '$file_name' AND college_id = '$cid'";
        
        // Execute the SQL query
        if (!mysqli_query($mysqli, $sql90)) {
            // Handle errors in SQL execution
            echo "Error updating record: " . mysqli_error($mysqli);
        }
    }
}
    $_SESSION['msg'] = "11";
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
      <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">

        <div class="section">
          <div class="section-body">

            <div class="row form-group">
              
                <div class="col-md-6">
                  <label class="col-md-12 control-label">College Name :-</label><br/>
                  <input type="text" name="name" id="name" value="<?= $row_college['name']?>" class="form-control">
                </div>
                
                <div class="col-md-6">
                <label class="col-md-12 control-label">Courses :-</label>
                <select name="course_id[]" id="course_id" class="select2" required multiple="multiple">
                  <?php
                  // Convert the old_courses array to a format that's easy to check
                  $old_courses = $row_college['old_courses'];

                  while($cat_row = mysqli_fetch_array($cat_result)) {
                      $selected = array_key_exists($cat_row['id'], $old_courses) ? 'selected' : '';
                      ?>                       
                      <option value="<?php echo $cat_row['id']; ?>" <?php echo $selected; ?>>
                          <?php echo $cat_row['name']; ?>
                      </option>
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
                <input type="text" name="address" id="address" value="<?= $row_college['address']?>" class="form-control" >
              </div>

              <div class="col-md-6">
                <label class="col-md-12 control-label">City :-</label>
                <input type="text" name="city" id="city" value="<?= $row_college['city']?>" class="form-control" >
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">State :-</label>
                
                <select name="state_id" id="state_id" class="select2 form-control"  required >
                  <option  >Select State</option>
                  <?php

                  while($state_row=mysqli_fetch_array($State_result))
                  {
                    ?>                       
                    <option value="<?php echo $state_row['id'];?>" <?= ($row_college['state_id'] == $state_row['id']) ? 'selected' : ''; ?>><?php echo $state_row['name'];?></option>                           
                    <?php
                  }
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="col-md-12 control-label">Program :-</label>
                <input type="text" name="program" id="program" value="<?= $row_college['program']?>" class="form-control" >
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">USPs of the College :-</label>
                <input type="text" name="usp_of_college" id="usp_of_college" value="<?= $row_college['usp_of_college']?>" class="form-control" >
              </div>
              <div class="col-md-6">
                <label class="col-md-12 control-label">Affiliations  :-</label>
                <input type="text" name="affiliations" id="affiliations" value="<?= $row_college['affiliation']?>" class="form-control" >
              </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                  <label class="col-md-12 control-label">Featured Image :-
                    <p class="control-label-help">(Recommended resolution: 500x400, 600x500, 700x600 OR width greater than height)</p>
                  </label>
                  <div class="fileupload_block">
                    <input type="hidden" name="featured_image_url"  value="">
                    <input type="file" name="news_featured_image"  accept=".png, .jpg, .jpeg, .svg, .gif"  id="fileupload">
                    <div class="fileupload_img featured_image">
                      <img type="image" src="images/<?= $row_college['image'] ?>"   style="width: 120px; height: 90px" alt="Featured image"/>
                    </div>
                  </div>
                </div>
             
 <div class="col-md-6">
    <label class="col-md-12 control-label">Gallery Image:
        <p class="control-label-help">(Recommended resolution: 500x400, 600x500, 700x600 OR width greater than height)</p>
    </label>
    <div class="fileupload_block">
        <input type="file" id="fileInput" name="college_gallery_image[]" accept=".png, .jpg, .jpeg, .svg, .gif" multiple>
        
        <input type="hidden" id="removedFiles" name="removed_files">

        <div class="fileupload_img featured_image" id="fileList">
            <?php foreach ($images as $image): ?>
                <div class="file-item" data-file="<?php echo htmlspecialchars($image); ?>">
                    <a href="images/college_gallery/<?php echo htmlspecialchars($image); ?>" target="_blank">
                        <p><?php echo htmlspecialchars($image); ?></p>
                    </a>
                    <i class="fa fa-trash" id="remove-file"></i>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
        .file-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .file-item p {
            margin: 0;
            margin-right: 10px;
        }
        .file-item i {
            cursor: pointer;
            color: red;
        }
    </style>


            <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Eligibility  :-</label>
                <input type="text" name="eligibility" id="eligibility" value="<?= $row_college['eligibility']?>"  class="form-control">
              </div>  
              <div class="col-md-6">
                <label class="col-md-12 control-label">Tution Fees :-</label>
                <input type="text" name="tution_fee" id="tution_fee" value="<?= $row_college['tution_fee']?>"  class="form-control" >
              </div>
            </div>
             <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Tution Fee State Quota  :-</label>
                <input type="text" name="tution_fee_state_quota" id="tution_fee_state_quota" value="<?= $row_college['tution_fee_state_quota']?>"  class="form-control">
              </div>  
              <div class="col-md-6">
                <label class="col-md-12 control-label">Tution Fee Mgmt Quota :-</label>
                <input type="text" name="tution_fee_mgmt_quota" id="tution_fee_mgmt_quota" value="<?= $row_college['tution_fee_mgmt_quota']?>"  class="form-control" >
              </div>
            </div>
             <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Other Fee Annual  :-</label>
                <input type="text" name="other_fee_annual" id="other_fee_annual" value="<?= $row_college['other_fee_annual']?>"  class="form-control">
              </div>  
              <div class="col-md-6">
                <label class="col-md-12 control-label">One Time Fee :-</label>
                <input type="text" name="one_time_fee" id="one_time_fee" value="<?= $row_college['one_time_fee']?>"  class="form-control" >
              </div>
            </div>
             <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Refundable Fee  :-</label>
                <input type="text" name="refundable_fee" id="refundable_fee" value="<?= $row_college['refundable_fee']?>"  class="form-control">
              </div>  
             <div class="col-md-6">
                <label class="col-md-12 control-label">Mess Fee Inr :-</label>
                <input type="text" name="mess_fee_inr" id="mess_fee_inr" value="<?= $row_college['mess_fee_inr']?>"  class="form-control" >
              </div>
            </div>
            </div>
             <div class="row">
              <div class="col-md-6">
                <label class="col-md-12 control-label">Hostel Fee Ac  :-</label>
                <input type="text" name="hostel_fee_ac" id="hostel_fee_ac" value="<?= $row_college['hostel_fee_ac']?>"  class="form-control">
              </div>  
              <div class="col-md-6">
                <label class="col-md-12 control-label">Hostel Fee Non Ac :-</label>
                <input type="text" name="hostel_fee_non_ac" id="hostel_fee_non_ac" value="<?= $row_college['hostel_fee_non_ac']?>"  class="form-control" >
              </div>
            </div>
             
             <div class="row">
               <div class="col-md-6">
                  <label class="col-md-12 control-label">Meta Title :-</label>
                  <input type="text" name="meta_title" id="meta_title" value="<?= $row_college['meta_title']?>"  class="form-control">
                </div>
                <div class="col-md-6">
                  <label class="col-md-12 control-label">Meta Keywords :-</label>
                  <input type="text" name="meta_keywords" id="meta_keywords" value="<?= $row_college['meta_keywords']?>"  class="form-control tagify-input" data-role="tagsinput">
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
                <textarea name="meta_description" id="meta_description" class="form-control"> <?= htmlspecialchars($row_college['meta_description']) ?></textarea>
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
                <textarea name="news_description" id="news_description" value=""  class="form-control"> <?= htmlspecialchars($row_college['desc']) ?></textarea>
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
                <textarea name="specialization" id="specialization" value=""  class="form-control"> <?= htmlspecialchars($row_college['spacialization']) ?></textarea>
                
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
                <textarea name="admission_process" id="admission_process" value=""  class="form-control"> <?= htmlspecialchars($row_college['admission_process']) ?></textarea>
                
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
  </div><?php include("includes/footer.php");?> 
</div>
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

          <script src="path/to/font-awesome/js/all.min.js"></script> <!-- Include FontAwesome for icons -->
   <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileList = document.getElementById('fileList');
        const removedFilesInput = document.getElementById('removedFiles');
        
        fileList.addEventListener('click', function(e) {
            if (e.target.id === 'remove-file') {
                const fileItem = e.target.parentElement;
                const fileName = fileItem.getAttribute('data-file');
                
                // Get current removed files and append the new one
                let removedFiles = removedFilesInput.value ? removedFilesInput.value.split(',') : [];
                removedFiles.push(fileName);
                removedFilesInput.value = removedFiles.join(',');
                
                fileItem.remove();
            }
        });
    });
</script>