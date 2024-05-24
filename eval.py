import matplotlib.pyplot as plt
import pandas as pd

# Load the data
file_path = 'prof_eval.csv'
df = pd.read_csv(file_path)

# Calculate descriptive statistics for each criterion
stats = df.describe()
print(stats)

# Calculate average ratings for each professor
avg_ratings = df.groupby('Professor ID').mean()
print(avg_ratings)

# Compute overall rating for each professor (excluding the existing Overall Rating column)
df['Calculated Overall Rating'] = df[['Clarity of Instruction', 'Knowledge of Subject', 'Communication Skills', 'Approachability', 'Organization', 'Fairness', 'Engagement']].mean(axis=1)

# Compare the manually calculated overall rating with the existing one
comparison = df[['Overall Rating', 'Calculated Overall Rating']]
print(comparison)

# Plot average ratings for each criterion per professor and save as an image
avg_ratings.T.plot(kind='bar', figsize=(12, 6))
plt.title('Average Ratings by Criterion for Each Professor')
plt.ylabel('Average Rating')
plt.xlabel('Criterion')
plt.legend(title='Professor ID')
plt.tight_layout()
plt.savefig('average_ratings_by_criterion.png')
plt.close()

# Plot overall average rating for each professor and save as an image
overall_avg = df.groupby('Professor ID')['Calculated Overall Rating'].mean()
overall_avg.plot(kind='bar', figsize=(8, 6))
plt.title('Overall Average Rating for Each Professor')
plt.ylabel('Average Rating')
plt.xlabel('Professor ID')
plt.tight_layout()
plt.savefig('overall_average_rating.png')
plt.close()
