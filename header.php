<?php
$url = str_replace(basename($_SERVER['PHP_SELF']), "", $_SERVER['PHP_SELF']);
session_start();
ob_start();

include 'assets/db_confing.php'; // Ensure this path is correct

// Initialize default values
$default_values = [
    'title' => 'RightUni',
    'description' => 'Find comprehensive details about colleges, courses, and university rankings at RightUni. Your guide to higher education.',
    'keywords' => 'colleges, university details, college rankings, higher education, course details, university admissions, college reviews',
    'og_title' => 'RightUni - Your Guide to Higher Education',
    'og_url' => 'https://www.rightuni.in',
    'og_description' => 'Find comprehensive details about colleges, courses, and university rankings at RightUni. Your guide to higher education.',
    'og_image' => 'https://www.defaulturl.com/defaultimage.jpg',
    'structured_data' => ''
];

// Check if type and slug are provided via GET request
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['type']) && isset($_GET['slug'])) {
    $type = $_GET['type']; // This could be 'college', 'course', or 'blog'
    $slug = $_GET['slug'];

    $allowed_types = ['college', 'course', 'blog'];
    if (in_array($type, $allowed_types)) {
        // Prepare the SQL query based on the type
        $sql = "SELECT name, slug, description, meta_title, meta_keywords, meta_description, image
                FROM $type
                WHERE slug = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $slug);
            $stmt->execute();
            $stmt->bind_result($title, $slug, $description, $meta_title, $meta_keywords, $meta_description, $image);
            $stmt->fetch();
            $stmt->close();

            // Override default values with fetched data
            $default_values['title'] = $meta_title ?: $default_values['title'];
            $default_values['description'] = $meta_description ?: $default_values['description'];
            $default_values['keywords'] = $meta_keywords ?: $default_values['keywords'];
            $default_values['og_title'] = $meta_title ?: $default_values['og_title'];
            $default_values['og_description'] = $meta_description ?: $default_values['og_description'];
            $default_values['og_image'] = $image ?: $default_values['og_image'];
            $default_values['og_url'] = $url . "?type=$type&slug=$slug"; // Dynamically generate URL

            // Add structured data based on type
            switch ($type) {
                case 'colleges':
                    $default_values['structured_data'] = json_encode([
                        "@context" => "https://schema.org",
                        "@type" => "CollegeOrUniversity",
                        "name" => $title,
                        "description" => $description,
                        "url" => $default_values['og_url'],
                        "image" => $image,
                    ]);
                    break;
                case 'courses':
                    $default_values['structured_data'] = json_encode([
                        "@context" => "https://schema.org",
                        "@type" => "Course",
                        "name" => $title,
                        "description" => $description,
                        "provider" => [
                            "@type" => "Organization",
                            "name" => "RightUni",
                            "url" => "https://www.rightuni.com"
                        ],
                        "image" => $image,
                    ]);
                    break;
                case 'blog':
                    $default_values['structured_data'] = json_encode([
                        "@context" => "https://schema.org",
                        "@type" => "BlogPosting",
                        "headline" => $title,
                        "description" => $description,
                        "image" => $image,
                        "url" => $default_values['og_url'],
                        "publisher" => [
                            "@type" => "Organization",
                            "name" => "RightUni",
                            "logo" => [
                                "@type" => "ImageObject",
                                "url" => "https://www.rightuni.com/logo.png"
                            ]
                        ]
                    ]);
                    break;
            }
        }
    }
}

// Sanitize the output
$title = htmlspecialchars($default_values['title']);
$description = htmlspecialchars($default_values['description']);
$keywords = htmlspecialchars($default_values['keywords']);
$og_title = htmlspecialchars($default_values['og_title']);
$og_url = htmlspecialchars($default_values['og_url']);
$og_description = htmlspecialchars($default_values['og_description']);
$og_image = htmlspecialchars($default_values['og_image']);
$structured_data = htmlspecialchars($default_values['structured_data']);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta property="og:title" content="<?php echo $og_title; ?>">
    <meta property="og:url" content="<?php echo $og_url; ?>">
    <meta property="og:description" content="<?php echo $og_description; ?>">
    <meta property="og:image" content="<?php echo $og_image; ?>">
    <meta property="og:type" content="article">
    <meta name="robots" content="index, follow">
    <title><?php echo $title; ?></title>
    <link rel="icon" href="favicon.ico">
    <!-- CSS Links -->
    <link href="<?php echo $url; ?>assets/css/plugins/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $url; ?>assets/fonts/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo $url; ?>assets/fonts/bs-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $url; ?>assets/css/plugins/slick.css" rel="stylesheet">
    <link href="<?php echo $url; ?>assets/css/plugins/nice-select.css" rel="stylesheet">
    <link href="<?php echo $url; ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo $url; ?>assets/css/responsive.css" rel="stylesheet">
    <link href="<?php echo $url; ?>assets/css/scorolling.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <base href="<?php echo $url; ?>">
    <script type="application/ld+json">
        <?php echo $structured_data; ?>
    </script>
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