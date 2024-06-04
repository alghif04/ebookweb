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
            <div class="product-item" data-id="1" data-title="The Last of the Mohicans" data-author="James Fenimore Cooper" data-description="The Last of the Mohicans is a classic tale of the American frontier, set during the French and Indian War. It is the second book in James Fenimore Cooper's Leatherstocking Tales and stands as one of the greatest action stories ever told." data-price="IDR 93.000" data-url="checkout1.html" data-date-published="2014-09-23" data-date-added="2024-01-05" data-category="Adventure">
                <img src="Mohicans.jpeg" alt="The Last of the Mohicans">
                <h3>The Last of the Mohicans</h3>
                <p>Author: James Fenimore Cooper</p>
                <p>Published: 2014</p>
                <button class="wishlist-button" onclick="addToWishlist(1)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="2" data-title="Unfinished Tales of Númenor and Middle-Earth" data-author="J.R.R. Tolkien" data-description="This book explores Middle-earth's landscapes, lore, and history, featuring Gandalf's account of the Dwarves' meeting at Bag-End, Ulmo's encounter with Tuor on Beleriand's shores, and details on the Riders of Rohan's military structure and the Black Riders' pursuit of the Ring." data-price="IDR 115.000" data-url="checkout2.html" data-date-published="2022-07-14" data-date-added="2024-02-05" data-category="Fantasy">
                <img src="Unfinished.jpg" alt="Unfinished Tales of Númenor and Middle-Earth">
                <h3>Unfinished Tales of Númenor and Middle-Earth</h3>
                <p>Author: J.R.R. Tolkien</p>
                <p>Published: 2021</p>
                <button class="wishlist-button" onclick="addToWishlist(2)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="3" data-title="Calamity" data-author="Brandon Sanderson"  data-description="the third product." data-price="IDR 102.000" data-url="checkout3.html" data-date-published="2016-12-01" data-date-added="2024-03-05" data-category="Thriller">
                <img src="Calamity.jpg" alt="Calamity">
                <h3>Calamity</h3>
                <p>Author: Brandon Sanderson</p>
                <p>Published: 2016</p>
                <button class="wishlist-button" onclick="addToWishlist(3)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="4" data-title="Fire Fight" data-author="Brandon Sanderson" data-description="This is the fourth product." data-price="IDR 85.000" data-url="checkout4.html" data-date-published="2016-12-01" data-date-added="2024-04-05" data-category="Thriller">
                <img src="Firefight.jpg" alt="Fire Fight">
                <h3>Fire Fight</h3>
                <p>Author: Brandon Sanderson</p>
                <p>Published: 2016</p>
                <button class="wishlist-button" onclick="addToWishlist(4)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="5" data-title="The Scorch Trials" data-author="James Dashner" data-description="This is the fiveth product." data-price="IDR 80.000" data-url="checkout5.html" data-date-published="2014-09-18" data-date-added="2024-05-05" data-category="Adventure">
                <img src="Scorch.jpg" alt="The Scorch Trials">
                <h3>The Scorch Trials</h3>
                <p>Author: James Dashner</p>
                <p>Published: 2014</p>
                <button class="wishlist-button" onclick="addToWishlist(5)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="6" data-title="Red Queen" data-author="Victoria Aveyard" data-description="This is the sixth product." data-price="IDR 95.000" data-url="checkout6.html" data-date-published="2015-08-10" data-date-added="2024-05-06" data-category="Fantasy">
                <img src="RedQ.jpg" alt="Red Queen">
                <h3>Red Queen</h3>
                <p>Author: Victoria Aveyard</p>
                <p>Published: 2015</p>
                <button class="wishlist-button" onclick="addToWishlist(5)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="7" data-title="A Discovery of Witches" data-author="Deborah Harkness" data-description="This is the seventh product." data-price="IDR 105.000" data-url="checkout7.html" data-date-published="2011-08-10" data-date-added="2024-05-06" data-category="Fantasy">
                <img src="Witches.jpg" alt="A Discovery of Witches">
                <h3>A Discovery of Witches</h3>
                <p>Author: Deborah Harkness</p>
                <p>Published: 2011</p>
                <button class="wishlist-button" onclick="addToWishlist(5)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="8" data-title="Aku Tahu Kapan Kamu Mati" data-author="Arumi E" data-description="This is the eight product." data-price="IDR 75.000" data-url="checkout8.html" data-date-published="2018-09-19" data-date-added="2024-05-06" data-category="Horror">
                <img src="KapanM.jpeg" alt="Aku Tahu Kapan Kamu Mati">
                <h3>Aku Tahu Kapan Kamu Mati</h3>
                <p>Author: Arumi E</p>
                <p>Published: 2018</p>
                <button class="wishlist-button" onclick="addToWishlist(5)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="9" data-title="Noir" data-author="Renita Nozaria" data-description="This is the nineth product." data-price="IDR 75.000" data-url="checkout9.html" data-date-published="2017-11-19" data-date-added="2024-05-07" data-category="Horror">
                <img src="Noir.jpg" alt="Noir">
                <h3>Noir</h3>
                <p>Author: Renita Nozaria</p>
                <p>Published: 2017</p>
                <button class="wishlist-button" onclick="addToWishlist(5)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="10" data-title="Caraval" data-author="Stephanie Garber" data-description="This is the tenth product." data-price="IDR 95.000" data-url="checkout10.html" data-date-published="2022-06-20" data-date-added="2024-05-07" data-category="Romance">
                <img src="Caraval.jpg" alt="Caraval">
                <h3>Caraval</h3>
                <p>Author: Stephanie Garber</p>
                <p>Published: 2022</p>
                <button class="wishlist-button" onclick="addToWishlist(5)">♡ Wishlist</button>
            </div>
            <div class="product-item" data-id="11" data-title="Your Party Girl" data-author="Lexie Xu" data-description="This is the eleventh product." data-price="IDR 75.000" data-url="checkout11.html" data-date-published="2018-01-28" data-date-added="2024-05-07" data-category="Romance">
                <img src="PartyG.jpg" alt="Your Party Girl">
                <h3>Your Party Girl</h3>
                <p>Author: Lexie Xu</p>
                <p>Published: 2018</p>
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

            item.querySelector('.wishlist-button').addEventListener('click', function (event) {
            event.stopPropagation();
            const id = item.getAttribute('data-id');
            window.location.href = `wishlist.php?id=${id}`;
            });

            item.addEventListener('click', function () {
            const id = item.getAttribute('data-id');
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
