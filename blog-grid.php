<?php 
include 'header.php';
include 'assets/db_confing.php';

// To GET the blog table                                       
$sql = "SELECT image, slug, title, description, created_at, status, deleted FROM blogs WHERE status = 1 AND deleted = 0";
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
      <!-- Subheader Start -->
      <div class="section-bg section-padding subheader" style="background-image: url(assets/images/subheader.jpg);">
         <div class="container">
            <div class="row">
               <div class="col-12">
                  <h1 class="page-title">Blog Grid</h1>
                  <nav aria-label="breadcrumb">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Blog Grid</li>
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
               <p class="text">Stay updated with our latest news and announcements. Discover current events, important updates, and insights from our community. </p>
            </div>
            <div class="row">
               <!-- item -->
               <?php foreach ($blogs as $blog): ?>
               <article class="col-lg-4 col-md-6 post">
                  <div class="post_wrapper">
                     <div class="post_image">
                        <a href="blog/<?php echo ($blog['slug']); ?>" class="d-flex h-100">
                        <img src="admin/images/<?php echo ($blog['image']); ?>" alt="img" class="image-fit blog-image">
                        </a>
                     </div>
                     <div class="post_caption">
                        <div class="post_date">
                           <?php echo date('d M', strtotime($blog['created_at'])); ?>
                        </div>
                        <h2 class="post_title">
                           <a href="blog/<?php echo ($blog['slug']); ?>"><?php echo truncateText(htmlspecialchars($blog['title']), 4); ?></a>
                        </h2>
                        <p class="post_desc mb-0"><?php echo truncateText(htmlspecialchars($blog['description']), 20); ?>
</p>
                     </div>
                  </div>
               </article>
               <?php endforeach; ?>
               <!-- item -->
            </div>
         </div>
      </section>
      <!-- Section End -->

<?php 
   include 'footer.php';
?>
