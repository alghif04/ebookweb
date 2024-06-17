<?php
include 'dbconn.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];

    // Get image URL and PDF URL before deleting the book
    $getUrlsQuery = "SELECT image_url, pdf_url FROM books WHERE id = $bookId";
    $urlsResult = $conn->query($getUrlsQuery);
    if ($urlsResult->num_rows > 0) {
        $row = $urlsResult->fetch_assoc();
        $imageUrl = $row['image_url'];
        $pdfUrl = $row['pdf_url'];

        // Debugging output
        echo "Image URL: $imageUrl<br>";
        echo "PDF URL: $pdfUrl<br>";

        // Delete book from books table
        $deleteBookQuery = "DELETE FROM books WHERE id = $bookId";
        if ($conn->query($deleteBookQuery) === TRUE) {
            // Delete book from wishlist table
            $deleteWishlistQuery = "DELETE FROM wishlist WHERE book_id = $bookId";
            $conn->query($deleteWishlistQuery);

            // Delete image file from uploads folder and resized image if it exists
            if (file_exists($imageUrl)) {
                unlink($imageUrl);
                echo "Image file deleted<br>";
            }
            $resizedImageUrl = 'uploads/resized_' . basename($imageUrl);
            if (file_exists($resizedImageUrl)) {
                unlink($resizedImageUrl);
                echo "Resized image file deleted<br>";
            }

            // Delete PDF file if it exists
            if (file_exists($pdfUrl)) {
                unlink($pdfUrl);
                echo "PDF file deleted<br>";
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
