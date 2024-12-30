<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <title>Teacher Workload Prediction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        main {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .notification {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid;
        }

        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .notification.success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .notification.info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
        }

        .notification.warning {
            background-color: #fff3cd;
            color: #856404;
            border-color: #ffeeba;
        }

        .results {
            margin-top: 20px;
        }
        button {
            margin-top: 20px;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: bold;
            color: white;
            background: #375534;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background: #0F2A1D;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background-color: #375534;
            color: white;
            border-radius: 8px;
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
        .back-button i {
            font-size: 18px;
        }
    </style>
    <script>
        async function handleSubmit(event) {
            event.preventDefault(); // Prevent default form submission

            const apiUrl = 'http://127.0.0.1:5001/predict'; // API endpoint

            // Collect input values
            const studentCount = document.getElementById('student_count').value;
            const limitPerTeacher = document.getElementById('limit_per_teacher').value;
            const subjects = document.getElementById('subjects').value;
            const maxWorkload = document.getElementById('max_workload').value;
            const currentTeachers = document.getElementById('current_teachers').value;

            // Simple validation
            if (!studentCount || !limitPerTeacher || !subjects || !maxWorkload || !currentTeachers) {
                displayNotification('All fields are required.', 'error');
                return;
            }

            if ([studentCount, limitPerTeacher, subjects, maxWorkload, currentTeachers].some(value => value <= 0 || isNaN(value))) {
                displayNotification('All inputs must be positive integers.', 'error');
                return;
            }

            const data = {
                student_count: parseInt(studentCount),
                limit_per_teacher: parseInt(limitPerTeacher),
                subjects: parseInt(subjects),
                max_workload: parseInt(maxWorkload),
                current_teachers: parseInt(currentTeachers)
            };

            try {
                // Send request to the API
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                // Handle API errors
                if (!response.ok) {
                    displayNotification(result.error || 'An unexpected error occurred.', 'error');
                    return;
                }

                // Display results
                displayResults(result);

            } catch (error) {
                console.error('Error:', error);
                displayNotification('Failed to connect to the API. Please check your network and try again.', 'error');
            }
        }

        function displayResults(result) {
            let resultHTML = `
                <h2>Results:</h2>
                <p><strong>Teachers Needed:</strong> ${result.teachers_needed}</p>
                <p><strong>Subjects Per Teacher:</strong> ${result.subjects_per_teacher}</p>
                <p><strong>Utilization Rate:</strong> ${(result.utilization_rate * 100).toFixed(2)}%</p>
            `;

            // Check if there are notifications and display them accordingly
            if (result.notifications && result.notifications.length > 0) {
                resultHTML += '<h3>Notifications:</h3>';

<<<<<<< Updated upstream
                result.notifications.forEach(notification => {
                    switch (notification.type) {
                        case 'overcrowding':
                            resultHTML += `
                                <div class="notification error">
                                    <p>${notification.message}</p>
                                    ${notification.additional_teachers_needed ? `<p><strong>Additional Teachers Needed:</strong> ${notification.additional_teachers_needed}</p>` : ''}
                                </div>`;
                            break;
                        case 'underutilization':
                            resultHTML += `
                                <div class="notification info">
                                    <p>${notification.message}</p>
                                </div>`;
                            break;
                        case 'workload':
                            resultHTML += `
                                <div class="notification warning">
                                    <p>${notification.message}</p>
                                    ${notification.additional_teachers_needed ? `<p><strong>Additional Teachers Needed:</strong> ${notification.additional_teachers_needed}</p>` : ''}
                                </div>`;
                            break;
                        case 'teacher_overload': // Handle teacher overload
                            resultHTML += `
                                <div class="notification error">
                                    <p>${notification.message}</p>
                                    <p><strong>Suggested Action:</strong> Reduce workload or hire additional teachers.</p>
                                </div>`;
                            break;
                        case 'hiring':
                            resultHTML += `
                                <div class="notification info">
                                    <p>${notification.message}</p>
                                </div>`;
                            break;
                        case 'sufficient_teachers':
                            resultHTML += `
                                <div class="notification success">
                                    <p>${notification.message}</p>
                                </div>`;
                            break;
                        default:
                            break;
                    }
                });
=======
        result.notifications.forEach(notification => {
            switch (notification.type) {
                case 'overcrowding':
                    resultHTML += `
                        <div class="notification error">
                            <p>${notification.message}</p>
                            ${notification.additional_teachers_needed ? `<p><strong>Additional Teachers Needed:</strong> ${notification.additional_teachers_needed}</p>` : ''}
                        </div>`;
                    break;
                case 'underutilization':
                    resultHTML += `
                        <div class="notification info">
                            <p>${notification.message}</p>
                        </div>`;
                    break;
                case 'workload':
                    resultHTML += `
                        <div class="notification warning">
                            <p>${notification.message}</p>
                            ${notification.additional_teachers_needed ? `<p><strong>Additional Teachers Needed:</strong> ${notification.additional_teachers_needed}</p>` : ''}
                        </div>`;
                    break;
                case 'hiring':
                    resultHTML += `
                        <div class="notification info">
                            <p>${notification.message}</p>
                        </div>`;
                    break;
                case 'sufficient_teachers': // Case for sufficient teachers
                    resultHTML += `
                        <div class="notification success">
                            <p>${notification.message}</p>
                        </div>`;
                    break;
                default:
                    break;
>>>>>>> Stashed changes
            }

<<<<<<< Updated upstream
            document.getElementById('results').innerHTML = resultHTML;
        }
=======

<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======

>>>>>>> Stashed changes
=======

>>>>>>> Stashed changes
=======

>>>>>>> Stashed changes
    </script>
</head>
<body>
    <header>
        <h1>Teacher Workload Prediction</h1>
    </header>

    <!-- Back Button -->
    <a href="hr_dashboard.php" class="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>

    <main>
        <form onsubmit="handleSubmit(event)">
            <label for="student_count">Number of Students:</label>
            <input type="number" id="student_count" name="student_count" min="1" required>

            <label for="limit_per_teacher">Student Limit per Teacher:</label>
            <input type="number" id="limit_per_teacher" name="limit_per_teacher" min="1" required>

            <label for="subjects">Number of Subjects:</label>
            <input type="number" id="subjects" name="subjects" min="1" required>

            <label for="max_workload">Maximum Workload per Teacher:</label>
            <input type="number" id="max_workload" name="max_workload" min="1" required>

            <label for="current_teachers">Current Number of Teachers:</label>
            <input type="number" id="current_teachers" name="current_teachers" min="1" required>

            <button type="submit">Predict</button>
        </form>

        <div id="notification"></div>
        <div id="results" class="results"></div>
    </main>
</body>
</html>
