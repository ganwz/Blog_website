<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST["post_id"];
    $user_id = $_SESSION["user_id"];
    $content = trim($_POST["content"]);

    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $post_id, $user_id, $content);

        if ($stmt->execute()) {
            header("Location: single_post.php?id=" . $post_id);
            exit;
        } else {
            echo "Error: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Comment cannot be empty";
    }
}

$conn->close();
?>