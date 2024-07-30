<?php
	
    $page_title="Page not found";
    include("includes/header.php");
?>

<div class="row">
  <div class="col-xs-12 text-center">
    <div class="card mrg_bottom error_page_item">
		<img class="error_img" src="assets/images/error-404.png" alt="" />
        <div class="mb-4 lead">The page you are looking for was not found.</div>
		<div class="add_btn_primary">
            <?php 
                if(isset($_SESSION['login_type']) AND $_SESSION['login_type']=='reporter'){
                  echo '<a href="reporter_dashboard.php">Back to Home</a>';
                }
                else{
                    echo '<a href="home.php">Back to Home</a>';
                }
            ?>
        </div>
        
    </div>
  </div>
</div>


<?php include("includes/footer.php");?>