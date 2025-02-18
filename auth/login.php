<?php
include_once __DIR__ . "/../includes/db_connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Fetch user details, including role
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password, $role);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION["user_id"] = $id;
        $_SESSION["username"] = $username;
        $_SESSION["role"] = $role; 

        // Redirect based on role
        if ($role === "admin") {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        echo "<p style='color:red;'>Invalid email or password!</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header_footer.css">
    <link rel="shortcut icon" href="../assets/images/logo.png" type="image/x-icon">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <main class="auth-container">
    <h2>Login</h2>
        <form method="POST">
            <label>Email: </label>
            <input type="email" name="email" placeholder="Enter Email Address" required>
            <br>
            <label>Password: </label>
            <input type="password" name="password" placeholder="Enter Password" required>
            <br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </main>

    <?php include "../includes/footer.php"; ?>

</body>
</html>
