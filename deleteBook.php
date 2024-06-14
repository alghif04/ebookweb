<?php
include 'dbconn.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];

    // Get image URL before deleting the book
    $getImageUrlQuery = "SELECT image_url FROM books WHERE id = $bookId";
    $imageResult = $conn->query($getImageUrlQuery);
    if ($imageResult->num_rows > 0) {
        $row = $imageResult->fetch_assoc();
        $imageUrl = $row['image_url'];
        
        // Delete book from books table
        $deleteBookQuery = "DELETE FROM books WHERE id = $bookId";
        if ($conn->query($deleteBookQuery) === TRUE) {
            // Delete book from wishlist table
            $deleteWishlistQuery = "DELETE FROM wishlist WHERE book_id = $bookId";
            $conn->query($deleteWishlistQuery);

            // Delete image file from uploads folder
            if (file_exists($imageUrl)) {
                unlink($imageUrl);
            }

            echo json_encode(['status' => 'success', 'message' => 'Book deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting book from database.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Book not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}

$conn->close();
?>
