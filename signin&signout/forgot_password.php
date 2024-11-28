<?php
require 'config.php'; // Your database configuration

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        $row = $result->fetch_assoc();
        $userId = $row['id'];

        // Save the token in the database with an expiration time
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour
        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $token, $expiry);
        $stmt->execute();

        // Create the password reset link
        $resetLink = "http://yourwebsite.com/reset_password.php?token=" . $token;  // Ensure the reset link is correct

        // Send email using PHP mail() function
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n\n$resetLink";
        
        // Set the headers to specify the no-reply address
        $headers = "From: No Reply <no-reply@yourwebsite.com>\r\n";
        $headers .= "Reply-To: no-reply@yourwebsite.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('A password reset link has been sent to your email.');</script>";
        } else {
            echo "<script>alert('Failed to send email. Please check your server configuration.');</script>";
        }
    } else {
        echo "<script>alert('No account found with that email address.');</script>";
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
    </style>
</head>
<body>
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
</body>
</html>
