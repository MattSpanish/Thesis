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

// Prepare SQL statement for fetching user data
$stmt = $conn->prepare("SELECT username, email, gender, department, subject, status, profile_pic FROM users WHERE id = ?");
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
$conn->close();

// Store the profile image path in the session
$_SESSION['profile_pic'] = isset($user['profile_pic']) && !empty($user['profile_pic']) 
    ? 'Faculty/uploads/' . htmlspecialchars($user['profile_pic'], ENT_QUOTES, 'UTF-8') 
    : 'default-profile.jpg';

$user_name = isset($user['username']) ? htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') : 'Unknown User';
$email = isset($user['email']) ? htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') : 'Not Available';
$gender = isset($user['gender']) ? htmlspecialchars($user['gender'], ENT_QUOTES, 'UTF-8') : 'Not Specified';
$department = isset($user['department']) ? htmlspecialchars($user['department'], ENT_QUOTES, 'UTF-8') : 'Unknown Department';
$subject = isset($user['subject']) ? htmlspecialchars($user['subject'], ENT_QUOTES, 'UTF-8') : 'Not Assigned';
$status = isset($user['status']) ? htmlspecialchars($user['status'], ENT_QUOTES, 'UTF-8') : 'Not Specified';

// Include database connection
$servername = "localhost"; // Change to your server name
$username = "root";        // Change to your database username
$password = "";            // Change to your database password
$dbname = "enrollment_db"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch schedule records for the specific user from the database
$sql = "SELECT subject, sections, time, day, total_students 
        FROM FacultySchedule 
        WHERE id = ?";

// Prepare and bind the statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing the SQL statement: " . $conn->error);
}

$stmt->bind_param("i", $user_id); // Bind the logged-in user's ID
$stmt->execute();

// Get the result
$schedule_result = $stmt->get_result();
if (!$schedule_result) {
    die("SQL error: " . $stmt->error);
}

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
    margin: 20px auto;      /* Adjust the top and bottom margins to control spacing */
    max-width: 1500px;
    margin-bottom: 40px;   /* Add bottom margin to create space below the container */
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
    .action-button {
        background-color: #28a745; /* Green color */
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
      }

      .action-button:hover {
        background-color: #218838; /* Darker green on hover */
      }

      .action-button:active {
        background-color: #1e7e34; /* Even darker green when active */
      }
        .info-details {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap; /* Ensures responsiveness */
    padding: 20px;
  }

  .info-card {
    background-color: #F8F9FA; /* Light background */
    border: 1px solid #AEC3B0; /* Accent border */
    border-radius: 8px;
    padding: 20px;
    width: 100%;
    max-width: 400px; /* Control the card size */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
  }

  .info-card h5 {
    margin-bottom: 15px;
    color: #0F2A1D;
    font-size: 18px;
    font-weight: bold;
  }

  .info-card p {
    font-size: 16px;
    color: #0F2A1D;
    margin-bottom: 10px;
  }

  .info-card a {
    color: #375534; /* Accent color */
    text-decoration: none;
    font-weight: bold;
  }

  .info-card a:hover {
    text-decoration: underline;
  }

  .form-control {
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #AEC3B0;
    padding: 5px;
    color: #0F2A1D;
  }

  .btn-success {
    width: 100%; /* Full-width button for better responsiveness */
    margin-top: 10px;
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
  <div class="info-card">
    <h5><strong>Contact Information</strong></h5>
    <p><strong>Email:</strong> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p>
  </div>

  <div class="info-card">
    <h5><strong>Profile Details</strong></h5>
    <form action="update_user_info.php" method="POST">
      <!-- Editable Gender -->
      <p><strong>Gender:</strong> 
        <select name="gender" class="form-control" required>
          <option value="MALE" <?php if ($gender == 'MALE') echo 'selected'; ?>>MALE</option>
          <option value="FEMALE" <?php if ($gender == 'FEMALE') echo 'selected'; ?>>FEMALE</option>
        </select>
      </p>

      <!-- Editable Subject -->
      <p><strong>Subject:</strong> 
        <select name="subject" class="form-control" required>
          <option value="STEM" <?php if ($subject == 'STEM') echo 'selected'; ?>>STEM</option>
          <option value="HUMMS" <?php if ($subject == 'HUMMS') echo 'selected'; ?>>HUMMS</option>
          <option value="ABM" <?php if ($subject == 'ABM') echo 'selected'; ?>>ABM</option>
        </select>
      </p>

      <!-- Editable Status -->
      <p><strong>Status:</strong> 
        <select name="status" class="form-control" required>
          <option value="ACTIVE" <?php if ($status == 'ACTIVE') echo 'selected'; ?>>ACTIVE</option>
          <option value="INACTIVE" <?php if ($status == 'INACTIVE') echo 'selected'; ?>>INACTIVE</option>
        </select>
      </p>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-success mt-3">Update Information</button>
    </form>
  </div>
</div>

    <div class="info-right">
      <div class="stats-right">
        <button class="action-button" onclick="handleCombinedAction()">
          📩 Message / 🏥 Sick Leave
        </button>
      </div>
    </div>
  </div>

  <hr>

<!-- Schedule Section -->
<div class="schedule-section">
  <div class="schedule-header">
    <h4>Schedule</h4>
  </div>
  <table class="table mt-3" id="schedule-table">
    <thead>
      <tr>
        <th>Subject</th>
        <th>Sections</th>
        <th>Time</th>
        <th>Day</th>
        <th>Total Students</th>
      </tr>
    </thead>
    <tbody id="schedule-table-body">
  <?php if ($schedule_result->num_rows > 0): ?>
    <?php while ($schedule = $schedule_result->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($schedule['subject'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($schedule['sections'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($schedule['time'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($schedule['day'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars($schedule['total_students'], ENT_QUOTES, 'UTF-8'); ?></td>
      </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr>
      <td colspan="5">No schedule available for your account.</td>
    </tr>
  <?php endif; ?>
</tbody>

  </table>
</div>

</div>

<script>
function handleCombinedAction() {
  // Prompt the user with a choice
  let userChoice = confirm('Do you want to send a message or request sick leave? Click OK for Message or Cancel to do nothing.');

  if (userChoice) {
    alert('Opening messaging interface...');
    // Redirect to the messaging interface
    window.location.href = "messaging_interface.php";
  } else {
    // User clicked Cancel; do nothing
    alert('Action canceled.');
  }
}
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
