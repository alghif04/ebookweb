<?php
session_start();
require 'dbconn.php';

// Check if the book_id parameter is set in the URL
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Fetch book details from the database
    $sql = "SELECT b.*, a.name AS author_name, b.language AS book_language, GROUP_CONCAT(g.name SEPARATOR ', ') AS genres
            FROM books b
            LEFT JOIN authors a ON b.author_id = a.id
            LEFT JOIN book_genres bg ON b.id = bg.book_id
            LEFT JOIN genres g ON bg.genre_id = g.id
            WHERE b.id = ?
            GROUP BY b.id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        header("Location: index.php");
        exit();
    }
    
        // Check if the user has already purchased the book
        $isPurchased = false;
        if ($userId) {
            $sql = "SELECT 1 FROM purchased_books WHERE user_id = ? AND book_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $bookId);
            $stmt->execute();
            $purchaseResult = $stmt->get_result();
            if ($purchaseResult->num_rows > 0) {
                $isPurchased = true;
            }
        }

    // Handle rating submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && !isset($_POST['delete_comment'])) {
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $userId = $_SESSION['user_id'];

        $sql = "INSERT INTO ratings (book_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $bookId, $userId, $rating, $comment);
        if ($stmt->execute()) {
            header("Location: viewBook.php?id=$bookId");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Handle comment deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment']) && isset($_SESSION['user_id'])) {
        $commentId = $_POST['comment_id'];
        $userId = $_SESSION['user_id'];
        $isAdmin = $_SESSION['is_admin'];

        // Check if the user is the owner of the comment or an admin
        $sql = "DELETE FROM ratings WHERE id = ? AND (user_id = ? OR ? = 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $commentId, $userId, $isAdmin);
        $stmt->execute();

        header("Location: viewBook.php?id=$bookId");
        exit();
    }

    // Fetch existing ratings and comments
    $sql = "SELECT r.id, r.rating, r.comment, r.created_at, r.user_id, u.username
            FROM ratings r
            JOIN user_details u ON r.user_id = u.user_id
            WHERE r.book_id = ?
            ORDER BY r.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $ratingsResult = $stmt->get_result();

    $ratings = [];
    if ($ratingsResult->num_rows > 0) {
        while ($row = $ratingsResult->fetch_assoc()) {
            $ratings[] = $row;
        }
    }

    $imageUrl = htmlspecialchars($book['image_url']);

    // Resize the image to be 240x345
    list($width, $height) = getimagesize($imageUrl);
    $newWidth = 240;
    $newHeight = 345;
    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

    switch (exif_imagetype($imageUrl)) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($imageUrl);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($imageUrl);
            break;
        default:
            // Handle unsupported image types
            break;
    }

    // Resize and save the image
    imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    $resizedImagePath = 'uploads/resized_' . basename($imageUrl);
    imagejpeg($resizedImage, $resizedImagePath);

    // Free up memory
    imagedestroy($sourceImage);
    imagedestroy($resizedImage);

    // Update the image URL with the resized image path
    $imageUrl = $resizedImagePath;

} else {
    header("Location: index.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?></title>
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
        .book-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .book-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding-right: 20px;
        }
        .book-title {
            font-size: 2.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .author, .publish-info, .buttons {
            margin-bottom: 10px;
            color: #555;
        }
        .author {
            font-size: 1.5em;
        }
        .publish-info {
            font-size: 1.1em;
        }
        .pages {
            display: inline-block;
            margin-left: 10px;
            font-size: 0.9em;
            color: #777;
        }
        .pages span {
            display: block;
        }
        .wishlist-button, .purchase-button {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-right: 10px;
        }
        .purchase-button {
            background-color: #007bff;
            color: #ffffff;
        }
        .wishlist-button {
            color: #333;
            border: 1px solid #ccc;
        }
        .purchase-button:hover {
            background-color: #0056b3;
        }
        .wishlist-button:hover {
            background-color: #e0e0e0;
        }
        .about-section {
            cursor: pointer;
            color: #007bff;
            margin-bottom: 20px;
        }
        .description {
            margin-bottom: 20px;
            color: #444;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
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
        .popup hr {
            margin: 10px 0;
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
        .rating-form {
            margin-top: 30px;
            background-color: #f8f8f8;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .rating-form h3 {
            margin-top: 0;
        }
        .rating-form label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }
        .rating-form .stars {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
        }
        .rating-form .stars input {
            display: none;
        }
        .rating-form .stars label {
            font-size: 2em;
            color: #ccc;
            cursor: pointer;
        }
        .rating-form .stars input:checked ~ label,
        .rating-form .stars input:hover ~ label,
        .rating-form .stars input:checked ~ label ~ label,
        .rating-form .stars input:hover ~ label ~ label {
            color: #f39c12;
        }
        .rating-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .rating-form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .rating-form button:hover {
            background-color: #0056b3;
        }
        .ratings-list {
            margin-top: 30px;
        }
        .ratings-list .rating-item {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }
        .ratings-list .rating-item .rating {
            font-weight: bold;
            color: #333;
        }
        .ratings-list .rating-item .username {
            color: #777;
        }
        .ratings-list .rating-item .comment {
            margin-top: 5px;
            color: #444;
        }
        .ratings-list .rating-item .date {
            font-size: 0.9em;
            color: #999;
        }
        .ratings-list .rating-item .delete-button {
            color: #e74c3c;
            cursor: pointer;
            font-size: 0.9em;
            margin-top: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="book-header">
        <div class="book-info">
            <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
            <div class="author"><?php echo htmlspecialchars($book['author_name']); ?></div>
            <div class="publish-info">
                <?php echo date('F j, Y', strtotime($book['date_published'])); ?>
                <div class="pages">
                    <span><?php echo $book['pages']; ?></span>
                    <span>Pages</span>
                </div>
            </div>
            <div class="buttons">
                <?php if ($isPurchased) { ?>
                    <a href="readBook.php?book_id=<?php echo $bookId; ?>" class="purchase-button">Read the Book</a>
                <?php } else { ?>
                    <a href="#" class="purchase-button" onclick="showCardSelection()">Purchase</a>
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <form action="viewBook.php?id=<?php echo $bookId; ?>" method="POST" style="display: inline;">
                            <input type="hidden" name="wishlist" value="1">
                            <button type="submit" class="wishlist-button">Add to Wishlist</button>
                        </form>
                    <?php } else { ?>
                        <a href="login.php" class="wishlist-button">Add to Wishlist</a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="book-image">
            <img src="<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" width="240" height="345">
        </div>
    </div>
    <div class="about-section" onclick="showPopup()">
        About this eBook &#x25BC;
    </div>
    <div class="description">
        <p>
            <?php 
            $description = htmlspecialchars($book['description']);
            if (strlen($description) > 200) {
                echo substr($description, 0, 200) . '...';
            } else {
                echo $description;
            }
            ?>
        </p>
    </div>
    <a href="javascript:history.back()" class="back-button">Back</a>

    <!-- Rating Form -->
    <?php if (isset($_SESSION['user_id'])) { ?>
    <div class="rating-form">
        <h3>Rate this Book</h3>
        <form action="viewBook.php?id=<?php echo $bookId; ?>" method="POST">
            <label for="rating">Rating:</label>
            <div class="stars">
                <input type="radio" id="star5" name="rating" value="5" required>
                <label for="star5">&#9733;</label>
                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4">&#9733;</label>
                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1">&#9733;</label>
            </div>
            <label for="comment">Comment:</label>
            <textarea name="comment" id="comment" rows="4" required></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>
    <?php } else { ?>
    <p>Please <a href="login.php">log in</a> to rate and comment on this book.</p>
    <?php } ?>

<!-- Ratings List -->
<div class="ratings-list">
    <h3>User Ratings and Comments</h3>
    <?php foreach ($ratings as $rating) { ?>
    <div class="rating-item">
        <div class="rating">Rating: <?php echo $rating['rating']; ?>/5</div>
        <div class="username">By: <?php echo htmlspecialchars($rating['username']); ?></div>
        <div class="comment"><?php echo htmlspecialchars($rating['comment']); ?></div>
        <div class="date">Posted on: <?php echo date('F j, Y', strtotime($rating['created_at'])); ?></div>
        <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $rating['user_id'] || $_SESSION['is_admin'] == 1)) { ?>
            <div class="delete-button">
                <form action="viewBook.php?id=<?php echo $bookId; ?>" method="POST">
                    <input type="hidden" name="comment_id" value="<?php echo $rating['id']; ?>">
                    <input type="hidden" name="delete_comment" value="1">
                    <button type="submit" class="delete-comment">Delete</button>
                </form>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
</div>

</div>

<div class="overlay" onclick="hidePopup()"></div>

<div class="popup" id="popup">
    <h2><?php echo htmlspecialchars($book['title']); ?></h2>
    <p><?php echo htmlspecialchars($book['description']); ?></p>
    <hr>
    <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author_name']); ?></p>
    <p><strong>Language:</strong> <?php echo htmlspecialchars($book['book_language']); ?></p>
    <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['isbn']); ?></p>
    <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genres']); ?></p>
    <p><strong>Date Published:</strong> <?php echo date('F j, Y', strtotime($book['date_published'])); ?></p>
    <p><strong>Pages:</strong> <?php echo $book['pages']; ?></p>
    <div class="close" onclick="hidePopup()">Close</div>
</div>
<!-- Card Selection Popup -->
<div class="popup" id="cardSelectionPopup" style="display: none;">
    <h2>Select Card for Purchase</h2>
    <?php if (!empty($_SESSION['user_cards'])) { ?>
        <form action="purchase.php?book_id=<?php echo $book['id']; ?>" method="POST">
            <label for="card">Select Card:</label>
            <select name="card" id="card" required>
                <?php foreach ($_SESSION['user_cards'] as $card) { ?>
                    <option value="<?php echo $card['card_id']; ?>">
                        Card ending with <?php echo substr($card['card_number'], -4); ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit">Proceed to Purchase</button>
        </form>
    <?php } else { ?>
        <p>Please setup your card first.</p>
    <?php } ?>
    <div class="close" onclick="hideCardSelection()">Close</div>
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

document.addEventListener('DOMContentLoaded', function () {
    const wishlistButton = document.querySelector('.wishlist-button');

    wishlistButton.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        const bookId = <?php echo $bookId; ?>;

        // Simulate adding to wishlist
        addToWishlist(bookId, wishlistButton);
        wishlistButton.disabled = true;
    });
});

function addToWishlist(bookId, buttonElement) {
    console.log('Adding book to wishlist with ID:', bookId);

    // Disable the button to prevent multiple clicks
    buttonElement.disabled = true;

    <?php if (isset($_SESSION['username'])) { ?>
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "wishlist_handler.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                alert('Book added to wishlist');
            } else {
                alert(response.message);
            }
            buttonElement.disabled = false;
        }
    };

    xhr.send(`book_id=${bookId}`);
    <?php } else { ?>
    // Display a message or open a popup for non-logged-in users
    alert('Please log in to add this book to your wishlist.');
    <?php } ?>
}

function showCardSelection() {
    document.getElementById('cardSelectionPopup').style.display = 'block';
    document.querySelector('.overlay').style.display = 'block';
}

function hideCardSelection() {
    document.getElementById('cardSelectionPopup').style.display = 'none';
    document.querySelector('.overlay').style.display = 'none';
}
</script>
</body>
</html>