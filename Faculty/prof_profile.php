<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
    header("Location: ../login.php");
    exit;
}

// Fetch user information from the database
$user_id = $_SESSION["id"];
require '../signin&signout/config.php'; // Database connection

// Prepare SQL statement
$stmt = $conn->prepare("SELECT username, email, profile_pic FROM users WHERE id = ?");
if ($stmt === false) {
    // Error occurred while preparing the statement
    die("Error preparing the SQL statement: " . $conn->error);
}

// Bind the parameter
$stmt->bind_param("i", $user_id);

// Execute the query
$stmt->execute();

// Check if the query execution was successful
$result = $stmt->get_result();
if ($result === false) {
    die("Error executing the SQL query: " . $stmt->error);
}

// Fetch the user data
$user = $result->fetch_assoc();

// Close the statement and connection
$stmt->close();
$conn->close();

// Store the profile image path in the session
$_SESSION['profile_pic'] = isset($user['profile_pic']) && !empty($user['profile_pic']) 
    ? 'Faculty/uploads/' . htmlspecialchars($user['profile_pic'], ENT_QUOTES, 'UTF-8') 
    : 'default-profile.jpg';

$user_name = isset($user['username']) ? htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') : 'Unknown User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instructor Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Include Font Awesome for the back arrow icon -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #E3EED4; /* Light Accent */
    }

    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        background-color: #FFFFFF;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
        max-width: 1500px;
    }

    .info-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 20px 0;
        border-bottom: 2px solid #AEC3B0; /* Highlight */
    }

    .info-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        position: relative;
    }

    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #375534; /* Secondary Color */
    }

    .badge-custom {
        background-color: #6B9071; /* Neutral */
        color: white;
        padding: 5px 10px;
        border-radius: 50px;
    }

    .info-details p {
        font-size: 16px;
        margin: 5px 0;
        color: #0F2A1D; /* Primary */
    }

    .info-right {
        text-align: right;
    }

    .stats-right {
        display: flex;
        gap: 20px;
    }

    .stats-card {
        text-align: center;
        background-color: #AEC3B0; /* Highlight */
        color: #0F2A1D; /* Primary */
        border-radius: 8px;
        padding: 20px;
        width: 100px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .stats-card h3 {
        font-size: 36px;
        margin-bottom: 5px;
    }

    .stats-card p {
        font-size: 14px;
        margin: 0;
    }

    .schedule-section {
        background-color: #F8F9FA;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
        border: 2px solid #AEC3B0; /* Highlight */
    }

    .schedule-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #AEC3B0; /* Highlight */
        padding: 10px;
        border-radius: 8px 8px 0 0;
        color: white;
    }

    .schedule-header h4 {
        margin: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table thead th {
        background-color: #E3EED4; /* Light Accent */
        color: #0F2A1D; /* Primary */
        text-align: left;
        padding: 10px;
    }

    table tbody td {
        padding: 10px;
        border-bottom: 1px solid #AEC3B0; /* Highlight */
    }

    #add-class-form {
        background-color: #E3EED4; /* Light Accent */
        padding: 15px;
        border: 1px solid #AEC3B0; /* Highlight */
        border-radius: 8px;
        margin-top: 20px;
        display: none;
    }

    #add-class-form h5 {
        margin-bottom: 15px;
        color: #0F2A1D; /* Primary */
    }

    .btn {
        font-weight: bold;
    }

    .btn-primary {
        background-color: #375534; /* Secondary */
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0F2A1D; /* Primary */
    }

    .btn-danger {
        background-color: #D9534F;
        color: white;
    }

    .btn-success {
        background-color: #6B9071; /* Neutral */
        color: white;
    }

    /* Back Arrow Button */
    .back-arrow {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 18px;
        padding: 10px;
        background-color: #375534; /* Secondary */
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .back-arrow:hover {
        background-color: #0F2A1D; /* Primary */
    }
</style>

</head>
<body>

<div class="container mt-5">
  <!-- Instructor Info Section -->
  <div class="info-section">
    <div class="info-left">
      <!-- Back Arrow Icon -->
      <a href="../Faculty/profDASHBOARD.php">
        <button class="back-arrow">
        <i class="fa-solid fa-arrow-left"></i>
        </button>
      </a>
      <!-- Display the profile picture dynamically using PHP -->
      <img src="../<?php echo $_SESSION['profile_pic']; ?>" alt="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>" class="profile-pic rounded-circle">
      <h3><?php echo $user_name; ?></h3>
      <span class="badge badge-custom">FACULTY</span>
      
      <!-- Change Profile Button -->
      <button class="btn btn-primary mt-2" id="change-profile-btn">Change Profile Picture</button>
      
      <!-- Upload Form (Initially Hidden) -->
      <form id="profile-upload-form" action="update_profile_pic.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_pic" accept="image/*" required>
        <button type="submit" class="btn btn-success mt-2">Upload</button>
        <button type="button" class="btn btn-danger mt-2" id="cancel-btn">Cancel</button>
      </form>
    </div>

    <div class="info-details">
      <p><strong>Email :</strong> <a href="mailto:jrespanol485@gmail.com">jrespanol485@gmail.com</a></p>
      <p><strong>Gender :</strong> Male</p>
      <p><strong>Department :</strong> Senior High School</p>
      <p><strong>Subject :</strong> Physical Education</p>
      <p><strong>Status :</strong> Full Time</p>
    </div>
    <div class="info-right">
      <div class="stats-right">
        <div class="stats-card">
          <h3>64</h3>
          <p>STUDENTS</p>
        </div>
        <div class="stats-card">
          <h3 id="classes-count">0</h3> <!-- Updated classes count -->
          <p>CLASSES</p>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <!-- Schedule Section -->
  <div class="schedule-section">
    <div class="schedule-header">
      <h4>Schedule</h4>
      <button class="btn btn-success" id="add-class-btn">Add Classes</button>
    </div>
    <table class="table mt-3" id="schedule-table">
      <thead>
        <tr>
          <th>Strand/Sections</th>
          <th>Time</th>
          <th>Days</th>
        </tr>
      </thead>
      <tbody id="schedule-table-body">
        <!-- Dynamically populate schedule rows here -->
      </tbody>
    </table>

    <!-- Add Class Form (Initially Hidden) -->
    <div id="add-class-form">
      <h5>Add a New Class</h5>
      <form action="add_class.php" method="POST">
        <div class="form-group">
          <label for="strand">Strand/Section:</label>
          <input type="text" class="form-control" id="strand" name="strand" required>
        </div>
        <div class="form-group">
          <label for="time">Time:</label>
          <input type="text" class="form-control" id="time" name="time" required>
        </div>
        <div class="form-group">
          <label for="days">Days:</label>
          <input type="text" class="form-control" id="days" name="days" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Add Class</button>
        <button type="button" class="btn btn-danger mt-2" id="cancel-class-btn">Cancel</button>
      </form>
    </div>
  </div>
</div>

<script>
  // Show the profile upload form when the "Change Profile Picture" button is clicked
  document.getElementById('change-profile-btn').addEventListener('click', function() {
    document.getElementById('profile-upload-form').style.display = 'block';
    document.getElementById('change-profile-btn').style.display = 'none';
  });

  // Hide the profile upload form when the "Cancel" button is clicked
  document.getElementById('cancel-btn').addEventListener('click', function() {
    document.getElementById('profile-upload-form').style.display = 'none';
    document.getElementById('change-profile-btn').style.display = 'block';
  });

  // Show the "Add Class" form when the "Add Classes" button is clicked
  document.getElementById('add-class-btn').addEventListener('click', function() {
    document.getElementById('add-class-form').style.display = 'block';
    document.getElementById('add-class-btn').style.display = 'none';
  });

  // Hide the "Add Class" form when the "Cancel" button is clicked
  document.getElementById('cancel-class-btn').addEventListener('click', function() {
    document.getElementById('add-class-form').style.display = 'none';
    document.getElementById('add-class-btn').style.display = 'block';
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
