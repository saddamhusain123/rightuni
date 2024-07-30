<?php
$page_title = "Courses";

include("includes/header.php");
require("includes/function.php");
require("language/language.php");
require("includes/check_availability.php");

// Get category list
$tableName = "courses";
$limit = 10;
$stages = 3;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

// Prepare SQL query with pagination and search
$searchTxt = isset($_POST['data_search']) ? '%' . mysqli_real_escape_string($mysqli, $_POST['search_value']) . '%' : null;
$status = isset($_GET['status']) ? ($_GET['status'] === 'enable' ? '1' : '0') : null;
if ($searchTxt) {
    $qry = "SELECT * FROM courses WHERE name LIKE ? AND deleted = 0 AND id != 1 ORDER BY name ASC";
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param('s', $searchTxt);
} elseif ($status !== null) {
    $qry = "SELECT COUNT(*) as num FROM $tableName WHERE status=? AND deleted = 0 AND id != 1";
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param('s', $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_pages = $result->fetch_assoc();
    $total_pages = $total_pages['num'];

    $targetpage = "courses.php?status=" . $_GET['status'];

    $qry = "SELECT * FROM courses WHERE status=? AND deleted = 0 AND id != 1 ORDER BY name ASC LIMIT ?, ?";
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param('sii', $status, $start, $limit);
} else {
    $qry = "SELECT COUNT(*) as num FROM $tableName WHERE deleted = 0 AND id != 1";
    $stmt = $mysqli->prepare($qry);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_pages = $result->fetch_assoc();
    $total_pages = $total_pages['num'];

    $targetpage = "courses.php";

    $qry = "SELECT * FROM courses WHERE deleted = 0 AND id != 1 ORDER BY name ASC LIMIT ?, ?";
    $stmt = $mysqli->prepare($qry);
    $stmt->bind_param('ii', $start, $limit);
}

$stmt->execute();
$result = $stmt->get_result();

function get_total_college($course_id)
{
    global $mysqli;

    $sql = "SELECT COUNT(*) as num FROM college_course_manage WHERE FIND_IN_SET(?, course_id)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_news = $result->fetch_assoc();
    return $total_news['num'];
}
?>

<div class="row">
    <div class="col-xs-12">
        <?php if (isset($_SERVER['HTTP_REFERER'])) : ?>
            <a href="<?= htmlspecialchars($_SERVER['HTTP_REFERER']) ?>">
                <h4 class="pull-left" style="font-size: 20px; color: #e91e63">
                    <i class="fa fa-arrow-left"></i> Back
                </h4>
            </a>
        <?php endif; ?>
        <div class="card mrg_bottom">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?= htmlspecialchars($page_title) ?></div>
                </div>
                <div class="col-md-7 col-xs-12">
                    <div class="search_list">
                        <div class="search_block">
                            <form method="post" action="">
                                <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" required value="<?= isset($_POST['search_value']) ? htmlspecialchars($_POST['search_value']) : '' ?>">
                                <button type="submit" name="data_search" class="btn-search">
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="add_btn_primary">
                            <a href="add_course.php?add=yes">Add Course</a>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <form id="filterForm" method="GET">
                    <div class="col-md-3">
                        <div style="padding: 0px 0px 5px;">
                            <select name="status" class="form-control select2 filter" style="padding: 5px 10px; height: 40px;">
                                <option value="">--All--</option>
                                <option value="enable" <?= isset($_GET['status']) && $_GET['status'] == 'enable' ? 'selected' : '' ?>>Enable</option>
                                <option value="disable" <?= isset($_GET['status']) && $_GET['status'] == 'disable' ? 'selected' : '' ?>>Disable</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 mrg-top">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course</th>
                            <th>Total Colleges</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = $start + 1;
                        while ($row = $result->fetch_assoc()) {
                            $categoryImage = !file_exists('images/' . $row['category_image']) || $row['category_image'] == '' ? 'https://via.placeholder.com/250x150?text=No image' : 'images/' . $row['category_image'];
                        ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <?= htmlspecialchars($row['name']) ?> 
                                </td>
                                <td><?= get_total_college($row['id']) ?></td>
                                <td>
                                    <div class="row toggle_btn">
                                        <input type="checkbox" id="enable_disable_check_<?= $i ?>" data-id="<?= $row['id'] ?>" data-table="courses" data-column="status" class="cbx hidden enable_disable" <?= $row['status'] == 1 ? 'checked' : '' ?>>
                                        <label for="enable_disable_check_<?= $i ?>" class="lbl"></label>
                                    </div>
                                </td>

                                <td nowrap="">
                                    <a href="add_course.php?cat_id=<?= $row['id'] ?>" class="btn btn-primary btn_edit" data-tooltip="edit course">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" data-id="<?= $row['id'] ?>" data-action="delete" class="btn btn-danger btn_delete btn_cust" data-table="courses" data-tooltip="delete course">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 col-xs-12">
                <div class="pagination_item_block">
                    <nav>
                        <?php if (!isset($_POST["data_search"])) {
                            include("pagination.php");
                        } ?>
                    </nav>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>

<script type="text/javascript">
    // Filter start
    $(".filter").on("change", function(e) {
        $("#filterForm *").filter(":input").each(function() {
            if ($(this).val() === '') {
                $(this).prop("disabled", true);
            }
        });
        $("#filterForm").submit();
    });
</script>
