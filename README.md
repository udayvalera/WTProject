php # User Management System

This is a simple user management system built with PHP and MySQL. It includes functionalities for user registration, login, profile editing, and user management for administrators.

## Features

- User Registration
- User Login
- User Logout
- Profile Editing
- Admin Dashboard for managing users (view, edit, delete)

## Setup

1. **Clone the repository:**

    ```sh
    git clone https://github.com/udayvalera/WTProject.git
    cd WTProject
    ```

2. **Create the database:**

    ```sql
    CREATE DATABASE user_db;
    ```

3. **Configure the database connection:**

    Update the database credentials in [`config.php`](config.php):

    ```php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user_db";
    ```

4. **Seed the database:**

    Run [`seed.php`](seed.php) to create the `users` table and insert initial data:

    ```sh
    php seed.php
    ```

## Usage

1. **Register a new user:**

    Open [`register.php`](register.php) in your browser and fill out the registration form.

2. **Login:**

    Open [`login.php`](login.php) in your browser and log in with your credentials.

3. **Admin Dashboard:**

    If logged in as an admin, you will be redirected to [`admin_dashboard.php`](admin_dashboard.php) where you can manage users.

4. **User Dashboard:**

    If logged in as a regular user, you will be redirected to [`dashboard.php`](dashboard.php) where you can edit your profile and log out.

## File Descriptions

- **admin_dashboard.php:** Admin dashboard for managing users.
- **config.php:** Database configuration file.
- **dashboard.php:** User dashboard.
- **delete_user.php:** Script to delete a user.
- **edit_profile.php:** Script to edit user profile.
- **edit_user.php:** Script to edit user details by admin.
- **login.php:** User login page.
- **logout.php:** User logout script.
- **register.php:** User registration page.
- **seed.php:** Script to create the `users` table and seed initial data.

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Acknowledgements

- PHP
- MySQL
- HTML
- CSS

Feel free to contribute to this project by submitting issues or pull requests.
