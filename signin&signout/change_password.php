<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== 'ADMIN') {
    header("Location: /hr_dashboard.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hr_data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword === $confirmPassword) {
        $stmt = $conn->prepare("UPDATE admin_credentials SET password = SHA2(?, 256) WHERE username = 'ADMIN'");
        $stmt->bind_param("s", $newPassword);
        if ($stmt->execute()) {
            $message = "Password updated successfully!";
        } else {
            $message = "Error updating password.";
        }
        $stmt->close();
    } else {
        $message = "Passwords do not match.";
    }
}
$conn->close();
?>


<form method="POST" action="change_password.php">
  <div class="form-group">
    <label for="new_password">New Password</label>
    <input type="password" name="new_password" id="new_password" class="form-control" required>
  </div>
  <div class="form-group">
    <label for="confirm_password">Confirm Password</label>
    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary">Update Password</button>
</form>
<?php if (!empty($message)) echo "<p>$message</p>"; ?>
