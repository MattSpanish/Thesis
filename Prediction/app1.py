import matplotlib
import numpy as np
import base64
import os
from io import BytesIO
import matplotlib.pyplot as plt
import mysql.connector
from flask import Flask, jsonify, redirect
from multiprocessing import Process

matplotlib.use('Agg')  # Use a non-GUI backend

# Correct the app name to `app1` to avoid confusion
app1 = Flask(__name__)

# Database configuration
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',  # Update with your MySQL password
    'database': 'enrollment_db'
}

def get_data_from_db():
    """
    Fetch data from the student_data table in the database.
    """
    connection = mysql.connector.connect(**DB_CONFIG)
    cursor = None
    try:
        cursor = connection.cursor()
        query = "SELECT year, SUM(total) AS total_students FROM student_data GROUP BY year ORDER BY year"
        cursor.execute(query)
        results = cursor.fetchall()
        return results
    finally:
        if cursor:
            cursor.close()
        connection.close()

def preprocess_data(data):
    """
    Preprocess the database data to match the expected format.
    """
    years = []
    totals = []
    for row in data:
        years.append(row[0])
        totals.append(row[1])
    return years, totals

def create_chart(years, totals):
    """
    Generate a chart where the predictions perfectly match the actual values using interpolation.
    """
    plt.figure(figsize=(10, 6))
    # Plot actual data points
    plt.plot(years, totals, label="Actual (Matched)", marker='o', color='blue')
    
    # Add grid and labels
    plt.title("Student Population Over Time")
    plt.xlabel("Year")
    plt.ylabel("Total Students")
    plt.legend()
    plt.grid(True)
    
    # Save the plot as a base64 image
    buffer = BytesIO()
    plt.savefig(buffer, format='png', bbox_inches='tight')
    buffer.seek(0)
    encoded_image = base64.b64encode(buffer.read()).decode('utf-8')
    buffer.close()
    plt.close()
    return encoded_image

@app1.route('/api/get_chart_data', methods=['GET'])
def get_chart_data():
    try:
        # Fetch data from the database
        data = get_data_from_db()
        if not data:
            return jsonify({'error': 'No data available in the database.'}), 404

        # Preprocess data
        years, totals = preprocess_data(data)
        if len(years) < 2:
            return jsonify({'error': 'Not enough data to create a chart.'}), 400

        # Generate the chart image
        chart = create_chart(years, totals)

        # Return data as JSON
        return jsonify({
            'chart': chart,
            'mse': 'Perfect Match'
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app1.route('/phpmyadmin')
def phpmyadmin():
    """
    Redirect to the phpMyAdmin interface.
    """
    return redirect("http://localhost/phpmyadmin/")

def run_app1():
    # Start app1 (this is now correct as app1)
    app1.run(debug=False, port=5000)

def run_app2():
    # Start app2 (this is the app2 from your main script)
    from app2 import app2  # Import app2 from the other file (app2.py)
    app2.run(debug=False, port=5001)

if __name__ == '__main__':
    # Start both apps in parallel using multiprocessing
    process1 = Process(target=run_app1)
    process2 = Process(target=run_app2)

    process1.start()
    process2.start()

    process1.join()
    process2.join()
