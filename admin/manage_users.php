<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../index.php");
    exit();
}

// Handle role update
if (isset($_GET['promote'])) {
    $user_id = intval($_GET['promote']);
    $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "User promoted to admin!";
    } else {
        echo "Error promoting user: " . $conn->error;
    }
    $stmt->close();
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all users
$result = $conn->query("SELECT id, username, email, role FROM users");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

    <?php include "../includes/admin_sidebar.php"; ?>

    <!-- Main Content -->
    <main class="content">
        <header>
            <h1>Manage Users</h1>
            <a href="add_admin.php" class="add-btn">Add New Admin</a>
        </header>

        <h2>User List</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td>
                        <?php if ($row['role'] === 'guest') : ?>
                            <a href="manage_users.php?promote=<?= $row['id'] ?>" style="color: green;">Promote to Admin</a> |
                        <?php endif; ?>
                        <a href="manage_users.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>

</html>