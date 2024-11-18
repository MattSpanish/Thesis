<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
    header("Location: ../signin&signout/LoginPage.php");
    exit;
}

$user_id = $_SESSION["id"];
require '../signin&signout/config.php'; // Database connection

// Retrieve profile picture from the database
$stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profile_pic);
$stmt->fetch();
$stmt->close();

// Set the profile picture path
$_SESSION['profile_pic'] = $profile_pic ? $profile_pic : 'default.jpg'; // Use a default image if none is set

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instructor Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .info-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 0;
    }
    .info-left {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .profile-pic {
      width: 150px;
      height: 150px;
      object-fit: cover;
      margin-bottom: 10px;
    }
    .badge-custom {
      background-color: #28a745;
      color: white;
      margin-top: 5px;
    }
    .info-details {
      text-align: left;
      margin-left: 15px;
    }
    .info-right {
      text-align: right;
    }
    .stats-right {
      display: flex;
      justify-content: space-between;
      width: 180px;
      text-align: center;
    }
    .stats-card {
      flex: 1;
    }
    .stats-card h3 {
      margin: 0;
      font-size: 36px;
    }
    .stats-card p {
      margin: 0;
      font-size: 16px;
    }
    .schedule-section {
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 5px;
      padding: 20px;
      margin-top: 20px;
    }
    .schedule-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #f1f1f1;
      padding: 10px 15px;
      border-bottom: 1px solid #dee2e6;
      border-radius: 5px 5px 0 0;
    }
    .schedule-header h4 {
      margin: 0;
    }
    table thead th {
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
    }
    table tbody tr {
      border-bottom: 1px solid #dee2e6;
    }
    /* Hide the form by default */
    #add-class-form {
      display: none;
      margin-top: 20px;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <!-- Instructor Info Section -->
  <div class="info-section">
  <div class="info-left">
  <img src="../<?php echo $_SESSION['profile_pic']; ?>" alt="John Mathew Espanol" class="profile-pic rounded-circle">
  <h3>John Mathew Espanol</h3>
  <span class="badge badge-custom">FACULTY</span>
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

    <!-- Add Class Form -->
    <form id="add-class-form">
      <div class="mb-3">
        <label for="strand-input" class="form-label">Strand/Sections</label>
        <input type="text" class="form-control" id="strand-input" placeholder="Enter Strand/Sections">
      </div>
      <div class="mb-3">
        <label for="time-input" class="form-label">Time</label>
        <input type="text" class="form-control" id="time-input" placeholder="Enter time (e.g., 10:00 AM - 11:00 AM)">
      </div>
      <div class="mb-3">
        <label for="days-input" class="form-label">Days</label>
        <input type="text" class="form-control" id="days-input" placeholder="Enter days (e.g., Mon, Wed, Fri)">
      </div>
      <button type="submit" class="btn btn-primary">Submit Class</button>
      <button type="button" class="btn btn-danger" id="exit-btn">Exit</button> <!-- Exit Button -->
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const addClassBtn = document.getElementById('add-class-btn');
  const addClassForm = document.getElementById('add-class-form');
  const scheduleTableBody = document.getElementById('schedule-table-body');
  const exitBtn = document.getElementById('exit-btn'); // Exit button
  const classesCountElement = document.getElementById('classes-count'); // Classes count element

  // Variable to track the number of classes
  let numberOfClasses = parseInt(classesCountElement.textContent);

  // Show the form when the "Add Classes" button is clicked
  addClassBtn.addEventListener('click', () => {
    addClassForm.style.display = 'block'; // Show the form
  });

  // Handle form submission
  addClassForm.addEventListener('submit', (event) => {
    event.preventDefault(); // Prevent default form submission behavior

    // Get form values
    const strand = document.getElementById('strand-input').value;
    const time = document.getElementById('time-input').value;
    const days = document.getElementById('days-input').value;

    // Validate inputs (basic validation)
    if (!strand || !time || !days) {
      alert('Please fill out all fields.');
      return;
    }

    // Create a new table row
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
      <td>${strand}</td>
      <td>${time}</td>
      <td>${days}</td>
    `;

    // Append the new row to the schedule table body
    scheduleTableBody.appendChild(newRow);

    // Increment the number of classes
    numberOfClasses += 1;
    // Update the classes count in the header
    classesCountElement.textContent = numberOfClasses;

    // Reset the form fields
    addClassForm.reset();

    // Hide the form after submission
    addClassForm.style.display = 'none';
  });

  // Hide the form when the "Exit" button is clicked
  exitBtn.addEventListener('click', () => {
    addClassForm.style.display = 'none'; // Hide the form
    addClassForm.reset(); // Optionally reset the form fields
  });
</script>
 
</body>
</html>