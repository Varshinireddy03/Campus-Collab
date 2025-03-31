<?php
$host = "localhost";
$user = "root";  // Default user in XAMPP
$pass = "";      // No password by default in XAMPP
$dbname = "campus_collab";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
