<?php
session_start();
require 'config.php'; // Adjust the path as necessary

// Encryption key (use a secure, private key for your application)
define('ENCRYPTION_KEY', 'your-secure-key-here');

// Function to encrypt data
function encryptData($data, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

// Function to decrypt data
function decryptData($data, $key) {
    $decoded = base64_decode($data);
    $ivLength = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($decoded, 0, $ivLength);
    $encrypted = substr($decoded, $ivLength);
    return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
}

// Initialize variables for remembered credentials
$rememberedEmail = isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email']) : '';
$rememberedPassword = isset($_COOKIE['remember_password']) ? decryptData($_COOKIE['remember_password'], ENCRYPTION_KEY) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $rememberMe = isset($_POST['remember_me']);

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verify password
                if (password_verify($password, $row["password"])) {
                    // Set session variables
                    $_SESSION["login"] = true;
                    $_SESSION["id"] = $row["id"];

                    // Handle "Remember Me" functionality
                    if ($rememberMe) {
                        setcookie('remember_email', $email, time() + (86400 * 30), "/", "", true, true);
                        setcookie('remember_password', encryptData($password, ENCRYPTION_KEY), time() + (86400 * 30), "/", "", true, true);
                    } else {
                        setcookie('remember_email', '', time() - 3600, "/", "", true, true);
                        setcookie('remember_password', '', time() - 3600, "/", "", true, true);
                    }

                    // Generate OTP
                    $otp = rand(100000, 999999);
                    $_SESSION['otp'] = $otp; // Store OTP in session

                    // Send OTP to user's email
                    $subject = "Your OTP for Login";
                    $message = "Your OTP for login is: $otp";
                    $headers = "From: no-reply@yourwebsite.com";

                    if (mail($email, $subject, $message, $headers)) {
                        header("Location: otp_verification.php"); // Redirect to OTP verification page
                        exit;
                    } else {
                        $error = "Failed to send OTP. Please try again.";
                    }
                } else {
                    $error = "Invalid email or password.";
                }
            } else {
                $error = "No account found with the provided email.";
            }
        } else {
            $error = "Database error. Please try again later.";
        }

        $stmt->close();
    } else {
        $error = "Please fill in both email and password.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Faculty</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="./LoginPage.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row" style="margin-top: 50px;">
        <div class="col-lg-4 offset-lg-1">
            <a href="LandingPage.php">
            </a>
            <p class="h2 font-weight-bold">UNLEASH EFFICIENCY EMPOWER YOUR WORKFORCE</p>
            <p class="p- font-weight-normal align-bottom mt-4">
                A Predictive Modeling for Optimal Workforce 
                Allocation and Performance Rate Enhancement Website
            </p>
        </div>

<!-- Back Button -->
<a href="../signin&signout/LandingPage.php" class="back-button">
    <i class="fas fa-arrow-left"></i>
</a>

        <div class="col-lg-4 offset-lg-2">
        <form action="LoginPage.php" method="post" autocomplete="off">
    <!-- Email input -->
    <div class="form-outline mb-4">
        <label class="form-label" for="email">Email</label>
        <input type="email" name="email" required value="<?php echo htmlspecialchars($rememberedEmail); ?>" id="email" class="form-control" />
    </div>

    <!-- Password input -->
    <div class="form-outline mb-4">
        <label class="form-label" for="password">Password</label>
        <input type="password" name="password" required value="<?php echo htmlspecialchars($rememberedPassword); ?>" id="password" class="form-control" />
    </div>

    <!-- Error Message -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Remember Me -->
    <div class="row mb-4">
        <div class="col d-flex justify-content-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me" <?php echo isset($_COOKIE['remember_email']) ? 'checked' : ''; ?> />
                <label class="form-check-label" for="remember_me"> Remember me </label>
            </div>
        </div>
        <div class="col">
            <a href="forgot_password.php" class="text-success">Forgot password?</a>
        </div>
    </div>

    <!-- Submit button -->
    <button type="submit" name="submit" class="btn btn-success btn-block mb-4">Sign in</button>

    <!-- Register -->
    <div class="text-center">
        <p class="mb-0">Don't have an account? <a href="register.php" class="text-success">Register</a></p>
    </div>
</form>

        </div>
    </div>
</div>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <style>
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

</body>
</html>

