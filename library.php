<?php
session_start();

require 'dbconn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch purchased books
$sqlLibrary = "SELECT b.*, pb.purchase_date
               FROM books b
               INNER JOIN purchased_books pb ON b.id = pb.book_id
               WHERE pb.user_id = ?";
$stmtLibrary = $conn->prepare($sqlLibrary);
$stmtLibrary->bind_param("i", $userId);
$stmtLibrary->execute();
$resultLibrary = $stmtLibrary->get_result();

// Function to display a book item
function displayBookItem($book) {
    echo '<div class="product-item" data-id="' . $book['id'] . '" data-title="' . htmlspecialchars($book['title']) . '" data-description="' . htmlspecialchars($book['description']) . '" data-price="$' . $book['price'] . '" data-url="readBook.php?book_id=' . $book['id'] . '" data-date-published="' . $book['date_published'] . '" data-date-added="' . $book['date_added'] . '">';
    echo '<img src="' . htmlspecialchars($book['image_url']) . '" alt="' . htmlspecialchars($book['title']) . '">';
    echo '<div class="product-item-content">';
    echo '<h3>' . htmlspecialchars($book['title']) . '</h3>';
    echo '<p>$' . $book['price'] . '</p>';
    echo '</div>';
    echo '<a href="readBook.php?book_id=' . $book['id'] . '" class="read-button">Read Book</a>';
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library</title>
    <style>
          body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .book-list {
            list-style: none;
            padding: 0;
        }
        .book-item {
            margin-bottom: 20px;
        }
        .book-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
        }
        .book-author, .purchase-date {
            margin-top: 5px;
            font-size: 1.2em;
            color: #555;
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
            margin-bottom: 20px;
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

        .read-button {
            display: inline-block;
            padding: 8px 12px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .read-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="container">
    <h1>My Library</h1>
    <div class="product-catalog">
        <?php while ($book = $resultLibrary->fetch_assoc()): ?>
            <?php displayBookItem($book); ?>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
