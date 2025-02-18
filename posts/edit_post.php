<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Redirect if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$post_id = $_GET["id"] ?? 0;
$author_id = $_SESSION["user_id"];

$message = ""; // To store success/error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_title = trim($_POST["title"]);
    $new_content = trim($_POST["content"]);

    if (!empty($new_title) && !empty($new_content)) {
        $update_stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND author_id = ?");
        $update_stmt->bind_param("ssii", $new_title, $new_content, $post_id, $author_id);

        if ($update_stmt->execute()) {
            $message = "<p class='success-message'>Post updated successfully!</p>";
        } else {
            $message = "<p class='error-message'>Error updating post: " . $conn->error . "</p>";
        }

        $update_stmt->close();
    } else {
        $message = "<p class='error-message'>All fields are required!</p>";
    }
}

// ✅ **Re-fetch updated data**
$stmt = $conn->prepare("SELECT title, content FROM posts WHERE id = ? AND author_id = ?");
$stmt->bind_param("ii", $post_id, $author_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($title, $content);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post | Daily Muse</title>
    <link rel="stylesheet" href="../assets/css/edit_post.css">
    <link rel="stylesheet" href="../assets/css/header_footer.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>  

    <div class="container">
        <h2>Edit Post</h2>

        <?php echo $message; ?>

        <form method="POST">
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

            <label>Content:</label>
            <textarea name="content" rows="8" required><?php echo htmlspecialchars($content); ?></textarea>

            <button type="submit">Update Post</button>
        </form>

        <p><a href="../users/my_blogs.php">Back to My Blogs</a></p>
    </div>

    <?php include "../includes/footer.php"; ?>
</body>
</html>
