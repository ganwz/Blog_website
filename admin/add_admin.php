<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = 'admin'; // Default role for new accounts

    if (!empty($username) && !empty($email) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
        
        if ($stmt->execute()) {
            echo "Admin account created successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add admin</title>
    <link rel="stylesheet" href="../assets/css/admin_edit_post.css">
</head>
<body>
    <h2>Add admin Account</h2>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" placeholder="Enter Name" required>
        <br>
        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter Email Address" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter Password" required>
        <br>
        <button type="submit">Add Admin</button>
    </form>
    <a href="manage_users.php">← Back to Manage User</a>
</body>
</html>
