<?php
session_start();
require '../signin&signout/config.php'; // Ensure correct path for database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $strand = $_POST['strand'];
    $time = $_POST['time'];
    $days = $_POST['days'];

    // Get the user_id from the session
    $user_id = $_SESSION["id"]; // Assuming user_id is stored in the session

    // Prepare the SQL statement to include user_id and auto-increment id
    $stmt = $conn->prepare("INSERT INTO schedule (user_id, strand, time, days) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing the SQL statement: " . $conn->error);
    }

    // Bind parameters (user_id, strand, time, and days)
    $stmt->bind_param("isss", $user_id, $strand, $time, $days);

    // Execute and check for success
    if ($stmt->execute()) {
        header("Location: prof_profile.php?success=1"); // Redirect to dashboard with success message
    } else {
        die("Error executing SQL query: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: prof_profile.php?error=1"); // Redirect if method isn't POST
    exit;
}
?>
