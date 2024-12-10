<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your DB password
$dbname = "hr_data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Predefined admin email
$email = 'hrworkforce04@gmail.com';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM admin_credentials WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Generate a secure reset token
        $token = bin2hex(random_bytes(32));

        // Remove any existing reset tokens for this email
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Insert the new reset token into the database
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();

        // Create the password reset link
        $host = $_SERVER['HTTP_HOST'];
        $resetLink = "http://$host/signin&signout/reset_passwordHR.php?token=" . urlencode($token);

        // Send the reset link via email
        $subject = "Password Reset Request";
        $message = "Hi,\n\nWe received a request to reset your password. Click the link below to reset it:\n\n$resetLink\n\nIf you did not request this, please ignore this email.";
        $headers = "From: No Reply <no-reply@$host>\r\n";
        $headers .= "Reply-To: no-reply@$host\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($email, $subject, $message, $headers)) {
            echo "<p class='success'>A password reset link has been sent to the admin email.</p>";
        } else {
            echo "<p class='error'>Failed to send the reset link. Please check your server email configuration.</p>";
        }
    } else {
        echo "<p class='error'>No admin email found.</p>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            color: #333333;
            margin-bottom: 20px;
        }
        p {
            color: #555555;
            margin-bottom: 20px;
        }
        .success {
            color: #28a745;
            font-size: 14px;
        }
        .error {
            color: #dc3545;
            font-size: 14px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin: 10px 0;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-button {
            background-color: #6c757d;
            margin-top: 10px;
        }
        .back-button:hover {
            background-color: #5a6268;
        }
        
        /* Positioning the back button on the left side */
        .back-button-container {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>
<body>
    <div class="back-button-container">
        <a href="javascript:history.back()">
            <button class="back-button">Back</button>
        </a>
    </div>

    <div class="container">
        <h2>Forgot Password</h2>
        <form method="POST" action="">
            <p>A reset link will be sent to the admin email: <strong>hrworkforce04@gmail.com</strong></p>
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</body>
</html>
