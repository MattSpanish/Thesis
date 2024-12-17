import threading
from flask import Flask, jsonify, redirect
import base64
import mysql.connector
import matplotlib
import matplotlib.pyplot as plt
import numpy as np
from io import BytesIO

matplotlib.use('Agg')  # Use a non-GUI backend

# Database configuration
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',  # Update with your MySQL password
    'database': 'enrollment_db'
}

# Flask app instances
app1 = Flask(__name__)
app3 = Flask(__name__)

# Utility functions for database interaction
def get_data_from_db(query):
    """
    Fetch data from the database based on the given query.
    """
    connection = mysql.connector.connect(**DB_CONFIG)
    cursor = None
    try:
        cursor = connection.cursor()
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

def create_chart(years, totals, title, ylabel, color):
    """
    Generate a chart visualizing the data.
    """
    plt.figure(figsize=(10, 6))
    plt.plot(years, totals, label=title, marker='o', color=color)
    plt.title(f"{title} Over Time")
    plt.xlabel("Year")
    plt.ylabel(ylabel)
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

# app1 routes
@app1.route('/api/get_chart_data', methods=['GET'])
def get_chart_data():
    try:
        query = "SELECT year, SUM(total) AS total_students FROM student_data GROUP BY year ORDER BY year"
        data = get_data_from_db(query)
        if not data:
            return jsonify({'error': 'No data available in the database.'}), 404

        years, totals = preprocess_data(data)
        if len(years) < 2:
            return jsonify({'error': 'Not enough data to create a chart.'}), 400

        chart = create_chart(years, totals, "Student Population", "Total Students", "blue")
        return jsonify({'chart': chart, 'mse': 'Perfect Match'})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app1.route('/phpmyadmin')
def phpmyadmin():
    """
    Redirect to the phpMyAdmin interface.
    """
    return redirect("http://localhost/phpmyadmin/")

# app3 routes
@app3.route('/api/faculty-data', methods=['GET'])
def faculty_data_api():
    try:
        query = "SELECT year, SUM(total_teachers) AS total_teachers FROM faculty_data GROUP BY year ORDER BY year"
        data = get_data_from_db(query)
        if not data:
            return jsonify({"success": False, "message": "No data available."}), 404

        years, totals = preprocess_data(data)
        if len(years) < 2:
            return jsonify({"success": False, "message": "Not enough data to create a chart."}), 400

        chart = create_chart(years, totals, "Teacher Population", "Total Teachers", "green")
        return jsonify({
            "success": True,
            "years": years,
            "totals": totals,
            "chart": chart
        })
    except Exception as e:
        return jsonify({"success": False, "message": str(e)}), 500

# Threading to run all three apps simultaneously
def run_app1():
    app1.run(debug=False, port=5000)

def run_app2():
    from app2 import app2  # Ensure app2 is defined in a separate file called app2.py
    app2.run(debug=False, port=5001)

def run_app3():
    app3.run(debug=False, port=5002)

if __name__ == '__main__':
    thread1 = threading.Thread(target=run_app1)
    thread2 = threading.Thread(target=run_app2)
    thread3 = threading.Thread(target=run_app3)

    thread1.start()
    thread2.start()
    thread3.start()

    thread1.join()
    thread2.join()
    thread3.join()
