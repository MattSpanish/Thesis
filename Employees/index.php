<?php
// Include database connection
include 'db.php';

// Fetch employee records from the database
$sql = "SELECT * FROM employees";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Records</title>
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

        .status-full-time {
            color: green;
            font-weight: bold;
        }

        .status-part-time {
            color: red;
            font-weight: bold;
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

        <h2 class="mb-4">Employee Records</h2>
        <a href="add_employee.php" class="btn btn-primary mb-3">Add Employee</a>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Employee</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Subjects</th> <!-- New Column for Subjects -->
                        <th>Gender</th> <!-- New Column for Gender -->
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                            <td><?php echo htmlspecialchars($row['subjects']); ?></td> <!-- Display Subjects -->
                            <td><?php echo htmlspecialchars($row['gender']); ?></td> <!-- Display Gender -->
                            <td class="<?php echo $row['status'] == 'FULL TIME' ? 'status-full-time' : 'status-part-time'; ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </td>
                            <td>
                                <a href="edit_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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
