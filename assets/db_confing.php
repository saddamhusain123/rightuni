<?php

// Database credentials
$servername = "localhost";
$usernamee = "root";
$password = "";
$dbname = "righttutorsatation_web_app_db";

// Create connection
$conn = new mysqli($servername, $usernamee, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
