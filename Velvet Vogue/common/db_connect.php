<?php
// Session configuration - Only set if session not active
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 0);
}

// Database Configuration
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'velvet_vogue';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>