<?php
session_start();
require 'config.php'; // Adjust the path as necessary

// Initialize variables to hold remembered username and password
$rememberedUsername = isset($_COOKIE['remember_username']) ? $_COOKIE['remember_username'] : '';
$rememberedPassword = isset($_COOKIE['remember_password']) ? $_COOKIE['remember_password'] : '';

if (isset($_POST["submit"])) {
    $usernameemail = $_POST["usernameemail"];
    $password = $_POST["password"];
    $rememberMe = isset($_POST['remember_me']); // Check if "Remember Me" is checked

    // Validate input
    if (!empty($usernameemail) && !empty($password)) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $usernameemail, $usernameemail);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $row["password"])) {
                // Set session variables
                $_SESSION["login"] = true;
                $_SESSION["id"] = $row["id"];

                // Handle the "Remember Me" functionality
                if ($rememberMe) {
                    setcookie('remember_username', $usernameemail, time() + (86400 * 30), "/"); // 30 days
                    setcookie('remember_password', $password, time() + (86400 * 30), "/"); // 30 days
                } else {
                    setcookie('remember_username', '', time() - 3600, "/"); // Clear username cookie
                    setcookie('remember_password', '', time() - 3600, "/"); // Clear password cookie
                }

                // Redirect to dashboard
                header("Location: ../Faculty/profDASHBOARD.php");
                exit;
            } else {
                echo "<script>alert('Incorrect password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('No account found with the provided username/email.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please enter both username/email and password.');</script>";
    }
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
            </a>
            <p class="h2 font-weight-bold">UNLEASH EFFICIENCY EMPOWER YOUR WORKFORCE</p>
            <p class="p- font-weight-normal align-bottom mt-4">
                A Predictive Modeling for Optimal Workforce 
                Allocation and Performance Rate Enhancement Website
            </p>
        </div>

        <!-- Back Arrow -->
        <a href="../signin&signout/LandingPage.php">
        <button class="back-arrow"><i class="fa-solid fa-arrow-left"></i></button>
      </a>

        <div class="col-lg-4 offset-lg-2">
            <form action="LoginPage.php" method="post" autocomplete="off">
                <!-- Username input -->
                <div class="form-outline mb-4">
                    <label class="usernameemail" for="form2Example1">Username or Email</label>
                    <input type="text" name="usernameemail" required value="<?php echo htmlspecialchars($rememberedUsername); ?>" id="usernameemail" class="form-control" />
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="form2Example2">Password</label>
                    <input type="password" name="password" required value="<?php echo htmlspecialchars($rememberedPassword); ?>" id="password" class="form-control" />
                </div>

                <!-- Remember Me -->
                <div class="row mb-4">
                    <div class="col d-flex justify-content-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember_me" id="form2Example31"
                                <?php echo isset($_COOKIE['remember_username']) ? 'checked' : ''; ?> />
                            <label class="form-check-label" for="form2Example31"> Remember me </label>
                        </div>
                    </div>
                    <div class="col">
                        <a href="#!" class="text-success">Forgot password?</a>
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

</body>
</html>

