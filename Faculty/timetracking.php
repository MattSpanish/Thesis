<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: ../signin&signout/LoginPage.php");
    exit;
}

// Safely fetch the logged-in user's full name from the session
$loggedInUserName = isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Unknown User';

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "register";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get today's date
$today = date('Y-m-d');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $loggedInUserName;
    $date = $_POST['date'] ?? $today;
    $time_in = $_POST['time-in'] ?? null;
    $time_out = $_POST['time-out'] ?? null;

    if (strtotime($date) <= strtotime($today)) {
        if ($time_in && $time_out) {
            // Calculate the difference in hours
            $inTime = strtotime($time_in);
            $outTime = strtotime($time_out);
            $hoursWorked = ($outTime - $inTime) / 3600;

            if ($hoursWorked <= 0) {
                $_SESSION['errorMessage'] = "Time Out must be later than Time In.";
            } elseif ($hoursWorked > 4) {
                $_SESSION['errorMessage'] = "Logged hours cannot exceed 4 hours. You attempted to log $hoursWorked hours.";
            } else {
                $stmt = $conn->prepare("INSERT INTO time_logs (fullname, date, time_in, time_out) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $fullname, $date, $time_in, $time_out);

                if ($stmt->execute()) {
                    $_SESSION['successMessage'] = "Time log saved successfully! Teacher $fullname worked for " . number_format($hoursWorked, 2) . " hours.";
                } else {
                    $_SESSION['errorMessage'] = "Error saving time log: " . $stmt->error;
                }

                $stmt->close();
            }
        } else {
            $_SESSION['errorMessage'] = "Please provide both Time In and Time Out.";
        }
    } else {
        $_SESSION['errorMessage'] = "Invalid date selected. Please choose today or earlier.";
    }

    // Redirect to the same page to reset the form and avoid duplicate submissions
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Display success or error messages
$successMessage = $_SESSION['successMessage'] ?? null;
$errorMessage = $_SESSION['errorMessage'] ?? null;

// Clear messages to prevent them from being displayed on subsequent loads
unset($_SESSION['successMessage'], $_SESSION['errorMessage']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Faculty Time Tracking</title>
    <style>
        body {
            background-color: #f4f7fc;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 80px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 25px 30px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .status {
            font-size: 1rem;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .status.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .back-arrow {
            position: fixed;
            top: 20px;
            left: 20px;
            font-size: 18px;
            padding: 10px 15px;
            background-color: #375534;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .back-arrow i {
            margin-right: 5px;
        }
        .back-arrow:hover {
            background-color: #0F2A1D;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<a href="../Faculty/profDASHBOARD.php" class="back-arrow">
    <i class="fa-solid fa-arrow-left"></i>
</a>

<div class="container">
    <h2>Faculty Time Tracking</h2>

    <?php if (isset($successMessage)): ?>
        <div class="status success"><?php echo $successMessage; ?></div>
    <?php elseif (isset($errorMessage)): ?>
        <div class="status error"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="teacher-name" class="form-label">Teacher Name:</label>
            <input type="text" id="teacher-name" name="teacher-name" value="<?php echo $loggedInUserName; ?>" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date: Auto-Generated</label>
            <input type="date" id="date" name="date" class="form-control" value="<?php echo $today; ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="time-in" class="form-label">Time In:</label>
            <input type="time" id="time-in" name="time-in" class="form-control">
        </div>

        <div class="mb-3">
            <label for="time-out" class="form-label">Time Out:</label>
            <input type="time" id="time-out" name="time-out" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

</body>
</html>