<?php
// Example PHP data fetching (in a real scenario, this would come from a database)
$professors = 10; // Replace with dynamic value from the database
$departments = 3; // Replace with dynamic value from the database
$evaluations = 20; // Replace with dynamic value from the database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #E9F0E5; /* Light greenish tone for body background */
            display: flex;
            min-height: 100vh; /* Ensures full height */
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #0F2A1D; /* Muted pastel green for sidebar */
            padding: 20px;
            position: fixed;
            overflow-y: auto; /* Scroll if content overflows */
        }

        .sidebar img {
            max-width: 100%; /* Makes the image responsive */
            height: auto; /* Maintains aspect ratio */
            margin-bottom: 20px; /* Spacing after the image */
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #E3EED4; /* Dark olive green for text */
            font-size: 18px;
            padding: 10px;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #506C5A; /* Soft muted green for hover */
            color: #E9F0E5; /* Light text for hover */
        }

        .content {
            margin-left: 270px; /* Space for left sidebar */
            margin-right: 270px; /* Space for right sidebar */
            padding: 20px;
            background-color: #FFFFFF; /* White for the main content area */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Shadow for depth */
            flex-grow: 1; /* Allows content to take remaining space */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            border-radius: 50%;
            width: 50px; /* Set a fixed size for consistency */
            height: 50px; /* Same as width for perfect circle */
        }

        .dashboard-cards {
            display: flex;
            justify-content: space-between; /* Space out cards */
            margin-bottom: 20px;
        }

        .dashboard-cards div {
            background-color: #FFFFFF; /* White for card background */
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 1; /* Equal size for cards */
            margin: 0 10px; /* Spacing between cards */
        }

        .dashboard-cards .card {
            border-left: 5px solid transparent;
            position: relative; /* Enable positioning for the overlay link */
            cursor: pointer; /* Pointer cursor for indication */
        }

        .dashboard-cards .card.professors {
            border-left-color: #6E8F6D; /* Medium sage green for professors */
        }

        .dashboard-cards .card.departments {
            border-left-color: #4A6847; /* Darker green for departments */
        }

        .dashboard-cards .card.evaluations {
            border-left-color: #506C5A; /* Muted green for evaluations */
        }

        .dashboard-cards h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .dashboard-cards p {
            font-size: 24px;
            color: #3B4F3D; /* Dark olive green for card text */
        }

        .performance-chart, .calendar, .pie-chart {
            background-color: #FFFFFF; /* White for charts and calendar */
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .calendar-pie {
            display: flex;
            justify-content: space-between; /* Space out pie chart and calendar */
            margin-bottom: 20px;
        }

        .pie-chart, .calendar {
            flex: 1;
            margin: 0 10px; /* Spacing between pie chart and calendar */
        }

        .right-sidebar {
            width: 250px;
            height: 100vh;
            background-color: #A8C3A5; /* Muted pastel green for right sidebar */
            position: fixed;
            right: 0; /* Position to the right */
            top: 0;
            padding: 20px;
            overflow-y: auto; /* Scroll if content overflows */
        }
    </style>
</head>
<body>

    <!-- Left Sidebar -->
    <div class="sidebar">
        <img src="signin&signout/assets1/img/logo.png" alt="MindVenture Logo">
        <ul>
            <li><a href="#" class="active">Dashboard</a></li>
            <li><a href="task.php">Task</a></li>
            <li><a href="Employees.php">Employees</a></li>
            <li><a href="UploadingFiles.php">Files</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="content">

    <!-- Header -->
<div class="header">
    <h1>Dashboard</h1>
    <div style="display: flex; align-items: center; gap: 10px;">
        <img src="https://via.placeholder.com/50" alt="Profile Picture">
        <span>ADMIN</span>
        <a href="../signin&signout/LandingPage.php" 
           style="text-decoration: none; padding: 8px 15px; background-color: #ff4d4d; color: white; border-radius: 5px; font-size: 14px;">
            Logout
        </a>
    </div>
</div>


        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
    <div class="card professors">
        <a href="professors.php"></a> <!-- Link covering the card -->
        <h3>Available Professors</h3>
        <p><?php echo $professors; ?></p>
    </div>
    <div class="card departments">
        <a href="DepartmentRecords.php"></a> <!-- Link covering the card -->
        <h3>Departments</h3>
        <p><?php echo $departments; ?></p>
    </div>
    <div class="card evaluations">
        <a href="#"></a> <!-- Link covering the card -->
        <h3>Files</h3>
        <p><?php echo $evaluations; ?></p>
    </div>
</div>


        <!-- Performance Chart -->
        <div class="performance-chart">
            <h3>Performance Ratings</h3>
            <p>Data from August 2018 to May 2024</p>
            <div>
                <img src="https://via.placeholder.com/600x200" alt="Performance Chart">
            </div>
        </div>

        <!-- Pie Chart and Calendar -->
        <div class="calendar-pie">
            <div class="pie-chart">
                <h3>Data Details</h3>
                <p>Full time / Part time</p>
                <img src="https://via.placeholder.com/150" alt="Pie Chart">
            </div>
            <div class="calendar">
                <h3>March 2024</h3>
                <img src="https://via.placeholder.com/250" alt="Calendar">
            </div>
        </div>

    </div>

  

    <!-- JavaScript to handle active sidebar link -->
    <script>
        const sidebarLinks = document.querySelectorAll('.sidebar ul li a');

        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Remove active class from all links
                sidebarLinks.forEach(item => item.classList.remove('active'));
                // Add active class to the clicked link
                this.classList.add('active');
            });
        });
    </script>

</body>
</html>

