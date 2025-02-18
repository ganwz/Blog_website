<?php
session_start();
include_once __DIR__ . '/../includes/db_connect.php';

// Redirect if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment_id = $_POST["comment_id"];
    $post_id = $_POST["post_id"];
    $user_id = $_SESSION["user_id"];

    $stmt = $conn->prepare("SELECT id FROM comments WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $comment_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Delete comment
        $delete_stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        $delete_stmt->bind_param("i", $comment_id);
        if ($delete_stmt->execute()) {
            header("Location: single_post.php?id=" . $post_id);
            exit;
        } else {
            echo "Error: " . $conn->error;
        }

        $delete_stmt->close();
    } else {
        echo "Unauthorized action!";
    }

    $stmt->close();
}

$conn->close();
?>