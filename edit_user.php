<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username='$username', password='$password' WHERE id='$id'";
    } else {
        $sql = "UPDATE users SET username='$username' WHERE id='$id'";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating user: " . $conn->error;
    }

    $conn->close();
}
?>