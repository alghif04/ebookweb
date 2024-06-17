<?php
session_start();
require 'dbconn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the book_id is set in the URL and card_id is in the POST data
if (isset($_GET['book_id']) && isset($_POST['card'])) {
    $bookId = $_GET['book_id'];
    $cardId = $_POST['card'];


    // Fetch book details from the database
    $sqlBook = "SELECT b.*, a.name AS author_name, b.language AS book_language, GROUP_CONCAT(g.name SEPARATOR ', ') AS genres
                FROM books b
                LEFT JOIN authors a ON b.author_id = a.id
                LEFT JOIN book_genres bg ON b.id = bg.book_id
                LEFT JOIN genres g ON bg.genre_id = g.id
                WHERE b.id = ?
                GROUP BY b.id";

    $stmtBook = $conn->prepare($sqlBook);
    $stmtBook->bind_param("i", $bookId);
    $stmtBook->execute();
    $resultBook = $stmtBook->get_result();

    if ($resultBook->num_rows > 0) {
        $book = $resultBook->fetch_assoc();
    } else {
        echo "Book not found<br>";
        header("Location: indexLogin.php");
        exit();
    }

    // Fetch card details from the database
    $userId = $_SESSION['user_id'];
    $sqlCard = "SELECT * FROM user_cards WHERE user_id = ? AND card_id = ?";
    $stmtCard = $conn->prepare($sqlCard);
    $stmtCard->bind_param("ii", $userId, $cardId);
    $stmtCard->execute();
    $resultCard = $stmtCard->get_result();

    if ($resultCard->num_rows > 0) {
        $card = $resultCard->fetch_assoc();
    } else {
        echo "Card not found<br>";
        header("Location: index.php");
        exit();
    }

    // Handle purchase confirmation
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_purchase'])) {
        echo "Confirm purchase<br>";

        // Insert the purchased book into the purchased_books table
        $sqlPurchase = "INSERT INTO purchased_books (user_id, book_id, purchase_date) VALUES (?, ?, NOW())";
        $stmtPurchase = $conn->prepare($sqlPurchase);
        $stmtPurchase->bind_param("ii", $userId, $bookId);

        if ($stmtPurchase->execute()) {
            echo "Purchase successful<br>";

            // Delete the book from the wishlist
            $sqlDeleteWishlist = "DELETE FROM wishlist WHERE user_id = ? AND book_id = ?";
            $stmtDeleteWishlist = $conn->prepare($sqlDeleteWishlist);
            $stmtDeleteWishlist->bind_param("ii", $userId, $bookId);
            $stmtDeleteWishlist->execute();

            echo "<script>alert('Purchase successful!'); window.location.href = 'library.php';</script>";
            exit();
        } else {
            echo "Error: " . $stmtPurchase->error . "<br>";
        }
    } else {
    }
} else {
    echo "Required parameters not set<br>";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Book</title>
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
        .book-info, .card-info {
            margin-bottom: 20px;
        }
        .book-title {
            font-size: 2em;
            font-weight: bold;
            color: #333;
        }
        .book-author, .card-details, .book-genres, .book-language {
            margin-top: 10px;
            font-size: 1.2em;
            color: #555;
        }
        .book-cover {
            width: 200px;
            height: auto;
            border-radius: 10px;
            margin-top: 20px;
        }
        .purchase-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        .purchase-button:hover {
            background-color: #0056b3;
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            z-index: 1000;
        }
        .popup h2 {
            margin-top: 0;
            color: #333;
        }
        .popup p {
            color: #444;
        }
        .popup .close {
            cursor: pointer;
            text-align: right;
            color: #007bff;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 500;
        }
        .popup button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .popup button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="book-info">
        <img src="<?php echo htmlspecialchars($book['image_url']); ?>" alt="Book Cover" class="book-cover">
        <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
        <div class="book-author"><?php echo htmlspecialchars($book['author_name']); ?></div>
        <div class="book-genres"><?php echo htmlspecialchars($book['genres']); ?></div>
        <div class="book-language"><?php echo htmlspecialchars($book['book_language']); ?></div>
    </div>
    <div class="card-info">
        <div class="card-details">Card ending with <?php echo substr($card['card_number'], -4); ?></div>
        <div class="card-details">Expiry Date: <?php echo htmlspecialchars($card['expiration_date']); ?></div>
    </div>
    <button class="purchase-button" onclick="showPopup()">Purchase</button>
</div>

<div class="overlay" onclick="hidePopup()"></div>

<div class="popup" id="popup">
    <h2>Confirm Purchase</h2>
    <p>Are you sure you want to purchase this book?</p>
    <form method="POST" action="">
        <input type="hidden" name="card" value="<?php echo htmlspecialchars($cardId); ?>">
        <button type="submit" name="confirm_purchase">Confirm</button>
    </form>
    <div class="close" onclick="hidePopup()">Close</div>
</div>

<script>
function showPopup() {
    document.getElementById('popup').style.display = 'block';
    document.querySelector('.overlay').style.display = 'block';
}

function hidePopup() {
    document.getElementById('popup').style.display = 'none';
    document.querySelector('.overlay').style.display = 'none';
}
</script>
</body>
</html>
