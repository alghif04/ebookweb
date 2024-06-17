<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Readopolis - Admin</title>
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
                echo '<div class="product-item" data-id="' . $book['id'] . '" data-title="' . htmlspecialchars($book['title']) . '" data-description="' . htmlspecialchars($book['description']) . '" data-price="$' . $book['price'] . '" data-url="viewBook.php?id=' . $book['id'] . '" data-date-published="' . $book['date_published'] . '" data-date-added="' . $book['date_added'] . '">';
                echo '<img src="' . htmlspecialchars($book['image_url']) . '" alt="' . htmlspecialchars($book['title']) . '">';
                echo '<div class="product-item-content">';
                echo '<h3>' . htmlspecialchars($book['title']) . '</h3>';
                echo '<p>$' . $book['price'] . '</p>';
                echo '</div>';
                echo '<button class="wishlist-button" onclick="addToWishlist(' . $book['id'] . ')">♡ Wishlist</button>';
                echo '<button class="delete-button" onclick="deleteBook(' . $book['id'] . ')"></button>'; // X button for delete
                echo '</div>';
            }
            ?>
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

    item.addEventListener('click', function (event) {
        if (!event.target.classList.contains('wishlist-button')) { // Exclude wishlist button clicks
            const url = this.getAttribute('data-url');
            window.location.href = url;
        }
    });
});

document.querySelectorAll('.sort-box a').forEach(sortLink => {
    sortLink.addEventListener('click', function (event) {
        event.preventDefault();
        const sortType = this.getAttribute('id').replace('sort-', '');
        sortProductItems(sortType);
    });
});

function addToWishlist(id, button) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'addWishlist.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert('Book added to wishlist successfully!');
            button.disabled = true; // Disable the button after successful addition
        } else {
            alert('Error adding book to wishlist. Please try again.');
        }
    };
    xhr.send('bookId=' + id);
}

function deleteBook(bookId) {
    if (confirm('Are you sure you want to delete this book?')) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'deleteBook.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert('Book deleted successfully!');
                document.querySelector(`.product-item[data-id='${bookId}']`).remove();
            } else {
                alert('Error deleting book. Please try again.');
            }
        };
        xhr.send('bookId=' + bookId);
    }
}

function sortProductItems(sortType) {
    const container = document.querySelector('.product-catalog');
    const items = Array.from(container.children);

    items.sort((a, b) => {
        let aValue, bValue;
        if (sortType === 'title') {
            aValue = a.getAttribute('data-title').toLowerCase();
            bValue = b.getAttribute('data-title').toLowerCase();
        } else if (sortType === 'date-published') {
            aValue = new Date(a.getAttribute('data-date-published'));
            bValue = new Date(b.getAttribute('data-date-published'));
        } else if (sortType === 'date-added') {
            aValue = new Date(a.getAttribute('data-date-added'));
            bValue = new Date(b.getAttribute('data-date-added'));
        }
        if (aValue < bValue) {
            return -1;
        }
        if (aValue > bValue) {
            return 1;
        }
        return 0;
    });

    items.forEach(item => container.appendChild(item));
}
    </script>
</body>
</html>
