<?php 

$page_title=(!isset($_GET['user_id'])) ? 'Add User' : 'Edit User';

include('includes/header.php');

include('includes/function.php');
include('language/language.php'); 

require("includes/check_availability.php");

require_once("thumbnail_images.class.php");

    // Get add user start
if(isset($_POST['submit']) and isset($_GET['add']))
{
  $sql="SELECT * FROM users WHERE `email` = '".trim($_POST['email'])."'";
  $res=mysqli_query($mysqli, $sql);

  if(mysqli_num_rows($res) == 0){
    $ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);

    $profile_img=rand(0,99999).'_'.date('dmYhis')."_user.".$ext;

    //Main Image
    $tpath1='images/'.$profile_img;   

    if($ext!='png')  {
      $pic1=compress_image($_FILES["profile_img"]["tmp_name"], $tpath1, 80);
    }
    else{
      $tmp = $_FILES['profile_img']['tmp_name'];
      move_uploaded_file($tmp, $tpath1);
    }


    $data = array(  
      'name'  =>  cleanInput($_POST['name']),
      'email'  =>  cleanInput($_POST['email']),
      'password'  =>  password_hash($_POST['password'], PASSWORD_DEFAULT),
      'phone'  =>  cleanInput($_POST['phone']),
      'address'  =>  cleanInput($_POST['address']),
      'image'  =>  $profile_img,
      'created_at'  =>  date('d-m-Y h:i:s A'), 
      'updated_at'  =>  date('d-m-Y h:i:s A'), 
    );
   
    $qry = Insert('users',$data);

    $_SESSION['class']="success";
    $_SESSION['msg']="10";
  }
  else{
    $_SESSION['class']="warn";
    $_SESSION['msg']="email_exist";
  }

  header("location:manage_users.php");	 
  exit;
}
    // Get add user end

    // Get users list start
if(isset($_GET['user_id']))
{
  $user_qry="SELECT * FROM users WHERE `id`='".$_GET['user_id']."'";
  $user_result=mysqli_query($mysqli,$user_qry);
  $user_row=mysqli_fetch_assoc($user_result);
}

    // Get update user start
if(isset($_POST['submit']) and isset($_POST['user_id']))
{

  if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
  {
    $_SESSION['class']="warn";
    $_SESSION['msg']="invalid_email_format";
    header("Location:add_user.php?user_id=".$_POST['user_id']);
    exit;
  }
  else{

    $email=cleanInput($_POST['email']);

    $sql="SELECT * FROM users WHERE `email` = '$email' AND `id` <> '".$_POST['user_id']."'";

    $res=mysqli_query($mysqli, $sql);

    if(mysqli_num_rows($res) == 0){
      $data = array(
        'name'  =>  cleanInput($_POST['name']),
        'email'  =>  cleanInput($_POST['email']),
        'phone'  =>  cleanInput($_POST['phone']),
        'address'  =>  cleanInput($_POST['address']),
      );

      if($_POST['password']!="")
      {
        $password=password_hash($_POST['password'], PASSWORD_DEFAULT);
        $data = array_merge($data, array("password"=>$password));
      }

      if($_FILES['profile_img']['name']!="")
      {

        if($user_row['image']!="" OR !file_exists('images/'.$user_row['image']))
        {
          unlink('images/'.$user_row['user_profile']);
        }

        $ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);

        $profile_img=rand(0,99999).'_'.date('dmYhis')."_user.".$ext;

                    //Main Image
        $tpath1='images/'.$profile_img;   

        if($ext!='png')  {
          $pic1=compress_image($_FILES["profile_img"]["tmp_name"], $tpath1, 80);
        }
        else{
          $tmp = $_FILES['profile_img']['tmp_name'];
          move_uploaded_file($tmp, $tpath1);
        }

        $data = array_merge($data, array("image" => $profile_img));

      }

      $user_edit=Update('users', $data, "WHERE id = '".$_POST['user_id']."'");

      $_SESSION['class']="success";

      $_SESSION['msg']="11";
    }
    else{
      $_SESSION['class']="warn";
      $_SESSION['msg']="email_exist";
      
      header("Location:add_user.php?user_id=".$_POST['user_id']);
      exit;
    }
  }

  if(isset($_GET['redirect'])){
    header("Location:".$_GET['redirect']);
  }
  else{
    header("Location:add_user.php?user_id=".$_POST['user_id']);
  }
  exit;
}
    // Get update user end
?>


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
      <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data" >
       <input  type="hidden" name="user_id" value="<?php if(isset($_GET['user_id'])){echo $_GET['user_id'];}?>" />
       <div class="section">
        <div class="section-body">
          <div class="form-group">
            <label class="col-md-3 control-label">Name :-</label>
            <div class="col-md-6">
              <input type="text" name="name" id="name" value="<?php if(isset($_GET['user_id'])){echo $user_row['name'];}?>" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Email :-</label>
            <div class="col-md-6">
              <input type="email" name="email" id="email" value="<?php if(isset($_GET['user_id'])){echo $user_row['email'];}?>" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Password :-</label>
            <div class="col-md-6">
              <input type="password" name="password" id="password" value="" class="form-control" <?php if(!isset($_GET['user_id'])){?>required<?php }?>>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Phone :-</label>
            <div class="col-md-6">
              <input type="text" name="phone" id="phone" value="<?php if(isset($_GET['user_id'])){echo $user_row['phone'];}?>" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Address :-</label>
            <div class="col-md-6">
              <textarea type="textarea" name="address" id="phonaddresse" value="<?php if(isset($_GET['user_id'])){echo $user_row['address'];}?>" class="form-control">
                <?php if(isset($_GET['user_id'])){echo $user_row['address'];}?>
              </textarea>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label">Profile Image :-
              <p class="control-label-help">(Recommended resolution: 100x100, 200x200) OR Squre image</p>
            </label>
            <div class="col-md-6">
              <div class="fileupload_block">
                <input type="file" name="profile_img" value="fileupload" accept=".png, .jpg, .jpeg, .svg, .gif" <?php echo (!isset($_GET['user_id'])) ? 'required="require"' : '' ?> id="fileupload">
                <div class="fileupload_img">
                  <?php 
                  $img_src="";
                  if(!isset($_GET['user_id']) || $user_row['image']==''){
                    $img_src='assets/images/landscape.jpg';
                  }else{
                    $img_src='images/'.$user_row['image'];
                  }
                  ?>
                  <img type="image" src="<?=$img_src?>" alt="image" style="width: 86px;height: 86px" />
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


<?php include('includes/footer.php');?>

<script type="text/javascript">
	// Get preview image start
  $("input[name='profile_img']").change(function() { 
    var file=$(this);

    if(file[0].files.length != 0){
      if(isImage($(this).val())){
        render_upload_image(this,$(this).next('.fileupload_img').find("img"));
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