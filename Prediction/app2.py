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
        # These values are predefined to mimic a trained model
        self.coefficients = [0.3, 0.2, 0.1, 0.4]  # Example coefficients
        self.intercept = 5  # Example intercept value

    def predict(self, features):
        """
        Predicts output based on linear regression formula:
        y = intercept + sum(coefficients[i] * features[i])
        """
        prediction = self.intercept + np.dot(features, self.coefficients)
        return [max(0, math.ceil(prediction))]  # Ensures the output is non-negative and rounded up

# Initialize the custom model
custom_model = CustomLinearRegression()

def validate_inputs(data):
    """Validates the input data."""
    required_keys = ['student_count', 'limit_per_teacher', 'subjects', 'max_workload', 'current_teachers']
    for key in required_keys:
        if key not in data:
            raise ValueError(f"Missing key: {key}")
    
    # Convert values to integers and validate they are positive
    try:
        inputs = {key: int(data[key]) for key in required_keys}
    except ValueError:
        raise ValueError("All inputs must be valid integers.")
    
    if any(value <= 0 for value in inputs.values()):
        raise ValueError("All inputs must be positive integers.")
    
    return inputs

def calculate_teachers(student_count, limit_per_teacher, subjects, max_workload):
    """Calculates the number of teachers required based on student count and workload."""
    teachers_for_students = math.ceil(student_count / limit_per_teacher)
    teachers_for_workload = math.ceil(subjects / max_workload)
    return max(teachers_for_students, teachers_for_workload), teachers_for_students

def calculate_notifications(student_count, teachers_needed, current_teachers, max_critical_ratio, min_critical_ratio, subjects, max_workload):
<<<<<<< Updated upstream
    """Calculates notifications based on various conditions."""
=======
    """Calculates notifications based on various conditions, including workload details."""
>>>>>>> Stashed changes
    notifications = []
    
    # Calculate current student-to-teacher ratio
    current_ratio = student_count / current_teachers
    logging.debug(f"Current ratio: {current_ratio}, Teachers needed: {teachers_needed}, Current teachers: {current_teachers}")

    # Calculate workload per teacher
    current_workload = math.ceil(subjects * student_count / current_teachers)
    logging.debug(f"Current workload per teacher: {current_workload}, Max allowed workload: {max_workload}")

    # Add a notification for no overcrowding if teachers are sufficient
    if current_teachers >= teachers_needed:
        notifications.append({
            "type": "no_overcrowding",
            "message": f"No overcrowding detected: The student-to-teacher ratio is {current_ratio:.2f}, which is within the acceptable threshold of {max_critical_ratio}."
        })
        logging.debug("Added 'no_overcrowding' notification.")

    # Add a notification for sufficient teachers
    if current_teachers >= teachers_needed:
        notifications.append({
            "type": "sufficient_teachers",
            "message": "No additional teachers are needed. The current number of teachers is sufficient."
        })
        logging.debug("Added 'sufficient_teachers' notification.")

    # Overcrowding notification
    if current_ratio > max_critical_ratio:
        additional_teachers_for_ratio = math.ceil(student_count / max_critical_ratio) - current_teachers
        notifications.append({
            "type": "overcrowding",
            "message": (
<<<<<<< Updated upstream
                f"Overcrowding detected: The student-to-teacher ratio is {current_ratio:.2f}, which exceeds the maximum critical threshold of {max_critical_ratio}."
            ),
            "additional_teachers_needed": max(0, additional_teachers_for_ratio)
=======
                f"Overcrowding detected: The student-to-teacher ratio is {current_ratio:.2f}, which exceeds the maximum critical threshold of {max_critical_ratio}. "
                f"Current workload per teacher is {current_workload} hours, which exceeds the maximum allowed workload of {max_workload} hours."
            ),
            "additional_teachers_needed": additional_teachers
>>>>>>> Stashed changes
        })
        logging.debug("Added 'overcrowding' notification with workload details.")

    # Teacher overload notification
    if current_workload > max_workload:
        additional_teachers_for_workload = math.ceil((subjects * student_count) / max_workload) - current_teachers
        notifications.append({
            "type": "teacher_overload",
            "message": (
                f"Teacher overload detected: The workload per teacher is {current_workload}, which exceeds the maximum allowed workload of {max_workload}."
            ),
            "additional_teachers_needed": max(0, additional_teachers_for_workload)
        })
        logging.debug("Added 'teacher_overload' notification.")

    # Add a notification for underutilization
    if current_ratio < min_critical_ratio:
        notifications.append({
            "type": "underutilization",
            "message": f"Underutilization detected: The student-to-teacher ratio is {current_ratio:.2f}, which is below the minimum critical threshold of {min_critical_ratio}."
        })
        logging.debug("Added 'underutilization' notification.")

    return notifications


@app2.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.json
        logging.debug(f"Received data: {data}")
        
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
        logging.debug(f"Teachers needed: {teachers_needed}, Subjects per teacher: {math.ceil(subjects / teachers_needed)}")

        subjects_per_teacher = math.ceil(subjects / teachers_needed)

        # Calculate notifications for overcrowding, teacher overload, and other conditions
        notifications = calculate_notifications(
            student_count, teachers_needed, current_teachers, MAX_CRITICAL_RATIO, MIN_CRITICAL_RATIO, subjects, max_workload
        )
        logging.debug(f"Notifications: {notifications}")

        # Calculate utilization rate
        utilization_rate = round(student_count / (current_teachers * MAX_CRITICAL_RATIO), 2)

        # Use the predefined linear regression model to predict additional teachers needed
        prediction_features = np.array([student_count, subjects, max_workload, current_teachers])
        additional_teachers_predicted = custom_model.predict(prediction_features)[0]
        logging.debug(f"Predicted additional teachers needed: {additional_teachers_predicted}")

        # Return the results
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

if __name__ == '_main_':
    app2.run(debug=True, port=5001)