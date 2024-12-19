<?php
// Start the session at the beginning
session_start();

// Redirect guests to the login page
if (!isset($_SESSION["id"])) {
    header("Location: ../signin&signout/LoginPage.php");
    exit(); // Ensure no further code is executed
}

// Include the database connection file
require_once '../signin&signout/config.php';

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize counts
$task_count_pending = 0;
$task_count_completed = 0;
$new_message_count = 0;
$sick_leave_count = 0;  // Initialize sick leave count

$user_id = $_SESSION["id"];

// Fetch pending tasks count
$sql_pending = "SELECT COUNT(*) AS task_count FROM tasks WHERE status = 'pending' AND employee_id = ?";
$stmt_pending = $conn->prepare($sql_pending);
if (!$stmt_pending) {
    die("Prepare failed: " . $conn->error);
}
$stmt_pending->bind_param("i", $user_id);
$stmt_pending->execute();
$result_pending = $stmt_pending->get_result();
$task_count_pending = $result_pending->fetch_assoc()['task_count'] ?? 0;
$stmt_pending->close();

// Fetch completed tasks count
$sql_completed = "SELECT COUNT(*) AS task_count FROM tasks WHERE status = 'complete' AND employee_id = ?";
$stmt_completed = $conn->prepare($sql_completed);
if (!$stmt_completed) {
    die("Prepare failed: " . $conn->error);
}
$stmt_completed->bind_param("i", $user_id);
$stmt_completed->execute();
$result_completed = $stmt_completed->get_result();
$task_count_completed = $result_completed->fetch_assoc()['task_count'] ?? 0;
$stmt_completed->close();

// Fetch message count for a specific user from the hr_data database
$sql_messages = "SELECT COUNT(*) AS message_count FROM hr_data.messages WHERE user_id = ?";
$stmt_messages = $conn->prepare($sql_messages);
if (!$stmt_messages) {
    die("Prepare failed: " . $conn->error);
}
$stmt_messages->bind_param("i", $user_id);
$stmt_messages->execute();
$result_messages = $stmt_messages->get_result();
$new_message_count = $result_messages->fetch_assoc()['message_count'] ?? 0;
$stmt_messages->close();

// Fetch sick leave count for the user
$sql_sick_leaves = "SELECT COUNT(*) AS sick_leave_count FROM hr_data.sick_leaves WHERE user_id = ?";
$stmt_sick_leaves = $conn->prepare($sql_sick_leaves);
if (!$stmt_sick_leaves) {
    die("Prepare failed: " . $conn->error);
}
$stmt_sick_leaves->bind_param("i", $user_id);
$stmt_sick_leaves->execute();
$result_sick_leaves = $stmt_sick_leaves->get_result();
$sick_leave_count = $result_sick_leaves->fetch_assoc()['sick_leave_count'] ?? 0;
$stmt_sick_leaves->close();

// Add sick leave count to the message count
$new_message_count += $sick_leave_count;

$sql_messages = "SELECT m.id, m.message, u.username 
                 FROM hr_data.messages m 
                 JOIN users u ON m.user_id = u.id 
                 WHERE m.user_id = ? 
                 ORDER BY m.created_at DESC LIMIT 5";
$stmt_messages = $conn->prepare($sql_messages);
if (!$stmt_messages) {
    die("Prepare failed: " . $conn->error);
}
$stmt_messages->bind_param("i", $user_id);
$stmt_messages->execute();
$result_messages = $stmt_messages->get_result();

$messages = [];
while ($row = $result_messages->fetch_assoc()) {
    $messages[] = [
        'id' => $row['id'], // Include the message ID
        'message' => $row['username'] == 'HR' ? 'HR Response: ' . $row['message'] : $row['message'],
        'sender' => $row['username']
    ];
}
$stmt_messages->close();

// Fetch user information
$stmt_user = $conn->prepare("SELECT username, email, profile_pic FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$stmt_user->close();

$user_image = $user && !empty($user['profile_pic']) ? '../Faculty/uploads/' . htmlspecialchars($user['profile_pic']) : 'default-profile.jpg';
$user_name = $user['username'] ?? 'Unknown User';

// Close the database connection
$conn->close();
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
            background: linear-gradient(180deg, #0F2A1D, #0F2A1D); /* Primary color */
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
        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .sidebar a i {
            margin-right: 15px;
        }
        .sidebar .logo {
            width: 100%;
            height: 120px;
            background: url('../signin&signout/assets1/img/logo.png') no-repeat center center;
            background-size: contain;
            margin-bottom: 30px;
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
            padding: 15px 25px;
            border: 1px solid #AEC3B0; /* Highlight */
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
         
        .header .user-info {
            display: flex;
            align-items: center;
            position: relative;
        }
                .header img {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            border: 3px solid #375534; /* Secondary Color */
            margin-right: 10px; /* Adjust spacing between profile picture and name */
        }
        .header .user-info span {
            margin-left: 10px;
            font-size: 18px;
            font-weight: 500;
        }
        
        .header .messages-icon {
            font-size: 22px;
            cursor: pointer;
            position: relative;
            margin-right: 20px; /* Add spacing between message icon and profile picture */
        }
        .header .messages-icon::after {
            content: attr(data-count);
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 14px;
        }
        /* Stats Section */
        .stats-section {
            display: flex;
            gap: 20px;
            margin-top: 30px;
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
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 120px;
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
            margin-top: 40px;
            padding: 20px;
            background: #F5F5F5; /* Light background */
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
            background-color: #375534;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }
        .logout-btn:hover {
            background-color: #0F2A1D;
        }
        .dropdown-icon {
            font-size: 16px;
            color: black;
            margin-left: 10px;
            cursor: pointer;
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            z-index: 1000;
            display: none;
        }
        .dropdown-menu a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: black;
        }
        .dropdown-menu a:hover {
            background-color: #f4f4f4;
            color: black;
        }

    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"></div>
        <a href="#"><i>🏠</i> Dashboard</a>
        <a href="prof_profile.php"><i>👤</i> Profile</a>
        <a href="faculty_task.php"><i>📝</i> Tasks</a>
    </div>
    <div class="content">

    <div class="header">
    <h2>Welcome, <?= htmlspecialchars($user_name) ?></h2>
    <div class="user-info">

    <a href="messaging_interface.php" onclick="resetMessageCount()">
    <i class="messages-icon" data-count="<?= $new_message_count ?>">📩</i>
</a>

        <img src="<?= htmlspecialchars($user_image) ?>" alt="Profile Picture" onclick="toggleDropdown()">
        <span onclick="toggleDropdown()"><?= htmlspecialchars($user_name) ?></span>
        <span class="dropdown-toggle" onclick="toggleDropdown()">
    <svg width="15" height="13" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" role="img">
        <title>Toggle Dropdown</title>
        <path d="M3.5 5.5a.5.5 0 0 1 .707-.707L8 8.793l3.793-3.998a.5.5 0 1 1 .707.707l-4 4.25a.5.5 0 0 1-.707 0l-4-4.25a.5.5 0 0 1-.707-.707z" fill="currentColor"/>
    </svg>
</span>
<div id="dropdownMenu" class="dropdown-menu">
    <a href="../Faculty/change_password2.php">Change Password</a>
    <a href="../signin&signout/LandingPage.php" style="background-color: #ff4d4d;">Logout</a>
</div>
    </div>
</div>

        <div class="stats-section">
            <a href="#" class="stat-card">
                <h4>Tasks Completed</h4>
                <p><?= $task_count_completed ?></p>
            </a>
            <a href="#" class="stat-card">
                <h4>New Notifications</h4>
                <p><?= $task_count_pending ?></p>
            </a>
            <a href="timetracking.php" class="stat-card">
                <h4>Time Log in/out</h4>
            </a>
        </div>
        <div class="notifications-section">
            <h3> New Notifications</h3>
            <ul>
            <ul>
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $msg): ?>
                    <a href="messaging_interface.php?message_id=<?php echo $msg['id']; ?>" style="display: block; text-decoration: none; color: inherit;">
                        <li>
                            <?php 
                                // Always prepend "HR Response:" to every message
                                echo 'HR Response: ' . htmlspecialchars($msg['message']); 
                            ?>
                            <span class="badge">New</span>
                        </li>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No new messages.</li>
            <?php endif; ?>
                <li>
                    Tasks Pending: <span class="badge"><?= $task_count_pending ?></span>
                </li>
            </ul>
        </div>
    </div>
        
<script>
   function resetMessageCount() {
    const messageIcon = document.querySelector('.messages-icon');
    if (messageIcon) {
        // Reset the data-count attribute
        messageIcon.setAttribute('data-count', '0');
        
        // Update the icon's displayed text if needed
        messageIcon.innerHTML = '📩'; // You can update the icon as well
        
        // Optionally, reset the badge number if a badge is used
        const badge = messageIcon.querySelector('.badge');
        if (badge) {
            badge.textContent = '0';  // Reset the badge number if it's visible
        }
    }
}

// Reset the message count when the page is loaded
window.onload = function() {
    if (window.location.pathname.includes("dashboard")) {
        resetMessageCount(); // This resets the message count to 0 on dashboard load
    }
};

// Function to toggle the dropdown menu
function toggleDropdown() {
    const menu = document.getElementById('dropdownMenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

// Close the dropdown menu if clicked outside
document.addEventListener('click', (event) => {
    const dropdown = document.querySelector('.dropdown-toggle');
    const menu = document.getElementById('dropdownMenu');
    if (!dropdown.contains(event.target) && !menu.contains(event.target)) {
        menu.style.display = 'none';
    }
});

</script>
</body>
</html>