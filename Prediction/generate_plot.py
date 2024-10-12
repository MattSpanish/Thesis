import matplotlib.pyplot as plt
import pandas as pd
import statsmodels.api as sm


def generate_plot(csv_file):
    # Load the data
    df = pd.read_csv(csv_file)

    # Ensure all relevant columns are numeric, excluding non-relevant columns
    df[['ENROLLMENT', 'SECTION', 'CLASS SIZE', 'PROF']] = df[['ENROLLMENT', 'SECTION', 'CLASS SIZE', 'PROF']].apply(pd.to_numeric, errors='coerce')

    # Drop rows with missing values (optional, you could also fill them)
    df = df.dropna(subset=['ENROLLMENT', 'SECTION', 'CLASS SIZE', 'PROF'])

    # Define the dependent variable (Y) and independent variables (X)
    Y = df['PROF']  # Dependent variable
    X = df[['ENROLLMENT', 'SECTION', 'CLASS SIZE']]  # Independent variables

    # Add a constant to the independent variables matrix (required for statsmodels)
    X = sm.add_constant(X)

    # Fit the regression model
    model = sm.OLS(Y, X).fit()

    # Make predictions using the model
    df['FORECASTED PROFESSOR'] = model.predict(X)

    # Combine date and enrollment for plotting
    df['DATE'] = pd.to_datetime(df['DATE'])  # Assuming 'DATE' is the column name containing the date
    df['DATE_ENROLLMENT'] = df['DATE'].dt.strftime('%Y-%m-%d') + ' (' + df['ENROLLMENT'].astype(str) + ')'

    # Plot the regression
    plt.figure(figsize=(10, 6))
    plt.plot(df['DATE_ENROLLMENT'], df['PROF'], color='blue', label='Actual')  # Plot the actual values as a regression line
    plt.scatter(df['DATE_ENROLLMENT'], df['FORECASTED PROFESSOR'], color='red', label='Predicted', marker='o')  # Plot the predicted values as bullet points
    plt.title('Regression Plot with Date and Enrollment')
    plt.xlabel('Date and Enrollment')
    plt.ylabel('Professor')
    plt.legend()
    plt.grid(True)
    plt.xticks(rotation=45)  # Rotate x-axis labels for better readability

    plt.tight_layout()  # Adjust layout to prevent clipping of labels

    # Save the plot as an image file
    plt.savefig('prediction_plot.png')

    # Close the plot to free up memory
    plt.close()
