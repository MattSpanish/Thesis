<?php
$host = 'localhost';
$dbname = 'register';
$username = '';  // Add your database username
$password = '';  // Add your database password

$conn = mysqli_connect($host, $username, $password, $dbname);

if ($conn === false) {
    die("Error: Could not connect. " . mysqli_connect_error());
}
