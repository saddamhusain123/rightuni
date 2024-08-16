<?php 

$page_title = 'View User';

include('includes/header.php');
include('includes/function.php');
include('language/language.php'); 
 
// Get user details start
if (isset($_GET['user_id'])) {
    $user_qry = "SELECT * FROM users WHERE `id`='" . $_GET['user_id'] . "'";
    $user_result = mysqli_query($mysqli, $user_qry);
    $user_row = mysqli_fetch_assoc($user_result);
} else {
    header("Location: manage_users.php");
    exit;
}

// Get user details end
?>


<div class="row">
  <div class="col-md-12">
  	<?php
    if(isset($_SERVER['HTTP_REFERER']))
    {
     echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>';
   }
   ?>
   <div class="card">
    <div class="page_title_block">
      <div class="col-md-5 col-xs-12">
        <div class="page_title"><?=$page_title?></div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="card-body mrg_bottom"> 
      <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data" >
       <input  type="hidden" name="user_id" value="<?php if(isset($_GET['user_id'])){echo $_GET['user_id'];}?>" />
       <div class="section">
        <div class="section-body">
          <div class="form-group">
            <label class="col-md-3 control-label">Name :-</label>
            <div class="col-md-6">
              <input type="text" name="name" id="name" readonly value="<?= htmlspecialchars($user_row['name']) ?>" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Email :-</label>
            <div class="col-md-6">
              <input type="email" name="email" id="email" readonly value="<?= htmlspecialchars($user_row['email']) ?>" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Password :-</label>
            <div class="col-md-6">
              <input type="password" name="password" id="password" readonly value="" class="form-control" <?php if(!isset($_GET['user_id'])){?>required<?php }?>>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Phone :-</label>
            <div class="col-md-6">
              <input type="text" name="phone" id="phone" readonly value="<?= htmlspecialchars($user_row['phone']) ?>" class="form-control">
            </div>
          </div>
          <div class="form-group">
    <label class="col-md-3 control-label">Address:</label>
    <div class="col-md-6">
        <textarea name="address" id="phonaddresse" readonly class="form-control">
            <?php if (isset($_GET['user_id'])) {
                echo htmlspecialchars($user_row['address']);
            } ?>
        </textarea>
    </div>
</div>
          <div class="form-group">
            <label class="col-md-3 control-label">Profile Image :-
              <p class="control-label-help">(Recommended resolution: 100x100, 200x200) OR Squre image</p>
            </label>
							            <!-- User Image Section -->
							<div class="col-md-6">
							    <label class="control-label">User Image:</label>
							    <p class="control-label-help">(Recommended resolution: 200x200, or square images)</p>
							    <div class="fileupload_block">
							        <input type="file" name="profile_img" accept=".png, .jpg, .jpeg, .svg, .gif" <?php echo (!isset($_GET['user_id'])) ? 'required="required"' : '' ?> style = "display: none;" id="fileupload">
							        <div class="fileupload_img">
							            <?php 
							            $img_src = "";
							            if (!isset($_GET['user_id']) || $user_row['image'] == '') {
							                $img_src = 'assets/images/landscape.jpg'; // Default image
							            } else {
							                $img_src = 'images/' . htmlspecialchars($user_row['image']); // User's image
							            }
							            ?>
							            <div class="file-item">
							                <img src="<?= htmlspecialchars($img_src) ?>" style="width: 120px; height: 90px;" alt="User Image"/>
							                <?php if (isset($_GET['user_id']) && !empty($user_row['image'])): ?>
							                    <i class="fa fa-eye" onclick="openModa2('<?= htmlspecialchars($user_row['image']) ?>')" title="View Image"></i>
							                <?php endif; ?>
							            </div>
							        </div>   
							    </div>
							</div>

          <div class="form-group">
            <div class="col-md-9 col-md-offset-3">
              <button type="submit" name="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
</div>

                           

            <div id="imageModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <img id="modalImage" src="" style="width: 100%;">
                </div>
            </div>

            <script>
                function openModal(imageSrc) {
                    var modal = document.getElementById("imageModal");
                    var modalImage = document.getElementById("modalImage");
                    modalImage.src = 'images/college_gallery/' + imageSrc;
                    modal.style.display = "block";
                }

                function closeModal() {
                    var modal = document.getElementById("imageModal");
                    modal.style.display = "none";
                }

                window.onclick = function(event) {
                    var modal = document.getElementById("imageModal");
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }
            </script>
             <script>
                function openModa2(imageSrc) {
                    var modal = document.getElementById("imageModal");
                    var modalImage = document.getElementById("modalImage");
                    modalImage.src = 'images/' + imageSrc;
                    modal.style.display = "block";
                }

                function closeModal() {
                    var modal = document.getElementById("imageModal");
                    modal.style.display = "none";
                }

                window.onclick = function(event) {
                    var modal = document.getElementById("imageModal");
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }
            </script>
             <style>
                       /* File upload image styles */
            .fileupload_img {
                display: flex;
                flex-wrap: wrap;
            }

            .file-item {
                position: relative;
                display: flex;
                align-items: center;
                margin: 10px;
                border: 1px solid #ddd;
                border-radius: 8px;
                overflow: hidden;
            }

            .file-item img {
                max-width: 120px;
                max-height: 120px;
            }

            .file-item .fa-eye {
                position: absolute;
                right: 10px;
                top: 10px;
                cursor: pointer;
                color: #007bff;
                font-size: 24px;
                background: #fff;
                border-radius: 50%;
                padding: 5px;
                transition: color 0.3s;
            }

            .file-item .fa-eye:hover {
                color: #0056b3;
            }

            /* Modal styles */
            .modal {
                display: none;
                position: fixed;
                z-index: 1050;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.5);
                padding-top: 60px;
            }

            .modal-content {
                background-color: #fff;
                margin: 5% auto;
                padding: 20px;
                border-radius: 8px;
                width: 80%;
                max-width: 800px;
            }

            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }

            .close:hover,
            .close:focus {
                color: #000;
                text-decoration: none;
            }

        </style>

                            </div>
                        </div>
                    </div>
                </div>
            </div>       <?php include("includes/footer.php"); ?>

        </div>
        <style>
          /* General styles for the view page */
        .page_title {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-body {
            padding: 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-body {
            padding: 15px;
        }

        .form-control {
            border-radius: 4px;
            box-shadow: none;
            background-color: #f9f9f9;
            padding: 10px;
            font-size: 14px;
            color: #333;
            border: 1px solid #ccc;
        }

        .form-control[readonly] {
            background-color: #eee;
            cursor: not-allowed;
        }

        .control-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .row {
            margin-bottom: 15px;
        }

        .col-md-6 {
            padding: 0 15px;
        }

        .img-responsive {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .no-images p {
            color: #888;
            font-style: italic;
        }

        </style>
 
<?php include('includes/footer.php'); ?>
