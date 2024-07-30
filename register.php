<?php

// Include database configuration
include 'header.php';
include 'assets/db_confing.php';

// Initialize variables for messages
$error_message = $success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and trim form inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']); // Updated field name

    // Handle file upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = 'admin/images/' . $image; // Updated path

    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($address) || empty($phone)) {
        $error_message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long!";
    } elseif (!is_numeric($phone)) {
        $error_message = "Phone number must be numeric!";
    } elseif (!move_uploaded_file($image_tmp, $image_folder)) {
        $error_message = "Failed to upload image!";
    } else {
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error_message = "This email is already registered!";
            } else {
                // Insert new record
                $password_hashed = password_hash($password, PASSWORD_DEFAULT); // Hash password
                $sql = "INSERT INTO users (name, email, password, address, phone, image, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, '1', NOW(), NOW())";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssssss", $username, $email, $password_hashed, $address, $phone, $image);

                    if ($stmt->execute()) {
                        $_SESSION['success_message'] = "Registered successfully!";
                        header("Location: register.php"); // Reload the page to show success message
                        exit();
                    } else {
                        $error_message = "Error executing query: " . $stmt->error;
                    }
                } else {
                    $error_message = "Error preparing query: " . $conn->error;
                }
            }
            $stmt->close();
        } else {
            $error_message = "Error preparing query: " . $conn->error;
        }
    }
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register / SignUp</title>
    <!-- Add your CSS here -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Subheader Start -->
<div class="section-bg section-padding subheader" style="background-image: url(assets/images/subheader.jpg);">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="page-title">Register / SignUp</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Register / SignUp</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Subheader End -->

<!-- Section Start -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="sign_in_up_box thm-bg-color-light">
                    <h3>SignUp</h3>
                    <?php if (!empty($_SESSION['success_message'])): ?>
                        <script>
                            Swal.fire({
                                title: 'Success!',
                                text: '<?php echo $_SESSION['success_message']; ?>',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'login'; // Redirect to the login page
                                }
                            });
                        </script>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control form-control-custom" placeholder="Username" autocomplete="off" value="<?php echo htmlspecialchars($username ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control form-control-custom" placeholder="Email" autocomplete="off" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" class="form-control form-control-custom" placeholder="Phone Number" autocomplete="off" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control form-control-custom" placeholder="Password" autocomplete="off">
                                    <button id="password_eye" class="fal fa-eye" type="button">
                                        <span></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Profile Picture</label>
                                    <input type="file" name="image" class="form-control form-control-custom">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" class="form-control form-control-custom" placeholder="Address" autocomplete="off" value="<?php echo htmlspecialchars($address ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <div class="custom-control form-check-radio me-sm-2 d-flex">
                                        <input type="radio" class="form-check-input" id="radioValidation">
                                        <label class="form-check-label ms-2" for="radioValidation">Remember me</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="thm-btn w-100">SignUp</button>
                                <p class="mt-3 mb-0 text-center fw-500">Already have an account? <a href="login" class="thm-color-one">Login</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Section End -->
<?php include 'footer.php'; ?>

</body>
</html>

<script>
document.getElementById('password_eye').addEventListener('click', function() {
    var passwordInput = document.querySelector('input[name="password"]');
    var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
});
</script>
