# HabitatSwim
Swim meet fundraiser management system created for the 2015 Habitat for Humanity Sponsored Swim at the Jakarta International School

## Setup
- Place the app files on a web server with PHP installed.
- Create a MySQL database for the app using the [db_init SQL script](./bin/db_init.sql).
	- The name of the database created by the script will be ```habitatswim```. If necessary, this can be changed in the script before running it.
- Create a MySQL user for the app and give it full data access (SELECT, INSERT, UPDATE, DELETE) to the database.
- Specify the MySQL server host, username, password, and database name to be used by the app in the [db_config PHP file](./bin/db_config.php).