<?php 
date_default_timezone_set("Asia/Kolkata");
include("includes/connection.php");
include("includes/session_check.php");

$admin_id=$_SESSION["id"];

$currentTime = time() + 25200;

    // if current time is more than session timeout back to login page
if($currentTime > $_SESSION['timeout']){
  session_destroy();
  echo '<script language=javascript> localStorage.removeItem("activeTab"); location.href="index.php";</script>';
}

    //Get file name
$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentFile);
$currentFile = $parts[count($parts) - 1];

$requestUrl = $_SERVER["REQUEST_URI"];
$urlparts = Explode('/', $requestUrl);
$redirectUrl = $urlparts[count($urlparts) - 1]; 

$mysqli->set_charset("utf8mb4"); 

$user_img='';

if(isset($_SESSION['login_type']) AND $_SESSION['login_type']=='admin'){

  $profile_qry="SELECT * FROM admin WHERE id='".$_SESSION['id']."'";
  $profile_result=mysqli_query($mysqli,$profile_qry);
  $profile_details=mysqli_fetch_assoc($profile_result);

  $user_img='images/'.$profile_details['image'];
}                 
else{

  $profile_qry="SELECT * FROM users WHERE id='$admin_id'";
  $profile_result=mysqli_query($mysqli,$profile_qry);
  $profile_details=mysqli_fetch_assoc($profile_result);

  if($profile_details['image']!='' && file_exists('images/'.$profile_details['image'])){
    $user_img='images/'.$profile_details['image'];
  }
  else{
    $user_img='assets/images/user-icons.jpg';
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="author" content="">
  <meta name="description" content="">
  <meta http-equiv="Content-Type"content="text/html;charset=UTF-8"/>
  <meta name="viewport"content="width=device-width, initial-scale=1.0">
  <title> <?php if(isset($page_title)){ echo $page_title.' | '.APP_NAME; }else{ echo APP_NAME; } ?></title>
  <link rel="icon" href="images/<?php echo APP_LOGO;?>" sizes="16x16">
  <link rel="stylesheet" type="text/css" href="assets/css/vendor.css">
  <link rel="stylesheet" type="text/css" href="assets/css/flat-admin.css">

  <!-- Theme -->
  <link rel="stylesheet" type="text/css" href="assets/css/theme/blue-sky.css">
  <link rel="stylesheet" type="text/css" href="assets/css/theme/blue.css">
  <link rel="stylesheet" type="text/css" href="assets/css/theme/red.css">
  <link rel="stylesheet" type="text/css" href="assets/css/theme/yellow.css">

  
  <link rel="stylesheet" href="assets/snackbar-master/snackbar.css">

  <link rel="stylesheet" type="text/css" href="assets/duDialog-master/duDialog.min.css?v=<?= date('dmYhis') ?>">

  <script src="assets/ckeditor/ckeditor.js"></script>

  <style type="text/css">
     .btn_edit, .btn_cust{
      padding: 5px 10px !important;
    }
    .social_img{
      width: 20px !important;
      height: 20px !important;
      position: absolute;
      top: -11px;
      z-index: 1;
      left: 40px;
      margin:5px;
    }

    .multi_action .dropdown-menu{
      padding-top: 0px;
      padding-bottom: 0px;
      box-shadow: 0px 6px 12px 1px rgba(4, 4, 4, 0.23);
    }
    .multi_action .dropdown-menu > li > a{
      padding: 8px 20px !important;
    }
    .multi_action .dropdown-menu > li > a{
      border-bottom: 1px solid #eee;
    }
    p.not_data{
      font-size: 16px;
      text-align: center;
      margin-top: 10px;
    }

    .top{
      position: relative !important;
      padding: 0px 0px 20px 0px !important;
    }
    .dataTables_wrapper{
      overflow: initial !important;
    }  
    @media (min-width:200px) and (max-width:991px){
      .mytooltip:hover .tooltip-content{
        display:none
      }
    }

  </style>

</head>
<body>
  <div class="app app-default">
    <aside class="app-sidebar" id="sidebar">
      <div class="sidebar-header"> <a class="sidebar-brand" href="home.php"><img src="images/<?php echo APP_LOGO;?>" alt="app logo" /></a>
        <button type="button" class="sidebar-toggle"> <i class="fa fa-times"></i> </button>
      </div>
      <div class="sidebar-menu">
        <?php 
        if(isset($_SESSION['login_type']) && $_SESSION['login_type']=='admin'){
          ?>
          <ul class="sidebar-nav">

            <li <?php if($currentFile=="home.php"){?>class="active"<?php }?>> <a href="home.php">
              <div class="icon"> <i class="fa fa-dashboard" aria-hidden="true"></i> </div>
              <div class="title">Dashboard</div>
            </a> 
          </li>
          <li <?php if($currentFile=="courses.php" or $currentFile=="add_news.php" or $currentFile=="edit_news.php" or (isset($current_page) AND $current_page=='news')){?>class="active"<?php }?>> <a href="courses.php">
          <div class="icon"> <i class="fa fa-newspaper-o" aria-hidden="true"></i> </div>
          <div class="title">Courses</div>
        </a> 
      </li>
          <li <?php if($currentFile=="colleges.php" or $currentFile=="add_news.php" or $currentFile=="edit_news.php" or (isset($current_page) AND $current_page=='news')){?>class="active"<?php }?>> <a href="colleges.php">
          <div class="icon"> <i class="fa fa-newspaper-o" aria-hidden="true"></i> </div>
          <div class="title">Colleges</div>
        </a> 
      </li>

       <li <?php if($currentFile=="manage_blog.php" or $currentFile=="manage_blog.php" or $currentFile=="manage_blog.php" or (isset($current_page) AND $current_page=='news')){?>class="active"<?php }?>> 
          <a href="manage_blog.php">
            <div class="icon"> <i class="fa fa-newspaper-o" aria-hidden="true"></i> </div>
            <div class="title">Blogs</div>
          </a> 
        </li>
    
      <li <?php if($currentFile=="manage_users.php" or $currentFile=="add_user.php" or (isset($current_page) AND $current_page=='users')){?>class="active"<?php }?>> 
        <a href="manage_users.php">
          <div class="icon"> <i class="fa fa-users" aria-hidden="true"></i> </div>
          <div class="title">Users</div>
        </a> 
      </li>

  
</ul>
<?php }else{
  ?>
  <ul class="sidebar-nav">
    <li <?php if($currentFile=="reporter_dashboard.php"){?>class="active"<?php }?>> <a href="reporter_dashboard.php">
      <div class="icon"> <i class="fa fa-dashboard" aria-hidden="true"></i> </div>
      <div class="title">Dashboard</div>
    </a> 
  </li>
  <li <?php if($currentFile=="manage_reporter_news.php" or $currentFile=="add_news.php" or $currentFile=="edit_news.php"){?>class="active"<?php }?>> <a href="manage_reporter_news.php">
    <div class="icon"> <i class="fa fa-newspaper-o" aria-hidden="true"></i> </div>
    <div class="title">Manage News</div>
  </a> 
</li>
</ul>
<?php
} ?>
</div>

</aside>   
<div class="app-container">
  <nav class="navbar navbar-default" id="navbar">
    <div class="container-fluid">
      <div class="navbar-collapse collapse in">
        <ul class="nav navbar-nav navbar-mobile">
          <li>
            <button type="button" class="sidebar-toggle"> <i class="fa fa-bars"></i> </button>
          </li>
          <li class="logo"> <a class="navbar-brand" href=""><?php echo APP_NAME;?></a> </li>
          <li>
            <button type="button" class="navbar-toggle">
              <?php if($user_img){?>               
                <img class="profile-img" src="<?php echo $user_img;?>">
              <?php }else{?>
                <img class="profile-img" src="assets/images/profile.png">
              <?php }?>
              
            </button>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-left">
          <li class="navbar-title"><?php echo APP_NAME;?></li>
          
        </ul>
        <ul class="nav navbar-nav navbar-right">

          <?php 
          if(isset($_SESSION['login_type']) AND $_SESSION['login_type']=='admin'){
            ?>

            <li class="dropdown notification danger">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
                <div class="title">Notifications</div>
                <div class="count notify_count">0</div>
              </a>
              <div class="dropdown-menu">
                <ul>
                  <li class="dropdown-header">Notification</li>
                </ul>
              </div>
            </li>
          <?php } ?>
          <li class="dropdown profile"> <a href="profile.php" class="dropdown-toggle" data-toggle="dropdown"> <?php if($user_img){?>               
            <img class="profile-img" src="<?php echo $user_img;?>">
          <?php }else{?>
            <img class="profile-img" src="assets/images/profile.png">
          <?php }?>
          <div class="title">Profile</div>
        </a>
        <div class="dropdown-menu">
          <div class="profile-info">
            <h4 class="username">Admin</h4>
          </div>
          <ul class="action">
          <?php 
            if(isset($_SESSION['login_type']) AND $_SESSION['login_type']=='admin'){
              echo '<li><a href="profile.php">Profile</a></li>';
            }
            else{
              echo '<li><a href="reporter_profile.php?reporter_id='.$admin_id.'">Profile</a></li>';
            }
            ?>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</div>
</nav>