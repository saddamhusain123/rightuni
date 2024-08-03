<?php 
include 'header.php';

$college_name = "College not found"; // Default message
$colleges = [];


if (isset($_GET['id'])) {
    $cat_id = intval($_GET['id']); // Sanitize the input

    // Fetch category name
    $sqlcat = "SELECT name FROM courses WHERE id = $cat_id";
    $resultcat = $conn->query($sqlcat);
    
    if ($resultcat && $resultcat->num_rows > 0) {
        $row = $resultcat->fetch_assoc();
        $college_name = ($row['name']);
    }

    // Fetch college details for the specific category
    $sql = "SELECT colleges.*, college_details.address, college_details.created_at
            FROM college_course_manage
            LEFT JOIN colleges ON college_course_manage.college_id = colleges.id
            LEFT JOIN college_details ON college_details.college_id = colleges.id
            WHERE college_course_manage.course_id = $cat_id
            GROUP BY colleges.id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $colleges[] = $row;
        }
    } else {
        $college_name = "No college found for this course.";
    }
} else {
    $college_name = "No category ID provided";
}

?>
<!-- Subheader Start -->
<div class="section-bg section-padding subheader" style="background-image: url(assets/images/subheader.jpg);">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <h1 class="page-title"><?php echo ($college_name); ?></h1>
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="home">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo ($college_name); ?></li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- Subheader End -->

<!-- Section Start -->
<section class="section-padding">
   <div class="container">
      <div class="section-header">
         <p class="text">Explore the profiles of these top universities and find the one that best fits your educational goals.</p>
      </div>
      <!-- Items start -->
      <div class="row">
         <?php if (!empty($colleges)): ?>
            <?php foreach ($colleges as $college_data): ?>
               <div class="col-lg-4 col-md-6">
                  <div class="listing_box">
                     <div class="listing_image">
                        <a href="college/<?php echo ($college_data['slug']); ?>" class="d-flex h-100">
                           <img src="admin/images/<?php echo ($college_data['image']); ?>" alt="Rightuni" class="image-fit">
                        </a>
                     </div>
                     <div class="listing_caption">
                        <h4 class="title"><a href="college/<?php echo ($college_data['slug']); ?>"><?php echo ($college_data['name']); ?></a></h4>
                        <ul class="listing_meta">
                           <li><i class="fas fa-map-marker-alt"></i> <?php echo ($college_data['address']); ?></li>
                           <li><i class="fas fa-calendar-day"></i><?php echo ($college_data['created_at']); ?></li>
                        </ul>
                     </div>
                     <div class="listing_footer">
                        <div class="action_btn">
                           <button type="button" class="listing_btn">
                              <a href="college/<?php echo ($college_data['slug']); ?>">Read More..</a>
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         <?php else: ?>
            <div class="col-12">
               <p><?php echo ($college_name); ?></p>
            </div>
         <?php endif; ?>
      </div>
      <!-- Items end -->
   </div>
</section>
<!-- Section End -->

<?php
include 'footer.php';
?>
