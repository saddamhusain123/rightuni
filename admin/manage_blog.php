<?php 
$page_title = "Manage Blogs";
include('includes/header.php'); 
include('includes/function.php');
include('language/language.php');
require("includes/check_availability.php");

// Check database connection
if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$searchTxt = isset($_POST['search_value']) ? '%' . mysqli_real_escape_string($mysqli, $_POST['search_value']) . '%' : null;
$status = isset($_GET['status']) ? ($_GET['status'] === 'enable' ? '1' : '0') : null;

// Pagination
$tableName = "blogs";   
$targetpage = "manage_blog.php";   
$limit = 10; 

$page = 0;
if (isset($_GET['page'])) {
    $page = mysqli_real_escape_string($mysqli, $_GET['page']);
}
if ($page) {
    $start = ($page - 1) * $limit; 
} else {
    $start = 0;  
}

// Fetch total number of records and query blogs
if ($searchTxt) {
    $qry = "SELECT * FROM blogs WHERE (title LIKE ? OR description LIKE ?) AND deleted = 0 ORDER BY id DESC";
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param('ss', $searchTxt, $searchTxt);
    $stmt->execute();
    $blogs_result = $stmt->get_result();
    $total_pages = $blogs_result->num_rows;
} elseif ($status !== null) {
    $qry = "SELECT COUNT(*) as num FROM $tableName WHERE status=? AND deleted = 0";
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param('s', $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_pages = $result->fetch_assoc();
    $total_pages = $total_pages['num'];

    $targetpage .= "?status=" . $_GET['status'];

    $qry = "SELECT * FROM blogs WHERE status=? AND deleted = 0 ORDER BY id ASC LIMIT ?, ?";
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param('sii', $status, $start, $limit);
    $stmt->execute();
    $blogs_result = $stmt->get_result();
} else {
    $qry = "SELECT COUNT(*) as num FROM $tableName WHERE deleted = 0";
    $stmt = $mysqli->prepare($qry);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_pages = $result->fetch_assoc();
    $total_pages = $total_pages['num'];

    $qry = "SELECT * FROM blogs WHERE deleted = 0 ORDER BY id ASC LIMIT ?, ?";
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param('ii', $start, $limit);
    $stmt->execute();
    $blogs_result = $stmt->get_result();
}
?>

<div class="row">
  <div class="col-xs-12">
    <?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
      <a href="<?= htmlspecialchars($_SERVER['HTTP_REFERER']) ?>">
        <h4 class="pull-left" style="font-size: 20px; color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4>
      </a>
    <?php } ?>
    <div class="card mrg_bottom">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title"><?= htmlspecialchars($page_title) ?></div>
        </div>
        <div class="col-md-7 col-xs-12">              
          <div class="search_list">
            <div class="search_block">
              <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input class="form-control input-sm" placeholder="Search..." type="search" name="search_value" value="<?= isset($_POST['search_value']) ? htmlspecialchars(trim($_POST['search_value'])) : '' ?>" required>
                <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
              </form>  
            </div>
            <div class="add_btn_primary"> <a href="blogs.php">Add Blog</a> </div>
          </div>
        </div>
        <div class="col-md-4 col-xs-12 text-right" style="float: right;">
          <div class="checkbox" style="width: 95px; margin-top: 5px; margin-left: 10px; right: 100px; position: absolute;">
            <input type="checkbox" id="checkall_input">
            <label for="checkall_input">Select All</label>
          </div>
          <div class="dropdown" style="float:right">
            <button class="btn btn-primary dropdown-toggle btn_cust" type="button" data-toggle="dropdown">Action
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" style="right:0;left:auto;">
              <li><a href="<?= $targetpage ?>?status=enable" class="actions" data-action="enable" data-table="blogs">Enable</a></li>
              <li><a href="<?= $targetpage ?>?status=disable" class="actions" data-action="disable" data-table="blogs">Disable</a></li>
              <li><a href="" class="actions" data-action="delete" data-table="blogs">Delete</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="col-md-12 mrg-top">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th style="width: 50px">
                <div class="checkbox">
                  <input type="checkbox" id="checkall_input">
                  <label for="checkall_input"></label>
                </div>
              </th>
              <th>#</th>
              <th>Image</th>
              <th>Blog Title</th>
              <th>Description</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = $start + 1; // Initialize serial number
            if ($blogs_result->num_rows > 0) {
              while ($blog = $blogs_result->fetch_assoc()) {
                $blog_image = $blog['image'] ? 'images/' . htmlspecialchars($blog['image']) : 'assets/images/no-image.png';
                ?>
                <tr>
                  <td>
                    <div class="checkbox" style="float: right; margin: 0px;">
                      <input type="checkbox" name="post_ids[]" id="checkbox<?= $i ?>" value="<?= htmlspecialchars($blog['id']) ?>" class="post_ids" style="margin: 0px;">
                      <label for="checkbox<?= $i ?>"></label>
                    </div>
                  </td>
                  <td><?= $i ?></td> <!-- Display serial number -->
                  <td>
                    <img src="<?= $blog_image ?>" alt="blog image" style="width: 40px; height: 40px; border-radius: 4px;">
                  </td>
                  <td><?= htmlspecialchars($blog['title']) ?></td>
                  <td><?= htmlspecialchars(substr(strip_tags($blog['description']), 0, 50)) ?>...</td>
                  <td>
                    <div class="row toggle_btn">
                      <input type="checkbox" id="enable_disable_check_<?= $i ?>" data-id="<?= htmlspecialchars($blog['id']) ?>" data-table="blogs" data-column="status" class="cbx hidden enable_disable" <?= $blog['status'] == 1 ? 'checked' : '' ?>>
                      <label for="enable_disable_check_<?= $i ?>" class="lbl"></label>
                    </div>
                  </td>
                  <td nowrap="">
                    <a href="blogs.php?id=<?= htmlspecialchars($blog['id']) ?>&redirect=<?= htmlspecialchars($targetpage) ?>" class="btn btn-primary btn_edit" data-tooltip="edit blog"><i class="fa fa-edit"></i></a>
                    <a href="javascript:void(0)" data-id="<?= htmlspecialchars($blog['id']) ?>" data-action="delete" class="btn btn-danger btn_delete btn_cust" data-table="blogs" data-tooltip="delete blog"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
                <?php $i++; }
            } else { ?>
              <tr>
                <td colspan="7">
                  <p class="not_data"><strong>Sorry!</strong> No data available</p>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="col-md-12 col-xs-12">
        <div class="pagination_item_block">
          <nav>
            <?php if (!isset($_POST["user_search"])) { include("pagination.php"); } ?>                 
          </nav>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>     

<?php include('includes/footer.php'); ?>
