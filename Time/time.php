<?php
// Include database connection
include '../Employees/db.php';

// Fetch employee records from the database
$sql = "SELECT * FROM employees";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Tracking</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="timestyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>

<div class="container-fluid mt-5">
    <div class="header-container">
        <a href="../Employees.php" class="back-link">
            <i class="fas fa-arrow-left"></i>
        </a>
        <a class="navbar-brand brand-logo" href="../Employees.php">
            <img src="../signin&signout/assets1/img/logo.png" alt="logo" />
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
        <label for="timePeriod">Time Period :</label>
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
                        <td class="employee-name"><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['name']); ?></td>
                        <td><input type="number" class="hours-input" id="regular-<?php echo $row['id']; ?>" placeholder="0" oninput="calculateTotal(<?php echo $row['id']; ?>)"></td>
                        <td><input type="number" class="hours-input" id="overtime-<?php echo $row['id']; ?>" placeholder="0" oninput="calculateTotal(<?php echo $row['id']; ?>)"></td>
                        <td><input type="number" class="hours-input" id="sick-<?php echo $row['id']; ?>" placeholder="0" oninput="calculateTotal(<?php echo $row['id']; ?>)"></td>
                        <td><input type="number" class="hours-input" id="pto-<?php echo $row['id']; ?>" placeholder="0" oninput="calculateTotal(<?php echo $row['id']; ?>)"></td>
                        <td><input type="number" class="hours-input" id="holiday-<?php echo $row['id']; ?>" placeholder="0" oninput="calculateTotal(<?php echo $row['id']; ?>)"></td>
                        <td id="total-<?php echo $row['id']; ?>">0</td>
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

        // Function to filter employee results
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

        // Event listener for search input
        searchInput.addEventListener("input", filterResults);
        clearButton.addEventListener("click", function() {
            searchInput.value = "";
            filterResults();
        });

        // Function to update time period based on selected dates
        function updateTimePeriod() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            if (!isNaN(startDate) && !isNaN(endDate) && startDate <= endDate) {
                const options = { month: 'long', day: 'numeric', year: 'numeric' };
                const startDateFormatted = startDate.toLocaleDateString(undefined, options);
                const endDateFormatted = endDate.toLocaleDateString(undefined, options);
                timePeriodInput.value = `${startDateFormatted} - ${endDateFormatted}`;
            } else {
                timePeriodInput.value = ''; // Clear the field if dates are invalid
            }
        }

        // Event listeners for date inputs
        startDateInput.addEventListener("change", updateTimePeriod);
        endDateInput.addEventListener("change", updateTimePeriod);
    });

    // Function to calculate total hours
    function calculateTotal(employeeId) {
        const regular = parseFloat(document.getElementById(`regular-${employeeId}`).value) || 0;
        const overtime = parseFloat(document.getElementById(`overtime-${employeeId}`).value) || 0;
        const sick = parseFloat(document.getElementById(`sick-${employeeId}`).value) || 0;
        const pto = parseFloat(document.getElementById(`pto-${employeeId}`).value) || 0;
        const holiday = parseFloat(document.getElementById(`holiday-${employeeId}`).value) || 0;
        
        const total = regular + overtime + sick + pto + holiday;
        document.getElementById(`total-${employeeId}`).textContent = total.toFixed(2);
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
