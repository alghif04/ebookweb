<?php
session_start();
// Include database connection
require 'dbconn.php';

// Check if the book_id parameter is set in the URL
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];

    // Fetch book details from the database based on the book_id
    $sql = "SELECT b.*, GROUP_CONCAT(g.name SEPARATOR ', ') AS genres
            FROM books b
            LEFT JOIN book_genres bg ON b.id = bg.book_id
            LEFT JOIN genres g ON bg.genre_id = g.id
            WHERE b.id = $bookId
            GROUP BY b.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();

        // Get the image URL
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
        // Redirect if book ID is not found
        header("Location: index.php");
        exit(); // Stop script execution
    }
} else {
    // Redirect if book ID is not provided
    header("Location: index.php");
    exit(); // Stop script execution
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

        .author,
        .publish-info,
        .buttons {
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

        .wishlist-button,
        .purchase-button {
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

        .description
        {
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
</style>
</head>
<body>
<div class="container">
    <div class="book-header">
        <div class="book-info">
            <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
            <div class="author"><?php echo htmlspecialchars($book['author']); ?></div>
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
</div>

<div class="overlay" onclick="hidePopup()"></div>

<div class="popup" id="popup">
    <h2><?php echo htmlspecialchars($book['title']); ?></h2>
    <p><?php echo htmlspecialchars($book['description']); ?></p>
    <hr>
    <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
    <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['isbn']); ?></p>
    <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genres']); ?></p>
    <p><strong>Date Published:</strong> <?php echo date('F j, Y', strtotime($book['date_published'])); ?></p>
    <p><strong>Pages:</strong> <?php echo $book['pages']; ?></p>
    <div class="close" onclick="hidePopup()">Close</div>
</div>

<script>
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

function showPopup() {
    document.getElementById('popup').style.display = 'block';
    document.querySelector('.overlay').style.display = 'block';
}

function hidePopup() {
    document.getElementById('popup').style.display = 'none';
    document.querySelector('.overlay').style.display = 'none';
}
</script>

</script>
</body>
</html>
