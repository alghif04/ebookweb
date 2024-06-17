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
        .read-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            cursor: pointer;
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
    <ul class="book-list">
        <?php while ($book = $resultLibrary->fetch_assoc()): ?>
        <li class="book-item">
            <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
            <div class="purchase-date">Purchased on: <?php echo htmlspecialchars($book['purchase_date']); ?></div>
            <a href="readBook.php?book_id=<?php echo $book['id']; ?>" class="read-button">Read Book</a>
        </li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>
