import matplotlib
import numpy as np
import base64
import os
from io import BytesIO
import matplotlib.pyplot as plt
import mysql.connector
from flask import Flask, jsonify, redirect

matplotlib.use('Agg')  # Use a non-GUI backend

app3 = Flask(__name__)

# Database configuration
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',  # Update with your MySQL password
    'database': 'enrollment_db'
}

def get_faculty_data():
    """
    Fetch teacher data from the database.
    """
    connection = mysql.connector.connect(**DB_CONFIG)
    cursor = None
    try:
        cursor = connection.cursor()
        query = "SELECT year, SUM(total_teachers) AS total_teachers FROM faculty_data GROUP BY year ORDER BY year"
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
    Generate a chart visualizing teacher data over the years.
    """
    plt.figure(figsize=(10, 6))
    # Plot actual data points
    plt.plot(years, totals, label="Total Teachers", marker='o', color='green')
    
    # Add grid and labels
    plt.title("Teacher Population Over Time")
    plt.xlabel("Year")
    plt.ylabel("Total Teachers")
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

@app3.route('/api/faculty-data', methods=['GET'])
def faculty_data_api():
    try:
        # Fetch teacher data from the database
        data = get_faculty_data()
        if not data:
            return jsonify({"success": False, "message": "No data available."}), 404

        # Preprocess data
        years, totals = preprocess_data(data)
        if len(years) < 2:
            return jsonify({"success": False, "message": "Not enough data to create a chart."}), 400

        # Generate the chart image
        chart = create_chart(years, totals)

        # Return the JSON response
        return jsonify({
            "success": True,
            "years": years,
            "totals": totals,
            "chart": chart
        })
    except mysql.connector.Error as db_err:
        return jsonify({"success": False, "message": f"Database error: {str(db_err)}"}), 500
    except Exception as e:
        return jsonify({"success": False, "message": f"Internal server error: {str(e)}"}), 500


if __name__ == '__main__':
    app3.run(debug=False, port=5002)
