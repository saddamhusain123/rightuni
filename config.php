<?php
include 'assets/db_confing.php';
include 'DbSessionHandler.php';

// Create a new session handler instance
$sessionHandler = new DbSessionHandler($conn);

// Set the custom session handler
session_set_save_handler($sessionHandler, true);

// Start the session
session_start();
?>
