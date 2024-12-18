<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "enrollment_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle form submissions
function handleFormSubmission($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['uploadTeacher'])) {
            handleTeacherData($conn);
        } elseif (isset($_POST['program'], $_POST['total'], $_POST['year']) && !isset($_POST['section'])) {
            handleEnrollmentData($conn);
        } elseif (isset($_POST['program'], $_POST['year'], $_POST['section'], $_POST['total_students'])) {
            handleStudentData($conn);
        } elseif (isset($_POST['total_teachers'], $_POST['year'])) {
            handleFacultyData($conn);
        }
    }
}

// Function to handle faculty data form submission
function handleFacultyData($conn) {
    $total_teachers = mysqli_real_escape_string($conn, $_POST['total_teachers']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);

    $sql = "INSERT INTO faculty_data (total_teachers, year) VALUES ('$total_teachers', '$year')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Faculty data uploaded successfully!');
                window.location.href = window.location.href;
              </script>";
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Function to handle student enrollment form submission
function handleEnrollmentData($conn) {
    $program = mysqli_real_escape_string($conn, $_POST['program']);
    $total = mysqli_real_escape_string($conn, $_POST['total']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);

    $sql = "INSERT INTO student_data (program, total, year) VALUES ('$program', '$total', '$year')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Student enrollment data uploaded successfully!');
                window.location.href = window.location.href;
              </script>";
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Function to handle student data form submission
function handleStudentData($conn) {
    $program = mysqli_real_escape_string($conn, $_POST['program']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $total_students = mysqli_real_escape_string($conn, $_POST['total_students']);

    $sql = "INSERT INTO student_records (year, program, section, total) 
            VALUES ('$year', '$program', '$section', '$total_students')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Student data uploaded successfully!');
                window.location.href = window.location.href;
              </script>";
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Function to handle teacher data form submission
function handleTeacherData($conn) {
    $teacherIdNo = mysqli_real_escape_string($conn, $_POST['teacherIdNo']);
    $teacherLastName = mysqli_real_escape_string($conn, $_POST['teacherLastName']);
    $teacherFirstName = mysqli_real_escape_string($conn, $_POST['teacherFirstName']);
    $teacherMiddleName = mysqli_real_escape_string($conn, $_POST['teacherMiddleName']);
    $teacherDepartment = mysqli_real_escape_string($conn, $_POST['teacherDepartment']);
    $teacherPosition = mysqli_real_escape_string($conn, $_POST['teacherPosition']);
    $teacherDateHired = mysqli_real_escape_string($conn, $_POST['teacherDateHired']);
    $teacherYearsOfService = mysqli_real_escape_string($conn, $_POST['teacherYearsOfService']);
    $teacherRanking = mysqli_real_escape_string($conn, $_POST['teacherRanking']);
    $teacherStatus = mysqli_real_escape_string($conn, $_POST['teacherStatus']);

    $sql = "INSERT INTO historical_data (id_no, last_name, first_name, middle_name, department, position, date_hired, years_of_service, ranking, status) 
            VALUES ('$teacherIdNo', '$teacherLastName', '$teacherFirstName', '$teacherMiddleName', '$teacherDepartment', '$teacherPosition', '$teacherDateHired', '$teacherYearsOfService', '$teacherRanking', '$teacherStatus')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Teacher data uploaded successfully!');
                window.location.href = window.location.href;
              </script>";
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle form submissions
handleFormSubmission($conn);

// Connect to the `register` database
$register_conn = new mysqli('localhost', '', '', 'register');

// Connect to the `enrollment_db` database
$enrollment_conn = new mysqli('localhost', '', '', 'enrollment_db');

// Check connections
if ($register_conn->connect_error || $enrollment_conn->connect_error) {
    die("Connection failed: " . $register_conn->connect_error . " / " . $enrollment_conn->connect_error);
}

// Fetch users for the dropdown
$userDropdownQuery = "SELECT id, fullname FROM users";
$userDropdownResult = $register_conn->query($userDropdownQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $subject = $_POST['subject'] ?? '';
    $sections = $_POST['sections'] ?? '';
    $time = $_POST['time'] ?? '';
    $day = $_POST['day'] ?? '';
    $total_students = $_POST['total_students'] ?? 0;

    if ($id && $subject && $sections && $time && $day) {
        $fullnameQuery = "SELECT fullname FROM users WHERE id = ?";
        $fullnameStmt = $register_conn->prepare($fullnameQuery);
        $fullnameStmt->bind_param('i', $id);
        $fullnameStmt->execute();
        $fullnameResult = $fullnameStmt->get_result();

        if ($fullnameResult->num_rows > 0) {
            $fullnameRow = $fullnameResult->fetch_assoc();
            $fullname = $fullnameRow['fullname'];

            $insert_query = "INSERT INTO facultyschedule (id, fullname, subject, sections, time, day, total_students)
                             VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $enrollment_conn->prepare($insert_query);
            $stmt->bind_param('isssssi', $id, $fullname, $subject, $sections, $time, $day, $total_students);

            if ($stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?success=true");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "User not found.";
        }
    } else {
        echo "All fields are required.";
    }
}

if (isset($_GET['success']) && $_GET['success'] === 'true') {
    echo "<script>
            alert('Schedule added successfully!');
            window.location.href = document.referrer;
          </script>";
    exit();
}

$register_conn->close();
$enrollment_conn->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <title>UploadFile</title>
    <style>
             * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #E3EED4; /* Light Accent */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            display: flex;
            align-items: flex-start;
            width: 100%;
            max-width: 1200px;
            padding: 20px;
        }
        .sidebar {
            width: 220px;
            padding: 20px;
        }
        .sidebar .back-link {
            display: flex;
            align-items: center;
            font-size: 1.3em;
            color: #0F2A1D;
            text-decoration: none;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .sidebar .back-link:hover {
            background-color: #AEC3B0;
        }
        .sidebar .back-link svg {
            margin-right: 10px;
            color: #0F2A1D;
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }
        .upload-container {
            background: #AEC3B0;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            margin-top: 20px;
            width: 100%;
            max-width: 1000px;
            text-align: left;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .upload-container:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }
        h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #0F2A1D;
        }
        .drag-drop-area {
            border: 3px dashed #375534;
            border-radius: 10px;
            padding: 25px;
            cursor: pointer;
            background: #6B9071;
            transition: all 0.3s ease;
            text-align: center;
            color: #0F2A1D;
        }
        .drag-drop-area:hover {
            background: #375534;
            border-color: #0F2A1D;
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-group label {
            display: block;
            font-size: 1rem;
            font-weight: 600;
            color: #375534;
            margin-bottom: 8px;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            
            padding: 12px;
            border: 1px solid #6B9071;
            border-radius: 6px;
            font-size: 1rem;
            background-color: #E3EED4;
            transition: border-color 0.3s ease;
            color: #0F2A1D;
        }
        .form-group input:focus,
        .form-group select:focus {
            border-color: #375534;
            outline: none;
        }
        .remove-button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #ff4d4d;
            background: none;
            border: none;
            font-size: 20px;
        }
        .add-button {
            margin: 10px 0;
            display: inline-block;
            color: #375534;
            text-decoration: none;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.3s ease;
        }
        .add-button:hover {
            color: #0F2A1D;
        }
        button {
            margin-top: 20px;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: bold;
            color: white;
            background: #375534;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background: #0F2A1D;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background-color: #375534;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: 0.3s;
        }
        .back-button:hover {
            background-color: #2a442e;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }
        .back-button i {
            font-size: 18px;
        }
        /* Style for Date Input */
        .form-group input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #6B9071;
            border-radius: 6px;
            font-size: 1rem;
            background-color: #E3EED4;
            color: #0F2A1D;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .form-group input[type="date"]:focus {
            border-color: #375534;
            box-shadow: 0 0 5px rgba(55, 85, 52, 0.5);
            outline: none;
        }

        .form-group input[type="date"]::-webkit-calendar-picker-indicator {
            color: #375534;
            cursor: pointer;
            border-radius: 6px;
            margin-left: 5px;
        }
    </style>
</head>
<body>

<!-- Back Button -->
<a href="hr_dashboard.php" class="back-button">
    <i class="fas fa-arrow-left"></i>
</a>

<!-- Main Content Area -->
<div class="content">
    <!-- Student Enrollment Data List Section -->
    <div class="upload-container">
        <h2>Student Enrollment Data List</h2>
        <form id="studentEnrollmentForm" action="" method="POST">
            <div class="form-group">
                <label for="program">Program</label>
                <input type="text" id="program" name="program" placeholder="e.g., SHS-STEM, SHS-ABM PLUS, etc.">
            </div>
            <div class="form-group">
                <label for="total">Total</label>
                <input type="text" id="total" name="total" placeholder="e.g., 30, 45, etc.">
            </div>
            <div class="form-group">
                <label for="year">Year</label>
                <input type="text" id="year" name="year" placeholder="e.g., 2021, 2024-2025, etc.">
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- Faculty Data Section -->
    <div class="upload-container">
        <h2>Faculty Data List</h2>
        <form id="facultyDataForm" action="" method="POST">
            <div class="form-group">
                <label for="total_teachers">Total Teachers</label>
                <input type="text" id="total_teachers" name="total_teachers" placeholder="e.g., 5, 10, etc.">
            </div>
            <div class="form-group">
                <label for="year">Year</label>
                <input type="text" id="year" name="year" placeholder="e.g., 2021, 2024-2025, etc.">
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- Teacher Historical Data Upload Section -->
    <div class="upload-container">
        <h2>Faculty Historical Data</h2>
        <form id="teacherUploadForm" action="" method="POST">
            <div class="form-group">
                <label for="teacherIdNo">ID No.</label>
                <input type="text" id="teacherIdNo" name="teacherIdNo" required>
            </div>
            <div class="form-group">
                <label for="teacherLastName">Last Name</label>
                <input type="text" id="teacherLastName" name="teacherLastName" required>
            </div>
            <div class="form-group">
                <label for="teacherFirstName">First Name</label>
                <input type="text" id="teacherFirstName" name="teacherFirstName" required>
            </div>
            <div class="form-group">
                <label for="teacherMiddleName">Middle Name</label>
                <input type="text" id="teacherMiddleName" name="teacherMiddleName" required>
            </div>
            <div class="form-group">
                <label for="teacherDepartment">Department</label>
                <input type="text" id="teacherDepartment" name="teacherDepartment" required>
            </div>
            <div class="form-group">
                <label for="teacherPosition">Position</label>
                <input type="text" id="teacherPosition" name="teacherPosition" required>
            </div>
            <div class="form-group">
                <label for="teacherDateHired">Date Hired</label>
                <input type="date" id="teacherDateHired" name="teacherDateHired" required>
            </div>
            <div class="form-group">
                <label for="teacherYearsOfService">Years of Service</label>
                <input type="number" id="teacherYearsOfService" name="teacherYearsOfService" min="0" required>
            </div>
            <div class="form-group">
                <label for="teacherRanking">Ranking</label>
                <input type="text" id="teacherRanking" name="teacherRanking" required>
            </div>
            <div class="form-group">
            <label for="teacherStatus">Status</label>
            <select id="teacherStatus" name="teacherStatus" required>
                <option value="ACTIVE">ACTIVE</option>
                <option value="INACTIVE">INACTIVE</option>
            </select>
        </div>
            <button type="submit" name="uploadTeacher">Upload</button>
        </form>
    </div>

    <!-- Student Historical Data Section -->
    <div class="upload-container">
        <h2>Student Historical Data</h2>
        <form id="historicalDataForm" action="" method="POST">
            <div class="form-group">
                <label for="program">Program</label>
                <input type="text" name="program" id="program" class="form-control" required>
            </div>
            
                <div class="form-group">
            <label for="year">Year</label>
            <select id="year" name="year" required>
            <option disabled selected>Please select a year</option>
                    <option value="2024-2025">2024-2025</option>
                    <option value="2023-2024">2023-2024</option>
                    <option value="2022-2023">2022-2023</option>
                    <option value="2021-2022">2021-2022</option>
                    <option value="2021">2021</option>
                </select>
            </div>

            <div class="form-group">
                <label for="section">Section</label>
                <input type="text" name="section" id="section" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="total_students">Total Students</label>
                <input type="number" name="total_students" id="total_students" class="form-control" required>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- Faculty File Upload Section -->
    <div class="upload-container">
        <h2>Faculty Schedule Input Form</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="id">Select Faculty Member</label>
                <select id="id" name="id" required>
                    <option disabled selected>Please select a faculty</option>
                    <?php 
                    if ($userDropdownResult && $userDropdownResult->num_rows > 0) {
                        while ($row = $userDropdownResult->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['fullname'] . '</option>';
                        }
                    } else {
                        echo '<option disabled>No faculty available</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="Enter subject name" required>
            </div>
            <div class="form-group">
                <label for="sections">Sections</label>
                <input type="text" id="sections" name="sections" placeholder="Enter sections (e.g., A, B, C)" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="text" id="time" name="time" placeholder="e.g., 8:00 AM - 10:00 AM" required>
            </div>
            <div class="form-group">
                <label for="day">Day</label>
                <input type="text" id="day" name="day" placeholder="e.g., Monday, Wednesday" required>
            </div>
            <div class="form-group">
                <label for="total_students">Total Students</label>
                <input type="number" id="total_students" name="total_students" min="0" placeholder="Enter total students" required>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

<script>

    // Automatically format the Program input field
    const programInput = document.getElementById('program');

    programInput.addEventListener('input', () => {
        let value = programInput.value.toUpperCase(); // Convert input to uppercase
        if (!value.startsWith("SHS-")) {
            value = "SHS-" + value.replace(/^SHS-*/, ""); // Remove any extra SHS- and add one
        }
        programInput.value = value; // Update the input value
    });
</script>

</body>
</html>