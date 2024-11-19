<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
    header("Location: ../signin&signout/LoginPage.php");
    exit;
}

// Fetch user information from the database
$user_id = $_SESSION["id"];
require '../signin&signout/config.php'; // Database connection

// Fetch schedule data
$sql_schedule = "SELECT * FROM schedule WHERE user_id = ?";
$stmt_schedule = $conn->prepare($sql_schedule);
$stmt_schedule->bind_param("i", $user_id);
$stmt_schedule->execute();
$schedule_result = $stmt_schedule->get_result();

// Prepare SQL statement for fetching user data
$stmt = $conn->prepare("SELECT username, email, profile_pic FROM users WHERE id = ?");
if ($stmt === false) {
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
$stmt_schedule->close();
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #E3EED4; /* Light Accent */
    }

    .container {
    background-color: #FFFFFF;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin: 20px auto;          /* Adjust the top and bottom margins to control spacing */
    max-width: 1500px;
    margin-bottom: 40px;        /* Add bottom margin to create space below the container */
}


    .info-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 20px 0;
        border-bottom: 2px solid #AEC3B0;
    }

 .info-left {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    position: relative;
    margin-left: 100px; /* Adjust this value to shift the entire section to the right */
}

.profile-pic {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #375534;
    
}

.badge-custom {
    margin-top: 5px; /* Optional: Adds spacing between the image and the badge */
}


    .info-details p {
        font-size: 16px;
        margin: 5px 0;
        color: #0F2A1D;
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
        background-color: #AEC3B0;
        color: #0F2A1D;
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
        border: 2px solid #AEC3B0;
    }

    .schedule-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #AEC3B0;
        padding: 10px;
        border-radius: 8px 8px 0 0;
        color: white;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table thead th {
        background-color: #E3EED4;
        color: #0F2A1D;
        text-align: left;
        padding: 10px;
    }

    table tbody td {
        padding: 10px;
        border-bottom: 1px solid #AEC3B0;
    }

    #add-class-form {
        background-color: #E3EED4;
        padding: 15px;
        border: 1px solid #AEC3B0;
        border-radius: 8px;
        margin-top: 20px;
        display: none;
    }

    #add-class-form h5 {
        margin-bottom: 15px;
        color: #0F2A1D;
    }

    .btn {
        font-weight: bold;
    }

    .btn-primary {
        background-color: #375534;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0F2A1D;
    }

    .btn-danger {
        background-color: #D9534F;
        color: white;
    }

    .btn-success {
        background-color: #6B9071;
        color: white;
    }

    .back-arrow {
    position: fixed;  /* Use fixed positioning to place it on the left side */
    top: 20px;        /* Adjust the top value to align vertically */
    left: 20px;       /* Position the button on the left side */
    font-size: 18px;  /* Make the icon a little bigger */
    padding: 10px 15px; /* Add some padding for better appearance */
    background-color: #375534;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: adds shadow for depth */
}

.back-arrow i {
    margin-right: 5px; /* Space between icon and text */
}

.back-arrow:hover {
    background-color: #0F2A1D;  /* Hover color */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Enhance hover effect */
}



  </style>
</head>
<body>

<div class="container mt-5">
  <!-- Instructor Info Section -->
  <div class="info-section">
    <div class="info-left">

      <a href="../Faculty/profDASHBOARD.php">
        <button class="back-arrow"><i class="fa-solid fa-arrow-left"></i></button>
      </a>
      
      <img src="../<?php echo $_SESSION['profile_pic']; ?>" alt="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>" class="profile-pic rounded-circle">
      <h3><?php echo $user_name; ?></h3>
      <span class="badge badge-custom">FACULTY</span>

      <button class="btn btn-primary mt-2" id="change-profile-btn">Change Profile Picture</button>
      <form id="profile-upload-form" action="update_profile_pic.php" method="POST" enctype="multipart/form-data" style="display: none;">
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
          <h3 id="classes-count">0</h3>
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
  <?php if ($schedule_result->num_rows > 0): ?>
    <?php while ($schedule = $schedule_result->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($schedule['strand'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($schedule['time'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($schedule['days'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td>
          <a href="edit_schedule.php?schedule_id=<?php echo $schedule['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="delete_schedule.php?schedule_id=<?php echo $schedule['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr>
      <td colspan="4">No schedule available.</td>
    </tr>
  <?php endif; ?>
</tbody>

  </table>

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
  document.getElementById('change-profile-btn').addEventListener('click', function() {
    document.getElementById('profile-upload-form').style.display = 'block';
    document.getElementById('change-profile-btn').style.display = 'none';
  });

  document.getElementById('cancel-btn').addEventListener('click', function() {
    document.getElementById('profile-upload-form').style.display = 'none';
    document.getElementById('change-profile-btn').style.display = 'block';
  });

  document.getElementById('add-class-btn').addEventListener('click', function() {
    document.getElementById('add-class-form').style.display = 'block';
    document.getElementById('add-class-btn').style.display = 'none';
  });

  document.getElementById('cancel-class-btn').addEventListener('click', function() {
    document.getElementById('add-class-form').style.display = 'none';
    document.getElementById('add-class-btn').style.display = 'block';
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
