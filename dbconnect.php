<?php
$host = "localhost"; // Change this if your database is hosted elsewhere
$user = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "bakeryshop";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
