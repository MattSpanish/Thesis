<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Records</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-text {
            font-weight: bold;
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
        .year-dropdown {
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
    <div class="container full-width-container mt-5">
        <!-- Back Button -->
        <div class="d-flex align-items-center mb-3">
            <a href="javascript:void(0);" class="back-arrow" onclick="goBack()">
                <img src="https://img.icons8.com/?size=100&id=70778&format=png&color=1A1A1A" alt="Back Arrow" class="back-arrow-icon">
            </a>
            <span class="ml-2">DEPARTMENT RECORDS</span>
        </div>

        <!-- Search and Year Dropdown -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="custom-search">
                <img src="https://img.icons8.com/ios-filled/50/000000/search.png" class="custom-search-icon" alt="Search Icon">
                <input type="text" class="form-control" placeholder="Department Name">
            </div>
            <select class="form-control year-dropdown ml-3">
                <option>Year</option>
    
                <!-- Add year options here -->
            </select>
        </div>

        <!-- Table Header and Button -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="header-text">Name of Department</div>
                    <!-- Dropdown button for SECTION -->
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="sectionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            SECTION
                        </button>
                        <div class="dropdown-menu" aria-labelledby="sectionDropdown">
                            <a class="dropdown-item" href="#">Option 1</a>
                            <a class="dropdown-item" href="#">Option 2</a>
                            <a class="dropdown-item" href="#">Option 3</a>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Section / Strand</th>
                            <th>Total Class</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data rows can be added here -->
                        <tr>
                            <td colspan="2" style="height: 200px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>