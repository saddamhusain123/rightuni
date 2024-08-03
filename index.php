<?php
include 'header.php';

include 'assets/db_confing.php';

$basename = basename($_SERVER['PHP_SELF']);
$url = str_replace("$basename", "", $_SERVER['PHP_SELF']);

?>
<!-- Banner Start -->
<div class="single_banner section-bg" style="background-image: url(assets/images/banner/single_banner.jpg); margin-bottom: 82px;">
   <div class="relative text-center">
      <div class="container">
         <div class="banner_text">
            <h1 class="title" style="margin-top: 82px;">
               Discover Universities
               <span class="thm-color-one fw-lighter"> Near By You.</span>
            </h1>
            <p class="subtitle">The Best Learning University</p><br>
            <p style="font-size: 18px" class="subtitle" >RightUni is a premier platform offering exhaustive college listings and detailed insights into various institutions. It provides an intuitive interface for exploring courses, campus amenities, and admission criteria, helping students make informed decisions.</p>
         </div>
         
      </div>
   </div>
   <span class="left_skew"></span>
   <span class="right_skew"></span>
</div>
<!-- Banner End -->
<?php
   include 'homecontact.php';
?>
<!-- Services Start -->
<section class="section-padding section-bg no-overlay" style="background-image: url(assets/images/bg/map_bg.jpg);">
   <div class="container">
      <div class="section-header">
         <h3 class="title">Best <span>Services</span></h3>
         <p class="text">Explore our top services: exceptional undergraduate programs, extensive library resources, diverse sports and games, and valuable free courses.</p>
      </div>
      <div class="row">
         
       
         <!-- item -->
         <div class="col-lg-3 col-sm-6">
            <div class="service_box">
               <div class="icon">
                  <i class="fa fa-graduation-cap" aria-hidden="true"></i>
               </div>
               <h5 class="title">
                  <a>Undergraduate Study</a>
               </h5>
               <p class="mb-0 text fw-500">
                  <span class="thm-color-one">
                     <!-- $28 -->
                  </span>
               </p>
            </div>
         </div>
         <!-- item -->
         <div class="col-lg-3 col-sm-6">
            <div class="service_box">
               <div class="icon">
                  <i class="fa fa-desktop" aria-hidden="true"></i>
               </div>
               <h5 class="title">
                  <a>Books & Library</a>
               </h5>
               <p class="mb-0 text fw-500">
                  <span class="thm-color-one">
                     <!-- $30 -->
                  </span>
               </p>
            </div>
         </div>
         <div class="col-lg-3 col-sm-6">
            <div class="service_box">
            <div class="icon">
                  <i class="fa fa-gamepad" aria-hidden="true"></i>
               </div>
               <h5 class="title">
                  <a>Sports & Games</a>
               </h5>
               <p class="mb-0 text fw-500">
                  <span class="thm-color-one">
                     <!-- $22 -->
                  </span>
               </p>
            </div>
         </div>
         <div class="col-lg-3 col-sm-6">
            <div class="service_box">
               <div class="icon">
                  <i class="fa fa-book"></i>
               </div>
               <h5 class="title">
                  <a>Free Courses</a>
               </h5>
               <p class="mb-0 text fw-500">
                  <span class="thm-color-one">
                     <!-- $15 -->
                  </span>
               </p>
            </div>
         </div>
         <!-- item -->
      </div>
   </div>
</section>
<!-- Services End -->
<!-- Listings Start -->
<?php
include "course_list_section.php";
?>


<!-- Listings End -->
<!-- How It Works Start -->
<section class="section-padding section-bg-fix" style="background-image: url(assets/images/bg/1.jpg);">
   <div class="container">
      <div class="section-header text-white">
         <h3 class="title">How it <span>Works</span></h3>
         <p class="text">Learn how our process works. Follow our simple steps to get started, access resources, and achieve your goals. </p>
      </div>
      <div class="row justify-content-center">
         <!-- item -->
         <div class="col-lg-4 col-md-6">
            <div class="hw_it_works_box">
               <div class="icon">
                  <i class="fal fa-search"></i>
               </div>
               <div class="text">
                  <h6 class="title mb-1">
                     Search Professor
                  </h6>
                  <p class="mb-0">Sed consequat sapien faus quam bibendum convallis. </p>
               </div>
            </div>
         </div>
         <!-- item -->
         <div class="col-lg-4 col-md-6">
            <div class="hw_it_works_box">
               <div class="icon">
                  <i class="fa fa-graduation-cap"></i>
               </div>
               <div class="text">
                  <h6 class="title mb-1">
                     Choose Universities
                  </h6>
                  <p class="mb-0">Sed consequat sapien faus quam bibendum convallis. </p>
               </div>
            </div>
         </div>
         <!-- item -->
         <div class="col-lg-4 col-md-6">
            <div class="hw_it_works_box">
               <div class="icon">
                  <i class="fal fa-money-bill-alt"></i>
               </div>
               <div class="text">
                  <h6 class="title mb-1">
                     Payment
                  </h6>
                  <p class="mb-0">Sed consequat sapien faus quam bibendum convallis. </p>
               </div>
            </div>
         </div>
         <!-- item -->
         
      </div>
   </div>
</section>
<script>


</script>
<!-- How It Works End -->
<!-- Testimonials Start -->
<section class="section section-bg no-overlay" style="background-image: url(assets/images/bg/map_bg.jpg);">
   <div class="container">
      <div class="section-header">
         <h3 class="title">What Students <span>say</span></h3>
         <p class="text">Read testimonials from our students about their experiences, successes, and how our programs have positively impacted their lives. </p>
      </div>
      <div class="row testimonial_slider">
         <!-- item -->
         <div class="px-3 slide_item">
            <div class="testimonial_item">
               <div class="author_image">
                  <img src="assets/images/testimonials/1.jpg" alt="Rightuni" class="image-fit">
               </div>
               <div class="testimonial_text">
                  <p class="comment">Finding the right college in Hyderabad seemed overwhelming until I discovered RightUni.in. Special thanks to Anjali Rao for her guidance and support throughout the process. I am now enrolled in my dream college thanks to you! </p>
                  <div class="author_info">
                     <h6 class="name mb-0">Zaheer Shaikh</h6>
                     <p>Hyderabad</p>
                  </div>
               </div>
            </div>
         </div>
         <!-- item -->
         <div class="px-3 slide_item">
            <div class="testimonial_item">
               <div class="author_image">
                  <img src="assets/images/testimonials/2.jpg" alt="Rightuni" class="image-fit">
               </div>
               <div class="testimonial_text">
                  <p class="comment">I was lost in the sea of college options in Mumbai, but RightUni.in and Ravi Kumar made my search so much easier. Thank you for your personalized advice and patience, Ravi. I couldn't have done it without you! </p>
                  <div class="author_info">
                     <h6 class="name mb-0">Ananya Patel</h6>
                     <p>Mumbai</p>
                  </div>
               </div>
            </div>
         </div>
         <!-- item -->
         <div class="px-3 slide_item">
            <div class="testimonial_item">
               <div class="author_image">
                  <img src="assets/images/testimonials/3.jpg" alt="Rightuni" class="image-fit">
               </div>
               <div class="testimonial_text">
                  <p class="comment">I was struggling to find a suitable college in Chennai, but RightUni.in and their advisor Priya Singh came to my rescue. Her insights and assistance were invaluable. Thank you, Priya, for making my college journey smooth and stress-free. </p>
                  <div class="author_info">
                     <h6 class="name mb-0">Rajesh Nair</h6>
                     <p>Chennai</p>
                  </div>
               </div>
            </div>
         </div>
         <!-- item -->
         <div class="px-3 slide_item">
            <div class="testimonial_item">
               <div class="author_image">
                  <img src="assets/images/testimonials/4.jpg" alt="Rightuni" class="image-fit">
               </div>
               <div class="testimonial_text">
                  <p class="comment">Choosing a college in Pune was a daunting task until I connected with Arjun Mehta from RightUni.in. His expert advice and friendly demeanor made the entire process seamless. Thanks, Arjun, for helping me find the perfect fit!</p>
                  <div class="author_info">
                     <h6 class="name mb-0">Meera Jain</h6>
                     <p>Pune</p>
                  </div>
               </div>
            </div>
         </div>
         <!-- item -->
      </div>
   </div>
</section>
<!-- Testimonials End -->
<?php
// Fetch the blog table
$sql = "SELECT image, slug, title, description, created_at FROM blogs WHERE status = 1 AND deleted = 0 ORDER BY id DESC LIMIT 3";
$result = $conn->query($sql);

$blogs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }
}

// Function to truncate text to a specified number of words
function truncateText($text, $wordLimit) {
    $words = explode(' ', $text);
    if (count($words) > $wordLimit) {
        $words = array_slice($words, 0, $wordLimit);
        return implode(' ', $words) . '...';
    }
    return $text;
}
?>
<!-- Listings Start -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <h3 class="title">Latest <span>Blogs</span></h3>
            <p class="text">Stay updated with our latest news and announcements. Discover current events, important updates, and insights from our community. </p>
        </div>
        <div class="row justify-content-center">
            <!-- item -->
            <?php foreach ($blogs as $blog): ?>
                <article class="col-lg-4 col-md-6 post">
                    <div class="post_wrapper">
                        <div class="post_image">
                            <a href="blog\<?php echo htmlspecialchars($blog['slug']); ?>" class="d-flex h-100">
                                <img src="admin/images/<?php echo htmlspecialchars($blog['image']); ?>" alt="Rightuni" class="image-fit blog-image">
                            </a>
                        </div>
                        <div class="post_caption">
                            <div class="post_date">
                                <?php echo date('d M', strtotime($blog['created_at'])); ?>
                            </div>
                            <h2 class="post_title">
                                <a href="blog\<?php echo htmlspecialchars($blog['slug']); ?>">
                                    <?php echo truncateText(htmlspecialchars($blog['title']), 4); ?>
                                </a>
                            </h2>
                            <p class="post_desc mb-0"><?php echo truncateText(htmlspecialchars(strip_tags($blog['description'])), 20); ?></p>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
            <div class="text-center">
                <a href="blogs">
                    <h4 class="getallcollege">View All Blogs</h4>
                </a>
            </div>
        </div>
    </div>
</section>


<!-- Listings End -->

<?php
include 'footer.php'
   ?>