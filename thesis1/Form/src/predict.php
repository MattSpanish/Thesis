<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $students = intval($_POST["students"]);
    $subjects = intval($_POST["subjects"]);
    $average_class_size = intval($_POST["average_class_size"]);

    // Ensure the inputs are valid positive integers
    if ($students > 0 && $subjects > 0 && $average_class_size > 0) {
        // Run the Python script with the number of students, subjects, and average class size as arguments
        $command = escapeshellcmd("python3 predict_professors.py " . $students . " " . $subjects . " " . $average_class_size);
        shell_exec($command);

        // Read the prediction result from the text file
        $prediction = file_get_contents('prediction.txt');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Allocation Forecast</title>
</head>
<body>
    <h1>Predicted Number of Professors Needed</h1>
    <?php if (isset($prediction)): ?>
        <p>For <?php echo htmlspecialchars($students); ?> students, <?php echo htmlspecialchars($subjects); ?> subjects, and an average class size of <?php echo htmlspecialchars($average_class_size); ?>, the predicted number of professors needed is: <?php echo htmlspecialchars($prediction); ?></p>
        <img src="regression_plot.png" alt="Regression Plot">
    <?php else: ?>
        <p>Please enter valid inputs.</p>
    <?php endif; ?>
    <a href="index.html">Go Back</a>
</body>
</html>
