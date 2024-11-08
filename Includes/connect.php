<?php
$servername = 'localhost';
$user = 'root';
$pass = '';
$db = 'portfolio';

// Establish the database connection
$conn = new mysqli($servername, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>