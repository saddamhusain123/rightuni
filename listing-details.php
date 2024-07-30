<?php
include 'header.php';
include 'assets/db_confing.php';

// From Slug to GET to Particular College
if (isset($_GET['url'])) {
    $slug = $_GET['url'];
            
    $slug = $conn->real_escape_string($slug); // Sanitize the input

    $sql = "
        SELECT 
            colleges.id,
            colleges.name AS college_name,
            colleges.slug,
            colleges.image,
            colleges.desc,
            colleges.status,
            college_details.address,
            college_details.city,
            college_details.state_id,
            college_details.program,
            college_details.spacialization,
            college_details.admission_process,
            college_details.usp_of_college,
            college_details.affiliation,
            college_details.eligibility,
            college_fees.tution_fee,
            college_fees.tution_fee_state_quota,
            college_fees.tution_fee_mgmt_quota,
            college_fees.other_fee_annual,
            college_fees.one_time_fee,
            college_fees.refundable_fee,
            college_fees.hostel_fee_ac,
            college_fees.hostel_fee_non_ac,
            college_fees.mess_fee_inr,
            GROUP_CONCAT(DISTINCT courses.name SEPARATOR ', ') AS course_names
        FROM 
            colleges
        LEFT JOIN 
            college_details ON colleges.id = college_details.college_id
        LEFT JOIN 
            college_fees ON colleges.id = college_fees.college_id
        LEFT JOIN 
            college_course_manage ON colleges.id = college_course_manage.college_id
        LEFT JOIN 
            courses ON college_course_manage.course_id = courses.id
        WHERE 
            colleges.slug = '$slug'
        GROUP BY 
            colleges.id
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $college_name = "College not found";
    }
}

// Get the college ID
$college_id = $row['id'];

// Get the last 4 comments
$sqlLeave = "SELECT comments.user_id, comments.comment, comments.created_at, users.name FROM comments JOIN users ON comments.user_id = users.id WHERE comments.college_id = $college_id AND comments.status = 1 AND comments.deleted = 0 ORDER BY comments.created_at DESC LIMIT 4";

$resultLeave = $conn->query($sqlLeave);

$comment_text = [];
if ($resultLeave->num_rows > 0) {
    while ($rowLeave = $resultLeave->fetch_assoc()) {
        $comment_text[] = $rowLeave;
    }
}

// print_r($_SESSION['id']);exit;

$is_logged_in = (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true);

if (isset($_POST['submit'])) {
    if ($is_logged_in) {
        // Handle form submission
        $user_id = $_SESSION['id'];
        $college_id = $_POST['college_id'];
        $user_email = $_POST['user_email'];
        $comment_text = $_POST['comment_text'];

        $sqlMessage = "INSERT INTO comments (college_id, user_id, comment, created_at, updated_at) VALUES ('$college_id', '$user_id', '$comment_text', NOW(), NOW() )";
        $resultmessage = $conn->query($sqlMessage);

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}

// $conn->close();

?>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Subheader Start -->
      <div class="section-bg section-padding subheader" style="background-image: url(admin/images/<?php echo $row['image']; ?>);">
         <div class="container">
            <div class="row">
               <div class="col-12">
                  <h1 class="page-title"><?php echo $row['college_name']; ?></h1>
                  <nav aria-label="breadcrumb">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $row['college_name']; ?></li>
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
            <div class="row flex-lg-row-reverse">
               <aside class="col-lg-4">
                  <div class="listing_sidebar style_two">
                     <div class="sidebar_inner">
                        <!-- item -->
                        <div class="sidebar_widget">
                           <div class="listing_details_box">
                              <h4 class="bar_title">Fees Details</h4>
                              <p>
                                 <b>Tution Fee :</b> <?php echo $row['tution_fee']; ?><br>
                                 <b>Tution Fee State Quota :</b> <?php echo $row['tution_fee_state_quota']; ?><br>
                                 <b>Tution Fee Mgmt Quota :</b> <?php echo $row['tution_fee_mgmt_quota']; ?><br>
                                 <b>Other Fees :</b> <?php echo $row['other_fee_annual']; ?><br>
                                 <b>One-time Fee :</b> <?php echo $row['one_time_fee']; ?><br>
                                 <b>Refundable :</b> <?php echo $row['refundable_fee']; ?><br>
                                 <b>Hostel A/c Fee :</b> <?php echo $row['hostel_fee_ac']; ?><br>
                                 <b>Hostel Non A/c Fee :</b> <?php echo $row['hostel_fee_non_ac']; ?><br>
                                 <b>Mess Fee INR :</b> <?php echo $row['mess_fee_inr']; ?>
                              </p>
                           </div>
                        </div>
                        <!-- item -->
                        <div class="sidebar_widget">
                          <h5 class="widget_title"><i class="fal fa-map-marker-alt icon"></i> Location</h5>
                          <div class="widget_inner">
                            <ul class="contact">
                              <li>
                                <a href="#">
                                  <i class="fal fa-map-marker-alt"></i>
                                  <?php echo $row['address'] . ', ' . $row['city'] . ',' . $row['state_id']; ?>
                                </a>
                              </li>
                            </ul>
                          </div>
                        </div>
                        <!-- item -->
                        <div class="sidebar_widget">
                          <h5 class="widget_title">Affiliation</h5>
                          <div class="widget_inner">
                            <ul class="contact">
                              <li>
                                <p>
                                  <?php echo $row['affiliation']; ?>
                                </p>
                              </li>
                            </ul>
                          </div>
                        </div>
                        <!-- item -->
                        <div class="sidebar_widget">
                            <h5 class="widget_title">Courses</h5>
                            <div class="widget_inner">
                                <ul class="contact">
                                    <li>
                                        <p>
                                            <?php echo htmlspecialchars($row['course_names']); ?>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- item -->
                        <!-- php for gallery -->
                        <?php
                        if (isset($college_id)) {
                            $sql_gallery = "SELECT id, college_id, image FROM college_gallery WHERE college_id = ? AND status = 1 AND deleted = 0";
                            $stmtGallery = $conn->prepare($sql_gallery);
                            $stmtGallery->bind_param("i", $college_id);
                            $stmtGallery->execute();
                            $result_gallery = $stmtGallery->get_result();

                            if ($result_gallery->num_rows > 0) { ?>
                                <div class="sidebar_widget">
                                    <h5 class="widget_title"><i class="fal fa-image icon"></i> Gallery</h5>
                                    <div class="widget_inner">
                                        <ul class="gallery row g-3">
                                            <?php
                                            while ($row_gallery = $result_gallery->fetch_assoc()) {
                                                // Sanitize the image path for safe output
                                                $image_url = htmlspecialchars($row_gallery["image"]);
                                            ?>
                                                <li class="col-4">
                                                    <img src="admin/images/<?= $image_url ?>" alt="Gallery Image" class="image-fit">
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php
                            } else {
                                echo "<p>No images found</p>";
                            }
                        }
                        ?>    
                        <!-- php for gallery -->
                        <!-- Reviews -->
                        <div class="listing_details_box">
                          <h4 class="bar_title">Reviews</h4>
                          <?php foreach ($comment_text as $leave_data): ?>
                          <div class="comment_box">
                            <div class="comments_section">                      
                              <ul class="comments mt-4">
                                <li class="comment">
                                  <article>
                                    <div class="comment_image">
                                      <img src="assets/images/blog/author_1.jpg" alt="img" class="image-fit">
                                      <button type="button" class="reply_btn thm-btn btn-small thm-bg-color-two">Reply</button>
                                    </div>
                                    <div class="comment_text">
                                      <h6 class="title"><?php echo htmlspecialchars($leave_data['name']); ?></h6>
                                      <div class="comment_date"><?php echo date('d M Y', strtotime($leave_data['created_at'])); ?></div>
                                      <p><?php echo htmlspecialchars($leave_data['comment']); ?></p>
                                    </div>
                                  </article>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <?php endforeach; ?>
                        </div>
                        <!-- Reviews -->
                     </div>
                  </div>
               </aside>
               <div class="col-lg-8">
                  <!-- item -->
                  <div class="listing_details_box">
                     <h4 class="bar_title"><?php echo $row['college_name']; ?> Description</h4>
                     <p class=""><?php echo $row['desc']; ?></p>
                  </div>
                  <div class="listing_details_box">
                     <h4 class="bar_title">Spacialization</h4>
                     <p class=""><?php echo $row['spacialization']; ?></p>
                  </div>
                  <div class="listing_details_box">
                     <h4 class="bar_title">Program</h4>
                     <p class=""><?php echo $row['program']; ?></p>
                  </div>
                  <div class="listing_details_box">
                     <h4 class="bar_title">Admission Process</h4>
                     <p class=""><?php echo $row['admission_process']; ?></p>
                  </div>
                  <div class="listing_details_box">
                     <h4 class="bar_title">Eligibility</h4>
                     <p class=""><?php echo $row['eligibility']; ?></p>
                  </div>
                  <div class="listing_details_box">
                     <h4 class="bar_title">College USP</h4>
                     <p class=""><?php echo $row['usp_of_college']; ?></p>
                  </div>
                  <!-- item -->
                  <!-- Comment -->
                  <div class="listing_details_box">
                      <h4 class="bar_title">Leave A Reply</h4>
                      <form class="form_style style_two" method="post" action="" id="contact-form" novalidate="novalidate" onsubmit="return validateForm()">
                          <div class="row">
                              <input type="hidden" name="college_id" value="<?php echo isset($row['id']) ? $row['id'] : ''; ?>" required>
                              <input type="hidden" name="submit" value="1">
                              <input type="hidden" id="is_logged_in" value="<?php echo $is_logged_in ? '1' : '0'; ?>">
                              <div class="col-md-12">
                                  <div class="form-group">
                                      <textarea style="resize:none" rows="5" name="comment_text" id="leave_message" class="form-control form-control-custom" placeholder="Write Message" autocomplete="off" required></textarea>
                                  </div>
                              </div>
                              <div class="col-md-12">
                                  <button type="submit" name="submit" class="thm-btn w-100">Leave Comment</button>
                              </div>
                          </div>
                      </form>
                  </div>
                  <!-- Comment -->
               </div>
            </div>
         </div>
      </section>
      <!-- Section End -->

<?php
   include 'footer.php'
?>


<script>
function validateForm() {
    var leave_message = document.getElementById("leave_message").value;

    if (leave_message == "") {
        alert("All fields are required.");
        return false;
    }

    return true;
}

$(document).ready(function() {
    $("#contact-form").on("submit", function(event) {
        event.preventDefault();
        var is_logged_in = $("#is_logged_in").val() === '1';

        if (!is_logged_in) {
            Swal.fire({
                title: 'Not Logged In',
                text: 'You must be logged in to leave a comment. Please log in to continue.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Login',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login'; // Redirect to login page
                }
            });
            return false;
        }

        if (!validateForm()) {
            return;
        }

        $.ajax({
            url: "",  // Current page URL
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    title: 'Success',
                    text: 'Message Sent Successfully',
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred: ' + xhr.responseText,
                    icon: 'error'
                });
            }
        });
    });
});
</script>