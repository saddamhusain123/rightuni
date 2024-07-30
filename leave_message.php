<?php

include 'assets/db_confing.php';
// print_r($_SERVER["REQUEST_METHOD"]); exit();   
if (isset($_POST['submit'])) {
// Handle form submission

    $leave_name = $_POST['leave_name'];
    $college_id = $_POST['college_id'];
    $leave_email = $_POST['leave_email'];
    $leave_message = $_POST['leave_message'];

    // Prepare and bind
    $sql = "INSERT INTO leave_message (leave_name, college_id, leave_email, leave_message) VALUES ('$leave_name', '$college_id', '$leave_email', '$leave_message')";
  
    $result = $conn->query($sql);
  print_r($result);exit;
    // Execute the query
    if ($result) {
        echo "Email Sent Successfully";
    } else {
        http_response_code(500);
        echo "Error: something went wrong";
    }

    // Close statement and connection
    $conn->close();
} 

?>
