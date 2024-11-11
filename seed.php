<?php
include 'config.php';

// Create users table if it doesn't exist
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

// Seed the users table with 5 entries including admin
$users = [
    ['username' => 'admin', 'password' => password_hash('pass', PASSWORD_DEFAULT)],
    ['username' => 'user1', 'password' => password_hash('password1', PASSWORD_DEFAULT)],
    ['username' => 'user2', 'password' => password_hash('password2', PASSWORD_DEFAULT)],
    ['username' => 'user3', 'password' => password_hash('password3', PASSWORD_DEFAULT)],
    ['username' => 'user4', 'password' => password_hash('password4', PASSWORD_DEFAULT)]
];

foreach ($users as $user) {
    $username = $user['username'];
    $password = $user['password'];
    $insert_sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if ($conn->query($insert_sql) === TRUE) {
        echo "User '$username' inserted successfully.<br>";
    } else {
        echo "Error inserting user '$username': " . $conn->error . "<br>";
    }
}

// Create todo table if it doesn't exist
$todo_table_sql = "CREATE TABLE IF NOT EXISTS todo (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    text VARCHAR(255) NOT NULL,
    completed BOOLEAN NOT NULL DEFAULT FALSE,
    task_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($todo_table_sql) === TRUE) {
    echo "Table 'todo' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Seed the todo table with some entries
$todos = [
    ['user_id' => 1, 'text' => 'Complete project documentation', 'completed' => false],
    ['user_id' => 2, 'text' => 'Review pull requests', 'completed' => false],
    ['user_id' => 3, 'text' => 'Update project dependencies', 'completed' => true],
    ['user_id' => 4, 'text' => 'Fix bugs in the code', 'completed' => false],
    ['user_id' => 5, 'text' => 'Prepare for the meeting', 'completed' => true]
];

foreach ($todos as $todo) {
    $user_id = $todo['user_id'];
    $text = $todo['text'];
    $completed = $todo['completed'] ? 1 : 0;
    $insert_sql = "INSERT INTO todo (user_id, text, completed) VALUES ('$user_id', '$text', '$completed')";
    if ($conn->query($insert_sql) === TRUE) {
        echo "Todo '$text' inserted successfully.<br>";
    } else {
        echo "Error inserting todo '$text': " . $conn->error . "<br>";
    }
}

$conn->close();
?>