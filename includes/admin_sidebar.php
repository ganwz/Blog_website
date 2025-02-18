<?php
// Get the current page name from the URL
$currentUrl = $_SERVER['REQUEST_URI'];
$currentPage = basename(parse_url($currentUrl, PHP_URL_PATH), ".php");
?>

<nav class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="../admin/dashboard.php" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">Dashboard</a></li>
        <li><a href="../admin/manage_posts.php" class="<?= $currentPage === 'manage_posts' ? 'active' : '' ?>">Manage Posts</a></li>
        <li><a href="../admin/manage_categories.php" class="<?= $currentPage === 'manage_categories' ? 'active' : '' ?>">Manage Categories</a></li>
        <li><a href="../admin/manage_comments.php" class="<?= $currentPage === 'manage_comments' ? 'active' : '' ?>">Manage Comments</a></li>
        <li><a href="../admin/manage_users.php" class="<?= $currentPage === 'manage_users' ? 'active' : '' ?>">Manage Users</a></li>
        <li><a href="../auth/logout.php" class="logout">Logout</a></li>
    </ul>
</nav>
