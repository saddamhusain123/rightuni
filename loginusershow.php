<?php
session_start();
include 'header.php';

// Check if the user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <div class="profile_info">
        <img src="assets/img/client_img.png" alt="Profile Picture">
        <div class="profile_info_iner">
            <div class="profile_author_name">
                <p><?php echo htmlspecialchars($_SESSION["profession"]); ?></p>
                <h5><?php echo htmlspecialchars($_SESSION["full_name"]); ?></h5>
            </div>
            <div class="profile_info_details">
                <a href="#">My Profile</a>
                <a href="#">Settings</a>
                <a href="logout.php">Log Out</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
include 'footer.php';
?>
