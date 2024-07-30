<?php 
$page_title="Manage Users";
include('includes/header.php'); 

include('includes/function.php');
include('language/language.php');

require("includes/check_availability.php");  

  	// Serach users
if(isset($_POST['user_search']))
{

  $keyword=cleanInput($_POST['search_value']);

  $user_qry="SELECT * FROM users WHERE users.`name` LIKE '%$keyword%' OR users.`email` LIKE '%$keyword%' AND `id` <> '0' ORDER BY users.`id` DESC";  

  $users_result=mysqli_query($mysqli,$user_qry);	 
}
else
{

  $tableName="users";		
  $targetpage = "manage_users.php"; 	
  $limit = 500; 

  $query = "SELECT COUNT(*) as num FROM $tableName WHERE `id` <> '0' AND `deleted`='0'";
  $total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
  $total_pages = $total_pages['num'];

  $stages = 3;
  $page=0;
  if(isset($_GET['page'])){
    $page = mysqli_real_escape_string($mysqli,$_GET['page']);
  }
  if($page){
   $start = ($page - 1) * $limit; 
 }else{
   $start = 0;	
 }	


 $users_qry="SELECT * FROM users WHERE `id` <> '0' AND `deleted`='0' ORDER BY users.`id` DESC LIMIT $start, $limit";  

 $users_result=mysqli_query($mysqli,$users_qry);

}

?>


<div class="row">
  <div class="col-xs-12">
  	<?php
   if(isset($_SERVER['HTTP_REFERER']))
   {
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
            <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" value="<?=(isset($_POST['search_value'])) ? trim($_POST['search_value']) : ''?>" name="search_value" required>
              <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
            </form>  
          </div>
          <div class="add_btn_primary"> <a href="add_user.php?add">Add User</a> </div>
        </div>
      </div>
      <div class="col-md-4 col-xs-12 text-right" style="float: right;">
        <div class="checkbox" style="width: 95px;margin-top: 5px;margin-left: 10px;right: 100px;position: absolute;">
          <input type="checkbox" id="checkall_input">
          <label for="checkall_input">
            Select All
          </label>
        </div>
        <div class="dropdown" style="float:right">
          <button class="btn btn-primary dropdown-toggle btn_cust" type="button" data-toggle="dropdown">Action
            <span class="caret"></span></button>
            <ul class="dropdown-menu" style="right:0;left:auto;">
              <li><a href="" class="actions" data-action="enable" data-table="users">Enable</a></li>
              <li><a href="" class="actions" data-action="disable" data-table="users">Disable</a></li>
              <li><a href="" class="actions" data-action="delete" data-table="users">Delete !</a></li>
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
              <th>Name</th>
              <th>Email</th>
              <th>Profile Image</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          	<?php
          	$i=0;

          	if(mysqli_num_rows($users_result) > 0)
          	{
          		while($users_row=mysqli_fetch_array($users_result))
          		{

          			$user_img='';

          			if($users_row['image']!='' && file_exists('images/'.$users_row['image'])){
          				$user_img='images/'.$users_row['image'];
          			}
          			else{
          				$user_img='assets/images/user-icons.jpg';
          			}

          			?>
                <tr>
                 <td>
                  <div class="checkbox" style="float: right;margin: 0px">
                   <input type="checkbox" name="post_ids[]" id="checkbox<?php echo $i;?>" value="<?php echo $users_row['id']; ?>" class="post_ids" style="margin: 0px;">
                   <label for="checkbox<?php echo $i;?>"></label>
                 </div>
               </td>
              <td>
                <a href="user_profile.php?user_id=<?=$users_row['id']?>"><?php echo $users_row['name'];?>	
              </a>
            </td>
            <td><?php echo $users_row['email'];?></td>
            <td>
                <div style="position: relative;">
                  <img type="image" src="<?php echo $user_img;?>" alt="image" style="width: 40px;height: 40px;border-radius: 4px"/>
                </div>
              </td>
            <td>
             <div class="row toggle_btn">
              <input type="checkbox" id="enable_disable_check_<?=$i?>" data-id="<?=$users_row['id']?>" data-table="users" data-column="status" class="cbx hidden enable_disable" <?php if($users_row['status']==1){ echo 'checked';} ?>>
              <label for="enable_disable_check_<?=$i?>" class="lbl"></label>
            </div>
          </td>
          <td nowrap="">
            <!-- <a href="user_profile.php?user_id=<?php echo $users_row['id'];?>&redirect=<?=$redirectUrl?>" class="btn btn-success btn_cust" data-toggle="tooltip" data-tooltip="User Profile"><i class="fa fa-history"></i></a> -->

            <a href="add_user.php?user_id=<?php echo $users_row['id'];?>&redirect=<?=$redirectUrl?>" class="btn btn-primary btn_edit"><i class="fa fa-edit"></i></a>

            <a href="javascript:void(0)" data-id="<?php echo $users_row['id'];?>" data-action="delete" class="btn btn-danger btn_delete btn_cust" data-table="users"><i class="fa fa-trash"></i></a>
          </td>
        </tr>
        <?php $i++;}
      }
      else{
       ?>
       <tr>
        <td colspan="7">
          <p class="not_data">
            <strong>Sorry!</strong> no data available
          </p>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
</div>
<div class="col-md-12 col-xs-12">
  <div class="pagination_item_block">
    <nav>
     <?php if(!isset($_POST["search"])){ include("pagination.php");}?>                 
   </nav>
 </div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>     

<?php include('includes/footer.php');?>
