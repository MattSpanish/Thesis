<?php
session_start();
require 'config.php'; // Adjust the path as necessary

if(isset($_POST["submit"])) {
    $usernameemail = $_POST["usernameemail"];
    $password = $_POST["password"];
    
    // Check if username/email and password are provided
    if(!empty($usernameemail) && !empty($password)) {
        // Perform SQL injection prevention (if not using prepared statements)
        $usernameemail = mysqli_real_escape_string($conn, $usernameemail);
        $password = mysqli_real_escape_string($conn, $password);
        
        // Perform the query
        $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$usernameemail' OR email = '$usernameemail'");
        
        if($result) {
            // Check if any rows are returned
            if(mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                
                // Check if the password matches
                if(password_verify($password, $row["password"])) {
                    // Password is correct, set session variables
                    $_SESSION["login"] = true;
                    $_SESSION["id"] = $row["id"];
                    
                    // Redirect to landing page
                    header("Location: LandingPage.php");
                    exit;
                } else {
                    // Incorrect password
                    echo "<script>alert('Wrong password');</script>";
                }
            } else {
                // No user found with the provided username/email
                echo "<script>alert('User not found');</script>";
            }
        } else {
            // Error in SQL query
            echo "<script>alert('Error: ". mysqli_error($conn) ."');</script>";
        }
    } else {
        // Username/email or password not provided
        echo "<script>alert('Please provide username/email and password');</script>";
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
    <div class="row" style="margin-top: 200px;">
      
      <div class="col-lg-4 offset-lg-1"> <!-- Adjust the column size as per your requirement -->
        <p class="h1 ">LOGO HERE</p>
        <p class="h2 font-weight-bold ">Lets Mange Your Time Right</p>
        <p class="p- font-weight-normal align-bottom mt-4">
          A Predictive Modeling for Optimal Workforce 
          Allocation and Performance Rate Enhancement Website
        </p>
      </div>

      <div class="col-lg-4 offset-lg-2"> <!-- Adjust the column size as per your requirement -->


      <form class="" action="LoginPage.php" method="post" autocomplete="off">
 <!-- Ensure the form action is set to your PHP file handling form submission -->
          <!-- Email input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <label class="usernameemail" for="form2Example1">Username or Email </label>
            <input type="text" name="usernameemail" required value="" id="usernameemail" class="form-control" />
          </div>

          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label" for="form2Example2">Password</label>
            <input type="password" name="password" required value="" id="password" class="form-control" />
          </div>

          <!-- 2 column grid layout for inline styling -->
          <div class="row mb-4">
            <div class="col d-flex justify-content-center">

              <!-- Checkbox -->
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
                <label class="form-check-label" for="form2Example31"> Remember me </label>
              </div>
            </div>
            <div class="col">
              <!-- Simple link -->
              <a href="#!" class="text-success">Forgot password?</a>
            </div>
          </div>
          <!-- Submit button -->
          <button type="submit" name="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-success btn-block mb-4">Sign in</button>

          <!-- Register buttons -->
          <div class="text-center">
        <p class="mb-0">Don't have an account? <a href="register.php" class="text-success">Register</a></p>
    </div>

          </div>
        </form>
      </div>
    </div>
  </div>
  

</script>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</body>
</html>
