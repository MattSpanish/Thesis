<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
    header("Location: ../signin&signout/LoginPage.php");
    exit;
}

// Database connection
require '../signin&signout/config.php';

// Get user ID from the session
$user_id = $_SESSION["id"];

// Check if form data is sent
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize user input
    $gender = isset($_POST['gender']) ? htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8') : null;
    $subject = isset($_POST['subject']) ? htmlspecialchars($_POST['subject'], ENT_QUOTES, 'UTF-8') : null;
    $status = isset($_POST['status']) ? htmlspecialchars($_POST['status'], ENT_QUOTES, 'UTF-8') : null;

    // Update query
    if ($gender && $subject && $status) {
        $stmt = $conn->prepare("UPDATE users SET gender = ?, subject = ?, status = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("sssi", $gender, $subject, $status, $user_id);

            if ($stmt->execute()) {
                // Success: redirect to dashboard
                header("Location: prof_profile.php?update=success");
                exit;
            } else {
                die("Error updating record: " . $stmt->error);
            }
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    } else {
        echo "Invalid input. Please fill all fields.";
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: prof_profile.php");
    exit;
}
?>
