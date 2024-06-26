
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


        <form method="POST" action="login_process.php"> <!-- Ensure the form action is set to your PHP file handling form submission -->
          <!-- Email input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label" for="form2Example1">Email </label>
            <input type="email" name="email" required id="email" class="form-control" />
          </div>

          <!-- Password input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <label class="form-label" for="form2Example2">Password</label>
            <input type="password" name="password" required id="password" class="form-control" />
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
          <button type="button" data-mdb-button-init data-mdb-ripple-init onclick="validateForm()" class="btn btn-success btn-block mb-4">Sign in</button>
          <!-- Register buttons -->
          
          </div>
        </form>
      </div>
    </div>
  </div>
  

  <script>

  function validateForm() {
    var email = document.getElementById('email').value; // Corrected ID
    var password = document.getElementById('password').value; // Corrected ID

    if (email === 'ADMIN' && password === 'PASSWORD') {

      window.location.href = '../Dashboard.php';
    } else {
      alert('Invalid email or password. Please try again.');
    }
  }



</script>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</body>
</html>
