<?php
session_start();
include 'dbconn.php';

if (isset($_SESSION['username'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];

    $sql = "INSERT INTO wishlists (user_id, book_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $book_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Book added to wishlist']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add book to wishlist']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
}
?>