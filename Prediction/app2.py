from flask import Flask, request, jsonify
from flask_cors import CORS
import math
import os

# Initialize Flask app and apply CORS for cross-origin requests
app2 = Flask(__name__)
CORS(app2, resources={r"/*": {"origins": "*"}})  # Allow all origins for testing

# Configuration for critical ratios
MIN_CRITICAL_RATIO = int(os.getenv('MIN_CRITICAL_RATIO', 30))
MAX_CRITICAL_RATIO = int(os.getenv('MAX_CRITICAL_RATIO', 35))

def validate_inputs(data):
    """Validates the input data."""
    required_keys = ['student_count', 'limit_per_teacher', 'subjects', 'max_workload', 'current_teachers']
    for key in required_keys:
        if key not in data:
            raise ValueError(f"Missing key: {key}")
    inputs = {key: int(data.get(key)) for key in required_keys}
    if any(value <= 0 for value in inputs.values()):
        raise ValueError("All inputs must be positive integers.")
    return inputs

def calculate_teachers(student_count, limit_per_teacher, subjects, max_workload):
    """Calculates the number of teachers required based on student count and workload."""
    teachers_for_students = math.ceil(student_count / limit_per_teacher)
    teachers_for_workload = math.ceil(subjects / max_workload)
    return max(teachers_for_students, teachers_for_workload), teachers_for_students

def calculate_notifications(student_count, teachers_needed, teachers_for_students, subjects, current_teachers):
    """Calculates notifications based on various conditions."""
    notifications = []
    current_ratio = student_count / teachers_needed

    # Overcrowding or underutilization notification
    if current_ratio > MAX_CRITICAL_RATIO:
        additional_teachers = math.ceil(student_count / MAX_CRITICAL_RATIO) - teachers_needed
        notifications.append({
            "type": "overcrowding",
            "message": f"Overcrowding detected: The student-to-teacher ratio is {current_ratio:.2f}, which exceeds the maximum critical threshold of {MAX_CRITICAL_RATIO}.",
            "additional_teachers_needed": additional_teachers
        })
    elif current_ratio < MIN_CRITICAL_RATIO:
        notifications.append({
            "type": "underutilization",
            "message": f"Underutilization detected: The student-to-teacher ratio is {current_ratio:.2f}, which is below the minimum critical threshold of {MIN_CRITICAL_RATIO}."
        })

    # Workload notification (if teachers are overloaded)
    if teachers_needed > teachers_for_students:
        additional_teachers = teachers_needed - teachers_for_students
        notifications.append({
            "type": "workload",
            "message": "Teachers are overloaded. Additional teachers are required to handle the subjects.",
            "additional_teachers_needed": additional_teachers
        })

    # Hiring notification (if current teachers are insufficient)
    if teachers_needed > current_teachers:
        additional_teachers_needed = teachers_needed - current_teachers
        notifications.append({
            "type": "hiring",
            "message": f"Additional teachers required. Current teachers are insufficient to meet the demand. {additional_teachers_needed} more teachers are needed.",
            "additional_teachers_needed": additional_teachers_needed
        })

    return notifications

@app2.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.json
        inputs = validate_inputs(data)

        student_count = inputs['student_count']
        limit_per_teacher = inputs['limit_per_teacher']
        subjects = inputs['subjects']
        max_workload = inputs['max_workload']
        current_teachers = inputs['current_teachers']

        # Calculate number of teachers required and subjects per teacher
        teachers_needed, teachers_for_students = calculate_teachers(
            student_count, limit_per_teacher, subjects, max_workload
        )
        subjects_per_teacher = math.ceil(subjects / teachers_needed)

        # Calculate notifications for overcrowding, underutilization, workload, and hiring needed
        notifications = calculate_notifications(
            student_count, teachers_needed, teachers_for_students, subjects, current_teachers
        )

        # Calculate utilization rate (use the MAX_CRITICAL_RATIO as a baseline for utilization)
        utilization_rate = round(student_count / (teachers_needed * MAX_CRITICAL_RATIO), 2)

        # Return the results
        return jsonify({
            "teachers_needed": teachers_needed,
            "subjects_per_teacher": subjects_per_teacher,
            "utilization_rate": utilization_rate,
            "notifications": notifications
        })

    except ValueError as ve:
        return jsonify({"error": str(ve)}), 400
    except Exception as e:
        return jsonify({"error": f"An unexpected error occurred: {str(e)}"}), 500

if __name__ == '__main__':
    app2.run(debug=True, port=5001)
