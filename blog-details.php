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
               <div class="col-12">
                  <h1 class="page-title"><?php echo $blog ? ($blog['title']) : 'Blog Not Found'; ?></h1>
                  <nav aria-label="breadcrumb">
                     <ol class="breadcrumb">
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
      <section class="section">
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                  <!-- item -->
                  <article class="post post_details">
                     <div class="post_wrapper mb-0">
                        <!-- post image -->
                        <div class="post_image">
                           <a class="d-flex h-100">
                           <img src="admin/images/<?php echo ($blog['image']); ?>" alt="Rightuni" class="image-fit">
                           </a>
                           <div class="post_date">
                              20 <br /> Mar
                           </div>
                        </div>
                        <!-- post caption -->
                        <div class="post_caption">
                           <h2 class="post_title">
                              <a><?php echo ($blog['title']); ?></a>
                           </h2>
                           <div class="post_description">
                           <?php echo ($blog['description']); ?>
                           </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Section End -->
      
      <?php
         include 'footer.php';
      ?>