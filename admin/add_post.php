<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../index.php");
    exit();
}

// Fetch Categories
$categories = $conn->query("SELECT id, name FROM categories");

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author_id = $_SESSION['user_id']; // Logged-in admin

    $stmt = $conn->prepare("INSERT INTO posts (title, content, author_id, category_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $title, $content, $author_id, $category_id);

    if ($stmt->execute()) {
        echo "Post added successfully!";
    } else {
        echo "Error adding post: " . $conn->error;
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
    <title>Add New Post</title>
    <link rel="stylesheet" href="../assets/css/admin_edit_post.css">
</head>

<body>
    <h2>Add New Post</h2>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" required>
        <br>
        <label>Content:</label>
        <textarea name="content" required></textarea>
        <br>
        <label>Category:</label>
        <select name="category_id" required>
            <option value="" disabled <?= empty($post['category_id']) ? 'selected' : '' ?>>Select category</option>
            <?php while ($cat = $categories->fetch_assoc()) : ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endwhile; ?>
        </select>
        <br>
        <button type="submit">Add Post</button>
    </form>
    <a href="manage_posts.php">← Back to Manage Posts</a>
</body>

</html>