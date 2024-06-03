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
            position: fixed;
            right: 15px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            width: 35%;
            z-index: 1000; 
        }

        #searchInput {
            flex: 1;
            padding: 8px 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .sort-box {
            display: flex;
            justify-content: flex-start;
            padding: 17px;
            border-bottom: 0.5px solid #333;
            border-radius: 5px;
            margin-bottom: 20px;
            margin-top: 25px;
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

        .category-filter {
            margin-bottom: 20px;
        }

        .category-filter label {
            margin-right: 10px;
            font-size: 1rem;
            font-weight: bold;
        }

        .category-filter select {
            padding: 8px 12px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
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
            position: relative;
        }

        .product-item:hover {
            transform: scale(1.05);
        }

        .product-item img {
            width: 100%;
            border-radius: 5px;
            margin-bottom: 10px;
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
        <div class="category">
            <label for="categorySelect">Filter by category:</label>
            <select id="categorySelect">
                <option value="all">All</option>
                <option value="Adventure">Adventure</option>
                <option value="Romance">Romance</option>
                <option value="Thriller">Thriller</option>
                <option value="Fantasy">Fantasy</option>
                <option value="Horror">Horror</option>
            </select>
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
            <div class="product-item" data-id="1" data-title="The Last of the Mohicans" data-author="James Fenimore Cooper" data-description="the first product." data-price="$10" data-url="checkout1.html" data-date-published="2014-23-09" data-date-added="2024-01-05" data-category="Adventure">
                <img src="Mohicans.jpeg" alt="The Last of the Mohicans">
                <h3>The Last of the Mohicans</h3>
                <p>Author: James Fenimore Cooper</p>
                <p>"The Last of the Mohicans" is a classic tale of the American frontier, set during the French and Indian War.
                </p>
                <p>Published: 2014</p>
                <button class="wishlist-button" onclick="addToWishlist(1)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="2" data-title="Unfinished Tales of Númenor and Middle-Earth" data-author="J.R.R. Tolkien" data-description="the second product." data-price="$15" data-url="checkout2.html" data-date-published="2022-14-07" data-date-added="2024-02-05" data-category="Fantasy">
                <img src="Unfinished.jpg" alt="Unfinished Tales of Númenor and Middle-Earth">
                <h3>Unfinished Tales of Númenor and Middle-Earth</h3>
                <p>Author: J.R.R. Tolkien</p>
                <p>"The Unfinished Tales of Númenor and Middle-Earth" provides a detailed exploration of Middle-earth's history and lore.</p>
                <p>Published: 2021</p>
                <button class="wishlist-button" onclick="addToWishlist(2)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="3" data-title="Unhealthy" data-author="Michael Brown" data-description="This is the third product." data-price="$20" data-url="checkout3.html" data-date-published="2023-03-01" data-date-added="2024-03-05" data-category="Thriller">
                <img src="product3.jpg" alt="Unhealthy">
                <h3>Unhealthy</h3>
                <p>Author: Michael Brown</p>
                <p>deskripsi singkat.</p>
                <p>Published: 2023</p>
                <button class="wishlist-button" onclick="addToWishlist(3)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="4" data-title="Titanic Ship" data-author="Alice Johnson" data-description="This is the fourth product." data-price="$25" data-url="checkout4.html" data-date-published="2019-04-01" data-date-added="2024-04-05" data-category="Science Fiction">
                <img src="product4.jpg" alt="Titanic Ship">
                <h3>Titanic Ship</h3>
                <p>Author: Alice Johnson</p>
                <p>deskripsi singkat.</p>
                <p>Published: 2019</p>
                <button class="wishlist-button" onclick="addToWishlist(4)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="5" data-title="Jungle Book" data-author="David Wilson" data-description="This is the fiveth product." data-price="$12" data-url="checkout5.html" data-date-published="2023-04-01" data-date-added="2024-05-05" data-category="Adventure">
                <img src="product5.jpg" alt="Jungle Book">
                <h3>Jungle Book</h3>
                <p>Author: David Wilson</p>
                <p>deskripsi singkat.</p>
                <p>Published: 2023</p>
                <button class="wishlist-button" onclick="addToWishlist(5)">♡ Wishlist</button>
            </div>
            <!-- Add more product items as needed -->
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

            item.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                window.location.href = `informasi.php?id=${id}`;
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
        }

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const categorySelect = document.getElementById('categorySelect');
            const productCatalog = document.querySelector('.product-catalog');

            function filterProducts() {
                const searchTerm = searchInput.value.trim().toLowerCase();
                const selectedCategory = categorySelect.value;

                const products = Array.from(productCatalog.children);

                products.forEach(product => {
                    const title = product.getAttribute('data-title').toLowerCase();
                    const description = product.getAttribute('data-description').toLowerCase();
                    const author = product.getAttribute('data-author').toLowerCase();
                    const category = product.getAttribute('data-category');

                    const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm) || author.includes(searchTerm);
                    const matchesCategory = selectedCategory === 'all' || category === selectedCategory;

                    if (matchesSearch && matchesCategory) {
                        product.style.display = 'block';
                    } else {
                        product.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterProducts);
            categorySelect.addEventListener('change', filterProducts);
        });
    </script>
</body>
</html>
