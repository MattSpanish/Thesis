<?php
// Database connection settings
$servername = "localhost"; // Change if using a remote server
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "enrollment_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Student Enrollment Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['uploadTeacher'])) {
    // Collect and sanitize user inputs
    $program = mysqli_real_escape_string($conn, $_POST['program']);
    $total = mysqli_real_escape_string($conn, $_POST['total']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $total_students = mysqli_real_escape_string($conn, $_POST['total_students']);

    // SQL queries to insert data into the database
    $sql1 = "INSERT INTO student_data (program, total, year) VALUES ('$program', '$total', '$year')";
    $sql2 = "INSERT INTO student_records (year, program, section, total) VALUES ('$year', '$program', '$section', '$total_students')";

    // Execute the queries and check for success
    if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
        echo "<script>
                alert('New record created successfully!');
                window.location.href = window.location.href; // Refresh the page
              </script>";
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle Teacher Data Upload Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['uploadTeacher'])) {
    // Collect and sanitize user inputs
    $teacherIdNo = mysqli_real_escape_string($conn, $_POST['teacherIdNo']);
    $teacherLastName = mysqli_real_escape_string($conn, $_POST['teacherLastName']);
    $teacherFirstName = mysqli_real_escape_string($conn, $_POST['teacherFirstName']);
    $teacherMiddleName = mysqli_real_escape_string($conn, $_POST['teacherMiddleName']);
    $teacherDepartment = mysqli_real_escape_string($conn, $_POST['teacherDepartment']);
    $teacherPosition = mysqli_real_escape_string($conn, $_POST['teacherPosition']);
    $teacherDateHired = mysqli_real_escape_string($conn, $_POST['teacherDateHired']);
    $teacherYearsOfService = mysqli_real_escape_string($conn, $_POST['teacherYearsOfService']);
    $teacherRanking = mysqli_real_escape_string($conn, $_POST['teacherRanking']);
    $teacherStatus = mysqli_real_escape_string($conn, $_POST['teacherStatus']); // New field for status

    // SQL query to insert data into historical_data table
    $sql = "INSERT INTO historical_data (id_no, last_name, first_name, middle_name, department, position, date_hired, years_of_service, ranking, status) 
            VALUES ('$teacherIdNo', '$teacherLastName', '$teacherFirstName', '$teacherMiddleName', '$teacherDepartment', '$teacherPosition', '$teacherDateHired', '$teacherYearsOfService', '$teacherRanking', '$teacherStatus')";

    // Execute the query and check for success
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Teacher data uploaded successfully!');
                window.location.href = window.location.href; // Refresh the page
              </script>";
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
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
    </style>
</head>
<body>

<!-- Back Button -->
<a href="hr_dashboard.php" class="back-button">
    <i class="fas fa-arrow-left"></i>
</a>

<!-- Main Content Area -->
<div class="content">
    <!-- Student Enrollment List Section -->
    <div class="upload-container">
        <h2>Student Enrollment List</h2>
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

    <!-- Teacher Data Upload Section -->
    <div class="upload-container">
        <h2>Upload Faculty Historical Data</h2>
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
                <select name="year" id="year" class="form-control" required>
                    <option value="">Select Year</option>
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
        <h2>Upload Faculty Schedule</h2>
        <form id="facultyUploadForm" action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="facultyName">Faculty Name</label>
                <input type="text" id="facultyName" name="facultyName" required>
            </div>

            <!-- Subject - Section Fields -->
            <div id="subject-section-container">
                <div class="form-group subject-section-group">
                    <label>Subject - Section</label>
                    <input type="text" name="subject_section[]" placeholder="e.g., Gen Chem - STEM 11-Y1-2" required>
                    <button type="button" class="remove-button" onclick="removeElement(this)">×</button>
                </div>
            </div>
            <div class="add-button" onclick="addSubjectSection()">+ Add Another Subject - Section</div>

            <!-- Day - Time Fields -->
            <div id="day-time-container">
                <div class="form-group day-time-group">
                    <label>Day - Time</label>
                    <input type="text" name="day_time[]" placeholder="e.g., Monday - 1:30 - 4:30" required onchange="calculateTotalHours()">
                    <button type="button" class="remove-button" onclick="removeElement(this)">×</button>
                </div>
            </div>
            <div class="add-button" onclick="addDayTime()">+ Add Another Day - Time</div>

            <!-- Total Hours -->
            <div class="form-group">
                <label for="total">Total</label>
                <input type="text" id="total" name="total" placeholder="e.g., 3, etc.">
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<script>
    // Add and Remove Elements
    function addSubjectSection() {
        const container = document.getElementById('subject-section-container');
        const newSection = document.createElement('div');
        newSection.className = 'form-group subject-section-group';
        newSection.innerHTML = `
            <label>Subject - Section</label>
            <input type="text" name="subject_section[]" placeholder="e.g., Gen Chem - STEM 11-Y1-2" required>
            <button type="button" class="remove-button" onclick="removeElement(this)">×</button>
        `;
        container.appendChild(newSection);
    }

    function addDayTime() {
        const container = document.getElementById('day-time-container');
        const newDayTime = document.createElement('div');
        newDayTime.className = 'form-group day-time-group';
        newDayTime.innerHTML = `
            <label>Day - Time</label>
            <input type="text" name="day_time[]" placeholder="e.g., Monday - 1:30 - 4:30" required>
            <button type="button" class="remove-button" onclick="removeElement(this)">×</button>
        `;
        container.appendChild(newDayTime);
    }

    function removeElement(button) {
        button.parentElement.remove();
    }

    function calculateTotalHours() {
        // Implement your logic for calculating total hours here
    }

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
