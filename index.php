<?php
session_start();
include_once __DIR__ . '/includes/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Muse</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header_footer.css">
    <script defer src="assets/js/script.js"></script>
    <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
</head>

<body>
    <?php include "includes/header.php"; ?>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <h2>Welcome to Daily Muse</h2>
            <p>Your daily source of inspiration and creativity.</p>
        </section>
        <br>

        <!-- Featured Post -->
        <section class="featured-post">
            <h2>Featured Post</h2>
            <hr>
            <?php
            $query = "SELECT * FROM posts ORDER BY RAND() LIMIT 1"; // Random featured post
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $post = mysqli_fetch_assoc($result);
                echo "<article>
                    <h3>Title: {$post['title']}</h3>
                    <p>Content: " . substr($post['content'], 0, 150) . "...</p>
                    <a href='posts/single_post.php?id={$post['id']}'>Read More</a>
                  </article>";
            } else {
                echo "<p>No featured post available.</p>";
            }
            ?>
        </section>
        <hr>

        <!-- Latest Posts -->
        <section class="latest-posts">
            <h2>Latest Posts</h2>
            <hr>
            <?php
            $query = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 5"; // Fetch latest 5 posts
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($post = mysqli_fetch_assoc($result)) {
                    echo "<article>
                        <h3>Title: {$post['title']}</h3>
                        <p>Content: " . substr($post['content'], 0, 100) . "...</p>
                        <a href='posts/single_post.php?id={$post['id']}'>Read More</a>
                      </article>";
                }
            } else {
                echo "<p>No posts available.</p>";
            }
            ?>
            <br><br>
        </section>
    </main>

    <?php include "includes/footer.php"; ?>
</body>

</html>