<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../index.php");
    exit();
}

// Handle comment deletion
if (isset($_GET['delete'])) {
    $comment_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->bind_param("i", $comment_id);

    if ($stmt->execute()) {
        echo "Comment deleted successfully!";
    } else {
        echo "Error deleting comment: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all comments
$result = $conn->query("SELECT comments.id, comments.content, comments.created_at, 
                               users.username, posts.title 
                        FROM comments 
                        JOIN users ON comments.user_id = users.id 
                        JOIN posts ON comments.post_id = posts.id 
                        ORDER BY comments.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments | Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>

    <?php include "../includes/admin_sidebar.php"; ?>

    <!-- Main Content -->
    <main class="content">
        <header>
            <h1>Manage Comments</h1>
        </header>

        <h2>All Comments</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Post</th>
                <th>Username</th>
                <th>Comment</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['content']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="manage_comments.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>

</html>