<?php

$servername = "localhost";
$username = "";
$password = "";
$dbname = "register";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$faculty_conn = new mysqli('localhost', 'root', '', 'register'); // Example for local development with no password

// Check connection
if ($faculty_conn->connect_error) {
    die("Faculty database connection failed: " . $faculty_conn->connect_error);
}
?>
