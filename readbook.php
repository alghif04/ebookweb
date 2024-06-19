<?php
session_start();
require 'dbconn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['book_id'])) {
    header("Location: library.php");
    exit();
}

$bookId = $_GET['book_id'];
$userId = $_SESSION['user_id'];

// Check if the user owns the book
$sqlCheckOwnership = "SELECT * FROM purchased_books WHERE user_id = ? AND book_id = ?";
$stmtCheckOwnership = $conn->prepare($sqlCheckOwnership);
$stmtCheckOwnership->bind_param("ii", $userId, $bookId);
$stmtCheckOwnership->execute();
$resultCheckOwnership = $stmtCheckOwnership->get_result();

if ($resultCheckOwnership->num_rows == 0) {
    echo "You do not own this book.";
    exit();
}

// Fetch book details
$sqlBook = "SELECT * FROM books WHERE id = ?";
$stmtBook = $conn->prepare($sqlBook);
$stmtBook->bind_param("i", $bookId);
$stmtBook->execute();
$resultBook = $stmtBook->get_result();

if ($resultBook->num_rows > 0) {
    $book = $resultBook->fetch_assoc();
} else {
    echo "Book not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Book</title>
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
            text-align: center;
        }
        .book-title {
            font-size: 2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        iframe {
            width: 100%;
            height: 500px; /* Adjust the height to make the window bigger */
            border: none;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="container">
    <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
    <iframe src="<?php echo htmlspecialchars($book['pdf_url']); ?>" allowfullscreen></iframe>
</div>
</body>
</html>
