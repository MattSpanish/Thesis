<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])) {
    $userOtp = trim($_POST['otp']);

    if ($userOtp == $_SESSION['otp']) {
        // OTP is correct
        $_SESSION['verified'] = true;
        header("Location: ../Faculty/profDASHBOARD.php"); // Redirect to dashboard
        exit;
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="mt-5">OTP Verification</h2>
    <form method="POST" action="otp_verification.php">
        <div class="form-group">
            <label for="otp">Enter OTP</label>
            <input type="text" name="otp" id="otp" class="form-control" required>
        </div>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <button type="submit" class="btn btn-success">Verify OTP</button>
    </form>
</div>
</body>
</html>
