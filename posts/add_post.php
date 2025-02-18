<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Redirect if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $author_id = $_SESSION["user_id"];
    $category_id = $_POST["category_id"] ?? NULL;

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO posts (title, content, author_id, category_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $title, $content, $author_id, $category_id);

        if ($stmt->execute()) {
            echo "Post added Successfully! <a href='../index.php'>Go to Home</a>";
        } else {
            echo "Error: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "All field are required to fill up!";
    }
}

// Fetch categories
$category_result = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post | Daily Muse</title>
    <link rel="stylesheet" href="../assets/css/add_post_style.css">
    <link rel="stylesheet" href="../assets/css/header_footer.css">
    <link rel="shortcut icon" href="../assets/images/logo.png" type="image/x-icon">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <main class="post-container">
        <h2>Add a New Post</h2>
        <form method="POST" class="post-form">
            <div class="input-group">
                <input type="text" name="title" id="title" placeholder="Enter Title" required>
            </div>
            <div class="input-group">
                <textarea name="content" id="content" placeholder="Write your post here..." required></textarea>
            </div>
            <div class="input-group">
                <select name="category_id" id="category" required>
                    <option value="">Select a Category</option>
                    <?php while ($category = $category_result->fetch_assoc()): ?>
                        <option value="<?php echo $category["id"]; ?>">
                            <?php echo htmlspecialchars($category["name"]); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn-submit">Publish Post</button>
        </form>
        <p><a href="../index.php" class="back-link">← Back to Home</a></p>
    </main>

    <?php include "../includes/footer.php"; ?>
</body>
</html>


<?php $conn->close(); ?>