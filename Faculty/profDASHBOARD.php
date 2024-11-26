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

// Check if the fields are returned as expected
$user_image = isset($user['profile_pic']) && !empty($user['profile_pic']) 
    ? '../Faculty/uploads/' . htmlspecialchars($user['profile_pic'], ENT_QUOTES, 'UTF-8') 
    : 'default-profile.jpg';

$user_name = isset($user['username']) ? htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') : 'Unknown User';

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
        display: flex;
    }
    /* Sidebar Styles */
    .sidebar {
        width: 280px;
        background: linear-gradient(180deg, #0F2A1D, #0F2A1D); /* Primary to Secondary */
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

        border: 1px solid #AEC3B0; /* Highlight */
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    .header .user-info {
        display: flex;
        align-items: center;
    }
    .header img {
        width: 75px;
        height: 70px;
        border-radius: 50%;
        border: 3px solid #375534; /* Secondary Color */
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
        background: #E3EED4; /* Light Accent */
        border: 1px solid #AEC3B0; /* Highlight */
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
        color: #375534; /* Secondary Color */
        margin-bottom: 10px;
    }
    .stat-card p {
        font-size: 24px;
        font-weight: bold;
        color: #0F2A1D; /* Primary Color */
    }

    /* Notifications Section */
    .notifications-section ul {
        list-style: none;
        padding: 0;
    }
    .notifications-section li {
        padding: 15px;
        background: #AEC3B0; /* Highlight Color */
        border: 1px solid #6B9071; /* Neutral */
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .notifications-section li .badge {
        background: #0F2A1D; /* Primary Color */
        color: white;
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: bold;
    }

    .logout-btn {
    margin-left: 20px;
    padding: 10px 20px;
    background-color: #375534; /* Secondary Color */
    color: white;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease-in-out;
}

.logout-btn:hover {
    background-color: #0F2A1D; /* Darker Shade */
}

</style>


</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h1>My Dashboard</h1>
        <a href="#" class="active"><i></i>Dashboard</a>
        <a href="prof_profile.php"><i></i>Profile</a>
        <a href="faculty_task.php"><i></i>Tasks</a>
    </div>

    <!-- Content Area -->
    <div class="content">
    <div class="header">
    <h2>Welcome, <?= $user_name ?></h2>
    <div class="user-info">
        <img src="<?= $user_image ?>" alt="Profile Picture">
        <span><?= $user_name ?></span>
        <a href="../Faculty/logout.php" class="logout-btn">Logout</a>
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
