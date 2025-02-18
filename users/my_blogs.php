<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Redirect if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// Fetch user's posts
$stmt = $conn->prepare("SELECT id, title, created_at FROM posts WHERE author_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blogs | Daily Muse</title>
    <link rel="stylesheet" href="../assets/css/my_blogs.css">
    <link rel="stylesheet" href="../assets/css/header_footer.css">
    <link rel="shortcut icon" href="../assets/images/logo.png" type="image/x-icon">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    
    <div class="container">
        <h2>My Blogs</h2>
        <a href="../posts/add_post.php" class="btn">Create New Post</a>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                <?php while ($post = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post["title"]); ?></td>
                        <td><?php echo $post["created_at"]; ?></td>
                        <td>
                            <a href="../posts/edit_post.php?id=<?php echo $post["id"]; ?>" class="btn-edit">Edit</a>
                            <a href="../posts/delete_post.php?id=<?php echo $post["id"]; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No posts found. Start writing now!</p>
        <?php endif; ?>

        <p><a href="../index.php" class="back-link">← Back to Home</a></p>
    </div>

    <?php include "../includes/footer.php"; ?>
</body>
</html>

<?php $stmt->close(); $conn->close(); ?>
