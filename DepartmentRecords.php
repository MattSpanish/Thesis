<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section Records</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-text {
            font-size: 18px;
            color: green;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .custom-search {
            position: relative;
            flex: 1;
        }
        .custom-search input {
            padding-left: 40px;
            width: 60%;
            height: 45px;
        }
        .custom-search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
        }
        .dropdown {
            width: 150px;
            height: 45px;
        }
        .back-arrow {
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        .full-width-container {
            max-width: 100%;
            padding: 0 15px;
        }
        .back-arrow-icon {
            width: 24px;
            height: 24px;
        }
    </style>
</head>
<body>
    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "enrollment_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle search and filter
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $year = isset($_GET['year']) ? $_GET['year'] : '';
    $section = isset($_GET['section']) ? $_GET['section'] : '';
    $program = isset($_GET['program']) ? $_GET['program'] : '';

    $sql = "SELECT * FROM student_records WHERE 1";

    if (!empty($search)) {
        $sql .= " AND section LIKE '%" . $conn->real_escape_string($search) . "%'";
    }
    if (!empty($year)) {
        $sql .= " AND year = '" . $conn->real_escape_string($year) . "'";
    }
    if (!empty($section)) {
        $sql .= " AND section = '" . $conn->real_escape_string($section) . "'";
    }
    if (!empty($program)) {
        $sql .= " AND program = '" . $conn->real_escape_string($program) . "'";
    }

    $result = $conn->query($sql);
    
    // Fetch all available sections for dropdown
    $sectionsResult = $conn->query("SELECT DISTINCT section FROM student_records");

    // Fetch all available programs for dropdown
    $programsResult = $conn->query("SELECT DISTINCT program FROM student_records");
    ?>

    <div class="container full-width-container mt-5">
        <!-- Back Button -->
        <div class="d-flex align-items-center mb-3">
            <a href="javascript:void(0);" class="back-arrow" onclick="goBack()">
                <img src="https://img.icons8.com/?size=100&id=70778&format=png&color=1A1A1A" alt="Back Arrow" class="back-arrow-icon">
            </a>
            <span class="ml-2">SECTION RECORDS</span>
        </div>

        <!-- Search and Filter Dropdowns -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form method="GET" class="d-flex align-items-center">
                <div class="custom-search">
                    <img src="https://img.icons8.com/ios-filled/50/000000/search.png" class="custom-search-icon" alt="Search Icon">
                    <input type="text" name="search" class="form-control" placeholder="Section Name" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                
                <select name="year" class="form-control year-dropdown ml-3">
                    <option value="">Year</option>
                    <option value="2024-2025" <?php echo ($year == "2024-2025") ? 'selected' : ''; ?>>2024-2025</option>
                    <option value="2023-2024" <?php echo ($year == "2023-2024") ? 'selected' : ''; ?>>2023-2024</option>
                    <option value="2022-2023" <?php echo ($year == "2022-2023") ? 'selected' : ''; ?>>2022-2023</option>
                    <option value="2021-2022" <?php echo ($year == "2021-2022") ? 'selected' : ''; ?>>2021-2022</option>
                    <option value="2021" <?php echo ($year == "2021") ? 'selected' : ''; ?>>2021</option>
                </select>

                <select name="section" class="form-control section-dropdown ml-3">
                    <option value="">Select Section</option>
                    <?php while ($row = $sectionsResult->fetch_assoc()): ?>
                        <option value="<?php echo $row['section']; ?>" <?php echo ($section == $row['section']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['section']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <select name="program" class="form-control program-dropdown ml-3">
                    <option value="">Select Program</option>
                    <?php while ($row = $programsResult->fetch_assoc()): ?>
                        <option value="<?php echo $row['program']; ?>" <?php echo ($program == $row['program']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['program']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit" class="btn btn-primary ml-3">Search</button>
            </form>
        </div>

        <!-- Table Header and Data -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="header-text">Name of Section</div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Section / Program</th>
                            <th>Total Students</th>
                            <th>Year</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['section'] . " / " . $row['program']); ?></td>
                                    <td><?php echo htmlspecialchars($row['total']); ?></td>
                                    <td><?php echo htmlspecialchars($row['year']); ?></td>
                                    
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript function to handle back navigation
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>
