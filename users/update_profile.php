<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Redirect if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$message = ""; // To store success/error messages

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST["username"]);
    $new_email = trim($_POST["email"]);
    $new_password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : null;

    if (!empty($new_username) && !empty($new_email)) {
        // Prepare query with or without password update
        if ($new_password) {
            $update_stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
            $update_stmt->bind_param("sssi", $new_username, $new_email, $new_password, $user_id);
        } else {
            $update_stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $update_stmt->bind_param("ssi", $new_username, $new_email, $user_id);
        }

        if ($update_stmt->execute()) {
            $message = "<p class='success-message'>Profile updated successfully!</p>";
        } else {
            $message = "<p class='error-message'>Error updating profile: " . $conn->error . "</p>";
        }

        $update_stmt->close();
    } else {
        $message = "<p class='error-message'>All fields are required except password!</p>";
    }
}

// ✅ **Re-fetch updated user details**
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile | Daily Muse</title>
    <link rel="stylesheet" href="../assets/css/my_blogs.css">
    <link rel="stylesheet" href="../assets/css/header_footer.css">
    <link rel="shortcut icon" href="../assets/images/logo.png" type="image/x-icon">
</head>
<body>

    <?php include "../includes/header.php"; ?>

    <div class="container">
        <h2>Update Profile</h2>

        <?php echo $message; ?>

        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label>New Password (leave blank to keep current password):</label>
            <input type="password" name="password">

            <button type="submit">Update Profile</button>
        </form>

        <p><a href="../index.php" class="back-link">← Back to Home</a></p>
    </div>

    <?php include "../includes/footer.php"; ?>

</body>
</html>
