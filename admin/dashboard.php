<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Daily Muse</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <div class="admin-container">
        <!-- Include Sidebar -->
        <?php include "../includes/admin_sidebar.php"; ?>

        <!-- Main Content -->
        <main class="content">
            <header>
                <h1>Welcome, Admin!</h1>
            </header>
        </main>
    </div>
</body>

</html>
