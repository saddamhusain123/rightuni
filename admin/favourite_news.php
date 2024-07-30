<?php 
	
	 $page_title="Reporter Favourite News";
	 include('includes/header_profile.php');

?>

<link rel="stylesheet" type="text/css" href="assets/css/stylish-tooltip.css">

<div class="card-body no-padding tab-content">
	<div role="tabpanel" class="tab-pane active">
      <div class="row">
        <div class="col-md-12">
          <table class="datatable table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Sr.</th>
                    <th>Image</th>
                    <th>News</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                <?php

                $sql="SELECT tbl_news.`id`,tbl_news.`news_heading`,tbl_news.`news_featured_image`, tbl_favourite.`id` AS favourite_id, tbl_favourite.`created_at` AS favourite_date FROM tbl_news
                  LEFT JOIN tbl_favourite ON tbl_news.`id`=tbl_favourite.`news_id`
                  WHERE tbl_favourite.`user_id`='$reporter_id' ORDER BY tbl_favourite.`id` DESC";

                $res=mysqli_query($mysqli, $sql);
                $no=1;
                while ($row=mysqli_fetch_assoc($res)) {
                  ?>
                  <tr>
                    <td><?=$no;?></td>
                    <td nowrap="">
                          <?php 
                            if(file_exists('images/'.$row['news_featured_image'])){
                          ?>
                          <span class="mytooltip tooltip-effect-3">
                            <span class="tooltip-item">
                              <img src="images/<?php echo $row['news_featured_image'];?>" alt="no image" style="width: 60px;height: auto;border-radius: 5px">
                            </span> 
                            <span class="tooltip-content clearfix">
                              <a href="images/<?php echo $row['news_featured_image'];?>" target="_blank"><img src="images/<?php echo $row['news_featured_image'];?>" alt="no image" /></a>
                            </span>
                          </span>
                          <?php }else{
                            ?>
                            <img src="" alt="no image" style="width: 60px;height: 60px;border-radius: 5px">
                            <?php
                          } ?>
                      </td>
                      <td title="<?=$row['news_heading']?>">
                          <?php
                              if(strlen($row['news_heading']) > 40){
                                echo substr(stripslashes($row['news_heading']), 0, 40).'...';  
                              }else{
                                echo $row['news_heading'];
                              }
                            ?>
                        </td>
                    <td><?=calculate_time_span($row['favourite_date'],true);?></td>
                  </tr>
                  <?php
                  $no++;
                }
                mysqli_free_result($res);

                ?>
              </tbody>
            </table>
        </div>
      </div>
    </div>
</div>


<!-- End profile header div -->
</div>
</div>
</div>


<?php 
    include('includes/footer.php');
?>