<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "Vishal12@";
$dbname = "university";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if($conn == false){
    die("ERROR: Could not connect. " . $conn->connect_error);
}
?>
