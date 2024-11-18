<!DOCTYPE html>
<html lang="en">
<>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UploadFile</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f6f8;
        }
        .container {
            display: flex;
            align-items: flex-start;
            width: 100%;
            max-width: 1200px;
            padding: 20px;
        }
        .sidebar {
            width: 200px;
            padding-top: 20px;
        }
        .logo {
            font-size: 1.5em;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .back-link {
            display: flex;
            align-items: center;
            font-size: 1.2em;
            color: #333;
            text-decoration: none;
            margin-top: 10px;
            padding-left: 20px;
        }
        .back-link svg {
            margin-right: 8px;
            color: #333;
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .upload-container {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            width: 100%;
            max-width: 600px;
            text-align: left;
        }
        h2 {
            margin-top: 0;
            font-size: 1.5rem;
        }
        .drag-drop-area {
            border: 2px dashed #007bff;
            border-radius: 5px;
            padding: 20px;
            cursor: pointer;
            margin: 20px 0;
            background-color: #f7f9fc;
            transition: background-color 0.2s ease;
            text-align: center;
        }
        .drag-drop-area.dragging {
            background-color: #e0f0ff;
        }
        .form-group {
            margin-bottom: 15px;
            position: relative;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .remove-button {
            position: absolute;
            right: 10px;
            top: 12px;
            cursor: pointer;
            color: red;
            font-weight: bold;
            border: none;
            background: none;
            font-size: 18px;
        }
        .add-button {
            margin: 10px 0;
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
            font-size: 0.9rem;
        }
        button {
            margin-top: 15px;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Sidebar for the Logo and Upload Link -->
    <div class="sidebar">
        <div class="logo">My School Logo</div>
        <a href="#" class="back-link">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path d="M15 18l-6-6 6-6"></path>
            </svg>
            UploadFile
        </a>
    </div>

    <!-- Main Content Area -->
    <div class="content">
        <!-- Student Enrollment List Section -->
        <div class="upload-container">
            <h2>Student Enrollment List</h2>
            <form id="studentEnrollmentForm" action="" method="POST">
                <div class="form-group">
                    <label for="enrollmentYear">Year</label>
                    <select id="enrollmentYear" name="enrollmentYear" required>
                        <option value="" disabled selected>Select Year</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="program">Program</label>
                    <select id="program" name="program" required>
                        <option value="" disabled selected>Select Program</option>
                        <option value="STEM">STEM</option>
                        <option value="ABM">ABM</option>
                        <option value="HUMSS">HUMSS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="yearLevel">Year Level</label>
                    <select id="yearLevel" name="yearLevel" required>
                        <option value="" disabled selected>Select Year Level</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="section">Section</label>
                    <input type="text" id="section" name="section" placeholder="e.g., A, B, etc." required>
                </div>
                <div class="form-group">
                    <label for="totalEnrolled">Total Enrolled Students</label>
                    <input type="number" id="totalEnrolled" name="totalEnrolled" readonly>
                </div>
                <button type="submit">Submit</button>
            </form>
        </div>

        <!-- Teacher Data Upload Section -->
        <div class="upload-container">
            <h2>Upload Faculty Historical Data</h2>
            <form id="teacherUploadForm" action="" method="POST" enctype="multipart/form-data">
                <div class="drag-drop-area" id="teacherDragDropArea">
                    Drag and Drop Excel File Here
                    <input type="file" id="teacherFileInput" name="teacherExcelFile" style="display: none;" accept=".xls,.xlsx">
                </div>
                <button type="submit" name="uploadTeacher">Upload</button>
            </form>
        </div>

        <!-- Faculty File Upload Section -->
        <div class="upload-container">
            <h2>Upload Faculty Schedule</h2>
            <form id="facultyUploadForm" action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="facultyName">Faculty Name</label>
                    <input type="text" id="facultyName" name="facultyName" required>
                </div>
                
                <!-- Subject - Section Fields -->
                <div id="subject-section-container">
                    <div class="form-group subject-section-group">
                        <label>Subject - Section</label>
                        <input type="text" name="subject_section[]" placeholder="e.g., Gen Chem - STEM 11-Y1-2" required>
                        <button type="button" class="remove-button" onclick="removeElement(this)">×</button>
                    </div>
                </div>
                <div class="add-button" onclick="addSubjectSection()">+ Add Another Subject - Section</div>
                
                <!-- Day - Time Fields -->
                <div id="day-time-container">
                    <div class="form-group day-time-group">
                        <label>Day - Time</label>
                        <input type="text" name="day_time[]" placeholder="e.g., Monday - 1:30 - 4:30" required onchange="calculateTotalHours()">
                        <button type="button" class="remove-button" onclick="removeElement(this)">×</button>
                    </div>
                </div>
                <div class="add-button" onclick="addDayTime()">+ Add Another Day - Time</div>
                
                <!-- Total Hours -->
                <div class="form-group">
                    <label for="totalHours">Total Hours</label>
                    <input type="text" id="totalHours" name="totalHours" readonly>
                </div>
                
                <!-- Drag and Drop Area for File Upload -->
                <div class="drag-drop-area" id="facultyDragDropArea">
                    Drag and Drop Excel File Here
                    <input type="file" id="facultyFileInput" name="facultyExcelFile" style="display: none;" accept=".xls,.xlsx">
                </div>
                
                <button type="submit" name="uploadFaculty">Upload</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Drag and Drop Setup
    const teacherDragDropArea = document.getElementById('teacherDragDropArea');
    const teacherFileInput = document.getElementById('teacherFileInput');
    const facultyDragDropArea = document.getElementById('facultyDragDropArea');
    const facultyFileInput = document.getElementById('facultyFileInput');

    teacherDragDropArea.addEventListener('click', () => teacherFileInput.click());
    facultyDragDropArea.addEventListener('click', () => facultyFileInput.click());

    teacherDragDropArea.addEventListener('dragover', (event) => {
        event.preventDefault();
        teacherDragDropArea.classList.add('dragging');
    });
    facultyDragDropArea.addEventListener('dragover', (event) => {
        event.preventDefault();
        facultyDragDropArea.classList.add('dragging');
    });

    teacherDragDropArea.addEventListener('dragleave', () => teacherDragDropArea.classList.remove('dragging'));
    facultyDragDropArea.addEventListener('dragleave', () => facultyDragDropArea.classList.remove('dragging'));

    teacherDragDropArea.addEventListener('drop', handleFileDrop.bind(null, teacherFileInput));
    facultyDragDropArea.addEventListener('drop', handleFileDrop.bind(null, facultyFileInput));

    function handleFileDrop(input, event) {
        event.preventDefault();
        event.target.classList.remove('dragging');
        input.files = event.dataTransfer.files;
    }

    // Add and Remove Elements
    function addSubjectSection() {
        const container = document.getElementById('subject-section-container');
        const newSection = document.createElement('div');
        newSection.className = 'form-group subject-section-group';
        newSection.innerHTML = `
            <label>Subject - Section</label>
            <input type="text" name="subject_section[]" placeholder="e.g., Gen Chem - STEM 11-Y1-2" required>
            <button type="button" class="remove-button" onclick="removeElement(this)">×</button>
        `;
        container.appendChild(newSection);
    }

    function addDayTime() {
        const container = document.getElementById('day-time-container');
        const newDayTime = document.createElement('div');
        newDayTime.className = 'form-group day-time-group';
        newDayTime.innerHTML = `
            <label>Day - Time</label>
            <input type="text" name="day_time[]" placeholder="e.g., Monday - 1:30 - 4:30" required>
            <button type="button" class="remove-button" onclick="removeElement(this)">×</button>
        `;
        container.appendChild(newDayTime);
    }

    function removeElement(button) {
        button.parentElement.remove();
    }

    function calculateTotalHours() {
        // Implement your logic for calculating total hours here
    }
</script>

</body>
</html>
