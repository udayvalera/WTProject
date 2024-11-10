<?php
include 'config.php';

// Create table if it doesn't exist
$table_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($table_sql) === TRUE) {
    echo "Table 'users' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Seed the table with 5 entries including admin
$users = [
    ['username' => 'admin', 'password' => password_hash('pass', PASSWORD_DEFAULT)],
    ['username' => 'user1', 'password' => password_hash('password1', PASSWORD_DEFAULT)],
    ['username' => 'user2', 'password' => password_hash('password2', PASSWORD_DEFAULT)],
    ['username' => 'user3', 'password' => password_hash('password3', PASSWORD_DEFAULT)],
    ['username' => 'user4', 'password' => password_hash('password4', PASSWORD_DEFAULT)],
    ['username' => 'user5', 'password' => password_hash('password5', PASSWORD_DEFAULT)],
];

foreach ($users as $user) {
    $username = $user['username'];
    $password = $user['password'];

    // Check if user already exists
    $check_sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($check_sql);

    if ($result->num_rows == 0) {
        $insert_sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "User '$username' inserted successfully.<br>";
        } else {
            echo "Error inserting user '$username': " . $conn->error . "<br>";
        }
    } else {
        echo "User '$username' already exists.<br>";
    }
}

$conn->close();
?>