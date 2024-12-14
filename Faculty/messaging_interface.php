<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
    header("Location: ../signin&signout/LoginPage.php");
    exit;
}

require '../signin&signout/config.php'; 
require '../signin&signout/hr_db.php'; // HR admin database

// Debugging: Check if session variables are properly set
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    die("Error: User ID is not set in session.");
}

$user_id = $_SESSION['id'];
$user_name = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : 'Unknown User';

// Handle form submission for sending message or sick leave request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (!empty($message)) {
        if ($action === 'message') {
            // Insert the message into the `messages` table
            $sql = "INSERT INTO hr_data.messages (user_id, message, created_at) VALUES (?, ?, NOW())";
        } elseif ($action === 'sick_leave') {
            // Insert the sick leave request into the `sick_leaves` table
            $sql = "INSERT INTO hr_data.sick_leaves (user_id, reason, created_at) VALUES (?, ?, NOW())";
        } else {
            $error_message = "Invalid action selected.";
        }

        if (isset($sql)) {
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("is", $user_id, $message);
                if ($stmt->execute()) {
                    $success_message = ucfirst($action) . " submitted successfully.";
                } else {
                    $error_message = "Error submitting " . $action . ": " . $conn->error;
                }
                $stmt->close();
            } else {
                $error_message = "Error preparing statement: " . $conn->error;
            }
        }
    } else {
        $error_message = "Message or reason cannot be empty.";
    }
}
// Fetch messages and HR responses
// Fetch messages and HR responses
$fetch_sql = "SELECT m.id, m.message, m.hr_response, m.created_at, 'message' AS message_type
              FROM hr_data.messages m
              WHERE m.user_id = ? 
              UNION
              SELECT s.id, s.reason AS message, s.hr_response, s.created_at, 'sick_leave' AS message_type
              FROM hr_data.sick_leaves s
              WHERE s.user_id = ? 
              ORDER BY created_at DESC";

$stmt = $conn->prepare($fetch_sql);

if ($stmt) {
    $stmt->bind_param("ii", $user_id, $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
    } else {
        $error_message = "Error executing query: " . $stmt->error;
        $result = null; // Ensuring $result is defined
    }
    $stmt->close();
} else {
    $error_message = "Error preparing query: " . $conn->error;
    $result = null; // Ensuring $result is defined
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Messaging Interface</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Messaging Interface</h1>
    <p class="text-center">Welcome, <?php echo $user_name; ?>. Use this interface to send messages or request sick leave.</p>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Display the Messages and Responses -->
    <h3>Your Messages and Sick Leave Requests</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Type</th>
                <th>Message/Reason</th>
                <th>HR Response</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo ucfirst($row['message_type']); ?></td>
                <td><?php echo htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $row['hr_response'] ? htmlspecialchars($row['hr_response'], ENT_QUOTES, 'UTF-8') : 'No response yet'; ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No messages or requests found.</td>
        </tr>
    <?php endif; ?>
</tbody>

    </table>

    <!-- Form to Submit Message or Sick Leave Request -->
    <form method="POST" action="" class="mt-4">
        <div class="mb-3">
            <label for="action" class="form-label">Choose an Action</label>
            <select name="action" id="action" class="form-select" required>
                <option value="message">Send a Message</option>
                <option value="sick_leave">Request Sick Leave</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message/Reason</label>
            <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="../Faculty/profDASHBOARD.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
