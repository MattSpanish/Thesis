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
    if (!empty($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profilePicture'];
        $targetDir = "UploadHrProfile/";
        $fileName = time() . "_" . basename($file["name"]);
        $targetFilePath = $targetDir . $fileName;

        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                $updateQuery = $db_hr->prepare("UPDATE ADMIN_CREDENTIALS SET profile_picture = ? WHERE id = 1");
                $updateQuery->bind_param('s', $fileName);
                $updateQuery->execute();
                $profilePicture = $fileName;
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

// Flask API data handling
$mse = 'No data available';
$flaskApiUrl1 = 'http://127.0.0.1:5000/api/get_chart_data';
$flaskApiUrl3 = 'http://127.0.0.1:5002/api/faculty-data';
$chartImage1 = null;
$chartImage2 = null;

try {
    $apiResponse1 = @file_get_contents($flaskApiUrl1);
    if ($apiResponse1 !== false) {
        $data1 = json_decode($apiResponse1, true);
        $chartImage1 = $data1['chart'] ?? null;
        $mse = $data1['mse'] ?? 'No MSE data available';
    }

    $apiResponse3 = @file_get_contents($flaskApiUrl3);
    if ($apiResponse3 !== false) {
        $data3 = json_decode($apiResponse3, true);
        $chartImage2 = $data3['chart'] ?? null;
    }
} catch (Exception $e) {
    $mse = "An error occurred while fetching chart data.";
}

// Database connection to enrollment_data
$db_enrollment = new mysqli('localhost', 'root', '', 'enrollment_db');
if ($db_enrollment->connect_error) {
    die("Database connection failed: " . $db_enrollment->connect_error);
}

// Fetch department counts
$departmentCounts = [];
$departmentQuery = $db_enrollment->query("SELECT program, COUNT(*) AS count FROM student_records GROUP BY program");

if ($departmentQuery) {
    while ($row = $departmentQuery->fetch_assoc()) {
        $departmentCounts[$row['program']] = $row['count'];
    }
} else {
    die("Error fetching department data: " . $db_enrollment->error);
}

// Fetch pending messages count
$count_sql = "
    SELECT COUNT(*) AS pending_count FROM (
        SELECT id FROM hr_data.messages WHERE hr_response IS NULL
        UNION
        SELECT id FROM hr_data.sick_leaves WHERE hr_response IS NULL
    ) AS pending_messages;
";
$count_result = $db_hr->query($count_sql);
if (!$count_result) {
    die("Error fetching pending messages: " . $db_hr->error);
}
$pending_count = $count_result->fetch_assoc()['pending_count'] ?? 0;

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
            background-color: #E3EED4; /* Light Accent */ /* White for the main content area */
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
            display: flex; /* Enable flexbox */
            align-items: center; /* Center content vertically */
            justify-content: center; /* Center content horizontally */
            flex-direction: column; /* Stack content vertically */
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

        .profile-btn span:last-child {
            font-size: 18px; /* Adjust the size of the arrow */
            margin-left: 5px; /* Space between the name and the arrow */
            color: #333; /* Match the profile text color */
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
            top: 85px; /* Adjust based on your header size */
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
                    <svg width="15" height="13">
                        <path d="M3.5 5.5a.5.5 0 0 1 .707-.707L8 8.793l3.793-3.998a.5.5 0 1 1 .707.707l-4 4.25a.5.5 0 0 1-.707 0l-4-4.25a.5.5 0 0 1-.707-.707z"/>
                    </svg>
                </button>

                <div id="dropdownMenu" class="dropdown-menu" style="display: none;">
                    <a href="../signin&signout/change_password.php">Change Password</a>
                    <a href="../signin&signout/LandingPage.php" style="background-color: #ff4d4d;">Logout</a>
                </div>
            </div>
        </div>

        <div class="dashboard-cards">
            <div class="card professors" onclick="window.location.href='workload.php';">
                <h3> Teacher Workload Prediction</h3>
            </div>
            <div class="card departments" onclick="window.location.href='DepartmentRecords.php';">
                <h3>Departments</h3>
                <p><?php echo htmlspecialchars(array_sum($departmentCounts)); ?></p>
            </div>
            <div class="card evaluations" onclick="window.location.href='hr_messaging.php';">
                <h3>Pending Messages</h3>
                <p><?php echo $pending_count; ?></p>
            </div>
        </div>

        <div class="chart-container">
            <h3>Student Population Chart</h3>
            <?php if ($chartImage1): ?>
                <img id="chart1" src="data:image/png;base64,<?php echo $chartImage1; ?>" alt="Student Population Chart">
            <?php else: ?>
                <p><?php echo htmlspecialchars($mse); ?></p>
            <?php endif; ?>

            <h3>Faculty Population Chart</h3>
            <?php if ($chartImage2): ?>
                <img id="chart2" src="data:image/png;base64,<?php echo $chartImage2; ?>" alt="Faculty Population Chart">
            <?php else: ?>
                <p><?php echo htmlspecialchars($mse); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Toggle the dropdown menu
        document.getElementById('profileDropdown').addEventListener('click', () => {
            const menu = document.getElementById('dropdownMenu');
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
