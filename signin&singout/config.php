<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "register";

$conn = mysqli_connect($servername, $username, $password, $database);

if ($conn === false) {
    die("Error: Could not connect. " . mysqli_connect_error());
}
?>
