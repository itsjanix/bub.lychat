<?php
$host = 'sql211.infinityfree.com';
$username = 'if0_36666529';
$password = 'VLlfgQEOYT';
$dbname = 'if0_36666529_chatboxdb';

// Create a MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>
