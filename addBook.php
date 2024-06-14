<?php
session_start();

// Include database connection
require 'dbconn.php';

// Paths for default and uploaded images
$defaultImagePath = 'images/default.jpg'; // Adjust this path
$uploadDirectory = 'uploads/'; // Adjust this path

// Function to resize image
function resizeImage($sourcePath, $targetPath, $width, $height) {
    list($sourceWidth, $sourceHeight, $sourceType) = getimagesize($sourcePath);

    switch ($sourceType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        default:
            return false; // Unsupported image type
    }

    $targetImage = imagecreatetruecolor($width, $height);
    imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

    // Save resized image
    switch ($sourceType) {
        case IMAGETYPE_JPEG:
            imagejpeg($targetImage, $targetPath);
            break;
        case IMAGETYPE_PNG:
            imagepng($targetImage, $targetPath);
            break;
    }

    imagedestroy($sourceImage);
    imagedestroy($targetImage);

    return true;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        // Get form data
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $date_published = $_POST['date_published'];

        // Default image path
        $image = $defaultImagePath;

        // Handling image upload and resize
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['image']['tmp_name'];
            $filename = basename($_FILES['image']['name']);
            $uploadPath = $uploadDirectory . $filename; // Final uploaded image path

            // Resize uploaded image to 900x600 if needed
            if (resizeImage($tmp_name, $uploadPath, 600, 900)) {
                $image = $uploadPath; // Use the resized image path
            } else {
                echo "Error resizing image.";
            }
        }

        // Prepare and execute SQL query to insert data into 'books' table
        $sql = "INSERT INTO books (title, description, price, image_url, date_published, date_added) 
                VALUES ('$title', '$description', '$price', '$image', '$date_published', NOW())";

        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Book added successfully!")</script>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        button {
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        .back-button {
            background-color: #007bff;
            margin-right: 10px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Book</h1>
        <form action="addBook.php" method="post" enctype="multipart/form-data">
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div>
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" required>
            </div>
            <div>
                <label for="date_published">Date Published:</label>
                <input type="date" id="date_published" name="date_published" required>
            </div>
            <div>
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <div>
                <button type="submit" name="submit">Add Book</button>
                <button type="button" class="back-button" onclick="history.back()">Back</button>
            </div>
        </form>
    </div>
</body>
</html>
