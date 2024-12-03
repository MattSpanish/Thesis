from flask import Flask, request, jsonify, render_template
import pandas as pd
from sklearn.linear_model import LinearRegression
from sklearn.model_selection import train_test_split
import mysql.connector
import matplotlib.pyplot as plt
import os

app = Flask(__name__)

# MySQL Database Connection
db_config = {
    'host': 'localhost',
    'user': 'root',  # Replace with your username
    'password': '',  # Replace with your password
    'database': 'enrollment_db'
}
conn = mysql.connector.connect(**db_config)
cursor = conn.cursor()

# Load data from MySQL into a DataFrame
def load_data_from_db():
    query = "SELECT year, total FROM enrollment_data"
    df = pd.read_sql(query, conn)
    return df

# Save CSV data into the database
def save_csv_to_db(file_path):
    df = pd.read_csv(file_path)
    for _, row in df.iterrows():
        cursor.execute(
            "INSERT INTO enrollment_data (program, section, total, year) VALUES (%s, %s, %s, %s)",
            (row['PROGRAM'], row['SECTION'], row['TOTAL'], row['YEAR'])
        )
    conn.commit()

# Load data for training
df = load_data_from_db()
X = df[['year']]
y = df['total']

# Train-test split
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train Linear Regression model
model = LinearRegression()
model.fit(X_train, y_train)

@app.route('/')
def home():
    return render_template('index.html')

@app.route('/predict', methods=['POST'])
def predict():
    """Predict the total enrollment based on a given year."""
    year = int(request.form['year'])
    prediction = model.predict([[year]])[0]
    return jsonify({'year': year, 'predicted_total': round(prediction, 2)})

@app.route('/plot')
def plot():
    """Generate and display a plot."""
    plt.figure(figsize=(10, 6))
    plt.scatter(X, y, color='blue', label='Actual Data')
    plt.plot(X, model.predict(X), color='red', label='Regression Line')
    plt.xlabel('Year')
    plt.ylabel('Total Enrollment')
    plt.title('Enrollment Trends')
    plt.legend()
    plot_path = 'static/plot.png'
    plt.savefig(plot_path)
    plt.close()
    return f'<img src="/{plot_path}" alt="Regression Plot">'

if __name__ == '__main__':
    if not os.path.exists('static'):
        os.makedirs('static')
    app.run(debug=True)
