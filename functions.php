<?php
function displayBook($book, $conn, $userId = null) {
    $bookId = $book['id'];
    $bookTitle = htmlspecialchars($book['title']);
    $bookDescription = htmlspecialchars($book['description']);
    $bookPrice = $book['price'];
    $bookImageUrl = htmlspecialchars($book['image_url']);
    $bookUrl = 'viewBook.php?id=' . $bookId;

    if ($userId) {
        $sql = "SELECT 1 FROM purchased_books WHERE user_id = ? AND book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $bookId);
        $stmt->execute();
        $purchaseResult = $stmt->get_result();

        $book['purchased'] = $purchaseResult->num_rows > 0;
    } else {
        $book['purchased'] = false;
    }

    echo '<div class="product-item" data-id="' . $bookId . '" data-title="' . $bookTitle . '" data-description="' . $bookDescription . '" data-price="$' . $bookPrice . '" data-url="' . $bookUrl . '" data-date-published="' . $book['date_published'] . '" data-date-added="' . $book['date_added'] . '">';
    echo '<img src="' . $bookImageUrl . '" alt="' . $bookTitle . '">';
    echo '<div class="product-item-content">';
    echo '<h3>' . $bookTitle . '</h3>';
    echo '<p>$' . $bookPrice . '</p>';
    echo '</div>';
    if ($book['purchased']) {
        echo '<button class="read-button" onclick="readBook(' . $bookId . ')">Read the Book</button>';
    } else {
        echo '<button class="wishlist-button" onclick="addToWishlist(' . $bookId . ')">♡ Wishlist</button>';
    }
    echo '</div>';
}
?>
