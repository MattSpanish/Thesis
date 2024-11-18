<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
    header("Location: ../login.php");
    exit;
}

// Fetch user information from the database
$user_id = $_SESSION["id"];
require '..//signin&signout/config.php'; // Database connection

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

// Check if the fields are returned as expected
$user_image = isset($user['profile_pic']) && !empty($user['profile_pic']) 
    ? '../Faculty/uploads/' . htmlspecialchars($user['profile_pic']) 
    : 'default-profile.jpg';

$user_name = isset($user['username']) ? $user['username'] : 'Unknown User';

// Ensure both are strings before passing to htmlspecialchars()
if (is_array($user_image)) {
    $user_image = ''; // Handle unexpected array if needed
}
if (is_array($user_name)) {
    $user_name = ''; // Handle unexpected array if needed
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            display: flex;
        }
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #4CAF50, #2e7d32);
            color: white;
            height: 100vh;
            padding: 30px 20px;
            position: fixed;
            display: flex;
            flex-direction: column;
            align-items: start;
        }
        .sidebar h1 {
            font-size: 24px;
            margin-bottom: 50px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            margin: 20px 0;
            font-size: 18px;
            font-weight: 500;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s ease-in-out;
        }
        .sidebar a i {
            margin-right: 15px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Content Styles */
        .content {
            margin-left: 280px;
            padding: 30px;
            width: calc(100% - 280px);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header .user-info {
            display: flex;
            align-items: center;
        }
        .header img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
        }
        .header .user-info span {
            margin-left: 10px;
            font-size: 18px;
            font-weight: 500;
        }

        /* Stats Section */
        .stats-section {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .stat-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 20px;
            flex: 1;
            text-align: center;
            min-width: 200px;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .stat-card h4 {
            color: #4CAF50;
            margin-bottom: 10px;
        }
        .stat-card p {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        /* Notifications Section */
        .notifications-section ul {
            list-style: none;
            padding: 0;
        }
        .notifications-section li {
            padding: 15px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notifications-section li .badge {
            background: #4CAF50;
            color: white;
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h1>My Dashboard</h1>
        <a href="#" class="active"><i>🏠</i>Dashboard</a>
        <a href="prof_profile.php"><i>👤</i>Profile</a>
        <a href="#"><i>📋</i>Tasks</a>
        <a href="#"><i>⚙️</i>Settings</a>
    </div>

    <!-- Content Area -->
    <div class="content">
        <!-- Header -->
        <div class="header">
            <h2>Welcome, <?= $user_name ?></h2>
            <div class="user-info">
                <img src="<?= $user_image ?>" alt="Profile Picture">
                <span><?= $user_name ?></span>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stat-card">
                <h4>Tasks Completed</h4>
                <p>42</p>
            </div>
            <div class="stat-card">
                <h4>New Notifications</h4>
                <p>5</p>
            </div>
            <div class="stat-card">
                <h4>Upcoming Deadlines</h4>
                <p>3</p>
            </div>
        </div>

        <!-- Notifications Section -->
        <div class="notifications-section" style="margin-top: 30px;">
            <h3>Latest Notifications</h3>
            <ul>
                <li>
                    Task Reminder: Complete the project report by tomorrow.
                    <span class="badge">Urgent</span>
                </li>
                <li>
                    Meeting Scheduled: Team meeting at 2 PM.
                    <span class="badge">Today</span>
                </li>
                <li>
                    New Message: John sent you a message.
                    <span class="badge">New</span>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>
