<?php
session_start();
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your DB password
$dbname = "hr_data"; // Replace with your database name

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
$rememberedPassword = '';

// Check if the password is stored in a cookie
if (isset($_COOKIE['remember_password'])) {
    $rememberedPassword = $_COOKIE['remember_password'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    // Validate credentials
    $stmt = $conn->prepare("SELECT * FROM admin_credentials WHERE username = ? AND password = SHA2(?, 256)");
    $stmt->bind_param("ss", $inputUsername, $inputPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['admin'] = $inputUsername; // Start a session for ADMIN

        // Handle the Remember Me functionality
        if ($rememberMe) {
            setcookie('remember_password', $inputPassword, time() + (86400 * 30), "/"); // Store the password for 30 days
        } else {
            setcookie('remember_password', '', time() - 3600, "/"); // Clear the cookie
        }

        header("Location: /hr_dashboard.php"); // Redirect to the dashboard
        exit();
    } else {
        $error = "Invalid username or password.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thesis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="./LoginPage.css"/>

</head>
<body>

<!-- Back Button -->
<a href="../signin&signout/LandingPage.php" class="back-button">
    <i class="fas fa-arrow-left"></i>
</a>


<div class="container-fluid">
    <div class="row" style="margin-top: 50px;">
        <div class="col-lg-4 offset-lg-1">
            <p class="h2 font-weight-bold">UNLEASH EFFICIENCY EMPOWER YOUR WORKFORCE</p>
            <p class="p- font-weight-normal align-bottom mt-4">
                A Predictive Modeling for Optimal Workforce 
                Allocation and Performance Rate Enhancement Website
            </p>
        </div>

        <div class="col-lg-4 offset-lg-2">
            <form method="POST" action="">
                <!-- Username input -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" name="username" id="username" value="ADMIN" class="form-control" readonly />
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" required id="password" class="form-control" 
                           value="<?php echo htmlspecialchars($rememberedPassword); ?>" />
                </div>

                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <!-- Remember me and forgot password -->
                <div class="row mb-4">
                    <div class="col d-flex justify-content-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember_me" value="1" id="remember_me" 
                                   <?php echo isset($_COOKIE['remember_password']) ? 'checked' : ''; ?> />
                            <label class="form-check-label" for="remember_me"> Remember me </label>
                        </div>
                    </div>
                    <div class="col">
                        <a href="./forgot_passwordHR.php" class="text-success">Forgot password?</a>
                    </div>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-success btn-block mb-4">Sign in</button>
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
