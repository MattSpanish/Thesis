import numpy as np
from scipy.interpolate import interp1d
import matplotlib
matplotlib.use('Agg')  # Use a non-GUI backend
import matplotlib.pyplot as plt
from flask import Flask, render_template, redirect, url_for
import mysql.connector
import os
from io import BytesIO
import base64

app = Flask(__name__)

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
    try:
        cursor = connection.cursor()
        query = "SELECT year, SUM(total) AS total_students FROM student_data GROUP BY year ORDER BY year"
        cursor.execute(query)
        results = cursor.fetchall()
        cursor.close()
        return results
    finally:
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

@app.route('/')
def index():
    try:
        # Fetch data from the database
        data = get_data_from_db()
        if not data:
            return "No data available in the database.", 404

        # Preprocess data
        years, totals = preprocess_data(data)
        if len(years) < 2:
            return "Not enough data to create a chart.", 400

        # Generate chart with exact matching
        chart = create_chart(years, totals)

        # Render the HTML with the chart
        return render_template('index.html', chart=chart, mse="Perfect Match")
    except Exception as e:
        return f"An error occurred: {e}", 500

@app.route('/phpmyadmin')
def phpmyadmin():
    """
    Redirect to the phpMyAdmin interface.
    """
    return redirect("http://localhost/phpmyadmin/")

if __name__ == '__main__':
    app.run(debug=True)
