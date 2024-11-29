<?php
// Include your database connection
require 'config.php';

// Initialize variables to handle errors, success, and reset link
$error = $success = $resetLink = "";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the token is provided in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists and is valid in the database
    $stmt = $conn->prepare("SELECT user_id, expiry FROM password_resets WHERE token = ?");
    if ($stmt) {
        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row['user_id'];
                $expiry = $row['expiry'];

                // Check if the token has expired
                if (strtotime($expiry) < time()) {
                    $error = "The token has expired. Please request a new password reset.";
                } else {
                    // Token is valid, proceed with password reset
                }
            } else {
                $error = "Invalid token.";
            }
        } else {
            $error = "Database query error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Database error: Unable to prepare statement.";
    }
} else {
    $error = "No token provided.";
}

// Process form submission for password reset
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($error)) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        // Hash the new password
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the users table
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_hashed_password, $user_id);

        if ($stmt->execute()) {
            // Check if rows were affected (meaning the password was updated)
            if ($stmt->affected_rows > 0) {
                $success = "Password updated successfully!";

                // Delete the token from the password_resets table
                $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
                $stmt->bind_param("s", $token);

                if ($stmt->execute()) {
                    // Token deleted successfully, nothing more to do
                } else {
                    $error = "Error deleting token: " . $stmt->error;
                }
            } else {
                $error = "Password update failed: No rows affected. Ensure the user exists.";
            }
        } else {
            $error = "Error updating password: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
    <!-- Add Font Awesome for arrow icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<style>
/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(180deg, #E3EED4, #AEC3B0); /* Gradient similar to the dashboard */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    padding: 20px;
}

/* Back Button */
.back-arrow {
    position: fixed;
    top: 20px;
    left: 20px;
    text-decoration: none;
    background-color: #375534; /* Match dashboard color */
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 16px;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s, transform 0.2s;
}

.back-arrow i {
    margin-right: 8px; /* Add spacing between icon and text */
}

.back-arrow:hover {
    background-color: #0F2A1D; /* Darker hover color */
    transform: scale(1.05); /* Slight enlarge effect on hover */
}

/* Form Container */
form {
    background: white;
    border: 1px solid #AEC3B0;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    max-width: 400px;
    width: 100%;
}

form h2 {
    margin-bottom: 20px;
    color: #375534;
    text-align: center;
}

/* Form Group */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 10px;
    color: #375534;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #AEC3B0;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease-in-out;
}

.form-group input:focus {
    outline: none;
    border-color: #375534;
}

/* Button */
button[type="submit"] {
    background: #375534;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    width: 100%;
    transition: background 0.3s ease-in-out;
}

button[type="submit"]:hover {
    background: #0F2A1D;
}

/* Message */
p {
    margin-top: 20px;
    text-align: center;
    font-size: 14px;
    color: #0F2A1D;
    font-weight: bold;
}
</style>

<body>
    <!-- Back Button -->
    <a href="LoginPage.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i> 
    </a>

    <div class="container">
    <form action="" method="POST">
    <h2>Reset Password</h2>
    <div class="form-group">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
    </div>
    <button type="submit">Reset Password</button>

    <?php if ($error): ?>
        <p class="error" style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="success" style="color: green;"><?= $success ?></p>
    <?php endif; ?>

</form>
    </div>
</body>
</html>
