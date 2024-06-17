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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Get form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date_published = $_POST['date_published'];
    $authorName = $_POST['author']; // Updated variable name to avoid confusion with ID
    $publisher = $_POST['publisher'];
    $isbn = $_POST['isbn'];
    $pages = $_POST['pages'];
    $language = $_POST['language']; // New field for language
    $genres = $_POST['genres']; // Array of genre IDs

    // Check if the author exists in the database
    $checkAuthorQuery = "SELECT id FROM authors WHERE name = '$authorName'";
    $authorResult = $conn->query($checkAuthorQuery);

    if ($authorResult->num_rows > 0) {
        // Author already exists, get the ID
        $authorRow = $authorResult->fetch_assoc();
        $authorId = $authorRow['id'];
    } else {
        // Author doesn't exist, create a new entry and get the ID
        $createAuthorQuery = "INSERT INTO authors (name) VALUES ('$authorName')";
        if ($conn->query($createAuthorQuery) === TRUE) {
            $authorId = $conn->insert_id; // Get the ID of the newly inserted author
        } else {
            echo "Error creating author: " . $conn->error;
            exit(); // Exit the script if there's an error
        }
    }

    // Default image path
    $image = $defaultImagePath;

    // Handling image upload and resize
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $filename = basename($_FILES['image']['name']);
        $uploadPath = $uploadDirectory . $filename; // Final uploaded image path
        move_uploaded_file($tmp_name, $uploadPath);

        // Resize uploaded image to 900x600
        $resizedImagePath = $uploadDirectory . 'resized_' . $filename;
        resizeImage($uploadPath, $resizedImagePath, 600, 900);

        // Delete the original uploaded image
        unlink($uploadPath);

        $image = $resizedImagePath; // Use the resized image path
    }

    // Handling PDF upload
if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
    $pdfTmpName = $_FILES['pdf']['tmp_name'];
    $pdfFilename = $_FILES['pdf']['name'];
    $pdfExtension = pathinfo($pdfFilename, PATHINFO_EXTENSION);
    $pdfNewName = $title . '.' . $pdfExtension; // Rename PDF file to book title

    // Define the directory to upload PDF files
    $pdfDirectory = 'pdf_files/';
    $pdfUploadPath = $pdfDirectory . $pdfNewName;

    // Move the uploaded PDF file to the specified directory with the new name
    if (move_uploaded_file($pdfTmpName, $pdfUploadPath)) {
        // Update the PDF URL in the database
        $pdfUrl = $pdfUploadPath;
    } else {
        echo "Error moving PDF file to destination.";
        exit();
    }
} else {
    echo "Error uploading PDF file.";
    exit();
}

    // Prepare and execute SQL query to insert data into 'books' table
    $sql = "INSERT INTO books (title, description, price, image_url, pdf_url, date_published, date_added, language, author_id, publisher, isbn, pages) 
            VALUES ('$title', '$description', '$price', '$image', '$pdfUrl', '$date_published', NOW(), '$language', '$authorId', '$publisher', '$isbn', '$pages')";

    echo "SQL Query: " . $sql; // Debugging line - Remove this in production

    if ($conn->query($sql) === TRUE) {
        $book_id = $conn->insert_id; // Get the inserted book's ID

        // Insert genres into the book_genres table
        foreach ($genres as $genre_id) {
            $conn->query("INSERT INTO book_genres (book_id, genre_id) VALUES ('$book_id', '$genre_id')");
        }

        echo '<script>alert("Book added successfully!")</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
            min-height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 600px;
            max-width: 100%;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        form .form-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        form .form-row > div {
            flex: 1;
            min-width: 48%;
            margin-bottom: 15px;
            margin-right: 20px; /* Add horizontal spacing between each field box */
        }

        form .form-row > div.full-width {
            min-width: 100%;
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
        input[type="file"],
        select {
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

        .add-genre-button {
            background-color: #ffc107;
            margin-top: 10px;
        }

        .add-genre-button:hover {
            background-color: #e0a800;
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const authorInput = document.getElementById('author');
        const authorList = document.getElementById('authorList');

        authorInput.addEventListener('input', function () {
            const inputValue = authorInput.value.trim();
            if (inputValue.length === 0) {
                authorList.innerHTML = ''; // Clear the suggestion list
                return;
            }

            // Fetch suggestions from the database
            fetch('getAuthors.php?name=' + inputValue)
                .then(response => response.json())
                .then(data => {
                    authorList.innerHTML = ''; // Clear the suggestion list
                    data.forEach(author => {
                        const option = document.createElement('option');
                        option.value = author.name;
                        authorList.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching authors:', error));
        });
    });
</script>

</head>
<body>
    <div class="container">
        <h1>Add New Book</h1>
        <form action="addBook.php" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div>
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div>
        <label for="author">Author:</label>
        <input type="text" id="author" name="author" list="authorList" required>
        <datalist id="authorList"></datalist>
    </div>
            </div>
            <div class="form-row">
                <div>
                    <label for="publisher">Publisher:</label>
                    <input type="text" id="publisher" name="publisher" required>
                </div>
                <div>
                <label for="isbn">ISBN:</label>
                <input type="text" id="isbn" name="isbn" maxlength="19" required>
                </div>
            </div>
            <div class="form-row">
                <div>
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" required>
                </div>
                <div>
                    <label for="pages">Pages:</label>
                    <input type="number" id="pages" name="pages" required>
                </div>
            </div>
            <div class="form-row">
                <div>
                    <label for="date_published">Date Published:</label>
                    <input type="date" id="date_published" name="date_published" required>
                </div>
                <div class="form-row">
    <div>
        <label for="language">Language:</label>
        <input type="text" id="language" name="language" required>
    </div>
</div>

                <div>
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
            </div>
            <div class="form-row full-width">
                <div>
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
            </div>
            <div class="form-row full-width">
                <div>
                    <label for="genres">Genres:</label>
                    <select id="genres" name="genres[]" multiple required>
                        <?php
                        // Fetch genres from the database
                        $result = $conn->query("SELECT id, name FROM genres");
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div>
    <label for="pdf">PDF File:</label>
    <input type="file" id="pdf" name="pdf" accept=".pdf" required>
</div>

            <div class="form-row full-width">
                <button type="submit" name="submit">Add Book</button>
                <button type="button" class="back-button" onclick="history.back()">Back</button>
            </div>
        </form>
        <button class="add-genre-button" onclick="window.location.href='addGenre.php'">Add New Genre</button>
    </div>
</body>

</html>
