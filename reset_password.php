<?php
// session_start();
include 'header.php';
include 'assets/db_confing.php';

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";     
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have at least 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before updating the database
    if (empty($new_password_err) && empty($confirm_password_err)) {
        // Get token from URL
        $token = $_GET['token'];

        // Verify token
        $sql = "SELECT register_id FROM password_resets WHERE token = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $token);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    // Token is valid, update the password
                    $stmt->bind_result($register_id);
                    $stmt->fetch();

                    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $sql = "UPDATE register SET password = ? WHERE register_id = ?";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("si", $new_password_hash, $register_id);
                        if ($stmt->execute()) {
                            // Delete the token from the password_resets table
                            $sql = "DELETE FROM password_resets WHERE token = ?";
                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param("s", $token);
                                $stmt->execute();
                            }

                            // Password updated successfully
                            $_SESSION['password_reset_success'] = "Your password has been reset successfully.";
                            header("location: reset_password.php?success=1");
                            exit();
                        }
                    }
                } else {
                    echo "Invalid token.";
                }
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
<br><br><br><br><br>
      
      <!-- Section Start -->
      <section class="section-padding pt-0">
         <div class="container">
            <div class="row g-0">
               <div class="col-lg-7">
                  <div class="contact_form">
                     <form class="form" id="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?token=' . $_GET['token']; ?>" id="contact-form" novalidate="novalidate" onsubmit="return validateForm()">
                        <div class="section-header text-start">
                              <h3 class="title">Reset <span>Password</span></h3>
                        </div><br>
                        <?php 
                        if (isset($_SESSION['reset_message'])) {
                              echo '<div class="alert alert-success">' . $_SESSION['reset_message'] . '</div>';
                              unset($_SESSION['reset_message']);
                        }
                        ?>
                        
                        <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control form-control-custom" id="password_value" value="<?php echo $new_password; ?>" placeholder="password" autocomplete="off" required="">
                                    <button id="password_eye" class="fal fa-eye" style="position: absolute; right: -30px; bottom: -15px; z-index: 10; color: #9f9f9f" type="button">
                                    <span class="help-block text-danger"><?php echo $new_password_err; ?></span>
                                    </button>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control form-control-custom" value="<?php echo $confirm_password; ?>" placeholder="confirm password"  autocomplete="off" required="">
                                    <span class="help-block text-danger"><?php echo $confirm_password_err; ?></span>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <input type="submit" name="submit" class="btn w-100" style="background-color: #f4792c; color: white;" value="Submit">
                                 </div>
                              </div>
                        </div>
                     </form>

                  </div>
               </div>
               <div class="col-lg-5">
                  <div class="contact_image">
                     <img src="assets/images/fores.jpg" alt="Rightuni" class="image-fit">
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Section End -->

      <?php 
      include 'footer.php';
      ?>

      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <script>
         // Show SweetAlert if password reset is successful
         <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            Swal.fire({
                  icon: 'success',
                  title: 'Success!',
                  text: 'Your password has been reset successfully.',
                  confirmButtonText: 'OK'
            }).then((result) => {
                  if (result.isConfirmed) {
                     window.location.href = 'login.php';
                  }
            });
         <?php endif; ?>
      </script>
   </body>
</html>
     
