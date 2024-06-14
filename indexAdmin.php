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

        .product-catalog {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 250px;
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

        .add-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            z-index: 1000;
        }

        .delete-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
            z-index: 10;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .delete-button::before {
            content: '×';
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

        <a href="addBook.php" class="add-button">Add Book</a>
        <div class="product-catalog">
            <?php
            // Include your database connection and fetching logic
            include 'dbconn.php'; 

            // Fetch book data
            $sql = "SELECT id, title, description, price, image_url, date_published, date_added FROM books";
            $result = $conn->query($sql);

            $books = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $books[] = $row;
                }
            }

// Generate HTML for each book
foreach ($books as $book) {
    $description = strlen($book['description']) > 200 ? substr($book['description'], 0, 200) . '...' : $book['description'];

    echo '<div class="product-item" data-id="' . $book['id'] . '" data-title="' . htmlspecialchars($book['title']) . '" data-description="' . htmlspecialchars($book['description']) . '" data-price="$' . $book['price'] . '" data-url="checkout' . $book['id'] . '.html" data-date-published="' . $book['date_published'] . '" data-date-added="' . $book['date_added'] . '">';
    echo '<img src="' . htmlspecialchars($book['image_url']) . '" alt="' . htmlspecialchars($book['title']) . '">';
    echo '<div class="product-item-content">';
    echo '<h3>' . htmlspecialchars($book['title']) . '</h3>';
    echo '<p>' . htmlspecialchars($description) . '</p>'; // Updated description with ellipsis
    echo '<p>Published: ' . date('Y', strtotime($book['date_published'])) . '</p>';
    echo '</div>';
    echo '<button class="wishlist-button" onclick="addToWishlist(' . $book['id'] . ')">♡ Wishlist</button>';
    echo '<button class="delete-button" onclick="deleteBook(' . $book['id'] . ')"></button>'; // Delete button
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
document.querySelectorAll('.product-item').forEach(item => {
    const wishlistButton = item.querySelector('.wishlist-button');
    wishlistButton.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default form submission
        event.stopPropagation(); // Stop event propagation

        const id = item.getAttribute('data-id');
        addToWishlist(id, wishlistButton); // Pass the button directly to addToWishlist

        wishlistButton.disabled = true; // Disable the button immediately after click
    });
});

function addToWishlist(id, buttonElement) {
    console.log('Adding book to wishlist with ID:', id);

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
            buttonElement.disabled = false; // Re-enable the button after handling the response
        }
    };

    xhr.send(`book_id=${id}`);
    <?php } else { ?>
    openPopup(event);
    <?php } ?>
}



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
});


function deleteBook(bookId) {
    if (confirm('Are you sure you want to delete this book?')) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "deleteBook.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText); // Log the response for debugging
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        alert('Book deleted successfully');
                        // Reload the page or update the book list
                        location.reload(); // You can also update the book list without reloading the page
                    } else {
                        alert(response.message);
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                }
            }
        };

        xhr.send(`book_id=${bookId}`);
    }
}






    </script>
</body>
</html>
