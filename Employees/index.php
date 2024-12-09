<?php
// Include database connection
include 'db.php';

$servername = "localhost"; // Change to your server name
$username = "root";        // Change to your database username
$password = "";            // Change to your database password
$dbname = "enrollment_db"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Fetch employee records from the database
$sql = "SELECT id_no, last_name, first_name, middle_name, department, position, date_hired, years_of_service, ranking, status FROM historical_data";
$result = $conn->query($sql);

if (!$result) {
    die("SQL error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HISTORICAL DATA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #E3EED4; /* Light Accent */
        }

        .container {
            background-color: #FFFFFF;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1500px;
            margin-bottom: 40px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-link {
            font-size: 20px;
            text-decoration: none;
            color: #0F2A1D;
            margin-right: 10px;
        }

        .logo {
            height: 50px;
        }

        .table-responsive {
            background-color: #F8F9FA;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            border: 2px solid #AEC3B0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }

        .thead-dark {
            background-color: #375534;
            color: white;
        }

        .btn {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #375534;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0F2A1D;
        }

        .btn-warning {
            background-color: #F8C23E;
            color: white;
        }

        .btn-danger {
            background-color: #D9534F;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="header mb-4">
            <a href="../Employees.php" class="back-link">
                <i class="fas fa-arrow-left"></i>
            </a>
            <img src="../signin&signout/assets1/img/logo.png" alt="Company Logo" class="logo">
        </div>

        <h2 class="mb-4">FACULTY HISTORICAL DATA</h2>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID No.</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Date Hired</th>
                        <th>Years of Service</th>
                        <th>Ranking</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['middle_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                            <td><?php echo htmlspecialchars($row['position']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_hired']); ?></td>
                            <td><?php echo htmlspecialchars($row['years_of_service']); ?></td>
                            <td><?php echo htmlspecialchars($row['ranking']); ?></td>
                            <td style="color: <?php echo trim($row['status']) === 'ACTIVE' ? 'green' : 'red'; ?>;">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </td>
                            <td>
                                <a href="edit_employee.php?id=<?php echo $row['id_no']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_employee.php?id=<?php echo $row['id_no']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
