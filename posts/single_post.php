<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

$post_id = $_GET["id"] ?? 0;

// Fetch post details
$stmt = $conn->prepare("SELECT posts.title, posts.content, users.username, posts.created_at 
                        FROM posts 
                        JOIN users ON posts.author_id = users.id 
                        WHERE posts.id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($title, $content, $author, $created_at);
$stmt->fetch();

if ($stmt->num_rows == 0) {
    echo "Post not found.";
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> | Daily Muse</title>
    <link rel="stylesheet" href="../assets/css/single_post.css">
    <link rel="stylesheet" href="../assets/css/header_footer.css">
</head>
<body>

    <?php include "../includes/header.php"; ?>

    <main class="post-container">
        <article class="post">
            <h1 class="post-title"><?php echo htmlspecialchars($title); ?></h1>
            <p class="post-meta">By <strong><?php echo htmlspecialchars($author); ?></strong> | <?php echo $created_at; ?></p>
            <div class="post-content">
                <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
            </div>
        </article>

        <section class="comments-section">
            <h2>Comments</h2>

            <?php
            $comment_stmt = $conn->prepare("SELECT comments.id, comments.content, users.username, comments.created_at, comments.user_id
                                            FROM comments 
                                            JOIN users ON comments.user_id = users.id 
                                            WHERE comments.post_id = ? 
                                            ORDER BY comments.created_at DESC");
            $comment_stmt->bind_param("i", $post_id);
            $comment_stmt->execute();
            $result = $comment_stmt->get_result();
            ?>
            
            <?php if ($result->num_rows > 0): ?>
                <?php while ($comment = $result->fetch_assoc()): ?>
                    <div class="comment">
                        <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong>: <?php echo nl2br(htmlspecialchars($comment["content"])); ?></p>
                        <small class="comment-meta"><?php echo $comment['created_at']; ?></small>
                        
                        <?php if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $comment["user_id"]): ?>
                            <form method="POST" action="delete_comment.php" class="delete-comment-form">
                                <input type="hidden" name="comment_id" value="<?php echo $comment["id"]; ?>">
                                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-comments">No comments yet. Be the first to comment!</p>
            <?php endif; ?>

            <?php if (isset($_SESSION["user_id"])): ?>
                <h3>Add a Comment</h3>
                <form method="POST" action="add_comment.php" class="comment-form">
                    <div class="input-group">
                        <textarea name="content" required class="comment-input" placeholder="Write a comment..."></textarea>
                    </div>
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>"><br>
                    <button type="submit" class="btn-submit">Submit Comment</button>
                </form>
            <?php else: ?>
                <p class="login-reminder"><a href="../auth/login.php">Login</a> to add a comment.</p>
            <?php endif; ?>
        </section>

        <p class="back-link"><a href="../index.php">← Back to Home</a></p>
    </main>

    <?php include "../includes/footer.php"; ?>

</body>
</html>

<?php $conn->close(); ?>