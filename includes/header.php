<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Muse</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script defer src="../assets/js/script.js"></script>
</head>
<body>

<header>
    <div class="logo">
        <h1><a href="http://localhost/Blog_website/index.php">Daily Muse</a></h1>
    </div>
    <button class="menu-toggle">&#9776;</button> <!-- Mobile Menu Button -->
    <nav>
        <ul class="nav-links">
            <li><a href="http://localhost/Blog_website/index.php">Home</a></li>
            <li><a href="http://localhost/Blog_website/posts/add_post.php">New Post</a></li>
            <li><a href="http://localhost/Blog_website/categories.php">Categories</a></li>

            <?php if (isset($_SESSION["user_id"])): ?>
                <li class="user-menu">
                    <button class="dropdown-toggle">
                        <?php echo htmlspecialchars($_SESSION["username"]); ?> ▼
                    </button>
                    <ul class="dropdown">
                        <li><a href="http://localhost/Blog_website/users/my_blogs.php">My Blog</a></li>
                        <li><a href="http://localhost/Blog_website/users/update_profile.php">Update Profile</a></li>
                        <li><a href="http://localhost/Blog_website/auth/logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="http://localhost/Blog_website/auth/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
