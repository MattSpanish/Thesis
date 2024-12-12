<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Time Tracking</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 450px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #4CAF50;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        select, input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .status {
            font-size: 1rem;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .status.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Faculty Time Tracking</h2>

        <label for="teacher-name">Select Teacher:</label>
        <select id="teacher-name">
            <option value="">-- Select Full Name --</option>
            <option value="John Doe">John Doe</option>
            <option value="Jane Smith">Jane Smith</option>
            <option value="Alice Johnson">Alice Johnson</option>
        </select>

        <label for="time-in">Time In:</label>
        <input type="time" id="time-in">

        <label for="time-out">Time Out:</label>
        <input type="time" id="time-out">

        <button onclick="trackTime()">Submit</button>

        <div class="status" id="status"></div>
    </div>

    <script>
        function trackTime() {
            const teacherName = document.getElementById('teacher-name').value;
            const timeIn = document.getElementById('time-in').value;
            const timeOut = document.getElementById('time-out').value;
            const statusDiv = document.getElementById('status');

            if (!teacherName) {
                statusDiv.textContent = "Please select a teacher.";
                statusDiv.className = 'status error';
                statusDiv.style.display = 'block';
                return;
            }

            if (!timeIn || !timeOut) {
                statusDiv.textContent = "Please enter both Time In and Time Out.";
                statusDiv.className = 'status error';
                statusDiv.style.display = 'block';
                return;
            }

            const inTime = new Date(`1970-01-01T${timeIn}:00`);
            const outTime = new Date(`1970-01-01T${timeOut}:00`);

            if (outTime <= inTime) {
                statusDiv.textContent = "Time Out must be later than Time In.";
                statusDiv.className = 'status error';
                statusDiv.style.display = 'block';
                return;
            }

            const workingHours = (outTime - inTime) / (1000 * 60 * 60);

            statusDiv.textContent = `Teacher: ${teacherName}, Hours Worked: ${workingHours.toFixed(2)} hrs.`;
            statusDiv.className = 'status success';
            statusDiv.style.display = 'block';
        }
    </script>
</body>
</html>