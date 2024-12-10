<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hr_data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];

    // Verify token
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND created_at >= NOW() - INTERVAL 1 HOUR");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Update password
        $hashedPassword = hash('sha256', $newPassword);
        $stmt = $conn->prepare("UPDATE admin_credentials SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();

        // Delete the token
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        // Redirect to login page after success
        header("Location: loginpageHR.php");
        exit; // Make sure to call exit after header to stop further script execution
    } else {
        echo "<p class='error'>Invalid or expired token.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #333333;
            margin-bottom: 20px;
        }

        label {
            color: #555555;
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-button-container {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .back-button {
            padding: 8px 16px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        .error {
            color: #dc3545;
            font-size: 14px;
        }

        .success {
            color: #28a745;
            font-size: 14px;
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
        <h2>Reset Password</h2>
        <form method="POST" action="">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <button type="submit">Reset Password</button>
        </form>
    </div>

</body>
</html>
