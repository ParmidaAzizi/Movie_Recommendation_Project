!! screenshots of the Website is available in the SneakPeek folder !!

The UI:
1.  Download XAMPP
		> https://www.apachefriends.org/
2.  Run mySQL on XAMPP
3.  Click on "Admin"
4.  Place the movie_rec folder in:
		> InstallationDirectoryOfXampp\htdocs
5.  Run the following URL in your browser to build and setup your database:
		> http://localhost/movie_rec/setup.php
6.  Now you can see the website and use it at:
		> http://localhost/movie_rec

Note: 
* The login page asks for a user id which is a number. users 1 to 30 also exist with 30 random ratings. You can use an axisting userID or you can enter a new one.
** Each user needs to have atleast 10 exising ratings. so a new user must rate at least 10 movies to see their recommendations.
*** Recommendations change when any user rate a new movie. And each user can see their top 20 recommendations
**** To compare our recommendation algorithm with recommendations produced my ML techniques, run “comparison.py”. the results will get printed in the command line:
