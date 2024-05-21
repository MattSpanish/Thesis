import matplotlib.pyplot as plt
import numpy as np
from sklearn.linear_model import LinearRegression

# Historical data
students = np.array([1000, 1500, 2000, 2500]).reshape(-1, 1)
professors_needed = np.array([20, 25, 30, 35])

# Create and fit the model
model = LinearRegression()
model.fit(students, professors_needed)

# Predict for 2110 students
predicted_professors = model.predict(np.array([[2110]]))
print(f"Predicted number of professors for 2110 students: {predicted_professors[0]:.2f}")

# Plotting the data
plt.scatter(students, professors_needed, color='blue')
plt.plot(students, model.predict(students), color='red')
plt.xlabel('Number of Students')
plt.ylabel('Number of Professors Needed')
plt.title('Linear Regression: Professors Needed vs. Number of Students')
plt.show()
