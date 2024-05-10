<!-- login_process.php -->

<?php
// Include database connection code here
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "register";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proceed with login process...
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from form
    $username = $_POST["email"];
    $password = $_POST["password"];

    // Retrieve user data from the database based on username
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, verify password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Password is correct, set session or perform other actions
            session_start();
            $_SESSION["user_id"] = $row["id"]; // Example: Storing user ID in session
            // Redirect user to dashboard or other page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            echo "Invalid password";
        }
    } else {
        // User not found
        echo "User not found";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>
