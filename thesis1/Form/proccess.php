<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $nationality = $_POST['nationality'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['passowrd'];
    $cellphone = $_POST['cellphone'];
    $viber_account = $_POST['viber_account'];

   
    
    

    $sql = "INSERT INTO students VALUES ('$fullname', '$dob', '$age', '$gender', '$nationality','$address','$email','$password','$cellphone','$viber_account'())";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data inserted successfully!');</script>";
        header("Location: LoginPage.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

 