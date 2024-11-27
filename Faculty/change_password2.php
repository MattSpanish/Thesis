<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
    header("Location: ../Faculty/profDASHBOARD.php");
    exit;
}

// Include the database configuration file
require '../signin&signout/config.php';

// Initialize variables
$error = $success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION["id"];

    // Validate form fields
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match.";
    } else {
        // Fetch the current password from the database
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        // Verify the current password
        if (!password_verify($current_password, $hashed_password)) {
            $error = "Current password is incorrect.";
        } else {
            // Hash the new password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_hashed_password, $user_id);
            if ($stmt->execute()) {
                $success = "Password changed successfully.";
            } else {
                $error = "Error updating the password.";
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
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
    <a href="../Faculty/profDASHBOARD.php" class="back-arrow">
        <i class="fas fa-arrow-left"></i> 
    </a>

    <div class="container">

    <form action="" method="POST">
    <h2>Change Password</h2>
  
        <div class="form-group">
            <label for="current_password">Current Password:</label>
            <input type="password" name="current_password" id="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit">Change Password</button>
        <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>
    </form>
</div>
</body>
</html>
