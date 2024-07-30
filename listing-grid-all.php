<?php
include 'header.php';
include 'assets/db_confing.php';
?>
<!-- Subheader Start -->
<div class="section-bg section-padding subheader" style="background-image: url(assets/images/subheader.jpg);">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <h1 class="page-title">Popular Universities</h1>
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="home">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Popular Universities</li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>

<!-- Listings Start -->
<?php
include "course_list_section.php";
?>
<!-- Listings End -->

<?php
include 'footer.php';
?>
