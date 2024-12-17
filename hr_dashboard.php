<?php
// Database connection to hr_data for profile management
$db_hr = new mysqli('localhost', 'root', '', 'hr_data');
if ($db_hr->connect_error) {
    die("Database connection failed: " . $db_hr->connect_error);
}

// Fetch admin data from hr_data
$result = $db_hr->query("SELECT * FROM ADMIN_CREDENTIALS WHERE id = 1");
if (!$result) {
    die("Error fetching admin credentials: " . $db_hr->error);
}
$ADMIN_CREDENTIALS = $result->fetch_assoc();
$profilePicture = $ADMIN_CREDENTIALS['profile_picture'] ?? 'default-profile.png';

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profilePicture'];
        $targetDir = "UploadHrProfile/";
        $fileName = time() . "_" . basename($file["name"]);
        $targetFilePath = $targetDir . $fileName;

        // Validate file type (only allow images)
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                // Update the database with the new profile picture
                $updateQuery = "UPDATE ADMIN_CREDENTIALS SET profile_picture = '$fileName' WHERE id = 1";
                $db_hr->query($updateQuery);
                $profilePicture = $fileName; // Update the profile picture in the session
                header("Location: hr_dashboard.php");
                exit;
            } else {
                echo "Failed to upload the profile picture.";
            }
        } else {
            echo "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        }
    }
}


// Initialize $mse with a default value
$mse = 'No data available';

// Fetch chart data from the Flask API
$flaskApiUrl = 'http://127.0.0.1:5000/api/get_chart_data';
$chartImage = null;

try {
    $apiResponse = @file_get_contents($flaskApiUrl);
    if ($apiResponse !== false) {
        $data = json_decode($apiResponse, true);
        $chartImage = $data['chart'] ?? null;
        $mse = $data['mse'] ?? 'No MSE data available';
    } else {
        $mse = "Unable to connect to the API.";
    }
} catch (Exception $e) {
    $mse = "An error occurred while fetching chart data.";
}


// Database connection to enrollment_db for department count
$db_enrollment = new mysqli('localhost', 'root', '', 'enrollment_db');
if ($db_enrollment->connect_error) {
    die("Database connection failed: " . $db_enrollment->connect_error);
}

// Fetch department count from historical_data table in enrollment_db
$departmentQuery = $db_enrollment->query("SELECT COUNT(*) AS total_departments FROM historical_data");
if (!$departmentQuery) {
    die("Error fetching department count: " . $db_enrollment->error); // Will show the actual query error
}

if ($departmentRow = $departmentQuery->fetch_assoc()) {
    $departments = (int)$departmentRow['total_departments'];
} else {
    $departments = 0; // Default to 0 if query fails
}

// Count available professors (You need to define this variable)
$professorQuery = $db_enrollment->query("SELECT COUNT(*) AS total_professors FROM professors");
if ($professorQuery) {
    $professors = (int)$professorQuery->fetch_assoc()['total_professors'];
} else {
    $professors = 0; // Default to 0 if query fails
}

// Count pending messages and sick leave requests from hr_data
$count_sql = "
    SELECT COUNT(*) AS pending_count FROM (
        SELECT id FROM hr_data.messages WHERE hr_response IS NULL
        UNION
        SELECT id FROM hr_data.sick_leaves WHERE hr_response IS NULL
    ) AS pending_messages;
";

$count_result = $db_hr->query($count_sql);
if (!$count_result) {
    die("Error fetching pending messages: " . $db_hr->error); // Will show the actual query error
}

$pending_count = $count_result->fetch_assoc()['pending_count'] ?? 0;

// Close the database connections
$db_hr->close();
$db_enrollment->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
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

        #dropdownMenu a:hover {
            background-color: #f4f4f4; /* Light gray on hover */
            color: #000; /* Black text on hover */
        }

        .chart-container {
            margin: 20px auto;
            width: 80%;
            text-align: center;
            padding: 20px;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .mse {
            font-size: 1.2em;
            color: #555;
            text-align: center;
            padding: 20px;
        }

        /* Profile Dropdown Styling */
        .profile-btn {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
        }

        .profile-btn img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .profile-btn span {
            font-size: 18px;
            color: #333;
        }

        .dropdown-menu {
            position: absolute;
            top: 50px; /* Adjust based on your header size */
            right: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 200px;
            z-index: 1000;
        }

        .dropdown-menu a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
        }

        .dropdown-menu a:hover {
            background-color: #f4f4f4;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="signin&signout/assets1/img/logo.png" alt="MindVenture Logo">
        <ul>
            <li><a href="#" class="active">Dashboard</a></li>
            <li><a href="task.php">Task</a></li>
            <li><a href="Employees.php">Employees</a></li>
            <li><a href="UploadingFiles.php">Files</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="header">
            <h1>Dashboard</h1>
            <div>
                <button id="profileDropdown" class="profile-btn">
                    <img src="UploadHrProfile/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile">
                    <span>ADMIN</span>
                </button>
                <div id="dropdownMenu" class="dropdown-menu" style="display: none;">
                    <a href="../signin&signout/change_password.php">Change Password</a>
                    <a href="../signin&signout/LandingPage.php" style="background-color: #ff4d4d;">Logout</a>
                </div>
            </div>
        </div>

        <div class="dashboard-cards">
    <div class="card professors" onclick="window.location.href='workload.php';">
        <h3>Available Professors</h3>
        <p><?php echo $professors; ?></p>
    </div>
    <div class="card departments" onclick="window.location.href='DepartmentRecords.php';">
        <h3>Departments</h3>
        <p><?php echo $departments; ?></p>
    </div>
    <div class="card evaluations" onclick="window.location.href='hr_messaging.php';">
        <h3>Pending Messages</h3>
        <p><?php echo $pending_count; ?></p>
    </div>
</div>


        <div class="chart-container">
            <?php if ($chartImage): ?>
                <img id="chart" src="data:image/png;base64,<?php echo $chartImage; ?>" alt="Performance Chart">
            <?php else: ?>
                <img id="chart" src="https://via.placeholder.com/600x200" alt="Placeholder Chart">
                <p><?php echo htmlspecialchars($mse); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Toggle the dropdown menu
        document.getElementById('profileDropdown').addEventListener('click', () => {
            const menu = document.getElementById('dropdownMenu');
            // Toggle visibility of the dropdown menu
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });

        // Close the dropdown menu if clicked outside
        document.addEventListener('click', (event) => {
            const menu = document.getElementById('dropdownMenu');
            const profileBtn = document.getElementById('profileDropdown');
            if (!profileBtn.contains(event.target) && !menu.contains(event.target)) {
                menu.style.display = 'none';
            }
        });
    </script>
</body>
</html>
