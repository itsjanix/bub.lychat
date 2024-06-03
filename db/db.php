<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'chatboxdb'; // Define the database name

// Create a MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>
