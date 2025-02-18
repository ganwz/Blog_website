<?php
include_once __DIR__ . '/../includes/db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        echo "<p style='color:red;'>Passwords do not match!</p>"; 
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
        $role = "guest"; // Default role

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $role); 

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Registered successfully! <a href='login.php'>Login Here</a></p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header_footer.css">
    <link rel="shortcut icon" href="../assets/images/logo.png" type="image/x-icon">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <main class="auth-container">
        <h2>Register</h2>
        <form method="post">
            <label>Username: </label>
            <input type="text" name="username" placeholder="Enter Name" required>
            <br>
            <label>Email Address: </label>
            <input type="email" name="email" placeholder="Enter Email Address" required>
            <br>
            <label>Password: </label>
            <input type="password" name="password" placeholder="Enter Password" required>
            <br>
            <label>Confirm Password: </label>
            <input type="password" name="confirm_password" placeholder="Enter Confirm Password" required>
            <br>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login Here</a></p>
    </main>

    <?php include "../includes/footer.php"; ?>

</body>
</html>
