<?php
require 'config.php'; // Adjust the path as necessary

// Initialize error messages as empty strings
$error_message = "";
$password_error = "";

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
        $error_message = "Email or username already taken.";
    } else {
        // Check if passwords match and if they are exactly 8 characters long
        if ($password == $confirmpassword) {
            if (strlen($password) != 8) {
                $password_error = "Password must be exactly 8 characters long.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                // Insert new user into the database with hashed password
                $query = "INSERT INTO users (fullname, username, email, password) VALUES ('$fullname', '$username', '$email', '$hashed_password')";
                if (mysqli_query($conn, $query)) {
                    $error_message = "Registration successful.";
                    
                    // Get the newly registered user's ID
                    $new_user_id = mysqli_insert_id($conn);

                    // Auto-assign a default task
                    $default_task_query = "INSERT INTO tasks (task_name, employee_id, due_date, status) VALUES 
                    ('Welcome Task', '$new_user_id', CURDATE() + INTERVAL 7 DAY, 'pending')";
        
                    if (mysqli_query($conn, $default_task_query)) {
                        $error_message = "Registration successful and default task assigned!";
                        // Redirect to login page
                        header("Location: LoginPage.php");
                        exit();
                    } else {
                        $error_message = "Registration successful but default task assignment failed.";
                    }
                } else {
                    $error_message = "Error: Registration failed.";
                }
            }
        } else {
            $error_message = "Passwords do not match.";
        }
    }
}
?>

<!-- HTML Form -->
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
            <form action="" method="post" autocomplete="off">
                <h4 class="text-center mb-5 mt-3">Register your Account</h4>
                
                <!-- Fullname -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="fullname">FullName</label>
                    <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo isset($fullname) ? $fullname : ''; ?>" />
                </div>
                
                <!-- Username -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo isset($username) ? $username : ''; ?>" />
                    <?php if ($error_message): ?>
                        <small class="text-danger"><?php echo $error_message; ?></small>
                    <?php endif; ?>
                </div>
                
                <!-- Email -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>" />
                </div>
                
                <!-- Password -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" />
                    <?php if ($password_error): ?>
                        <small class="text-danger"><?php echo $password_error; ?></small>
                    <?php endif; ?>
                </div>
                
                <!-- Confirm Password -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="confirmpassword">Confirm Password</label>
                    <input type="password" id="confirmpassword" name="confirmpassword" class="form-control" />
                </div>
                
                <!-- Terms and Conditions -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="" id="termsCheckbox" required>
                    <label class="form-check-label" for="termsCheckbox">
                        I agree to the <a href="terms&Condition.php" target="_blank">Terms and Conditions</a>
                    </label>
                </div>
                
                <!-- Submit button -->
                <button type="submit" name="submit" class="btn btn-success btn-block mb-4">Sign up</button>
                <div class="text-center">
                    <p class="mb-0">Already Have an Account? <a href="LoginPage.php" class="text-success">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>
