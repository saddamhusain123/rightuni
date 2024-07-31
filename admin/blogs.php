<?php
$page_title = (!isset($_GET['id'])) ? 'Add Blog' : 'Edit Blog';

include("includes/header.php");
require("includes/function.php");
require("language/language.php");
require("includes/check_availability.php");

// Get current datetime
$current_datetime = date('Y-m-d H:i:s');

// Handle adding new blog
if (isset($_POST['submit']) && !isset($_GET['id'])) {
    $blog_title = cleanInput($_POST['blog_title']);
    $meta_title = cleanInput($_POST['meta_title']);
    $blog_slug = cleanInput($_POST['blog_slug']);
    $blog_desc = cleanInput($_POST['blog_desc']);
    $meta_description = cleanInput($_POST['meta_description']);
    $created_at = $current_datetime;

    // Convert JSON meta_keywords to a comma-separated string
    $meta_keywords_json = $_POST['meta_keywords'];
    $meta_keywords_array = json_decode($meta_keywords_json, true);
    $meta_keywords = '';
    if (is_array($meta_keywords_array)) {
        $meta_keywords = implode(', ', array_column($meta_keywords_array, 'value'));
    }

    // Handle file upload
    if ($_FILES['blog_image']['name'] != '') {
        $ext = pathinfo($_FILES['blog_image']['name'], PATHINFO_EXTENSION);
        $blog_image = rand(0, 99999) . "_" . date('dmYhis') . "." . $ext;
        $tpath1 = 'images/' . $blog_image;

        if ($ext != 'png') {
            compress_image($_FILES["blog_image"]["tmp_name"], $tpath1, 80);
        } else {
            move_uploaded_file($_FILES['blog_image']['tmp_name'], $tpath1);
        }
    } else {
        $blog_image = '';
    }

    $data = array(
        'title' => $blog_title,
        'meta_title' => $meta_title,
        'slug'  => $blog_slug,
        'meta_keywords'  => $meta_keywords,
        'description'  => $blog_desc,
        'meta_description'  => $meta_description,
        'image' => $blog_image,
        'created_at' => $created_at,
        'updated_at' => $created_at,
        'status'     => 1
    );

    Insert('blogs', $data);

    $_SESSION['msg'] = "10";
    header("Location: manage_blog.php");
    exit;
}

// Handle editing existing blog
if (isset($_POST['submit']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $blog_title = cleanInput($_POST['blog_title']);
    $meta_title = cleanInput($_POST['meta_title']);
    $blog_slug = cleanInput($_POST['blog_slug']);
    $blog_desc = cleanInput($_POST['blog_desc']);
    $meta_description = cleanInput($_POST['meta_description']);
    $updated_at = $current_datetime;

    // Convert JSON meta_keywords to a comma-separated string
    $meta_keywords_json = $_POST['meta_keywords'];
    $meta_keywords_array = json_decode($meta_keywords_json, true);
    $meta_keywords = '';
    if (is_array($meta_keywords_array)) {
        $meta_keywords = implode(', ', array_column($meta_keywords_array, 'value'));
    }

    // Handle file upload
    if ($_FILES['blog_image']['name'] != '') {
        $ext = pathinfo($_FILES['blog_image']['name'], PATHINFO_EXTENSION);
        $blog_image = rand(0, 99999) . "_" . date('dmYhis') . "." . $ext;
        $tpath1 = 'images/' . $blog_image;

        if ($ext != 'png') {
            compress_image($_FILES["blog_image"]["tmp_name"], $tpath1, 80);
        } else {
            move_uploaded_file($_FILES['blog_image']['tmp_name'], $tpath1);
        }
    } else {
        // retain existing image if not updated
        $blog_image = $_POST['existing_image'];
    }

    $data = array(
        'title' => $blog_title,
        'meta_title' => $meta_title,
        'slug'  => $blog_slug,
        'meta_keywords'  => $meta_keywords,
        'description'  => $blog_desc,
        'meta_description'  => $meta_description,
        'image' => $blog_image,
        'updated_at' => $updated_at
    );

    Update('blogs', $data, "WHERE id = '" . mysqli_real_escape_string($mysqli, $id) . "'");

    $_SESSION['msg'] = "11";
    header("Location: manage_blog.php");
    exit;
}

// Fetch blog details for editing
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $qry = "SELECT * FROM blogs WHERE id='" . $id . "'";
    $result = mysqli_query($mysqli, $qry);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
    } else {
        die("Query failed: " . mysqli_error($mysqli));
    }
}
?>


<div class="row">
    <div class="col-md-12">
        <?php
        if (isset($_SERVER['HTTP_REFERER'])) {
            echo '<a href="' . htmlspecialchars($_SERVER['HTTP_REFERER']) . '"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
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
                <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars($row['id']); } ?>" />
                    <input type="hidden" name="existing_image" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars($row['image']); } ?>" />

                    <div class="section">
                        <div class="section-body">

                            <div class="row form-group">
                                <div class="col-md-6">
                                  <label class="col-md-12 control-label">Blog Title :-</label><br/><br/>
                                  <input type="text" name="blog_title" id="blog_title" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars($row['title']); } ?>" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                  <label class="col-md-12 control-label">Meta Title :-</label><br/><br/>
                                  <input type="text" name="meta_title" id="meta_title" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars($row['meta_title']); } ?>" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                              <div class="col-md-6">
                                <label class="col-md-12 control-label">Blog Slug :-</label><br/><br/>
                                <input type="text" name="blog_slug" id="blog_slug" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars($row['slug']); } ?>" class="form-control" readonly>
                              </div>  

                              <div class="col-md-6">
                                <label class="col-md-12 control-label">Meta Keywords :-</label><br/><br/>
                                <input type="text" name="meta_keywords" id="meta_keywords" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars($row['meta_keywords']); } ?>" class="form-control tagify-input" required>
                              </div>

                              <style type="text/css">
                                /* Add this CSS to adjust the height of the Tagify input field */
                                .tagify-input {
                                    height: 100px; /* Adjust the height as needed */
                                    overflow: auto; /* Ensure overflow is handled */
                                }

                              </style>

                            </div><br/>

                            <div class="row">
                              <div class="col-md-6">
                                <label class="col-md-12 control-label">Blog Description :-</label>
                                <br/><br/>
                                <textarea name="blog_desc" id="blog_desc" class="form-control" required><?php if (isset($_GET['id'])) { echo htmlspecialchars($row['description']); } ?></textarea>
                                <script>
                                  CKEDITOR.replace( 'blog_desc' ,{
                                    filebrowserBrowseUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                                    filebrowserUploadUrl : 'filemanager/dialog.php?type=2&editor=ckeditor&fldr=&akey=viaviweb',
                                    filebrowserImageBrowseUrl : 'filemanager/dialog.php?type=1&editor=ckeditor&fldr=&akey=viaviweb'
                                  });
                                </script>
                              </div>
                              <div class="col-md-6">
                                <label class="col-md-12 control-label">Meta Description :-</label><br/><br/>
                                <textarea name="meta_description" id="meta_description" class="form-control" required><?php if (isset($_GET['id'])) { echo htmlspecialchars($row['meta_description']); } ?></textarea>
                                
                                <script>
                                  CKEDITOR.replace( 'meta_description' ,{
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
                                <label class="col-md-12 control-label">Blog Image :-</label><br/><br/>
                                <div class="fileupload_block">
                                    <input type="file" name="blog_image" accept=".png, .jpg, .jpeg, .svg, .gif" id="fileupload">
                                    <div class="fileupload_img">
                                        <img type="image" src="<?php if (isset($_GET['id']) && !empty($row['image'])) { echo 'images/' . htmlspecialchars($row['image']); } else { echo 'assets/images/landscape.jpg'; } ?>" style="width: 120px;height: 90px" alt="Featured image" />
                                    </div>
                                </div>
                                <p class="control-label-help">(Recommended resolution: 500x400, 600x500, 700x600 OR width greater than height)</p>
                              </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 col-md-offset-0">
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

<?php include("includes/footer.php"); ?>

<script>
  function generateSlug(text) {
    return text.toString().toLowerCase()
      .replace(/\s+/g, '_')           // Replace spaces with underscores
      .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
      .replace(/\-\-+/g, '_')         // Replace multiple underscores with single underscore
      .replace(/^-+/, '')             // Trim underscores from start of text
      .replace(/-+$/, '');            // Trim underscores from end of text
  }

  document.getElementById('blog_title').addEventListener('input', function() {
    var title = this.value;
    var slug = generateSlug(title);
    document.getElementById('blog_slug').value = slug;
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
            },
            // Add any additional Tagify options here
        });
    });
</script>
