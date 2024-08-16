<?php 
$page_title = "View College Details";

include("includes/header.php");
require("includes/function.php");
require("language/language.php");

// Get College ID from URL
$cid = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch existing college data
$college_query = "SELECT colleges.*, college_details.address, college_details.city, college_details.program, college_details.spacialization, 
                  college_details.admission_process, college_details.usp_of_college, college_details.affiliation, college_details.eligibility, 
                  college_details.state_id, states.name AS state_name, 
                  college_fees.tution_fee, college_fees.tution_fee_state_quota, college_fees.tution_fee_mgmt_quota, college_fees.other_fee_annual, 
                  college_fees.one_time_fee, college_fees.refundable_fee, college_fees.hostel_fee_ac, college_fees.hostel_fee_non_ac, 
                  college_fees.mess_fee_inr
                  FROM colleges
                  JOIN college_details ON colleges.id = college_details.college_id
                  JOIN states ON college_details.state_id = states.id
                  LEFT JOIN college_fees ON colleges.id = college_fees.college_id
                  WHERE colleges.id = ?";
$stmt = $mysqli->prepare($college_query);
$stmt->bind_param("i", $cid);
$stmt->execute();
$college_result = $stmt->get_result();
$row_college = $college_result->fetch_assoc();

// Fetch selected courses
$course_query = "SELECT courses.name AS course_name, courses.id AS course_id
                 FROM colleges
                 LEFT JOIN college_course_manage ON colleges.id = college_course_manage.college_id
                 JOIN courses ON college_course_manage.course_id = courses.id
                 WHERE colleges.id = ?";
$stmt = $mysqli->prepare($course_query);
$stmt->bind_param("i", $cid);
$stmt->execute();
$course_result = $stmt->get_result();
$selected_courses = [];
while ($course_row = $course_result->fetch_assoc()) {
    $selected_courses[] = $course_row['course_name'];
}
$row_college['old_courses'] = $selected_courses;

// Fetch college gallery images
$gallery_query = "SELECT * FROM college_gallery WHERE college_id = ? AND deleted != 1";
$stmt = $mysqli->prepare($gallery_query);
$stmt->bind_param("i", $cid);
$stmt->execute();
$gallery_result = $stmt->get_result();
$images = [];
while ($image_row = $gallery_result->fetch_assoc()) {
    if (!empty($image_row['image'])) {
        $images[] = $image_row['image'];
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <a href="colleges.php"><h4 class="pull-left" style="font-size: 20px;color: #e91e63"><i class="fa fa-arrow-left"></i> Back</h4></a>
        <div class="card">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?= $page_title ?></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="card-body mrg_bottom">
                <div class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">College Name:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['name']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Courses:</label>
                                <input type="text" class="form-control" value="<?= implode(', ', $row_college['old_courses']) ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Address:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['address']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">City:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['city']) ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">State:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['state_name']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Program:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['program']) ?>" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">USPs of the College:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['usp_of_college']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Affiliations:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['affiliation']) ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Eligibility:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['eligibility']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Tution Fees:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['tution_fee']) ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Tution Fee State Quota:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['tution_fee_state_quota']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Tution Fee Mgmt Quota:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['tution_fee_mgmt_quota']) ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Other Fee Annual:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['other_fee_annual']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">One Time Fee:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['one_time_fee']) ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Refundable Fee:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['refundable_fee']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Mess Fee Inr:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['mess_fee_inr']) ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Hostel Fee AC:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['hostel_fee_ac']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Hostel Fee Non AC:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['hostel_fee_non_ac']) ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Meta Title:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['meta_title']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Meta Keywords:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['meta_keywords']) ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Meta Description:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['meta_description']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Description:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['desc']) ?>" readonly>
                            </div>
                           
                        </div>
                         <div class="row">
                            
                            <div class="col-md-6">
                                <label class="col-md-12 control-label">Specialization:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['spacialization']) ?>" readonly>
                            </div>
                             <div class="col-md-6">
                                <label class="col-md-12 control-label">Admission Process:</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($row_college['admission_process']) ?>" readonly>
                            </div>
                        </div>
                            <div class="row">
                            <!-- Featured Image Section -->
                            <div class="col-md-6">
                                <label class="control-label">Featured Image:</label>
                                <p class="control-label-help">(Recommended resolution: 500x400, 600x500, 700x600 OR width greater than height)</p>
                                <div class="fileupload_block">
                                    <div class="fileupload_img featured_image">
                                        <?php if (!empty($row_college['image'])): ?>
                                            <div class="file-item">
                                                <img src="images/<?= htmlspecialchars($row_college['image']) ?>" style="width: 120px; height: 90px;" alt="Featured Image"/>
                                                <i class="fa fa-eye" onclick="openModa2('<?php echo htmlspecialchars($row_college['image']); ?>')" title="View Image"></i>
                                            </div>
                                        <?php else: ?>
                                            <p>No image available</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Gallery Image Section -->
                            <div class="col-md-6">
                                <label class="control-label">Gallery Images:</label>
                                <p class="control-label-help">(Recommended resolution: 500x400, 600x500, 700x600 OR width greater than height)</p>
                                <div class="fileupload_block">
                                    <div class="fileupload_img featured_image" id="fileList">
                                        <?php if (!empty($images)): ?>
                                            <?php foreach ($images as $image): ?>
                                                <div class="file-item">
                                                    <img 
                                                        src="images/college_gallery/<?php echo htmlspecialchars($image); ?>" 
                                                        alt="Gallery Image" 
                                                        style="width: 120px; height: 90px;"
                                                    >
                                                    <i 
                                                        class="fa fa-eye" 
                                                        onclick="openModal('<?php echo htmlspecialchars($image); ?>')"
                                                        title="View Image"
                                                    ></i>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p>No images available</p>
                                        <?php endif; ?>
                                    </div>
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
 