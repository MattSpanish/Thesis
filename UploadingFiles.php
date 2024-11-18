<!DOCTYPE html>
<html lang="en">
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;

        }
        .container {
            display: flex;
            align-items: flex-start;
            width: 100%;
            max-width: 1200px;
            padding: 20px;
        }
        .sidebar {
            width: 220px;
            padding: 20px;
        }
        .sidebar .back-link {
            display: flex;
            align-items: center;
            font-size: 1.3em;
            color: #0F2A1D;
            text-decoration: none;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .sidebar .back-link:hover {
            background-color: #AEC3B0;
        }
        .sidebar .back-link svg {
            margin-right: 10px;
            color: #0F2A1D;
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }
        .upload-container {
            background: #AEC3B0;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            margin-top: 20px;
            width: 100%;
            max-width: 1000px;
            text-align: left;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .upload-container:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }
        h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #0F2A1D;
        }
        .drag-drop-area {
            border: 3px dashed #375534;
            border-radius: 10px;
            padding: 25px;
            cursor: pointer;
            background: #6B9071;
            transition: all 0.3s ease;
            text-align: center;
            color: #0F2A1D;
        }
        .drag-drop-area:hover {
            background: #375534;
            border-color: #0F2A1D;
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-group label {
            display: block;
            font-size: 1rem;
            font-weight: 600;
            color: #375534;
            margin-bottom: 8px;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #6B9071;
            border-radius: 6px;
            font-size: 1rem;
            background-color: #E3EED4;
            transition: border-color 0.3s ease;
            color: #0F2A1D;
        }
        .form-group input:focus,
        .form-group select:focus {
            border-color: #375534;
            outline: none;
        }
        .remove-button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #ff4d4d;
            background: none;
            border: none;
            font-size: 20px;
        }
        .add-button {
            margin: 10px 0;
            display: inline-block;
            color: #375534;
            text-decoration: none;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.3s ease;
        }
        .add-button:hover {
            color: #0F2A1D;
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
    </style>

</head>
<body>

<div class="container">
    <!-- Sidebar for the Logo and Upload Link -->
    <div class="sidebar">
        <a href="hr_dashboard.php" class="back-link">
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
