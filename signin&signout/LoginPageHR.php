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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Validate credentials
    $stmt = $conn->prepare("SELECT * FROM admin_credentials WHERE username = ? AND password = SHA2(?, 256)");
    $stmt->bind_param("ss", $inputUsername, $inputPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['admin'] = $inputUsername; // Start a session for ADMIN
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
    <link rel="stylesheet" href="./LoginPage.css"/>
</head>
<body>

<div class="container-fluid">
    <div class="row" style="margin-top: 50px;">
      
      <div class="col-lg-4 offset-lg-1">
        <a href="LandingPage.php">
          <img src="assets1/img/logo.png" alt="Logo" class="img-fluid">
        </a>
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
            <input type="password" name="password" required id="password" class="form-control" />
          </div>

          <!-- Error Message -->
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>

          <!-- Remember me and forgot password -->
          <div class="row mb-4">
            <div class="col d-flex justify-content-center">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="remember_me" checked />
                <label class="form-check-label" for="remember_me"> Remember me </label>
              </div>
            </div>
            <div class="col">
              <a href="#!" class="text-success">Forgot password?</a>
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
</body>
</html>
