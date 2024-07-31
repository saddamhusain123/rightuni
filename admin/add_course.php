<?php
$page_title = (!isset($_GET['cat_id'])) ? 'Add course' : 'Edit course';

include("includes/header.php");
require("includes/function.php");
require("language/language.php");
require("includes/check_availability.php");
require_once("thumbnail_images.class.php");

// Get current datetime
$current_datetime = date('Y-m-d H:i:s');


    $meta_keywords_json = $_POST['meta_keywords'];
    $meta_keywords_array = json_decode($meta_keywords_json, true); 
    
    $meta_keywords = '';
    if (is_array($meta_keywords_array)) {
        $meta_keywords = implode(', ', array_column($meta_keywords_array, 'value'));
    }
    

// Get category add start
if (isset($_POST['submit']) && isset($_GET['add'])) {
    $data = array(
        'name' => cleanInput($_POST['name']),
        'slug' => cleanInput($_POST['slug']),

        'meta_title' => cleanInput($_POST['meta_title']),
        'meta_keywords' => $meta_keywords,

        'meta_description' => cleanInput($_POST['meta_description']),

        
        'created_at' => $current_datetime // Add creation timestamp
    );

   


    $qry = Insert('courses', $data);

    $_SESSION['msg'] = "10";
    header("Location:courses.php");
    exit;
}
// Get category add end

// Get category list start
if (isset($_GET['cat_id'])) {
    $qry = "SELECT * FROM courses WHERE `id`='" . $_GET['cat_id'] . "'";
    $result = mysqli_query($mysqli, $qry);
    $row = mysqli_fetch_assoc($result);
}

// Get category update start
if (isset($_POST['submit']) && isset($_POST['cat_id'])) {
    $data = array(
        'name' => cleanInput($_POST['name']),
        'slug' => cleanInput($_POST['slug']),

        'meta_title' => cleanInput($_POST['meta_title']),
        'meta_keywords' => $meta_keywords,

        'meta_description' => cleanInput($_POST['meta_description']),

        
        'created_at' => $current_datetime // Add creation timestamp
    );

    $category_edit = Update('courses', $data, "WHERE id = '" . $_POST['cat_id'] . "'");

    $_SESSION['msg'] = "11";
    if (isset($_GET['redirect'])) {
        header("Location:" . $_GET['redirect']);
    } else {
        header("Location:courses.php?cat_id=" . $_POST['cat_id']);
    }
    exit;
}
// Get category update end
?>
<div class="row">
    <div class="col-md-12">
        <?php
        if (isset($_SERVER['HTTP_REFERER'])) {
            echo '<a href="' . $_SERVER['HTTP_REFERER'] . '"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
        }
        ?>
        <div class="card">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?= htmlspecialchars($page_title) ?></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="card-body mrg_bottom">
                <form action="" name="addeditcategory" method="post" class="form form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="cat_id" value="<?php if (isset($_GET['cat_id'])) { echo htmlspecialchars($_GET['cat_id']); } ?>" />
                    <input type="hidden" name="slug" id="slug" value="<?php if (isset($_GET['cat_id'])) { echo htmlspecialchars($row['slug']); } ?>" />

                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Course Name :-</label>
                                <div class="col-md-6">
                                    <input type="text" name="name" id="name" value="<?php if (isset($_GET['cat_id'])) { echo htmlspecialchars($row['name']); } ?>" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Slug :-</label>
                                <div class="col-md-6">
                                    <input type="text" name="slug_display" id="slug_display" value="<?php if (isset($_GET['cat_id'])) { echo htmlspecialchars($row['slug']); } ?>" class="form-control" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Meta Title :-</label>
                                <div class="col-md-6">
                                    <input type="text" name="meta_title" id="meta_title" value="<?php if (isset($_GET['cat_id'])) { echo htmlspecialchars($row['meta_title']); } ?>" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Meta Keywords :-</label>
                                <div class="col-md-6">
                                    <input type="text" name="meta_keywords" id="meta_keywords" value="<?php if (isset($_GET['cat_id'])) { echo htmlspecialchars($row['meta_keywords']); } ?>" class="form-control tagify-input" required>
                                </div>
                            </div>
                             <style type="text/css">
                                  /* Add this CSS to adjust the height of the Tagify input field */
                                  .tagify-input {
                                    height: 100px; /* Adjust the height as needed */
                                    overflow: auto; /* Ensure overflow is handled */
                                  }

                                </style>
                                 <br/>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Meta Description :-</label>
                                <div class="col-md-6">
                                   
                                   
                                    <textarea name="meta_description" id="meta_description" class="form-control"><?php if (isset($_GET['cat_id'])) { echo htmlspecialchars($row['meta_description']); } ?></textarea>
                                    <script>
                                      CKEDITOR.replace( 'meta_description' ,{
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
</div>

<?php include("includes/footer.php");?>

<script>
  function generateSlug(text) {
    return text.toString().toLowerCase()
      .replace(/\s+/g, '_')           // Replace spaces with -
      .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
      .replace(/\-\-+/g, '_')         // Replace multiple - with single -
      .replace(/^-+/, '')             // Trim - from start of text
      .replace(/-+$/, '');            // Trim - from end of text
  }

  document.getElementById('name').addEventListener('input', function() {
    var name = this.value;
    var slug = generateSlug(name);
    document.getElementById('slug').value = slug;
    document.getElementById('slug_display').value = slug;
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