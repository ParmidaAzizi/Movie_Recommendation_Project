import mysql.connector
from random import randint

db_config = {
    'host': 'localhost',
    'user': "root",
    'password': '',
    'database': 'movie_rec',
}

# Connect to the database
connection = mysql.connector.connect(**db_config)

cursor = connection.cursor()

# Loop through each user (add 30 users!)
for userID in range(1, 31):
    # Loop to add 30 ratings for each user

    
    # If the user does not exist, add it
    cursor.execute(f"SELECT COUNT(*) FROM users WHERE userID = {userID}")
    user_exists = cursor.fetchone()[0]
    if user_exists == 0:
        try:
            cursor.execute(f"INSERT INTO users (userID) VALUES ({userID})")
            print(f"Inserted new user with userID: {userID}")
        except Exception as e:
            print(f"Error with new user: {e}")


    user_movies = []
    i = 0
    while i < 30:
        itemID = randint(1, 251)  # Generate a random item_ID between 1 and 251
        rating = randint(1, 5)     # Generate a random rating between 1 and 5
        
        # only if this movie has not been rated by this user, add it to the db, else, get a new movie
        if itemID not in user_movies:
            user_movies.append(itemID)
            i = i+1

            # Insert the data into the rated_movies table
            sql = f"INSERT INTO rated_movies (userID, item_ID, rating) VALUES ({userID}, {itemID}, {rating})"
            
            try:
                cursor.execute(sql)
                print(f"Inserted record for userID: {userID}, item_ID: {itemID}, rating: {rating}")
            except Exception as e:
                print(f"Error: {e}")

# Commit the changes and close the connection
connection.commit()
connection.close()
