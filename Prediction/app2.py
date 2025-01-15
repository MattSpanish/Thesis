from flask import Flask, request, jsonify
from flask_cors import CORS
import math
import os
import logging
import numpy as np

# Configure logging
logging.basicConfig(level=logging.DEBUG)

# Initialize Flask app and apply CORS for cross-origin requests
app2 = Flask(__name__)
CORS(app2, resources={r"/*": {"origins": "*"}})

# Configuration for critical ratios
MIN_CRITICAL_RATIO = int(os.getenv('MIN_CRITICAL_RATIO', 30))
MAX_CRITICAL_RATIO = int(os.getenv('MAX_CRITICAL_RATIO', 35))

# Custom Linear Regression Model with predefined coefficients and intercept
class CustomLinearRegression:
    def __init__(self):
        # Predefined coefficients and intercept for simulation
        self.coefficients = [0.3, 0.2, 0.1, 0.4]
        self.intercept = 5

    def predict(self, features):
        """
        Predicts output based on the linear regression formula:
        y = intercept + sum(coefficients[i] * features[i])
        """
        prediction = self.intercept + np.dot(features, self.coefficients)
        return [max(0, math.ceil(prediction))]  # Non-negative, rounded up

# Initialize the custom model
custom_model = CustomLinearRegression()

def validate_inputs(data):
    """Validates the input data."""
    required_keys = ['student_count', 'limit_per_teacher', 'subjects', 'max_workload', 'current_teachers']
    for key in required_keys:
        if key not in data:
            raise ValueError(f"Missing key: {key}")

    try:
        inputs = {key: int(data[key]) for key in required_keys}
    except ValueError:
        raise ValueError("All inputs must be valid integers.")
    
    if any(value <= 0 for value in inputs.values()):
        raise ValueError("All inputs must be positive integers.")

    return inputs

def calculate_teachers(student_count, limit_per_teacher, subjects, max_workload):
    """Calculates the number of teachers required."""
    teachers_for_students = math.ceil(student_count / limit_per_teacher)
    teachers_for_workload = math.ceil(subjects / max_workload)
    return max(teachers_for_students, teachers_for_workload), teachers_for_students

def calculate_notifications(student_count, teachers_needed, current_teachers, max_critical_ratio, min_critical_ratio, subjects, max_workload):
    """Generates notifications based on conditions."""
    notifications = []

    if current_teachers == 0:
        notifications.append({
            "type": "error",
            "message": "No teachers are currently available. Please add teachers to the system."
        })
        return notifications

    current_ratio = student_count / current_teachers
    current_workload = math.ceil(subjects * student_count / current_teachers)

    # Overcrowding notification
    if current_ratio > max_critical_ratio:
        additional_teachers_for_ratio = math.ceil(student_count / max_critical_ratio) - current_teachers
        notifications.append({
            "type": "overcrowding",
            "message": (
                f"Overcrowding detected: The student-to-teacher ratio is {current_ratio:.2f}, exceeding the threshold of {max_critical_ratio}."
            ),
            "additional_teachers_needed": max(0, additional_teachers_for_ratio)
        })

    # Teacher overload notification
    if current_workload > max_workload:
        additional_teachers_for_workload = math.ceil((subjects * student_count) / max_workload) - current_teachers
        notifications.append({
            "type": "teacher_overload",
            "message": (
                f"Teacher overload detected: The workload per teacher is {current_workload}, exceeding the allowed maximum of {max_workload}."
            ),
            "additional_teachers_needed": max(0, additional_teachers_for_workload)
        })

    # Underutilization notification
    if current_ratio < min_critical_ratio:
        notifications.append({
            "type": "underutilization",
            "message": f"Underutilization detected: The student-to-teacher ratio is {current_ratio:.2f}, below the threshold of {min_critical_ratio}."
        })

    # No issues detected
    if not notifications:
        notifications.append({
            "type": "sufficient_teachers",
            "message": "The current number of teachers is sufficient, and no issues are detected."
        })

    return notifications

@app2.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.json
        logging.debug(f"Received data: {data}")
        
        # Validate inputs
        inputs = validate_inputs(data)

        student_count = inputs['student_count']
        limit_per_teacher = inputs['limit_per_teacher']
        subjects = inputs['subjects']
        max_workload = inputs['max_workload']
        current_teachers = inputs['current_teachers']

        # Calculate teachers needed
        teachers_needed, teachers_for_students = calculate_teachers(student_count, limit_per_teacher, subjects, max_workload)
        subjects_per_teacher = math.ceil(subjects / max(teachers_needed, 1))  # Avoid division by zero

        # Generate notifications
        notifications = calculate_notifications(student_count, teachers_needed, current_teachers, MAX_CRITICAL_RATIO, MIN_CRITICAL_RATIO, subjects, max_workload)

        # Calculate utilization rate
        utilization_rate = round(student_count / (current_teachers * MAX_CRITICAL_RATIO), 2)

        # Predict additional teachers
        prediction_features = np.array([student_count, subjects, max_workload, current_teachers])
        additional_teachers_predicted = custom_model.predict(prediction_features)[0]

        # Prepare the response
        result = {
            "teachers_needed": teachers_needed,
            "subjects_per_teacher": subjects_per_teacher,
            "utilization_rate": utilization_rate,
            "predicted_additional_teachers": additional_teachers_predicted,
            "notifications": notifications
        }
        logging.debug(f"Response: {result}")

        return jsonify(result)

    except ValueError as ve:
        logging.error(f"Validation Error: {ve}")
        return jsonify({"error": str(ve)}), 400
    except Exception as e:
        logging.error(f"Unexpected Error: {e}")
        return jsonify({"error": f"An unexpected error occurred: {str(e)}"}), 500

if __name__ == '__main__':
    app2.run(debug=True, port=5001)
