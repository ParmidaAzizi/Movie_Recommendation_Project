import mysql.connector
import csv
import sys

# get the first argument
arg1 = sys.argv[1] if len(sys.argv) > 1 else None
arg1 = int(arg1)

# Connect to the database
db_config = {
    'host': 'localhost',
    'user': "root",
    'password': '',
    'database': 'movie_rec',
}
conn = mysql.connector.connect(**db_config)
cursor = conn.cursor()

# Function get ratijng from DB and create a user-item matrix
def read_ratings():

    select_query = f"SELECT * FROM rated_movies"
    cursor.execute(select_query)

    # Fetch all rows from the result set
    rows = cursor.fetchall()
    user_item_matrix={}

    for row in rows:
        user_id, movie_id, rating = map(int, row[1:4])

        if user_id not in user_item_matrix:
            user_item_matrix[user_id] = {}
        user_item_matrix[user_id][movie_id] = rating

    return user_item_matrix

# Function to calculate cosine similarity between two users
def cosine_similarity(user1, user2):
    
    # Common movies rated by user1 & user2
    common_movies = set(user1.keys()) & set(user2.keys())
    
    if not common_movies:
        return 0
    
    numerator = sum(user1[movie] * user2[movie] for movie in common_movies)
    # User1 normalized ratings
    denominator1 = sum(user1[movie] ** 2 for movie in common_movies) ** 0.5 
    # User2 normalized ratings
    denominator2 = sum(user2[movie] ** 2 for movie in common_movies) ** 0.5
    if denominator1 * denominator2 != 0:
        return numerator / (denominator1 * denominator2)
    # Special case where user1 has not rated X and have no common movies with user2 except for X
    else:
        return 0

# Function to perform user-based collaborative filtering
def user_based_collaborative_filtering(user_item_matrix, target_user_id):
    target_user = user_item_matrix.get(target_user_id, {})
    
    # Calculate similarity between the target user and all other users
    similarities = {}
    for user_id, user in user_item_matrix.items():
        if user_id != target_user_id:
            similarities[user_id] = cosine_similarity(target_user, user)
    
    # Sort users by similarity in descending order
    similar_users = sorted(similarities.items(), key=lambda x: x[1], reverse=True)
    
    # Find unrated movies by the target user
    all_movie_ids = set(range(1, max(max(user_item_matrix.values(), key=lambda x: max(x, default=0)), default=0) + 1))
    rated_movies = set(target_user.keys())
    unrated_movies = all_movie_ids - rated_movies

    # Predict ratings for unrated movies based on similar users
    predictions = {}
    for movie_id in unrated_movies:
        numerator = 0
        denominator = 0
        
        for user_id, similarity in similar_users:
            # Check if the movie_id is present in the user's ratings
            if movie_id in user_item_matrix[user_id]:
                numerator += similarity * user_item_matrix[user_id][movie_id]
                denominator += abs(similarity)
        
        if denominator != 0:
            predictions[movie_id] = numerator / denominator
    
    return predictions

# Usage
user_item_matrix = read_ratings()

# Input the target_user_id
target_user_id = arg1
predictions = user_based_collaborative_filtering(user_item_matrix, target_user_id)

# Check if the user already exists in the users table
cursor.execute(f"SELECT COUNT(*) FROM users WHERE userID = {arg1}")
user_exists = cursor.fetchone()[0]

if not user_exists:
    # If the user doesn't exist, add them to the users table
    try:
        cursor.execute(f"INSERT INTO users (userID) VALUES ({arg1})")
        print(f"Inserted new user with userID: {arg1}")
        conn.commit()
    except Exception as e:
        print(f"Error inserting user: {e}")

# Check if the user already exists in the rated_movies table
cursor.execute(f"SELECT COUNT(*) FROM recommended_movies WHERE userID = {arg1}")
user_has_ratings = cursor.fetchone()[0]

# If the user has existing ratings, remove them
if user_has_ratings:
    try:
        cursor.execute(f"DELETE FROM recommended_movies WHERE userID = {arg1}")
        print(f"Removed existing ratings for user with userID: {arg1}")
        conn.commit()
    except Exception as e:
        print(f"Error removing existing ratings: {e}")

K = 20 # Change K based on youe preference
count = 1
results = []
for movie_id, rating in sorted(predictions.items(), key=lambda x: x[1], reverse=True):
    if count <= K:
        count += 1
        try:
            cursor.execute(f"INSERT INTO recommended_movies (userID, item_ID, rating) VALUES ({arg1}, {movie_id}, {rating})")
            print(f"Inserted record for userID: {arg1}, item_ID: {movie_id}, rating: {rating}")
            conn.commit()
        except Exception as e:
            print(f"Error inserting rating: {e}")

        
