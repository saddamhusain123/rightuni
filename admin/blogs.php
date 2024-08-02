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
    $blog_slug = cleanInput($_POST['blog_slug']);
    $blog_desc = cleanInput($_POST['blog_desc']);
    $created_at = $current_datetime;

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
        'slug'  => $blog_slug,
        'description'  => $blog_desc,
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
    $blog_slug = cleanInput($_POST['blog_slug']);
    $blog_desc = cleanInput($_POST['blog_desc']);
    $updated_at = $current_datetime;

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
        'slug'  => $blog_slug,
        'description'  => $blog_desc,
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
                            <div class="form-group">
                                <label class="col-md-3 control-label">Blog Title :-</label>
                                <div class="col-md-6">
                                    <input type="text" name="blog_title" id="blog_title" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars($row['title']); } ?>" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Blog Slug :-</label>
                                <div class="col-md-6">
                                    <input type="text" name="blog_slug" id="blog_slug" value="<?php if (isset($_GET['id'])) { echo htmlspecialchars($row['slug']); } ?>" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Blog Description :-</label>
                                <div class="col-md-6">
                                    <textarea name="blog_desc" id="blog_desc" class="form-control" required><?php if (isset($_GET['id'])) { echo htmlspecialchars($row['description']); } ?></textarea>    
                                    <script>
                                        CKEDITOR.replace('description');
                                    </script>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Blog Image :-
                                    <p class="control-label-help">(Recommended resolution: 500x400, 600x500, 700x600 OR width greater than height)</p>
                                </label>
                                <div class="col-md-6">
                                    <div class="fileupload_block">
                                        <input type="file" name="blog_image" accept=".png, .jpg, .jpeg, .svg, .gif" id="fileupload">
                                        <div class="fileupload_img">
                                            <img type="image" src="<?php if (isset($_GET['id']) && !empty($row['image'])) { echo 'images/' . htmlspecialchars($row['image']); } else { echo 'assets/images/landscape.jpg'; } ?>" style="width: 120px;height: 90px" alt="Featured image" />
                                        </div>
                                    </div>
                                </div>
                            </div>

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
