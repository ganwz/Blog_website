<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../index.php");
    exit();
}

// Handle post deletion
if (isset($_GET['delete'])) {
    $post_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    if ($stmt->execute()) {
        echo "Post deleted successfully!";
    } else {
        echo "Error deleting post: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all blog posts
$result = $conn->query("SELECT posts.id, posts.title, users.username AS author_name, posts.created_at
                        FROM posts
                        JOIN users ON posts.author_id = users.id
                        ORDER BY posts.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts | Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <?php include "../includes/admin_sidebar.php"; ?>

    <!-- Main Content -->
    <main class="content">
        <header>
            <h1>Manage Blog Posts</h1>
            <div>
                <a href="add_post.php" class="add-btn">Add New Post</a>
            </div>
        </header>

        <main>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['author_name']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="edit_post.php?id=<?= $row['id'] ?>" style="color: blue;">Edit</a> |
                            <a href="manage_posts.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </table>
        </main>
    </main>
</body>

</html>