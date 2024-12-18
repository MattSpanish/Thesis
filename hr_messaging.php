<?php
session_start();

// Database connections
require '../Thesis/signin&signout/hr_db.php'; // HR database connection
require '../Thesis/signin&signout/config.php'; // Faculty database connection

// Ensure HR database connection
if (!$conn) {
    die("HR database connection failed: " . mysqli_connect_error());
}

// Ensure Faculty database connection
require '../Thesis/signin&signout/config.php';

if (!isset($faculty_conn)) {
    die("Faculty database connection variable not defined in config.php.");
}

$sql = "
    SELECT m.id, m.message, m.hr_response, m.created_at, u.username, 'message' AS message_type
    FROM hr_data.messages m
    JOIN register.users u ON m.user_id = u.id  -- Reference users table from the register database
    WHERE m.hr_response IS NULL
    UNION
    SELECT s.id, s.reason AS message, s.hr_response, s.created_at, u.username, 'sick_leave' AS message_type
    FROM hr_data.sick_leaves s
    JOIN register.users u ON s.user_id = u.id  -- Reference users table from the register database
    WHERE s.hr_response IS NULL
    ORDER BY created_at DESC
";


$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

// Fetch results
$messages = [];
if (method_exists($stmt, 'get_result')) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
} else {
    $stmt->bind_result($id, $message, $hr_response, $created_at, $username, $message_type);
    while ($stmt->fetch()) {
        $messages[] = [
            'id' => $id,
            'message' => $message,
            'hr_response' => $hr_response,
            'created_at' => $created_at,
            'username' => $username,
            'message_type' => $message_type,
        ];
    }
}

$stmt->close();

// Handle HR responses to faculty messages or sick leave requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id'], $_POST['hr_response'], $_POST['message_type'])) {
    $message_id = (int) $_POST['message_id'];
    $hr_response = trim($_POST['hr_response']);
    $message_type = $_POST['message_type'] === 'message' ? 'messages' : 'sick_leaves';

    if (!empty($hr_response)) {
        $update_sql = "UPDATE hr_data.$message_type SET hr_response = ?, updated_at = NOW() WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);

        if ($update_stmt) {
            $update_stmt->bind_param("si", $hr_response, $message_id);
            if ($update_stmt->execute()) {
                $success_message = "HR Response sent successfully.";

                // Insert response into faculty database
                $faculty_insert_sql = "INSERT INTO faculty_responses (message_id, response, created_at) VALUES (?, ?, NOW())";
                $faculty_stmt = $faculty_conn->prepare($faculty_insert_sql);

                if ($faculty_stmt) {
                    $faculty_stmt->bind_param("is", $message_id, $hr_response);
                    if ($faculty_stmt->execute()) {
                        $success_message .= " Response sent to the faculty database.";
                    } else {
                        $error_message = "Error sending the response to the faculty database: " . $faculty_conn->error;
                    }
                    $faculty_stmt->close();
                } else {
                    $error_message = "Error preparing the faculty database query: " . $faculty_conn->error;
                }

                // Redirect to prevent form resubmission
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $error_message = "Error updating the HR database: " . $conn->error;
            }
            $update_stmt->close();
        } else {
            $error_message = "Error preparing the HR update query: " . $conn->error;
        }
    } else {
        $error_message = "HR response cannot be empty.";
    }
}

$conn->close();
$faculty_conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Messaging System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Green Theme -->
    <style>
        body {
            background-color: #e6f5e6; /* Light green background */
        }
        h1, h3 {
            color: #155724; /* Dark green headings */
        }
        .card {
            border: 1px solid #198754; /* Bootstrap green border */
        }
        .card-header {
            background-color: #198754;
            color: #fff;
        }
        .btn-primary {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-primary:hover {
            background-color: #146c43;
            border-color: #146c43;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
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

<div class="container mt-5">
    <h1 class="text-center mb-4">HR Messaging System</h1>

    <!-- Success and Error Messages -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <h3 class="mb-3">Pending Messages</h3>

    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $row): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <strong>Message from <?php echo htmlspecialchars($row['username']); ?></strong>
                </div>
                <div class="card-body">
                    <p><strong>Type:</strong> <?php echo $row['message_type'] === 'message' ? 'Message' : 'Sick Leave Request'; ?></p>
                    <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                    <p><small><em>Sent on: <?php echo htmlspecialchars($row['created_at']); ?></em></small></p>

                    <?php if (empty($row['hr_response'])): ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="hr_response_<?php echo $row['id']; ?>" class="form-label">HR Response</label>
                                <textarea name="hr_response" id="hr_response_<?php echo $row['id']; ?>" class="form-control" rows="4" required></textarea>
                            </div>
                            <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="message_type" value="<?php echo $row['message_type']; ?>">
                            <button type="submit" class="btn btn-primary">Send Response</button>
                        </form>
                    <?php else: ?>
                        <p><strong>HR Response:</strong> <?php echo nl2br(htmlspecialchars($row['hr_response'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-success">No messages pending response.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>
</html>