import mysql.connector
import csv

db_config = {
    'host': 'localhost',
    'user': "root",
    'password': '',
    'database': 'movie_rec',
}

# Connect to the database
connection = mysql.connector.connect(**db_config)

# Create a cursor object
cursor = connection.cursor()


# CSV file path
csv_file_path = 'movie_data.csv'

# query to insert data into the 'item' table
insert_query = "INSERT INTO item (title, logo, year, rating, genres, directors, actors, plot, age_rating) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"


with open(csv_file_path, 'r') as csv_file:
    csv_reader = csv.reader(csv_file)
    next(csv_reader)  # Skip header

    for row in csv_reader:
        title = row[0]
        logo = row[1]
        year = int(row[2])  
        rating = row[3]
        genres = row[4]
        directors = row[5]
        actors = row[6]
        plot = row[7]
        age_rating = row[8]

        data_to_insert = (title, logo, year, rating, genres, directors, actors, plot, age_rating)

        # Execute the query
        cursor.execute(insert_query, data_to_insert)

# Commit the transaction
connection.commit()

# Close the cursor and connection
cursor.close()
connection.close()
