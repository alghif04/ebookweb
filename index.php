<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Readopolis</title>
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

        .search {
            margin-bottom: 20px;
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

        .book-section {
            margin-bottom: 40px;
        }

        .book-section h2 {
            margin-bottom: 10px;
            font-size: 1.5rem;
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
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <div class="search">
        <input type="text" id="searchInput" placeholder="Search by title or author">
    </div>
    <div class="sort-box">
        <span class="SortLabel">Sort by:</span>
        <div class="SortContainer">
            <span class="SortLabel"><a href="#" id="sort-title">Title</a></span>
        </div>
        <div class="SortContainer">
            <span class="SortLabel"><a href="#" id="sort-date-published">Date Published</a></span>
        </div>
        <div class="SortContainer">
            <span class="SortLabel"><a href="#" id="sort-date-added">Date Added</a></span>
        </div>
    </div>

    <div class="product-catalog">
        <?php
        // Include your database connection
        include 'dbconn.php';

        // Function to display a book
        function displayBook($book) {
            $bookId = $book['id'];
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

            global $conn;

            if ($userId) {
                $sql = "SELECT 1 FROM purchased_books WHERE user_id = ? AND book_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $userId, $bookId);
                $stmt->execute();
                $purchaseResult = $stmt->get_result();

                if ($purchaseResult->num_rows > 0) {
                    $book['purchased'] = true;
                } else {
                    $book['purchased'] = false;
                }
            } else {
                $book['purchased'] = false;
            }

            echo '<div class="product-item" data-id="' . $book['id'] . '" data-title="' . htmlspecialchars($book['title']) . '" data-description="' . htmlspecialchars($book['description']) . '" data-price="$' . $book['price'] . '" data-url="viewBook.php?id=' . $book['id'] . '" data-date-published="' . $book['date_published'] . '" data-date-added="' . $book['date_added'] . '">';
            echo '<img src="' . htmlspecialchars($book['image_url']) . '" alt="' . htmlspecialchars($book['title']) . '">';
            echo '<div class="product-item-content">';
            echo '<h3>' . htmlspecialchars($book['title']) . '</h3>';
            echo '<p>$' . $book['price'] . '</p>';
            echo '</div>';
            if ($book['purchased']) {
                echo '<button class="read-button" onclick="readBook(' . $book['id'] . ')">Read the Book</button>';
            } else {
                echo '<button class="wishlist-button" onclick="addToWishlist(' . $book['id'] . ')">♡ Wishlist</button>';
            }
            echo '</div>';
        }

        // Fetch best sellers
        $sqlBestSellers = "
            SELECT books.id, books.title, books.description, books.price, books.image_url, books.date_published, books.date_added, COUNT(purchased_books.book_id) as purchase_count 
            FROM books 
            JOIN purchased_books ON books.id = purchased_books.book_id 
            GROUP BY books.id 
            ORDER BY purchase_count DESC 
            LIMIT 5";
        $resultBestSellers = $conn->query($sqlBestSellers);

        if ($resultBestSellers->num_rows > 0) {
            echo '<div class="book-section">';
            echo '<h2>Best Sellers</h2>';
            echo '<div class="product-catalog">';
            while($row = $resultBestSellers->fetch_assoc()) {
                displayBook($row);
            }
            echo '</div>';
            echo '</div>';
        }

        // Fetch genres that have books
        $sqlGenres = "
            SELECT genres.id, genres.name 
            FROM genres 
             JOIN book_genres ON genres.id = book_genres.genre_id 
            GROUP BY genres.id 
            HAVING COUNT(book_genres.book_id) > 0 
            ORDER BY RAND() 
            LIMIT 2";
        $resultGenres = $conn->query($sqlGenres);
        $genres = [];
        if ($resultGenres->num_rows > 0) {
            while ($row = $resultGenres->fetch_assoc()) {
                $genres[] = $row;
            }
        }

        // Fetch books by genre
        foreach ($genres as $genre) {
            $genreId = $genre['id'];
            $genreName = $genre['name'];
            $sqlGenreBooks = "
                SELECT books.id, books.title, books.description, books.price, books.image_url, books.date_published, books.date_added 
                FROM books 
                JOIN book_genres ON books.id = book_genres.book_id 
                WHERE book_genres.genre_id = $genreId 
                LIMIT 5";
            $resultGenreBooks = $conn->query($sqlGenreBooks);

            if ($resultGenreBooks->num_rows > 0) {
                echo '<div class="book-section">';
                echo "<h2>$genreName Books</h2>";
                echo '<div class="product-catalog">';
                while($row = $resultGenreBooks->fetch_assoc()) {
                    displayBook($row);
                }
                echo '</div>';
                echo '</div>';
            }
        }

        // Fetch all books
        $sqlAllBooks = "SELECT id, title, description, price, image_url, date_published, date_added FROM books";
        $resultAllBooks = $conn->query($sqlAllBooks);

        if ($resultAllBooks->num_rows > 0) {
            echo '<div class="book-section">';
            echo '<h2>All Books</h2>';
            echo '<div class="product-catalog">';
            while($row = $resultAllBooks->fetch_assoc()) {
                displayBook($row);
            }
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
    <div class="product-preview" id="product-preview">
        <h3 id="preview-title">Product Title</h3>
        <p id="preview-description">Product description will appear here when you hover over a product.</p>
        <a id="preview-price" href="#" target="_blank">Price</a>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const productCatalog = document.querySelector('.product-catalog');

    function filterProducts(searchTerm) {
        const products = Array.from(productCatalog.children);

        products.forEach(product => {
            const title = product.getAttribute('data-title').toLowerCase();
            const description = product.getAttribute('data-description').toLowerCase();

            if (title.includes(searchTerm) || description.includes(searchTerm)) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.trim().toLowerCase();
        filterProducts(searchTerm);
    });

    productCatalog.addEventListener('click', function (event) {
        const productItem = event.target.closest('.product-item');
        if (productItem) {
            const url = productItem.getAttribute('data-url');
            window.location.href = url;
        }
    });

    document.querySelectorAll('.wishlist-button, .read-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.stopPropagation();
        });
    });
});

function addToWishlist(id) {
    console.log('Adding book to wishlist with ID:', id);

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
        }
    };

    xhr.send(`book_id=${id}`);
    <?php } else { ?>
    openPopup(event);
    <?php } ?>
}

function readBook(id) {
    window.location.href = 'readBook.php?book_id=' + id;
}
</script>
</body>
</html>

