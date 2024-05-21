import sys

import matplotlib.pyplot as plt
import numpy as np
import pandas as pd
from sklearn.linear_model import LinearRegression
from sklearn.metrics import r2_score

# Sample data (replace with actual data)
data = {
    'students': [1000, 1500, 2000, 2500],
    'subjects': [8, 10, 12, 14],
    'average_class_size': [25, 30, 35, 40],
    'professors_needed': [20, 25, 30, 35]
}

df = pd.DataFrame(data)

# Independent variables
X = df[['students', 'subjects', 'average_class_size']]

# Dependent variable
y = df['professors_needed']

# Create and fit the model
model = LinearRegression()
model.fit(X, y)

# Get the input number of students, subjects, and average class size from command line arguments
num_students = int(sys.argv[1])
num_subjects = int(sys.argv[2])
avg_class_size = int(sys.argv[3])

# Predict the number of professors
prediction_data = np.array([[num_students, num_subjects, avg_class_size]])
predicted_professors = model.predict(prediction_data)
print(f"Predicted number of professors for {num_students} students, {num_subjects} subjects, and average class size of {avg_class_size}: {predicted_professors[0]:.2f}")

# Save the prediction result to a text file
with open('prediction.txt', 'w') as f:
    f.write(f"{predicted_professors[0]:.2f}")

# Generate the plot (for visualization purposes, using only students vs. professors_needed)
plt.scatter(df['students'], df['professors_needed'], color='blue', label='Actual data')
plt.plot(df['students'], model.predict(X), color='red', label='Regression line')
plt.scatter([num_students], [predicted_professors], color='green', label=f'Prediction for {num_students} students')
plt.xlabel('Number of Students')
plt.ylabel('Number of Professors Needed')
plt.title('Multiple Regression: Professors Needed vs. Number of Students')
plt.legend()
plt.savefig('regression_plot.png')
