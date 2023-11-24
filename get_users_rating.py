import mysql.connector
import csv

# Replace these values with your XAMPP MySQL database credentials
db_config = {
    'host': 'localhost',
    'user': "root",
    'password': '',
    'database': 'movie_rec',
}
# Connect to the database
conn = mysql.connector.connect(**db_config)
cursor = conn.cursor()

# Specify the table you want to export
table_name = 'rated_movies'

# Execute a SELECT query to fetch the data from the table
select_query = f"SELECT * FROM {table_name}"
cursor.execute(select_query)

# Fetch all rows from the result set
rows = cursor.fetchall()

# Get the column names
column_names = [desc[0] for desc in cursor.description]

# Specify the CSV file path
csv_file_path = 'output.csv'

# Write the data to the CSV file
with open(csv_file_path, 'w', newline='') as csv_file:
    csv_writer = csv.writer(csv_file)
    
    # Write the header
    csv_writer.writerow(column_names)
    
    # Write the data rows
    csv_writer.writerows(rows)

print(f"Data from table '{table_name}' has been exported to '{csv_file_path}'.")

# Close the database connection
conn.close()
