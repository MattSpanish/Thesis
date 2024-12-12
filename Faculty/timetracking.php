<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: ../signin&signout/LoginPage.php");
    exit;
}

// Safely fetch the logged-in user's full name from the session
$loggedInUserName = isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Unknown User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Faculty Time Tracking</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 60px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 25px 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }
        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
        }
        input[type="text"], input[type="time"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .status {
            font-size: 1rem;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
        .status.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            text-decoration: none;
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

<a href="../Faculty/profDASHBOARD.php" class="back-arrow">
    <i class="fa-solid fa-arrow-left"></i>
</a>

<div class="container">
    <h2>Faculty Time Tracking</h2>

    <!-- Display the logged-in user's name -->
    <label for="teacher-name">Teacher Name:</label>
    <input type="text" id="teacher-name" name="teacher-name" value="<?php echo $loggedInUserName; ?>" readonly>

    <label for="time-in">Time In:</label>
    <input type="time" id="time-in">

    <label for="time-out">Time Out:</label>
    <input type="time" id="time-out">

    <button onclick="trackTime()">Submit</button>

    <div class="status" id="status"></div>
</div>

<script>
    function trackTime() {
        const teacherName = document.getElementById('teacher-name').value;
        const timeIn = document.getElementById('time-in').value;
        const timeOut = document.getElementById('time-out').value;
        const statusDiv = document.getElementById('status');

        if (!teacherName) {
            statusDiv.textContent = "Please select a teacher.";
            statusDiv.className = 'status error';
            statusDiv.style.display = 'block';
            return;
        }

        if (!timeIn || !timeOut) {
            statusDiv.textContent = "Please enter both Time In and Time Out.";
            statusDiv.className = 'status error';
            statusDiv.style.display = 'block';
            return;
        }

        const inTime = new Date(`1970-01-01T${timeIn}:00`);
        const outTime = new Date(`1970-01-01T${timeOut}:00`);

        if (outTime <= inTime) {
            statusDiv.textContent = "Time Out must be later than Time In.";
            statusDiv.className = 'status error';
            statusDiv.style.display = 'block';
            return;
        }

        const workingHours = (outTime - inTime) / (1000 * 60 * 60);

        statusDiv.textContent = `Teacher: ${teacherName}, Hours Worked: ${workingHours.toFixed(2)} hrs.`;
        statusDiv.className = 'status success';
        statusDiv.style.display = 'block';
    }
</script>
</body>
</html>
