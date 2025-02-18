<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../index.php");
    exit();
}

// Get the post ID
if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

// Fetch categories
$categories = $conn->query("SELECT id, name FROM categories");

$post_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT title, content, author_id, category_id FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Post not found.";
    exit();
}

$post = $result->fetch_assoc();
$stmt->close();
$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;

// Handle post update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("ssii", $title, $content, $category_id, $post_id);

    if ($stmt->execute()) {
        echo "Post updated successfully!";
    } else {
        echo "Error updating post: " . $conn->error;
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
    <title>Edit Post</title>
    <link rel="stylesheet" href="../assets/css/admin_edit_post.css">
</head>

<body>
    <h2>Edit Post</h2>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        <br>
        <label>Content:</label>
        <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
        <br>
        <label>Category:</label>
        <select name="category_id" required>
            <option value="" disabled <?= empty($post['category_id']) ? 'selected' : '' ?>>Select category</option>
            <?php while ($cat = $categories->fetch_assoc()) : ?>
                <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $post['category_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br>
        <button type="submit">Update Post</button>
    </form>
    <a href="manage_posts.php">← Back to Manage Posts</a>
</body>

</html>