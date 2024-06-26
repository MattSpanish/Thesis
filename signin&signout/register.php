<?php
require 'config.php'; // Adjust the path as necessary

// Check if connection to the database is successful
if ($conn === false) {
    die("Error: Could not connect. " . mysqli_connect_error());
}

// Check if the form is submitted
if (isset($_POST["submit"])) {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST["confirmpassword"];

    // Check for duplicate username or email
    $duplicate = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' OR email= '$email'");
    if (mysqli_num_rows($duplicate) > 0) {
        echo "<script> alert ('Email or username already taken'); </script>";
    } else {
        // Check if passwords match
        if ($password == $confirmpassword) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Insert new user into the database with hashed password
            $query = "INSERT INTO users (fullname, username, email, password) VALUES ('$fullname', '$username', '$email', '$hashed_password')";
            if (mysqli_query($conn, $query)) {
                echo "<script> alert('Registration successful') </script>";
                // Redirect to login page
                header("Location: LoginPage.php");
                exit; // Make sure to exit after redirection
            } else {
                echo "<script> alert('Error: Registration failed') </script>";
            }
        } else {
            echo "<script> alert('Passwords do not match') </script>";
        }
    }
}
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
      
      <div class="col-lg-4 offset-lg-1"> <!-- Adjust the column size as per your requirement -->
        <a href="LandingPage.php">
          <img src="assets1/img/logo.png" alt="Logo" class="img-fluid">
        </a>
        <p class="h2 font-weight-bold">UNLEASH EFFICIENCY EMPOWER YOUR WORKFORCE</p>
        <p class="p- font-weight-normal align-bottom mt-4">
          A Predictive Modeling for Optimal Workforce 
          Allocation and Performance Rate Enhancement Website
        </p>
      </div>

      <div class="col-lg-4 offset-lg-2"> <!-- Adjust the column size as per your requirement -->

                <form class="" action="" method="post" autocomplete="off">
                    <h4 class="text-center mb-5 mt-3">Register your Account</h4>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label" for="fullname">FullName</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" />
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" />
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" required value="" class="form-control" />
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" required value="" class="form-control" />
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <label class="form-label" for="confirmpassword">Confirm Password</label>
                        <input type="password" id="confirmpassword" name="confirmpassword" required value="" class="form-control" />
                    </div>
                    <!-- Terms and Conditions -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="" id="termsCheckbox" required>
                        <label class="form-check-label" for="termsCheckbox">
                            I agree to the <a href="terms&Condition.php" target="_blank">Terms and Conditions</a>
                        </label>
                    </div>
                    <!-- Submit button -->
                    <button type="submit" name="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-success btn-block mb-4">Sign up</button>
                    <div class="text-center">
                        <p class="mb-0">Already Have an Account? <a href="LoginPage.php" class="text-success">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>