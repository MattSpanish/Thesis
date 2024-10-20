<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <title>Employee Management</title>
    <style>
        .container {
            margin-top: 20px;
        }
        .card {
            border: none;
            transition: 0.3s;
            cursor: pointer;
            margin: 10px 0;
        }
        .card:hover {
            background-color: #28a745;
            color: white;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
        }
        .card-icon {
            font-size: 40px;
            color: #28a745;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .back-link {
            font-size: 30px;
            text-decoration: none;
            color: #000;
            margin-right: 10px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .logo {
            height: 50px;
        }
        @media (max-width: 768px) {
            .card-title {
                font-size: 16px;
            }
            .card-icon {
                font-size: 35px;
            }
        }
        @media (max-width: 576px) {
            .card-title {
                font-size: 14px;
            }
            .card-icon {
                font-size: 30px;
            }
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
            .logo {
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <div class="header">
            <a href="hr_dashboard.php" class="back-link">
                <i class="fas fa-arrow-left"></i> <!-- Font Awesome back arrow -->
            </a>
            <img src="../signin&signout/assets1/img/logo.png" alt="MindVenture Logo" class="logo"> <!-- Logo -->
        </div>
        <h1>Employees</h1>
        <div class="row mt-5">
            <div class="col-12 col-md-6 mb-3">
                <a href="../Time/time.php" class="card p-4 text-decoration-none">
                    <div class="card-body">
                        <div class="card-title">
                            <span class="card-icon">💼</span> Time Tracking
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <a href="../Employees/index.php" class="card p-4 text-decoration-none">
                    <div class="card-body">
                        <div class="card-title">
                            <span class="card-icon">📄</span> EMPLOYEE RECORDS
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
