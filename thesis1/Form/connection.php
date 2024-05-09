<?php
// Connect to your database (Replace placeholders with actual values)
$servername = "localhost";
$username = "root";
$password = "";
$database = "register";
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
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

    // Prepare and execute SQL statement to insert data into the database
    $stmt = $conn->prepare("INSERT INTO users (fullname, dob, age, gender, nationality, address, email, password, cellphone, viber_account) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssssii", $fullname, $dob, $age, $gender, $nationality, $address, $email, $password, $cellphone, $viber_account);
    $stmt->execute();

    // Close statement
    $stmt->close();

    // Redirect to a success page or do something else
    header("Location: LoginPage.php");
    exit();
}

// Close database connection
$conn->close();
?>
