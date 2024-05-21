import pandas as pd


def calculate_professors_needed(students, total_minutes_per_professor, total_needed_per_professor):
    # Calculate the ratio of total needed minutes to available minutes per professor
    ratio = total_needed_per_professor / total_minutes_per_professor
    
    # Calculate the number of professors needed for the given number of students
    professors_needed = students * ratio
    
    return professors_needed

def main():
    # Read data from Excel file
    excel_file = 'COMPUTATION.xlsx'
    
    try:
        # Read only the relevant data rows and columns
        df = pd.read_excel(excel_file, header=None, usecols="B, F, H")
        
        # Rename columns based on the first row (assuming it contains headers)
        df.columns = ['Students', 'Total Minutes per Professor', 'Total Needed per Professor']
        
        # Drop any rows with NaN values or non-numeric data
        df = df.dropna().reset_index(drop=True)
        
        # Convert columns to appropriate data types
        df['Students'] = pd.to_numeric(df['Students'], errors='coerce')  # Coerce errors to NaN if not numeric
        df['Total Minutes per Professor'] = pd.to_numeric(df['Total Minutes per Professor'], errors='coerce')
        df['Total Needed per Professor'] = pd.to_numeric(df['Total Needed per Professor'], errors='coerce')
        
        # Process each scenario
        for i, row in df.iterrows():
            students = row['Students']
            total_minutes_per_professor = row['Total Minutes per Professor']
            total_needed_per_professor = row['Total Needed per Professor']
            
            # Calculate the number of professors needed
            professors_needed = calculate_professors_needed(students, total_minutes_per_professor, total_needed_per_professor)
            
            # Output the result
            print(f"Scenario {i+1}: Professors needed for {students} students: {professors_needed:.2f}")
            
            # Example of adding an if-else condition based on the number of students
            if students > 2500:
                print(f"Warning: High number of students ({students}) may require additional resources.")
            else:
                print("No additional resources needed.")
            print("-" * 50)
    
    except Exception as e:
        print("Error reading Excel file or performing calculations:", e)

if __name__ == "__main__":
    main()
