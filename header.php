<?php
session_start();
ob_start(); 

include 'assets/db_confing.php'; // Ensure this file contains the correct DB connection settings

$url = str_replace(basename($_SERVER['PHP_SELF']), "", $_SERVER['PHP_SELF']);

// Initialize default values
$title = "RightUni";
$description = "Default description";
$meta_title = "Default Meta Title";
$meta_keywords = "default, keywords";
$meta_description = "Default Meta Description";
$image = "https://www.defaulturl.com/defaultimage.jpg";

// // Initialize Open Graph URL
$og_url = $url; // Default base URL

$sqlcourses = "SELECT meta_title, meta_keywords, meta_description FROM courses WHERE meta_title IS NOT NULL OR meta_keywords IS NOT NULL OR meta_description IS NOT NULL";
$result = $conn->query($sqlcourses);

if ($result) {
   
    $courses = $result->fetch_all(MYSQLI_ASSOC);
    // $data['courses'] = $courses;
    $result->free();
} else {
    echo "Error executing courses query: " . $conn->error;
}

$sqlblogs = "SELECT meta_title, meta_keywords, meta_description FROM blogs WHERE meta_title IS NOT NULL OR meta_keywords IS NOT NULL OR meta_description IS NOT NULL";
$result = $conn->query($sqlblogs);

if ($result) {
    $blogs = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
} else {
    echo "Error executing blogs query: " . $conn->error;
}
$allData = array_merge($courses,$blogs);


$sqlcolleges = "SELECT meta_title, meta_keywords, meta_description FROM colleges WHERE meta_title IS NOT NULL OR meta_keywords IS NOT NULL OR meta_description IS NOT NULL";
$result = $conn->query($sqlcolleges);

if ($result) {
    $colleges = $result->fetch_all(MYSQLI_ASSOC);
    // $data['colleges'] = $colleges;
    $result->free();
} else {
    echo "Error executing colleges query: " . $conn->error;
}

$allData = array_merge($allData,$colleges);
 
$commaSeparatedStringMetakeywords = "";
$commaSeparatedStringMetaDescription = "";
$meta_titles = array();
foreach ($allData as $key => $value) {
    if(!empty($value['meta_title'])){
        $meta_titles[] = $value['meta_title'];
    }
    
    if(!empty($value['meta_keywords'])){
        $commaSeparatedStringMetakeywords .= $value['meta_keywords'];
    }

    if(!empty($value['meta_description'])){
            $commaSeparatedStringMetaDescription .= $value['meta_description'];
    }
    
    
}

$commaSeparatedStringMetaTitle = implode(",  ", $meta_titles);



?>
<!DOCTYPE HTML>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($title); ?></title>
    <meta name='title' content='<?php echo htmlspecialchars($commaSeparatedStringMetaTitle); ?>'/>
    <meta name='description' content='<?php echo htmlspecialchars($commaSeparatedStringMetaDescription); ?>'/>
    <meta name='keywords' content='<?php echo htmlspecialchars($commaSeparatedStringMetakeywords); ?>' />
    <meta property='og:title' content='<?php echo htmlspecialchars($meta_title); ?>' />
    <meta property='og:url' content='<?php echo htmlspecialchars($og_url); ?>' />
    <meta property='og:description' content='<?php echo htmlspecialchars($description); ?>'>
    <meta property='og:image' content='<?php echo htmlspecialchars($image); ?>'>
    <meta property="og:type" content="article" />
    <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" href="favicon.ico">
    <!-- Css -->
    <link href="<?php echo htmlspecialchars($url); ?>assets/css/plugins/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo htmlspecialchars($url); ?>assets/fonts/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo htmlspecialchars($url); ?>assets/fonts/bs-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo htmlspecialchars($url); ?>assets/css/plugins/slick.css" rel="stylesheet">
    <link href="<?php echo htmlspecialchars($url); ?>assets/css/plugins/nice-select.css" rel="stylesheet">
    <link href="<?php echo htmlspecialchars($url); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo htmlspecialchars($url); ?>assets/css/responsive.css" rel="stylesheet">
    <link href="<?php echo htmlspecialchars($url); ?>assets/css/scorolling.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <base href="<?php echo htmlspecialchars($url); ?>" />
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