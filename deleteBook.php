<?php
include 'dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];

    try {
        // Get image URL and PDF URL before deleting the book
        $getUrlsQuery = "SELECT image_url, pdf_url FROM books WHERE id = ?";
        $stmtUrls = $conn->prepare($getUrlsQuery);
        $stmtUrls->bind_param("i", $bookId);
        $stmtUrls->execute();
        $urlsResult = $stmtUrls->get_result();

        if ($urlsResult->num_rows > 0) {
            $row = $urlsResult->fetch_assoc();
            $imageUrl = $row['image_url'];
            $pdfUrl = $row['pdf_url'];

            // Delete image file from uploads folder
            if (file_exists($imageUrl)) {
                unlink($imageUrl);
            }

            // Delete PDF file
            if (file_exists($pdfUrl)) {
                unlink($pdfUrl);
            }

            // Delete book from books table
            $deleteBookQuery = "DELETE FROM books WHERE id = ?";
            $stmtDeleteBook = $conn->prepare($deleteBookQuery);
            $stmtDeleteBook->bind_param("i", $bookId);

            if ($stmtDeleteBook->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Deletion process completed successfully.<br>']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error deleting book from database: ' . $stmtDeleteBook->error]);
            }

            $stmtDeleteBook->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Book not found.']);
        }

        $stmtUrls->close();
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}

$conn->close();
?>
