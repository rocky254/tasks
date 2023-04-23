# Loan Calculator System Requirements (Mac OS)
1. XAMPP Server (https://www.apachefriends.org/download.html)
2. Composer (https://getcomposer.org/download/)
3. PHP Version ^8.1 (https://windows.php.net/downloads/releases/php-8.2.5-src.zip)

# Loan Calculator System Requirements (Windows OS)
1. MAMP Server (https://www.mamp.info/en/downloads/)
2. Composer (https://getcomposer.org/download/)
3. PHP Version ^8.1 (https://www.php.net/distributions/php-8.2.5.tar.gz)

# Employee Performance Management System Installation
# Application uses a MySQL Database (XAMPP Apache)

1. Clone the Github Repository in the htdocs folder for your MAMP/XAMPP installation
2. Open a terminal inside the `api` folder
3. Run `composer update`
4. Open your favorite editor (e.g., VS Code) and Rename or copy `.env.example` file to `.env`
5. Run `php artisan jwt:secret` to generate the application jwt secret key
6. Set your Database credentials in your `.env` file
7. Open MAMP/XAMMP and create a new database with the same database name provided above
8. Set SMTP credentials in .env file (Personal Email and Password will work if you
change security settings for your Gmail Account to allow requests from less secure apps)
9. Run `php artisan migrate:fresh --seed` to create database tables and seed database
10. Open the `frontend` folder in your favorite editor (e.g., VS Code)
11. Run `npm install` in the terminal to install the required packages
11. Copy the `.env.example` and set the details to match your local environment (ensure you match
the correct API URL)
12. For your MAMP/XAMPP setup, the API URL should contain the correct port (e.g., if you access
through port 80, the API URL will be http://localhost/loan/api/public/api/)
13. Open your browser and type the URL given after running `npm run serve` to acccess the application