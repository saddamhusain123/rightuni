<?php

// Include database configuration
include 'header.php';
include 'assets/db_confing.php'; // Adjust the path to your configuration file

// Include custom session handler class
include 'DbSessionHandler.php';

// If the user is already logged in, redirect them to the index page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: index.php");
    exit;
}

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, name, email, password FROM users WHERE email = ? OR name = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_email, $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if email exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $name, $email, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session and save user info
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $name;
                            $_SESSION["email"] = $email;

                            // Write session to the database
                            session_write_close();
                            
                            // Redirect user to welcome page or dashboard
                            header("location: index.php");
                            exit();
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email/username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>
<!-- Your login form HTML goes here -->
<div class="section-bg section-padding subheader" style="background-image: url(assets/images/subheader.jpg);">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="page-title">Login / Sign-In</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Login / Sign-In</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Section Start -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <img src="assets/images/forgot-password.png">
            </div>
            <div class="col-lg-6">
                <div class="sign_in_up_box thm-bg-color-light">
                    <h3>Log In</h3>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Email/username</label>
                                    <input type="text" name="email" class="form-control form-control-custom" placeholder="Email or Username" autocomplete="off" required="">
                                    <span class="help-block text-danger"><?php echo $email_err; ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Password <a href="forgot_password.php" class="thm-color-one">Forgot Password?</a></label>
                                    <input type="password" name="password" class="form-control form-control-custom" id="password_value" placeholder="password" autocomplete="off" required="">
                                    
                                    <button id="password_eye" class="fal fa-eye" style="position: absolute; right: -30px; bottom: 0px; z-index: 10; color: var(--thm-body-color); " type="button">
                                    </button>
                                    <span class="help-block text-danger"><?php echo $password_err; ?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="thm-btn w-100">Login</button>
                                <p class="mt-3 mb-0 text-center fw-500">Don't have an account? <a href="register" class="thm-color-one">Register</a></p>
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
