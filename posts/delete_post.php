<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$post_id = $_GET["id"] ?? 0;
$author_id = $_SESSION["user_id"];

$stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND author_id = ?");
$stmt->bind_param("ii", $post_id, $author_id);

if ($stmt->execute()) {
    echo "Post Delete Successfully! <a href='../index.php'>Go to Home</a>";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>