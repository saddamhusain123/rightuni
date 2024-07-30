<?php
include 'header.php';
include 'assets/db_confing.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$email = ""; // Initialize the email variable
$email_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate email
    if (empty($email_err)) {
        // Check if email exists in the database
        $sql = "SELECT register_id, username FROM register WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    // Email exists, generate a unique token
                    $token = bin2hex(random_bytes(50));
                    $stmt->bind_result($register_id, $username);
                    $stmt->fetch();

                    // Insert token into the database
                    $sql = "INSERT INTO password_resets (register_id, email, token) VALUES (?, ?, ?)";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("iss", $register_id, $email, $token);
                        if ($stmt->execute()) {
                            // Send reset link via email
                            sendPasswordResetEmail($email, $token);
                            $_SESSION['reset_message'] = "A password reset link has been sent to your email.";
                            header("location: forgot_password.php");
                            exit();
                        }
                    }
                } else {
                    $email_err = "No account found with that email.";
                }
            }
            $stmt->close();
        }
    }
    $conn->close();
}

function sendPasswordResetEmail($email, $token) {

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'zaheerkhanhj@gmail.com';
    $mail->Password   = 'xyjccoiyyhsewwsg';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('zaheerkhanhj@gmail.com');

    $mail->addAddress($email); // Use the $email parameter

    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Request';
    $mail->Body    = "Hi,<br><br>To reset your password, please click on the link below:<br><br>
                      <a href='http://rightuni.in/reset_password.php?token=$token'>Reset Password</a><br><br>Thanks,<br>Right Uni";
                    
    if(!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
}
?>
      <br><br><br><br><br>
      
      <!-- Section Start -->
      <section class="section-padding pt-0">
         <div class="container">
            <div class="row g-0">
               <div class="col-lg-7">
                  <div class="contact_form">
                     <form class="form" id="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="contact-form" novalidate="novalidate" onsubmit="return validateForm()">
                        <div class="section-header text-start">
                            <h3 class="title">Forget <span>Password</span></h3>
                            <p class="text">Enter the email address associated with your account and we'll send you a link to reset your password.</p>
                        </div><br><br>
                        <?php 
                        if (isset($_SESSION['reset_message'])) {
                            echo '<div class="alert alert-success">' . $_SESSION['reset_message'] . '</div>';
                            unset($_SESSION['reset_message']);
                        }
                        ?>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>" >
                                    <input type="email" name="email" id="email" class="form-control form-control-custom" placeholder="Email I'd" autocomplete="off" required="" value="<?php echo htmlspecialchars($email); ?>">
                                    <span class="help-block text-danger"><?php echo $email_err; ?></span>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <input type="submit" name="submit" class="btn w-100" style="background-color: #f4792c; color: white;" value="Reset Password">
                            </div>
                        </div>
                    </form>

                  </div>
               </div>
               <div class="col-lg-5">
                  <div class="contact_image">
                     <img src="assets/images/fores.jpg" alt="img" class="image-fit">
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Section End -->
      
      <?php
      include'footer.php';
      ?>

      <script>
        function validateForm() {
            var email = document.getElementById("email").value;

            if (email == "") {
                alert("Gmail are required.");
                return false;
            }
  
            if (!validateEmail(email)) {
                alert("Invalid email format.");
                return false;
            }

            return true;
        }

        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }

      </script>