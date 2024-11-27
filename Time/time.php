<?php
// Include database connection
include '../Employees/db.php';

// Fetch employee records from the database
$sql = "
    SELECT 
        e.id AS employee_id,
        e.name,
        tt.date_added,
        tt.regular,
        tt.overtime,
        tt.sick_leave,
        tt.pto,
        tt.paid_holiday
    FROM employees e
    LEFT JOIN time_tracking tt ON e.id = tt.employee_id
";
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form inputs
    $employee_id = $_POST['employee_id'];
    $regular = $_POST['regular'];
    $overtime = $_POST['overtime'];
    $sick_leave = $_POST['sick_leave'];
    $pto = $_POST['pto'];
    $paid_holiday = $_POST['paid_holiday'];
    
    // Get the current date
    $date_added = date('Y-m-d'); // Set the date to today

    // Prepare the insert query
    $insert_sql = "INSERT INTO time_tracking (employee_id, regular, overtime, sick_leave, pto, paid_holiday, date_added)
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare and bind the statement
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("iiiiiii", $employee_id, $regular, $overtime, $sick_leave, $pto, $paid_holiday, $date_added);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Time tracking entry added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Tracking</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Time/timestyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="container-fluid mt-5">
    <div class="header-container">
        <a href="../Employees.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
        </a>

    </div>
    <div>
        <h1>Time Tracking</h1>
    </div>

    <div class="form-group">
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="timePeriod">Time Period:</label>
        <input type="text" id="timePeriod" class="form-control" readonly>
    </div>

    <div class="input-group mb-3">
        <input id="searchInput" type="text" class="form-control" placeholder="Search Employee">
        <div class="input-group-append">
            <button id="clearButton" class="btn btn-outline-secondary" type="button">&times;</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date Added</th>
                    <th>Employee Name</th>
                    <th>Regular</th>
                    <th>Overtime</th>
                    <th>Sick Leave</th>
                    <th>PTO</th>
                    <th>Paid Holiday</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="employee">
                        <td><?php echo htmlspecialchars($row['date_added'] ?? 'N/A'); ?></td>
                        <td class="employee-name"><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['regular']); ?></td>
                        <td><?php echo htmlspecialchars($row['overtime']); ?></td>
                        <td><?php echo htmlspecialchars($row['sick_leave']); ?></td>
                        <td><?php echo htmlspecialchars($row['pto']); ?></td>
                        <td><?php echo htmlspecialchars($row['paid_holiday']); ?></td>
                        <td>
                            <?php
                            // Calculate total hours and display
                            $total = ($row['regular'] ?? 0) + ($row['overtime'] ?? 0) + ($row['sick_leave'] ?? 0) + ($row['pto'] ?? 0) + ($row['paid_holiday'] ?? 0);
                            echo number_format($total, 2);
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        const clearButton = document.getElementById("clearButton");
        const startDateInput = document.getElementById("startDate");
        const endDateInput = document.getElementById("endDate");
        const timePeriodInput = document.getElementById("timePeriod");

        function filterResults() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            const employeeList = document.querySelectorAll('.employee-name');
            employeeList.forEach(function(employee) {
                const name = employee.textContent.trim().toLowerCase();
                if (name.includes(searchTerm)) {
                    employee.closest('.employee').style.display = '';
                } else {
                    employee.closest('.employee').style.display = 'none';
                }
            });
        }

        searchInput.addEventListener("input", filterResults);
        clearButton.addEventListener("click", function() {
            searchInput.value = "";
            filterResults();
        });

        function updateTimePeriod() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            if (!isNaN(startDate) && !isNaN(endDate) && startDate <= endDate) {
                const options = { month: 'long', day: 'numeric', year: 'numeric' };
                const startDateFormatted = startDate.toLocaleDateString(undefined, options);
                const endDateFormatted = endDate.toLocaleDateString(undefined, options);
                timePeriodInput.value = `${startDateFormatted} - ${endDateFormatted}`;
            } else {
                timePeriodInput.value = '';
            }
        }

        startDateInput.addEventListener("change", updateTimePeriod);
        endDateInput.addEventListener("change", updateTimePeriod);
    });
</script>

</body>
</html>

<?php
$conn->close();
?>
