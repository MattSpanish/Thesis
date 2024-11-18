<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["login"]) || !$_SESSION["login"]) {
    header("Location: ../signin&signout/LoginPage.php");
    exit;
}

$user_id = $_SESSION["id"];
require '../signin&signout/config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    // Handle the file upload
    $target_dir = "..//Faculty/uploads/";
    $file_name = basename($_FILES["profile_pic"]["name"]);
    $target_file = $target_dir . $file_name;
    $upload_ok = true;

    // Check file size (5MB limit)
    if ($_FILES["profile_pic"]["size"] > 5000000) {
        echo "Error: File is too large.";
        $upload_ok = false;
    }

    // Allow only certain file formats
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "Error: Only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = false;
    }

    // Upload file if valid
    if ($upload_ok && move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        // Update database
        $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
        $stmt->bind_param("si", $file_name, $user_id);

        if ($stmt->execute()) {
            echo "Profile picture updated successfully!";
            header("Location: ../Faculty/profDASHBOARD.php"); // Redirect to refresh the dashboard
            exit;
        } else {
            echo "Error updating profile picture: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
