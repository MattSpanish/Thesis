<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <title>Employee Management</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #E3EED4; /* Light Accent */
            position: relative;
        }

        .container {
            margin-top: 20px;
        }

        .card {
            border: none;
            transition: 0.3s;
            cursor: pointer;
            margin: 10px 0;
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            background-color: #28a745;
            color: white;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #375534; /* Dark Green */
        }

        .card-icon {
            font-size: 40px;
            color: #28a745;
        }

        /* Align back button to the left */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background-color: #375534;
            color: white;
            border-radius: 8px; /* Rounded corners */
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: 0.3s;
        }

        .back-button:hover {
            background-color: #2a442e;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
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
        }
    </style>
</head>
<body>
    <a href="hr_dashboard.php" class="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>
    
    <div class="container text-center">
        <h1>Employees</h1>
        <div class="row mt-5">
            <div class="col-12 col-md-6 mb-3">
                <a href="../time.php" class="card p-4 text-decoration-none">
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
                            <span class="card-icon">📄</span> FACULTY HISTORICAL DATA
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

