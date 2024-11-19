<?php
// Include the database connection
require '../signin&signout/config.php';

// Check if the schedule ID is passed via GET
if (isset($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];

    // Delete the schedule from the database
    $delete_sql = "DELETE FROM schedule WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $schedule_id);
    $delete_stmt->execute();

    // Redirect back to the dashboard or schedule page
    header("Location: ../Faculty/prof_profile.php");
    exit;
} else {
    echo "Schedule ID not provided.";
    exit;
}
?>
