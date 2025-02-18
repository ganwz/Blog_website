<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../index.php");
    exit();
}

// Handle category addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);

    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        echo "Category added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}

// Handle category deletion
if (isset($_GET['delete'])) {
    $category_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        echo "Category deleted successfully!";
    } else {
        echo "Error deleting category: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all categories
$result = $conn->query("SELECT * FROM categories ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories | Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <?php include "../includes/admin_sidebar.php"; ?>

    <!-- Main Content -->
    <main class="content">
        <header>
            <h1>Manage Categories</h1>
        </header>

        <!-- Add Category Form -->
        <section>
            <h2>Add New Category</h2>
            <form method="POST">
                <label for="category_name">Category Name:</label><br>
                <input type="text" id="category_name" name="category_name" placeholder="Enter category name" required>
                <button type="submit" name="add_category">Add Category</button>
            </form>
        </section>

        <!-- Category List -->
        <section>
            <h2>Existing Categories</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>
                            <a href="manage_categories.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </section>
    </main>

</body>

</html>