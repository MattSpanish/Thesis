<?php
// Example PHP data fetching (in a real scenario, this would come from a database)
$professors = 10; // Replace with dynamic value from the database
$departments = 3; // Replace with dynamic value from the database
$evaluations = 20; // Replace with dynamic value from the database

// Database connection
$db = new mysqli('localhost', 'root', '', 'hr_data');

// Fetch admin data (including profile picture)
$result = $db->query("SELECT * FROM ADMIN_CREDENTIALS WHERE id = 1");
$ADMIN_CREDENTIALS = $result->fetch_assoc();

// Default profile picture if none is set
$profilePicture = $ADMIN_CREDENTIALS['profile_picture'] ?? 'default-profile.png';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profilePicture'];
        $targetDir = "UploadHrProfile/";
        $fileName = time() . "_" . basename($file["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            // Update profile picture in database
            $db->query("UPDATE ADMIN_CREDENTIALS SET profile_picture = '$fileName' WHERE id = 1");
            $_SESSION['message'] = "Profile picture updated successfully.";
            // Update profile picture path for immediate display
            $profilePicture = $fileName;

            // Redirect to avoid resubmission
            header("Location: hr_dashboard.php");
            exit; // Ensure script stops after redirect
        } else {
            $_SESSION['error'] = "Failed to upload the profile picture.";
        }
    }
}
$db->close();
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
        <div style="position: relative;">
            <button id="profileDropdown" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                <img id="profilePicture" 
                    src="UploadHrProfile/<?php echo htmlspecialchars($profilePicture); ?>" 
                    alt="Profile Picture" 
                    style="cursor: pointer; border-radius: 50%; width: 50px; height: 50px;">
                <span>ADMIN</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <!-- Drop-down content -->
            <div id="dropdownMenu" 
                style="display: none; position: absolute; right: 0; top: 60px; background-color: white; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15); border-radius: 5px; overflow: hidden; z-index: 1000;">
                <a href="/signin&signout/change_password.php" 
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

    <!-- Profile Picture Modal -->
    <div id="profileModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background-color: white; padding: 20px; border-radius: 10px; width: 300px; text-align: center;">
            <h3>Change Profile Picture</h3>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="file" name="profilePicture" accept="image/*" required>
                <button type="submit" style="margin-top: 10px; padding: 8px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 5px;">Upload</button>
            </form>
            <button onclick="closeModal()" style="margin-top: 10px; padding: 8px 15px; background-color: #ff4d4d; color: white; border: none; border-radius: 5px;">Cancel</button>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="dashboard-cards">
        <div class="card professors">
            <a href="#" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; text-indent: -9999px; overflow: hidden;">Available Professors</a>
            <h3>Available Professors</h3>
            <p><?php echo $professors; ?></p>
        </div>
        <div class="card departments">
            <a href="DepartmentRecords.php" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; text-indent: -9999px; overflow: hidden;">Departments</a>
            <h3>Departments</h3>
            <p><?php echo $departments; ?></p>
        </div>
        <div class="card evaluations">
            <a href="#" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; text-indent: -9999px; overflow: hidden;">Files</a>
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
        document.addEventListener('DOMContentLoaded', () => {
        const profilePicture = document.getElementById('profilePicture');
        const modal = document.getElementById('profileModal');
        const profileDropdown = document.getElementById('profileDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');

        // Function to open the modal
        profilePicture.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevent event from propagating to the dropdown
            modal.style.display = 'flex'; // Show the modal
        });

        // Function to close the modal
        window.closeModal = () => {
            modal.style.display = 'none'; // Hide the modal
        };

        // Close the modal when clicking outside the modal content
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Toggle the dropdown menu
        profileDropdown.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevent modal event interference
            dropdownMenu.style.display = dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '' ? 'block' : 'none';
        });

        // Close the dropdown menu when clicking outside
        document.addEventListener('click', (event) => {
            if (!profileDropdown.contains(event.target)) {
                dropdownMenu.style.display = 'none';
            }
        });
    });
    </script>

</body>
</html>

