<?php
include 'header.php';
include 'assets/db_confing.php';

// Sanitize the input
$slug = isset($_GET['slug']) ? $conn->real_escape_string($_GET['slug']) : '';

if ($slug) {
    // Query to get the blog details by slug
    $sql = "SELECT image, title, description, created_at FROM blogs WHERE slug = '$slug' AND status = 1 AND deleted = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $blog = $result->fetch_assoc();
    } else {
        // Handle the case where no blog is found
        $blog = null;
    }
} else {
    // Handle the case where no slug is provided
    $blog = null;
}
?>

<!-- Subheader Start -->
<div class="section-bg section-padding subheader" style="background-image: url(assets/images/subheader.jpg);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="page-title"><?php echo $blog ? htmlspecialchars($blog['title'], ENT_QUOTES, 'UTF-8') : 'Blog Not Found'; ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Blog Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Subheader End -->

<!-- Section Start -->
<section class="section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center ">
                 <img src="admin/images/<?php echo htmlspecialchars($blog['image']); ?>" class="img-fluid mb-4" alt="<?php echo htmlspecialchars($blog['title'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </div>
        <br>
        <div class="row">
            <h3 class="post_title mb-3"><?php echo htmlspecialchars($blog['title']); ?></h3>
            <div class="post_description">
               <?php echo htmlspecialchars(strip_tags($blog['description'])); ?>
            </div>
        </div>
    </div>
</section>
<!-- Section End -->

<?php
include 'footer.php';
?>
