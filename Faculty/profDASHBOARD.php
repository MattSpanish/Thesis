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

    /* Sidebar logo */
.sidebar .logo {
    width: 100%;
    height: 120px; /* Adjust as needed */
    background: url('../signin&signout/assets1/img/logo.png') no-repeat center center;
    background-size: contain;
    margin-bottom: 1px; /* Spacing below the logo */
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
        margin-top: 30px; /* Space from the header */
        flex-wrap: wrap;
        justify-content: space-between; /* Distribute evenly across the available space */
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
        text-decoration: none; /* Removes underline from links */
        color: inherit; /* Maintains text color */
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
        .notifications-section {
        margin-top: 40px; /* Space from the stats section */
        padding: 20px;
        background: #F5F5F5; /* Light background for clarity */
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .notifications-section h3 {
        margin-bottom: 20px;
        color: #375534; /* Secondary color */
        font-weight: bold;
    }

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
<<div class="sidebar">
    <div class="logo"></div>
    <a href="#" class="active"><i></i>Dashboard</a>
    <a href="prof_profile.php"><i></i>Profile</a>
    <a href="faculty_task.php"><i></i>Tasks</a>
</div>

        <!-- Content Area -->
        <div class="content">
        <div class="header">
        <h2>Welcome, <?= $user_name ?></h2>
        <div class="user-info" style="position: relative;">
            <img src="<?= $user_image ?>" alt="Profile Picture">
            <span><?= $user_name ?></span>
        
        <!-- Dropdown Button -->
        <div style="position: relative; cursor: pointer;">
            <button onclick="toggleDropdown()" class="logout-btn" style="background: none; border: none; padding: 0; cursor: pointer;">
                <!-- SVG Arrow -->
                <svg width="15" height="13">
                    <path d="M3.5 5.5a.5.5 0 0 1 .707-.707L8 8.793l3.793-3.998a.5.5 0 1 1 .707.707l-4 4.25a.5.5 0 0 1-.707 0l-4-4.25a.5.5 0 0 1-.707-.707z"/>
                </svg>
            </button>
        </div>

        <!-- Drop-down content -->
        <div id="dropdownMenu" 
            style="display: none; position: absolute; right: 0; top: 75px; background-color: white; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15); border-radius: 5px; overflow: hidden; z-index: 1000;">
            
            <a href="../Faculty/change_password2.php" 
                style="text-decoration: none; padding: 10px 15px; display: block; color: #333; background-color: #fff; transition: background-color 0.3s;">
                Change Password
            </a>
            
            <a href="../signin&signout/LandingPage.php" 
                style="text-decoration: none; padding: 10px 15px; display: block; color: white; background-color: #ff4d4d; transition: background-color 0.3s;">
                Logout
            </a>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="stats-section">
    <a href="/tasks" class="stat-card">
        <h4>Tasks Completed</h4>
        <p>42</p>
    </a>
    <a href="/notifications" class="stat-card">
        <h4>New Notifications</h4>
        <p>5</p>
    </a>
    <a href="timetracking.php" class="stat-card">
        <h4>Time Log in/out</h4>
        <p>3</p>
    </a>
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
    <script>
    function toggleDropdown() {
        const dropdown = document.getElementById("dropdownMenu");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // Close the dropdown if the user clicks outside of it
    document.addEventListener("click", function (event) {
        const dropdown = document.getElementById("dropdownMenu");
        if (!event.target.closest(".user-info")) {
            dropdown.style.display = "none";
        }
    });
</script>
</body>
</html>
