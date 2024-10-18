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
            background-color: #f4f4f4;
            display: flex;
            min-height: 100vh; /* Ensures full height */
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #e7f0ec;
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
            color: #333;
            font-size: 18px;
            padding: 10px;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .sidebar ul li a:hover {
            background-color: #cce2d9; /* Hover background color */
            color: #000; /* Hover text color */
        }
        .sidebar ul li a.active {
            background-color: green; /* Active background color */
            color: white; /* Active text color */
        }
        .content {
            margin-left: 270px; /* Space for left sidebar */
            margin-right: 270px; /* Space for right sidebar */
            padding: 20px;
            background-color: #fff; /* Background color for the content area */
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
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 1; /* Equal size for cards */
            margin: 0 10px; /* Spacing between cards */
        }
        .dashboard-cards .card {
            border-left: 5px solid transparent;
        }
        .dashboard-cards .card.professors {
            border-left-color: #00aced; /* Blue for professors */
        }
        .dashboard-cards .card.departments {
            border-left-color: #e91e63; /* Pink for departments */
        }
        .dashboard-cards .card.evaluations {
            border-left-color: #9c27b0; /* Purple for evaluations */
        }
        .dashboard-cards h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .dashboard-cards p {
            font-size: 24px;
            color: #333;
        }
        .performance-chart, .calendar, .pie-chart {
            background-color: white;
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
            background-color: #e7f0ec;
            position: fixed;
            right: 0; /* Position to the right */
            top: 0;
            padding: 20px;
            overflow-y: auto; /* Scroll if content overflows */
        }
        .messages h3 {
            margin-bottom: 10px;
        }
        .messages .message {
            display: flex;
            align-items: center;
            padding: 10px 0;
        }
        .message img {
            width: 50px; /* Adjust size to match the profile image */
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .message span {
            font-size: 16px;
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
            <li><a href="#">Evaluation</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="content">

        <!-- Header -->
        <div class="header">
            <h1>Dashboard</h1>
            <div>
                <img src="https://via.placeholder.com/50" alt="Profile Picture">
                <span>John Doe Smith</span>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
            <div class="card professors">
                <h3>Available Professors</h3>
                <p><?php echo $professors; ?></p>
            </div>
            <div class="card departments">
                <h3>Departments</h3>
                <p><?php echo $departments; ?></p>
            </div>
            <div class="card evaluations">
                <h3>Evaluations</h3>
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

    <!-- Right Sidebar for Messages -->
    <div class="right-sidebar">
        <div class="messages">
            <h3>Messages</h3>
            <div class="message">
                <img src="https://via.placeholder.com/50" alt="Profile">
                <span>Steve S.: Hey, are you in school?</span>
            </div>
            <div class="message">
                <img src="https://via.placeholder.com/50" alt="Profile">
                <span>Harvey J.: Hey, are you in school?</span>
            </div>
            <div class="message">
                <img src="https://via.placeholder.com/50" alt="Profile">
                <span>Sarah M.: Hey, are you in school?</span>
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

