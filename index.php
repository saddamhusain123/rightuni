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
            <p class="subtitle">The Best Learning University</p>
         </div>
         <div class="row justify-content-center">
            <div class="col-lg-10">
               <div class="banner_form">
                  <div class="input-group">
                     <input type="text" name="#" class="form-control" placeholder="What are you looking for?"
                        autocomplete="off" required>
                     <select class="form-control custom-select" name="#" required>
                        <option selected>All Categories</option>
                        <option value="option 1">Option 1</option>
                        <option value="option 2">Option 2</option>
                        <option value="option 3">Option 3</option>
                     </select>
                     <input type="text" name="#" class="form-control location_input" placeholder="Location"
                        autocomplete="off" required>
                     <div class="input-group-append ms-lg-3 ms-sm-2 mb-xl-30">
                        <button type="submit" class="thm-btn w-100">
                           <i class="fal fa-search"></i>
                           Search
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <form class="row justify-content-center banner_form g-2">
            <div class="col-auto">
               <div class="form-group form-radio">
                  <input class="form-radio-input" type="radio" name="category" value="Hotels & Travels" id="radio_one">
                  <label class="form-radio-label" for="radio_one">
                     <div>
                        <!-- <span class="icon fal fa-hotel"></span> -->
                        <span><i class="fa fa-laptop"></i></span>
                        <p class="mb-0">All Online Courses</p>
                     </div>
                  </label>
               </div>
            </div>
            <div class="col-auto">
               <div class="form-group form-radio">
                  <input class="form-radio-input" type="radio" name="category" value="Hotels & Travels" id="radio_two"
                     checked>
                  <label class="form-radio-label" for="radio_two">
                     <div>
                        <span><i class="fa fa-graduation-cap"></i></span>
                        <p class="mb-0">Graduation Courses</p>
                     </div>
                  </label>
               </div>
            </div>
            <div class="col-auto">
               <div class="form-group form-radio">
                  <input class="form-radio-input" type="radio" name="category" value="Hotels & Travels"
                     id="radio_three">
                  <label class="form-radio-label" for="radio_three">
                     <div>
                        <span><i class="fa fa-users"></i></span>
                        <p class="mb-0">Professional Team</p>
                     </div>
                  </label>
               </div>
            </div>
            <div class="col-auto">
               <div class="form-group form-radio">
                  <input class="form-radio-input" type="radio" name="category" value="Hotels & Travels" id="radio_four">
                  <label class="form-radio-label" for="radio_four">
                     <div>
                        <span><i class="fa fa-briefcase" aria-hidden="true"></i></span>
                        <p class="mb-0">Job Placement Support</p>
                     </div>
                  </label>
               </div>
            </div>
            <div class="col-auto">
               <div class="form-group form-radio">
                  <input class="form-radio-input" type="radio" name="category" value="Hotels & Travels" id="radio_five">
                  <label class="form-radio-label" for="radio_five">
                     <div>
                        <span><i class="fa fa-paint-brush"></i></span>
                        <p class="mb-0">Art & Design</p>
                     </div>
                  </label>
               </div>
            </div>
         </form>
      </div>
   </div>
   <span class="left_skew"></span>
   <span class="right_skew"></span>
</div>
<!-- Banner End -->
<?php
   include 'homecontact.php';
?>
<!-- Explore Start -->
<!-- <section class="section">
   <div class="container">
      <div class="section-header">
         <h3 class="title">Most Popular <span>Professor</span></h3>
         <p class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
      </div>
      <div class="row explore_slider"> -->
         <!-- Item -->
         <!-- <div class="px-2 slide_item">
            <div class="explore_box">
               <div class="explore_image">
                  <a href="explore.php" class="d-flex h-100">
                     <img src="assets/images/explore/1.jpg" alt="img" class="image-fit">
                  </a>
               </div>
               <div class="explore_text">
                  <div class="rating">
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star"></i>
                  </div>
                  <h5 class="title"><a href="explore.php">Health Professor</a></h5>
               </div>
               <span class="listing_badge thm-btn btn-small">Expert</span>
            </div>
         </div> -->
         <!-- Item -->
         <!-- <div class="px-2 slide_item">
            <div class="explore_box">
               <div class="explore_image">
                  <a href="explore.php" class="d-flex h-100">
                     <img src="assets/images/explore/2.jpg" alt="img" class="image-fit">
                  </a>
               </div>
               <div class="explore_text">
                  <div class="rating">
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star"></i>
                  </div>
                  <h5 class="title"><a href="explore.php">English Lecturer Professor</a></h5>
               </div>
               <span class="listing_badge thm-btn btn-small">Expert</span>
            </div>
         </div> -->
         <!-- Item -->
         <!-- <div class="px-2 slide_item">
            <div class="explore_box">
               <div class="explore_image">
                  <a href="explore.php" class="d-flex h-100">
                     <img src="assets/images/explore/3.jpg" alt="img" class="image-fit">
                  </a>
               </div>
               <div class="explore_text">
                  <div class="rating">
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star"></i>
                  </div>
                  <h5 class="title"><a href="explore.php">Math Professor</a></h5>
               </div>
               <span class="listing_badge thm-btn btn-small">Expert</span>
            </div>
         </div> -->
         <!-- Item -->
         <!-- <div class="px-2 slide_item">
            <div class="explore_box">
               <div class="explore_image">
                  <a href="explore.php" class="d-flex h-100">
                     <img src="assets/images/explore/4.jpg" alt="img" class="image-fit">
                  </a>
               </div>
               <div class="explore_text">
                  <div class="rating">
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star"></i>
                  </div>
                  <h5 class="title"><a href="explore.php">Senior Lecturer</a></h5>
               </div>
               <span class="listing_badge thm-btn btn-small">Expert</span>
            </div>
         </div> -->
         <!-- Item -->
         <!-- <div class="px-2 slide_item">
            <div class="explore_box">
               <div class="explore_image">
                  <a href="explore.php" class="d-flex h-100">
                     <img src="assets/images/explore/5.jpg" alt="img" class="image-fit">
                  </a>
               </div>
               <div class="explore_text">
                  <div class="rating">
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star-fill"></i>
                     <i class="bi-star"></i>
                  </div>
                  <h5 class="title"><a href="explore.php">Biology Professor</a></h5>
               </div>
               <span class="listing_badge thm-btn btn-small">Expert</span>
            </div>
         </div> -->
         <!-- Item -->
      <!-- </div>
   </div>
</section> -->
<!-- Explore End -->
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
                  <a href="services.html">Undergrduate Study</a>
               </h5>
               <p class="mb-0 text fw-500">
                  <span class="thm-color-one">
                     <!-- $28 -->
                  </span>
                  <a href="services.html">Read More..</a>
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
                  <a href="services.html">Books & Library</a>
               </h5>
               <p class="mb-0 text fw-500">
                  <span class="thm-color-one">
                     <!-- $30 -->
                  </span>
                  <a href="services.html">Read More..</a>
               </p>
            </div>
         </div>
         <div class="col-lg-3 col-sm-6">
            <div class="service_box">
            <div class="icon">
                  <i class="fa fa-gamepad" aria-hidden="true"></i>
               </div>
               <h5 class="title">
                  <a href="services.html">Sports & Games</a>
               </h5>
               <p class="mb-0 text fw-500">
                  <span class="thm-color-one">
                     <!-- $22 -->
                  </span>
                  <a href="services.html">Read More..</a>
               </p>
            </div>
         </div>
         <div class="col-lg-3 col-sm-6">
            <div class="service_box">
               <div class="icon">
                  <i class="fa fa-book"></i>
               </div>
               <h5 class="title">
                  <a href="services.html">Free Courses</a>
               </h5>
               <p class="mb-0 text fw-500">
                  <span class="thm-color-one">
                     <!-- $15 -->
                  </span>
                  <a href="services.html">Read More..</a>
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
                  <img src="assets/images/testimonials/1.jpg" alt="img" class="image-fit">
               </div>
               <div class="testimonial_text">
                  <p class="comment">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc iaculis hendrerit
                     elementum. </p>
                  <div class="author_info">
                     <h6 class="name mb-0">John Wick</h6>
                     <p>New York</p>
                  </div>
               </div>
            </div>
         </div>
         <!-- item -->
         <div class="px-3 slide_item">
            <div class="testimonial_item">
               <div class="author_image">
                  <img src="assets/images/testimonials/2.jpg" alt="img" class="image-fit">
               </div>
               <div class="testimonial_text">
                  <p class="comment">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc iaculis hendrerit
                     elementum. </p>
                  <div class="author_info">
                     <h6 class="name mb-0">James</h6>
                     <p>Paris</p>
                  </div>
               </div>
            </div>
         </div>
         <!-- item -->
         <div class="px-3 slide_item">
            <div class="testimonial_item">
               <div class="author_image">
                  <img src="assets/images/testimonials/3.jpg" alt="img" class="image-fit">
               </div>
               <div class="testimonial_text">
                  <p class="comment">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc iaculis hendrerit
                     elementum. </p>
                  <div class="author_info">
                     <h6 class="name mb-0">Michael</h6>
                     <p>Bangkok</p>
                  </div>
               </div>
            </div>
         </div>
         <!-- item -->
         <div class="px-3 slide_item">
            <div class="testimonial_item">
               <div class="author_image">
                  <img src="assets/images/testimonials/4.jpg" alt="img" class="image-fit">
               </div>
               <div class="testimonial_text">
                  <p class="comment">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc iaculis hendrerit
                     elementum.</p>
                  <div class="author_info">
                     <h6 class="name mb-0">William</h6>
                     <p>Dubai</p>
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
            <h3 class="title">Latest <span>News</span></h3>
            <p class="text">Stay updated with our latest news and announcements. Discover current events, important updates, and insights from our community. </p>
        </div>
        <div class="row justify-content-center">
            <!-- item -->
            <?php foreach ($blogs as $blog): ?>
                <article class="col-lg-4 col-md-6 post">
                    <div class="post_wrapper">
                        <div class="post_image">
                            <a href="blog\<?php echo htmlspecialchars($blog['slug']); ?>" class="d-flex h-100">
                                <img src="admin/images/<?php echo htmlspecialchars($blog['image']); ?>" alt="img" class="image-fit blog-image">
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
                            <p class="post_desc mb-0"><?php echo truncateText(htmlspecialchars($blog['description']), 20); ?></p>
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