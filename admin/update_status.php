<?php
require('includes/config.php'); // Add your configuration file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $table = mysqli_real_escape_string($mysqli, $_POST['courses']);
    $column = mysqli_real_escape_string($mysqli, $_POST['status']);
    $status = intval($_POST['status']);

    $query = "UPDATE $table SET $column = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param('ii', $status, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to execute query.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare query.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$mysqli->close();
?>
