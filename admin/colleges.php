<?php 
$page_title = "Manage College";
include('includes/header.php'); 
include('includes/function.php');
include('language/language.php');
require("includes/check_availability.php");  

// Initializations
$tableName = "colleges";
$targetpage = "colleges.php"; 
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

// Handle search
$searchTxt = isset($_POST['search_value']) ? '%' . mysqli_real_escape_string($mysqli, $_POST['search_value']) . '%' : null;

// Handle status filter
$status = isset($_GET['status']) ? ($_GET['status'] === 'enable' ? '1' : '0') : null;

// Prepare SQL queries
if ($searchTxt) {
    $countQuery = "SELECT COUNT(*) as num FROM $tableName WHERE name LIKE ? AND deleted = 0";
    $qry = "SELECT colleges.*, college_details.city, states.name AS state_name 
            FROM $tableName 
            JOIN college_details ON colleges.id = college_details.college_id 
            JOIN states ON college_details.state_id = states.id 
            WHERE colleges.name LIKE ? AND colleges.deleted = 0 
            ORDER BY colleges.name ASC LIMIT ?, ?";
} elseif ($status !== null) {
    $countQuery = "SELECT COUNT(*) as num FROM $tableName WHERE status = ? AND deleted = 0";
    $qry = "SELECT colleges.*, college_details.city, states.name AS state_name 
            FROM $tableName 
            JOIN college_details ON colleges.id = college_details.college_id 
            JOIN states ON college_details.state_id = states.id 
            WHERE colleges.status = ? AND colleges.deleted = 0 
            ORDER BY colleges.name ASC LIMIT ?, ?";
    $targetpage .= "?status=" . $_GET['status'];
} else {
    $countQuery = "SELECT COUNT(*) as num FROM $tableName WHERE deleted = 0";
    $qry = "SELECT colleges.*, college_details.city, states.name AS state_name 
            FROM $tableName 
            JOIN college_details ON colleges.id = college_details.college_id 
            JOIN states ON college_details.state_id = states.id 
            WHERE colleges.deleted = 0 
            ORDER BY colleges.name ASC LIMIT ?, ?";
}

// Get total number of pages
$stmt = $mysqli->prepare($countQuery);
if ($searchTxt) {
    $stmt->bind_param('s', $searchTxt);
} elseif ($status !== null) {
    $stmt->bind_param('s', $status);
}
$stmt->execute();
$result = $stmt->get_result();
$total_pages = $result->fetch_assoc()['num'];

// Prepare and execute the main query
$stmt = $mysqli->prepare($qry);
if ($searchTxt) {
    $stmt->bind_param('sii', $searchTxt, $start, $limit);
} elseif ($status !== null) {
    $stmt->bind_param('sii', $status, $start, $limit);
} else {
    $stmt->bind_param('ii', $start, $limit);
}
$stmt->execute();
$colleges_result = $stmt->get_result();
?>

<div class="row">
  <div class="col-xs-12">
            <?php
              if(isset($_SERVER['HTTP_REFERER'])) {
      echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
              }
            ?>            
    <div class="card mrg_bottom">
      <div class="page_title_block">
        <div class="col-md-5 col-xs-12">
          <div class="page_title"><?=$page_title?></div>
        </div>
        <div class="col-md-7 col-xs-12">              
          <div class="search_list">
            <div class="search_block">
              <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" value="<?=(isset($_POST['search_value'])) ? trim($_POST['search_value']) : ''?>" name="search_value" >
                <button type="submit" name="data_search" class="btn-search"><i class="fa fa-search"></i></button>
              </form>  
            </div>
            <div class="add_btn_primary"> <a href="add_college.php">Add College</a> </div>
          </div>
        </div>
        <div class="col-md-4 col-xs-12 text-right" style="float: right;">
          <div class="checkbox" style="width: 95px;margin-top: 5px;margin-left: 10px;right: 100px;position: absolute;">
            <input type="checkbox" id="checkall_input">
            <label for="checkall_input">Select All</label>
          </div>
          <div class="dropdown" style="float:right">
            <button class="btn btn-primary dropdown-toggle btn_cust" type="button" data-toggle="dropdown">Action
              <span class="caret"></span></button>
              <ul class="dropdown-menu" style="right:0;left:auto;">
                <li><a href="" class="actions" data-action="enable" data-table="colleges">Enable</a></li>
                <li><a href="" class="actions" data-action="disable" data-table="colleges">Disable</a></li>
                <li><a href="" class="actions" data-action="delete" data-table="colleges">Delete!</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 mrg-top">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th style="width: 50px"></th>
                <th>#</th>
                <th>College Name</th>
                <th>City</th> 
                <th>State</th>
                <th>Image</th>
                <th>Status</th>  
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = $start + 1; // Initialize counter for row numbers
              if($colleges_result->num_rows > 0) {
                while($college_row = $colleges_result->fetch_assoc()) {
                  $college_img = ($college_row['image'] != '' && file_exists('images/'.$college_row['image'])) ? 'images/'.$college_row['image'] : 'assets/images/user-icons.jpg';
                  ?>
                  <tr>
                    <td>
                      <div class="checkbox" style="float: right;margin: 0px">
                        <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $college_row['id']; ?>" class="post_ids" style="margin: 0px;">
                        <label for="checkbox<?php echo $i;?>"></label>
                      </div>
                    </td>
                    <td><?php echo $i;?></td>
                    <td><a href="view_college.php?id=<?php echo $college_row['id'];?>&redirect=<?=$redirectUrl?>"><?php echo $college_row['name'];?></a></td>
                    <td><?php echo $college_row['city'];?></td>
                    <td><?php echo $college_row['state_name'];?></td>
                    <td><img src="<?php echo $college_img;?>" style="height: 100px; width: 100px;"></td>
                    <td>
                      <div class="row toggle_btn">
                        <input type="checkbox" id="enable_disable_check_<?=$i?>" data-id="<?=$college_row['id']?>" data-table="colleges" data-column="status" class="cbx hidden enable_disable" <?php if($college_row['status']==1){ echo 'checked';} ?>>
                        <label for="enable_disable_check_<?=$i?>" class="lbl"></label>
                      </div>
                    </td>
                    <td nowrap="">
                      <a href="edit_college.php?id=<?php echo $college_row['id'];?>&redirect=<?=$redirectUrl?>" class="btn btn-primary btn_edit" data-tooltip="edit college"><i class="fa fa-edit"></i></a>
                      <a href="javascript:void(0)" data-id="<?php echo $college_row['id'];?>" data-action="delete" class="btn btn-danger btn_delete btn_cust" data-table="colleges" data-tooltip="delete college"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                  <?php $i++;
                }
              } else { ?>
                <tr>
                  <td colspan="8">
                    <p class="not_data"><strong>Sorry!</strong> no data available</p>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="col-md-12 col-xs-12">
          <div class="pagination_item_block">
            <nav>
              <?php 
              if($total_pages > $limit){
                include("pagination.php");
              } 
              ?>
            </nav>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>

<?php include('includes/footer.php'); ?>
