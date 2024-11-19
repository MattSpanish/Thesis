<?php
// Include the database connection
require '../signin&signout/config.php';

// Check if the schedule ID is passed via GET
if (isset($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];

    // Fetch the current schedule details
    $sql = "SELECT * FROM schedule WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    $schedule = $stmt->get_result()->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get updated values from the form
        $strand = $_POST['strand'];
        $time = $_POST['time'];
        $days = $_POST['days'];

        // Update the schedule in the database
        $update_sql = "UPDATE schedule SET strand = ?, time = ?, days = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssi", $strand, $time, $days, $schedule_id);
        $update_stmt->execute();

        // Redirect back to the dashboard or schedule page
        header("Location: your_dashboard.php");
        exit;
    }
} else {
    echo "Schedule ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Schedule</title>
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
        margin: 20px auto;
        max-width: 1500px;
        margin-bottom: 40px;
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
        margin-left: 100px;
    }

    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #375534;
    }

    .badge-custom {
        margin-top: 5px;
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
        position: fixed;
        top: 20px;
        left: 20px;
        font-size: 18px;
        padding: 10px 15px;
        background-color: #375534;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .back-arrow i {
        margin-right: 5px;
    }

    .back-arrow:hover {
        background-color: #0F2A1D;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <!-- Instructor Info Section -->
  <div class="info-section">
    <div class="info-left">
      <a href="prof_profile.php">
        <button class="back-arrow"><i class="fa-solid fa-arrow-left"></i></button>
      </a>
      <img src="../<?php echo $_SESSION['profile_pic']; ?>" alt="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>" class="profile-pic rounded-circle">
      <h3><?php echo $user_name; ?></h3>
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
          <h3 id="classes-count">0</h3>
          <p>CLASSES</p>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <!-- Edit Schedule Form -->
  <h2>Edit Schedule</h2>
  <form method="POST">
    <div class="form-group">
      <label for="strand">Strand/Section:</label>
      <input type="text" name="strand" id="strand" value="<?php echo htmlspecialchars($schedule['strand'], ENT_QUOTES, 'UTF-8'); ?>" required class="form-control">
    </div>
    <div class="form-group">
      <label for="time">Time:</label>
      <input type="text" name="time" id="time" value="<?php echo htmlspecialchars($schedule['time'], ENT_QUOTES, 'UTF-8'); ?>" required class="form-control">
    </div>
    <div class="form-group">
      <label for="days">Days:</label>
      <input type="text" name="days" id="days" value="<?php echo htmlspecialchars($schedule['days'], ENT_QUOTES, 'UTF-8'); ?>" required class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
