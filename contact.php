<?php
  include 'header.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require 'PHPMailer/Exception.php';
  require 'PHPMailer/PHPMailer.php';
  require 'PHPMailer/SMTP.php';

  // Define a variable to hold the success message
  $success_message = '';
  $error_message = '';

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Retrieve form data
      $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
      $email = isset($_POST['email']) ? $_POST['email'] : '';
      $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
      $subject = isset($_POST['subject']) ? $_POST['subject'] : ''; // Capitalized "subject"
      $message = isset($_POST['message']) ? $_POST['message'] : '';
      
      // Validation
      if (empty($fullname) || empty($email) || empty($phone) || empty($subject)) {
          $error_message = "All fields are required.";
      } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $error_message = "Invalid email format.";
      } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
          $error_message = "Invalid phone number format.";
      } else {
          // Connect to your database
          $conn = new mysqli('68.183.80.139', 'root', 'RightTutorSatation@2023Database!', 'righttutorsatation_web_app_db');

          // Check connection
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }

          // Prepare SQL statement to insert data into a table
          $sql = "INSERT INTO contact (fullname, email, phone, subject, message) VALUES ('$fullname', '$email', '$phone', '$subject', '$message')";

          // Execute the SQL statement
          if ($conn->query($sql) === TRUE) {
              // Form data saved successfully, set success message
              $success_message = "Form submitted successfully!";
          } else {
              // Error occurred, display error message
              $error_message = "Error: " . $conn->error;
          }

          // Close the database connection
          $conn->close();

          // CONTACT FORM SEND TO EMAIL
          $mail = new PHPMailer(true);

          $mail->isSMTP();
          $mail->Host       = 'smtp.gmail.com';
          $mail->SMTPAuth   = true;
          $mail->Username   = 'zaheerkhanhj@gmail.com';
          $mail->Password   = 'xyjccoiyyhsewwsg';
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
          $mail->Port       = 587;

          $mail->setFrom('zaheerkhanhj@gmail.com');

          $mail->addAddress($_POST["email"]);

          $mail->isHTML(true);

          $mail->Subject = $_POST["subject"];

          $mail->Body = "Name: " . $fullname . "<br>" .
          "Email: " . $email . "<br>" .
          "Phone: " . $phone . "<br>" .
          "Subject: " . $subject . "<br>" .
          "Message: " . nl2br($message) . "<br>";

          try {
              $mail->send();
              echo "<script>
              alert('Email sent successfully');
              document.location.href = 'contact.php';
              </script>";
          } catch (Exception $e) {
              echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }
      }
  }
?>

      <!-- Subheader Start -->
      <div class="section-bg section-padding subheader" style="background-image: url(assets/images/subheader.jpg);">
         <div class="container">
            <div class="row">
               <div class="col-12">
                  <h1 class="page-title">Contact Us</h1>
                  <nav aria-label="breadcrumb">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
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
               <p class="text">Get in touch with us for inquiries, support, or feedback. We’re here to assist you with any questions. </p>
            </div>
            <div class="row justify-content-center">
               <!-- item -->
               <div class="col-lg-4 col-md-6">
                  <div class="contact_info_box section-bg" style="background-image: url(assets/images/contact/1.jpg);">
                     <div class="icon">
                        <i class="fal fa-map-marker-alt"></i>
                     </div>
                     <p class="text">2nd floor, Dev Tower, adjoining building to Anand Motors, Opp. main Kumbha Marg Gate, Pratap Nagar, Jaipur</p>
                  </div>
               </div>
               <!-- item -->
               <div class="col-lg-4 col-md-6">
                  <div class="contact_info_box section-bg" style="background-image: url(assets/images/contact/2.jpg);">
                     <div class="icon">
                        <i class="fal fa-phone"></i>
                     </div>
                     <a href="tel:(+91)97999 46027" class="text">+91 97999 46027</a>
                     <p class="text">Mon-Sat 9:00am-5:00pm</p>
                  </div>
               </div>
               <!-- item -->
               <div class="col-lg-4 col-md-6">
                  <div class="contact_info_box section-bg" style="background-image: url(assets/images/contact/3.jpg);">
                     <div class="icon">
                        <i class="fal fa-envelope"></i>
                     </div>
                     <a href="mailto:rightuni1@gmail.com" class="text">rightuni1@gmail.com</a>
                     <p class="text">24 X 7 online support</p>
                  </div>
               </div>
               <!-- item -->
            </div>
         </div>
      </section>
      <!-- Section End -->
      <!-- Section Start -->
      <section class="section-padding pt-0">
         <div class="container">
            <div class="row g-0">
               <div class="col-lg-8">
                  <div class="contact_form">
                     <form class="form" id="form" method="post" action="" id="contact-form" novalidate="novalidate" onsubmit="return validateForm()">
                        <div class="section-header text-start">
                            <h3 class="title">Get In <span>Touch</span></h3>
                            <p class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                 <input type="text" name="fullname" id="fullname" class="form-control form-control-custom" placeholder="Full Name" autocomplete="off" required="">
                              </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" id="email" class="form-control form-control-custom" placeholder="Email I'd" autocomplete="off" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="number" name="phone" id="phone" class="form-control form-control-custom" placeholder="Phone No." autocomplete="off" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                 <input type="text" name="subject" id="subject" class="form-control form-control-custom" placeholder="Subject" autocomplete="off" required="">
                              </div>
                           </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                 <textarea style="resize:none" rows="5" name="message" id="message" class="form-control form-control-custom" placeholder="Message" autocomplete="off" required=""></textarea>
                              </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="submit" class="thm-btn w-100">Submit</button>
                            </div>
                        </div>
                    </form>

                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="contact_image">
                     <img src="assets/images/contact_image.jpg" alt="Rightuni" class="image-fit">
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
            var fullname = document.getElementById("fullname").value;
            var email = document.getElementById("email").value;
            var phone = document.getElementById("phone").value;
            var subject = document.getElementById("subject").value;
            var message = document.getElementById("message").value;

            if (fullname == "" || email == "" || phone == "" || subject == "" || message == "") {
                alert("All fields are required.");
                return false;
            }
  
            if (!validateEmail(email)) {
                alert("Invalid email format.");
                return false;
            }

            if (!validatePhone(phone)) {
                alert("Invalid phone number format.");
                return false;
            }

            return true;
        }

        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }

        function validatePhone(phone) {
            var re = /^[0-9]{10}$/;
            return re.test(phone);
        }
      </script>