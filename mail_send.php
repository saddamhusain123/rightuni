<?php

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
          $conn = new mysqli('localhost', 'root', '', 'rightuni');

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


<?php
include 'header.php';
include 'assets/db_confing.php';

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

