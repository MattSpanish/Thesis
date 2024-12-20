<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "register");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch time_logs data
$sql = "SELECT fullname, time_in, time_out, TIMESTAMPDIFF(HOUR, time_in, time_out) AS total_hours, created_at FROM time_logs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Time Logs Record</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #E3EED4; /* Light Accent */
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #375534;
        }

        .filter-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter-bar input, .filter-bar button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .filter-bar button {
            background-color: #375534;
            color: white;
            cursor: pointer;
        }

        .filter-bar button:hover {
            background-color: #0F2A1D;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }

        table th {
            background-color: #375534;
            color: white;
        }

        table .no-records {
            text-align: center;
            font-style: italic;
            color: #555;
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
    <a href="../Employees.php" class="back-arrow">
        <i class="fa-solid fa-arrow-left"></i>
    </a>

    <div class="container">
        <div class="header">
            <h1>Faculty Time Logs</h1>
        </div>

        <div class="filter-bar">
            <input type="text" id="filter-name" placeholder="Search by Full Name">
            <input type="date" id="filter-date-start">
            <input type="date" id="filter-date-end">
            <button id="search-button">Search</button>
            <button id="reset-button">Reset</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Total Hours</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody id="time-logs-body">
                <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["fullname"] . "</td>";
                            echo "<td>" . $row["time_in"] . "</td>";
                            echo "<td>" . $row["time_out"] . "</td>";
                            echo "<td>" . $row["total_hours"] . "</td>";
                            echo "<td>" . $row["created_at"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='no-records'>No records found</td></tr>";
                    }

                    $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
