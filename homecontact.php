<?php
// Define a variable to hold the success message
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $father = isset($_POST['father']) ? $_POST['father'] : ''; // Corrected variable name
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $course = isset($_POST['Course']) ? $_POST['Course'] : ''; // Capitalized "Course"
    $message = isset($_POST['msg']) ? $_POST['msg'] : '';
    
    // Validation
    if (empty($name) || empty($father) || empty($email) || empty($phone) || empty($course)) {
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
        $sql = "INSERT INTO user (name, father, email, phone, course, message) VALUES ('$name', '$father', '$email', '$phone', '$course', '$message')";

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
    }
}
?>
<section class="section-padding pt-0">
    <div class="container">
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($success_message)) : ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <div class="row g-0">
            <div class="col-lg-8">
                <div class="contact_form">
                    <form method="post" action="" id="contact-form" novalidate="novalidate" onsubmit="return validateForm()">
                        <div class="section-header text-start">
                            <h3 class="title">Get In <span>Touch</span></h3>
                            <p class="text">Get in touch with us for inquiries, support, or feedback. We’re here to assist you with any questions. </p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="name" id="name" class="form-control form-control-custom" placeholder="Full Name" autocomplete="off" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="father" id="father" class="form-control form-control-custom" placeholder="Father's Name" autocomplete="off" required=""> <!-- Corrected variable name -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" id="email" class="form-control form-control-custom" placeholder="Email I'd" autocomplete="off" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="phone" id="phone" class="form-control form-control-custom" placeholder="Phone No." autocomplete="off" required="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="Course" id="course" class="form-control form-control-custom" placeholder="Course" autocomplete="off" required="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea rows="5" name="msg" id="msg" class="form-control form-control-custom" placeholder="Message" autocomplete="off" required=""></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="thm-btn w-100">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact_image">
                    <img src="assets/images/contact_image.jpg" alt="img" class="image-fit">
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function validateForm() {
        var name = document.getElementById("name").value;
        var father = document.getElementById("father").value;
        var email = document.getElementById("email").value;
        var phone = document.getElementById("phone").value;
        var course = document.getElementById("course").value;
        var msg = document.getElementById("msg").value;
        if (name == "" || father == "" || email == "" || phone == "" || course == "" || msg == "") {
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
