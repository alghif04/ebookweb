<?php
session_start();
include 'dbconn.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch unique wishlist items
    $sql = "SELECT DISTINCT books.id, books.title, books.description, books.price, books.image_url, books.date_published
            FROM books
            JOIN wishlists ON books.id = wishlists.book_id
            WHERE wishlists.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $wishlisted_books = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $wishlisted_books[] = $row;
        }
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist</title>
    <style>
       * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-content {
            position: relative;
            background-color: #eee;
            min-height: 100vh;
            top: 0;
            left: 80px;
            transition: all 0.5s ease;
            width: calc(100% - 80px);
            padding: 1rem;
        }

        .sort-box {
            display: flex;
            justify-content: flex-start;
            padding: 10px;
            border-bottom: 1px solid #333;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .SortContainer {
            display: inline-block;
            margin-right: 20px;
            vertical-align: middle;
            position: relative;
        }

        .SortLabel a {
            text-decoration: none;
            color: #333;
            position: relative;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .SortLabel a:hover {
            color: #555;
        }

        .SortLabel a::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .SortLabel a:hover::after,
        .SortLabel a:focus::after {
            opacity: 1;
        }

        .SortLabel {
            font-size: 1.2rem;
            font-weight: bold;
            margin-right: 5px;
            z-index: 2;
        }

        .SortValue {
            font-size: 1.2rem;
        }

        .product-catalog {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 200px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-item:hover {
            transform: scale(1.05);
        }

        .product-item img {
            width: 100%;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .product-item-content {
            flex: 1;
        }

        .product-preview {
            position: absolute;
            top: 10px;
            left: 220px;
            width: 300px;
            height: 400px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 10;
            padding: 10px;
        }

        .product-preview h3,
        .product-preview p,
        .product-preview a {
            margin-bottom: 10px;
        }

        .product-preview a {
            display: block;
            color: #007bff;
            text-decoration: none;
        }

        .product-preview a:hover {
            text-decoration: underline;
        }

        .wishlist-button {
            display: inline-block;
            padding: 8px 12px;
            margin-top: 10px;
            background-color: #f8f9fa;
            border: 1px solid #007bff;
            border-radius: 5px;
            color: #007bff;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .wishlist-button:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
<script>
    function deleteFromWishlist(bookId) {
        if (confirm("Are you sure you want to delete this book from your wishlist?")) {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert(xhr.responseText);
                        // Reload the wishlist after deletion
                        location.reload();
                    } else {
                        alert("Error deleting book from wishlist.");
                    }
                }
            };

            xhr.open("POST", "delete_from_wishlist.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("book_id=" + bookId);
        }
    }
</script>

</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <h1>My Wishlist</h1>
        <div class="product-catalog">
        <?php
        if (empty($wishlisted_books)) {
            echo "<p>Your wishlist is empty.</p>";
        } else {
            $addedBookIds = []; // Array to track added book IDs

            foreach ($wishlisted_books as $book) {
                $bookId = $book['id'];
                // Check if the book ID is already added, skip if so
                if (in_array($bookId, $addedBookIds)) {
                    continue;
                }

                // Add book ID to the tracking array
                $addedBookIds[] = $bookId;

                // Display the book in the wishlist
                echo '<div class="product-item">';
                echo '<img src="' . htmlspecialchars($book['image_url']) . '" alt="' . htmlspecialchars($book['title']) . '">';
                echo '<div class="product-item-content">';
                echo '<h3>' . htmlspecialchars($book['title']) . '</h3>';
                echo '<p>' . htmlspecialchars($book['description']) . '</p>';
                echo '<p>Published: ' . date('Y', strtotime($book['date_published'])) . '</p>';
                echo '</div>';
                echo '<button onclick="deleteFromWishlist(' . $book['id'] . ')">Remove from Wishlist</button>';
                echo '</div>';
            }
        }
        ?>
    </div>
</body>
</html>
