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
                echo '<div class="product-item" data-id="' . $book['id'] . '" data-title="' . htmlspecialchars($book['title']) . '" data-description="' . htmlspecialchars($book['description']) . '" data-price="$' . $book['price'] . '" data-url="checkout' . $book['id'] . '.html" data-date-published="' . $book['date_published'] . '" data-date-added="' . $book['date_added'] . '">';
                echo '<img src="' . htmlspecialchars($book['image_url']) . '" alt="' . htmlspecialchars($book['title']) . '">';
                echo '<div class="product-item-content">';
                echo '<h3>' . htmlspecialchars($book['title']) . '</h3>';
                echo '<p>' . htmlspecialchars($book['description']) . '</p>';
                echo '<p>Published: ' . date('Y', strtotime($book['date_published'])) . '</p>';
                echo '</div>';
                echo '<button class="wishlist-button" onclick="addToWishlist(' . $book['id'] . ')">♡ Wishlist</button>';
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
            item.addEventListener('mouseover', function () {
                const title = this.getAttribute('data-title');
                const description = this.getAttribute('data-description');
                const price = this.getAttribute('data-price');
                const url = this.getAttribute('data-url');

                const preview = document.getElementById('product-preview');
                document.getElementById('preview-title').textContent = title;
                document.getElementById('preview-description').textContent = description;
                const priceElement = document.getElementById('preview-price');
                priceElement.textContent = price;
                priceElement.href = url;

                preview.style.display = 'block';
                preview.style.top = `${this.offsetTop}px`;
                preview.style.left = `${this.offsetLeft + this.offsetWidth + 10}px`;
            });

            item.addEventListener('mouseout', function () {
                document.getElementById('product-preview').style.display = 'none';
            });

            item.addEventListener('click', function (event) {
        const id = this.getAttribute('data-id');
        window.location.href = `informasi.php?id=${id}`;
    });

    const wishlistButton = item.querySelector('.wishlist-button');
    wishlistButton.addEventListener('click', function (event) {
        <?php if (isset($_SESSION['username'])) { ?>
            const id = this.parentElement.getAttribute('data-id');
            addToWishlist(id);
        <?php } else { ?>
            event.preventDefault(); // Prevent default click action
            openPopup(event); // Show login popup
        <?php } ?>
        event.stopPropagation(); // Prevent event bubbling
    });
});

        function sortProducts(criteria) {
            const catalog = document.querySelector('.product-catalog');
            const products = Array.from(catalog.children);

            products.sort((a, b) => {
                const aValue = a.getAttribute(`data-${criteria}`).toLowerCase();
                const bValue = b.getAttribute(`data-${criteria}`).toLowerCase();

                if (criteria === 'title') {
                    return aValue.localeCompare(bValue);
                } else {
                    return new Date(aValue) - new Date(bValue);
                }
            });

            catalog.innerHTML = '';
            products.forEach(product => catalog.appendChild(product));
        }

        document.getElementById('sort-title').addEventListener('click', (e) => {
            e.preventDefault();
            sortProducts('title');
        });

        document.getElementById('sort-date-published').addEventListener('click', (e) => {
            e.preventDefault();
            sortProducts('date-published');
        });

        document.getElementById('sort-date-added').addEventListener('click', (e) => {
            e.preventDefault();
            sortProducts('date-added');
        });

        function addToWishlist(id) {
    <?php if (isset($_SESSION['username'])) { ?>
        const product = document.querySelector(`.product-item[data-id="${id}"]`);
        const title = product.getAttribute('data-title');
        const description = product.getAttribute('data-description');
        const price = product.getAttribute('data-price');
        const url = product.getAttribute('data-url');
        const datePublished = product.getAttribute('data-date-published');
        const dateAdded = product.getAttribute('data-date-added');

        const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        wishlist.push({ id, title, description, price, url, datePublished, dateAdded });
        localStorage.setItem('wishlist', JSON.stringify(wishlist));

        alert(`${title} has been added to your wishlist!`);
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
    </script>
</body>
</html>
