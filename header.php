<?php
$url = str_replace(basename($_SERVER['PHP_SELF']), "", $_SERVER['PHP_SELF']);
session_start();
ob_start(); 

include 'assets/db_confing.php'; // Adjust the path to your configuration file

// Check if college_id is provided via GET request
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['news_url'])) {
   // Get the 'college_slug' from the URL
   $news_url = $_GET['news_url'];

   // Prepare the SQL query

   $sql = "SELECT news_heading FROM tbl_news WHERE news_url = ?";

   if ($stmt = $conn->prepare($sql)) {
     
       $stmt->bind_param("s", $college_slug);
       
       $stmt->execute();
   
       $stmt->bind_result($news_heading, $description, $keyword, $og_title, $og_url, $og_description, $og_image);

       $stmt->fetch();
  
       $stmt->close();
   }


} else {
  
    $news_heading = "RightUni";
    $college_desc = "Default description";
    $keyword = "default, keywords";
    $og_title = "Default OG Title";
    $og_url = "https://www.defaulturl.com";
    $og_description = "Default OG Description";
    $og_image = "https://www.defaulturl.com/defaultimage.jpg";
}
?>

<!DOCTYPE HTML>
<html lang="zxx">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title><?php echo htmlspecialchars($news_heading); ?></title>
      <meta name='description' content='<?php echo htmlspecialchars($description); ?>'/>
      <meta name='keywords' content='<?php echo htmlspecialchars($keyword); ?>' />
      <meta property='og:title' content='<?php echo htmlspecialchars($og_title); ?>' />
      <meta property='og:url' content='<?php echo htmlspecialchars($og_url); ?>' />
      <meta property='og:description' content='<?php echo htmlspecialchars($og_description); ?>'>
      <meta property='og:image' content='<?php echo htmlspecialchars($og_image); ?>'>
      <meta property="og:type" content="article" />
      <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <link rel="icon" href="favicon.ico">
      <!-- Css -->
      <link href="<?php echo $url; ?>assets/css/plugins/bootstrap.min.css" rel="stylesheet">
      <link href="<?php echo $url; ?>assets/fonts/font-awesome.min.css" rel="stylesheet">
      <link href="<?php echo $url; ?>assets/fonts/bs-icons/bootstrap-icons.css" rel="stylesheet">
      <link href="<?php echo $url; ?>assets/css/plugins/slick.css" rel="stylesheet">
      <link href="<?php echo $url; ?>assets/css/plugins/nice-select.css" rel="stylesheet">
      <link href="<?php echo $url; ?>assets/css/style.css" rel="stylesheet">
      <link href="<?php echo $url; ?>assets/css/responsive.css" rel="stylesheet">
      <link href="<?php echo $url; ?>assets/css/scorolling.css" rel="stylesheet">
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <base href="<?php echo $url; ?>" />

   </head>
   <body>

    <div class="whatsapp_main">
      <a href="https://wa.me/919799946027" target="_blank">
        <img src="assets/images/whatsapp.png" width="7%" class="whatsapp_img">
      </a>
    </div>
    
    <!-- Header Start -->
    <header class="header can-sticky">
    <div class="container">
        <div class="header_inner">
            <!-- logo -->
            <div class="logo">
                <a href="home" class="d-flex h-100">
                    <img src="assets/images/RightUNI-logo.png" alt="logo" class="image-fit-contain">
                    <img src="assets/images/RightUNI-logo.png" alt="logo" class="image-fit-contain">
                </a>
            </div>
            <!-- logo -->
            <!-- Nav & Actions -->
            <div class="nav_actions">
                <!-- Navigation -->
                <nav id="main-nav" class="navigation">
                    <ul class="main-menu">
                        <li class="menu-item">
                            <a href="home" class="">Home</a>
                        </li>
                        <li class="menu-item ">
                            <a href="universities">University</a>
                        </li>
                        <li class="menu-item">
                            <a href="blogs">Blog</a>
                        </li>
                        <li class="menu-item">
                            <a href="contact">Contact</a>
                        </li>
                    </ul>
                </nav>
                <!-- Navigation -->
                <!-- Header Actions -->
                <div class="header_actions">
                    <ul>
                        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                            <li>
                                <a class="login_btn">
                                    <i class="fal fa-user-circle me-2"></i>
                                    <?php echo htmlspecialchars($_SESSION["name"]); ?>
                                </a>
                            </li>
                            <li>
                                <a href="logout.php" class="thm-btn btn-rounded">
                                    Logout
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="login" class="login_btn">
                                    <i class="fal fa-user-circle me-2"></i>
                                    Sign In
                                </a>
                            </li>
                            <li>
                                <a href="register" class="thm-btn btn-rounded">
                                    <i class="bi-plus"></i>
                                    Register Now
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="hamburger">
                        <div class="hamburger_btn">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
                <!-- Header Actions -->
            </div>
            <!-- Nav & Actions -->
        </div>
    </div>
</header>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const currentLocation = location.href;
        const menuItem = document.querySelectorAll('#main-nav .main-menu .menu-item a');
        const menuLength = menuItem.length;
        for (let i = 0; i < menuLength; i++) {
            if (menuItem[i].href === currentLocation) {
                menuItem[i].className = "active";
            }
        }
    });
</script>

      <!-- Header End -->