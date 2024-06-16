<?php
session_start();
require 'dbconn.php';

// Check if the book_id parameter is set in the URL
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];

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

    // Handle rating submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
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

    // Fetch existing ratings and comments
    $sql = "SELECT r.rating, r.comment, r.created_at, u.username
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
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .book-header {
            display: flex;
            align-items: flex-start;
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
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .author, .publish-info, .buttons {
            margin-bottom: 10px;
        }
        .author {
            font-size: 1.2em;
        }
        .publish-info {
            font-size: 1em;
        }
        .pages {
            display: inline-block;
            margin-left: 10px;
        }
        .pages span {
            display: block;
        }
        .wishlist-button, .purchase-button {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .wishlist-button {
            color: #333;
            font-size: 14px;
            border: 1px solid #ccc;
        }
        .purchase-button {
            background-color: #007bff;
            color: #fff;
            margin-right: 10px;
        }
        .wishlist-button:hover {
            background-color: #f5f5f5;
        }
        .about-section {
            cursor: pointer;
            color: #007bff;
            margin-bottom: 20px;
        }
        .description {
            margin-bottom: 20px;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
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
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            z-index: 1000;
        }
        .popup h2 {
            margin-top: 0;
        }
        .popup .close {
            cursor: pointer;
            color: #007bff;
        }
        .popup .close:hover {
            color: #0056b3;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .rating-form {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        .rating-form select, .rating-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .rating-form button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .rating-form button:hover {
            background-color: #0056b3;
        }
        .ratings-list {
            margin-top: 20px;
        }
        .rating-item {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }
        .rating-item:last-child {
            border-bottom: none;
        }
        .rating-item .rating {
            font-weight: bold;
        }
        .rating-item .username {
            color: #555;
        }
        .rating-item .comment {
            margin-top: 5px;
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
                <a href="#" class="purchase-button">Purchase</a>
                <a href="#" class="wishlist-button">Add to Wishlist</a>
            </div>
        </div>
        <div class="book-image">
        <img src="<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
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
            <select name="rating" id="rating" required>
                <option value="" disabled selected>Select your rating</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>
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
