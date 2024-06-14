<?php
session_start();
include 'dbconn.php';

if (isset($_SESSION['user_id']) && isset($_POST['book_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];

    // Check if the book exists in the wishlist for the user
    $check_sql = "SELECT * FROM wishlist WHERE user_id = ? AND book_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $book_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Book exists in the wishlist, delete it
        $delete_sql = "DELETE FROM wishlist WHERE user_id = ? AND book_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $user_id, $book_id);

        if ($delete_stmt->execute()) {
            echo "Book deleted from wishlist successfully.";
        } else {
            echo "Error deleting book from wishlist.";
        }
    } else {
        echo "Book not found in wishlist.";
    }

    $check_stmt->close();
    $delete_stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
