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

        <form action="connection.php" method="post">
        <form action="LoginPage.php" method="post">
          <h4 class="text-center mb-5 mt-3">Register your Account</h4>
          
          <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label" for="form2Example1">Fullname</label>
            <input type="text" id="form2Example1" name="fullname" class="form-control" />
          </div>

          <div class="form-row mb-4">
            <div class="col">
              <label class="form-label" for="form2Example2">Date of Birth</label>
              <input type="date" id="form2Example2" name="dob" class="form-control" />
            </div>
            
            <div class="col">
              <label class="form-label" for="form2Example3">Age</label>
              <input type="number" id="form2Example3" name="age" class="form-control" />
            </div>
          </div>

          <div class="form-row"> 
            <div class="col">
              <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="form2Example4">Gender</label>
                <select class="form-control" id="form2Example4" name="gender">
                  <option value="">Select Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>
            
            <div class="col">
              <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="form2Example5">Nationality</label>
                <input type="text" id="form2Example5" name="nationality" class="form-control" />
              </div>
            </div>
          </div>

          <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label" for="form2Example6">Email</label>
            <input type="email" name="email" required id="email" class="form-control" />
          </div>

          <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label" for="form2Example7">Password</label>
            <input type="password" name="password" required id="password" class="form-control" />
          </div>

          <div class="form-row mb-4">
            <div class="col">
              <label class="form-label" for="form2Example8">Cellphone Number</label>
              <input type="tel" id="form2Example8" name="cellphone" class="form-control" />
            </div>
            
            <div class="col">
              <label class="form-label" for="form2Example9">Viber Account</label>
              <input type="text" id="form2Example9" name="viber_account" class="form-control" />
            </div>
          </div>

          <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label" for="form2Example10">Address</label>
            <input type="text" id="form2Example10" name="address" class="form-control" />
          </div>

          <!-- Add an ID to the button -->
          <button id="nextButton" type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-success btn-block mb-4">Next</button>
          
          
          <div class="text-center">
            <p class="mb-0">Already Have an Account? <a href="LoginPage.php" class="text-success">Login</a></p>
          </div>

<!-- register_process.php -->

<?php
// Include database connection code here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = $_POST["fullname"];
    $dob = $_POST["dob"];
    // Other form fields...

    // Hash the password for security
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO users (fullname, dob, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fullname, $dob, $_POST["username"], $password);
    $stmt->execute();

    // Close statement and database connection
    $stmt->close();
    $conn->close();

    // Redirect to login page after successful registration
    header("Location: LoginPage.php");
    exit();
}
?>


        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>
