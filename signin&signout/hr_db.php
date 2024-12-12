<?php
$host = 'localhost';
$dbname = 'hr_data';
$username = '';  // Add your database username
$password = '';  // Add your database password

$conn = mysqli_connect($host, $username, $password, $dbname);

if ($conn === false) {
    die("Error: Could not connect. " . mysqli_connect_error());
}

?>