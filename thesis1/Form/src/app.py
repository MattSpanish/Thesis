from flask import Flask, render_template, request
import pandas as pd
from sklearn.linear_model import LinearRegression

app = Flask(__name__)

@app.route('/', methods=['GET', 'POST'])
def index():
    if request.method == 'POST':
        # Get the input data from the form
        try:
            data = request.form['data']
            # Convert the input data to a DataFrame
            df = pd.read_csv(pd.compat.StringIO(data), sep=",")
            # Assuming the last column is the target variable
            X = df.iloc[:, :-1].values
            y = df.iloc[:, -1].values
            # Perform the regression
            model = LinearRegression()
            model.fit(X, y)
            coefficients = model.coef_
            intercept = model.intercept_
            r_squared = model.score(X, y)
            return render_template('index.html', coefficients=coefficients, intercept=intercept, r_squared=r_squared)
        except Exception as e:
            return str(e)
    return render_template('index.html')

if __name__ == '__main__':
    app.run(debug=True)
