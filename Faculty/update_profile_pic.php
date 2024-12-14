<?php
session_start(); // Make sure the session is started
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $targetDir = "../Faculty/uploads/"; // Ensure this path is correct relative to the web root
    $fileName = basename($_FILES["profile_pic"]["name"]);
    $targetFile = $targetDir . $fileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a valid image
    if (getimagesize($_FILES["profile_pic"]["tmp_name"]) === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (5MB limit)
    if ($_FILES["profile_pic"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
    }

    // Check if upload was successful
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
            // Update the database with the new profile picture (only save the file name, not the full path)
            require '../signin&signout/config.php'; // Database connection
            $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
            $stmt->bind_param("si", $fileName, $_SESSION['id']);
            if ($stmt->execute()) {
                // Profile picture updated successfully
                $_SESSION['profile_pic'] = $fileName; // Update session to reflect the new profile picture
                // Redirect back to the profile page
                header("Location: ../Faculty/prof_profile.php");
                exit;
            } else {
                echo "Error updating profile picture in the database.";
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
