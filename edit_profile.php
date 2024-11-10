<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $current_username = $_SESSION['username'];

    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username='$username', password='$password' WHERE username='$current_username'";
    } else {
        $sql = "UPDATE users SET username='$username' WHERE username='$current_username'";
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $conn->close();
}
?>