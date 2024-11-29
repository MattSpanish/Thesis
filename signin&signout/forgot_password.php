<?php
require 'config.php'; // Database configuration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format.');</script>";
            exit;
        }

        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            // Generate a unique token
            $token = bin2hex(random_bytes(32));
            $row = $result->fetch_assoc();
            $userId = $row['id'];

            // Save the token with an expiration time
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // 1-hour expiration
            $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token=?, expiry=?");
            $stmt->bind_param("issss", $userId, $token, $expiry, $token, $expiry);
            $stmt->execute();

            // Create the reset link dynamically
            $host = $_SERVER['HTTP_HOST'];
            $resetLink = "http://localhost:3000/signin&signout/reset_password.php?token=" . urldecode($token);

            // Send the email
            $subject = "Password Reset Request";
            $message = "Hi,\n\nWe received a request to reset your password. Click the link below to reset it:\n\n$resetLink\n\nIf you did not request this, please ignore this email.";
            $headers = "From: No Reply <no-reply@$host>\r\n";
            $headers .= "Reply-To: no-reply@$host\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            if (mail($email, $subject, $message, $headers)) {
                echo "<script>alert('A password reset link has been sent to your email.');</script>";
            } else {
                echo "<script>alert('Failed to send the email. Please check your server configuration.');</script>";
            }
        } else {
            echo "<script>alert('No account found with that email address.');</script>";
        }
    } else {
        echo "<script>alert('Email is required.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group label {
            color: #555;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background-color: #375534;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: 0.3s;
        }
        .back-button:hover {
            background-color: #2a442e;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }
        .back-button i {
            font-size: 18px;
        }
    </style>
</head>
<body>
<a href="LoginPage.php" class="back-button">
    <i class="fas fa-arrow-left"></i>
</a>

<div class="container mt-5">
    <h2>Forgot Password</h2>
    <form action="forgot_password.php" method="post">
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" name="email" id="email" class="form-control" required placeholder="Enter your email">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Request Password Reset</button>
    </form>
    <div class="mt-3 text-center">
        <p>Remember your password? <a href="LoginPage.php">Login here</a></p>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>
