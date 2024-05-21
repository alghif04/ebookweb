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

        /* Styles for product catalog */
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

    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="sort-box">
            <span class="SortLabel">Sort by:</span>
            <div class="SortContainer">
                <span class="SortLabel"><a href="#">Title</a></span>
            </div>
            <div class="SortContainer">
                <span class="SortLabel"><a href="#">Date Published</a></span>
            </div>
            <div class="SortContainer">
                <span class="SortLabel"><a href="#">Date Added</a></span>
            </div>
        </div>
        <div class="product-catalog">
            <div class="product-item" data-id="1" data-title="Product 1" data-description="This is the first product." data-price="$10" data-url="checkout1.html">
                <img src="product1.jpg" alt="Product 1">
                <h3>Product 1</h3>
                <p>deskripsi singkat.</p>
            </div>
            <div class="product-item" data-id="2" data-title="Product 2" data-description="This is the second product." data-price="$15" data-url="checkout2.html">
                <img src="product2.jpg" alt="Product 2">
                <h3>Product 2</h3>
                <p>deskripsi singkat.</p>
            </div>
            <div class="product-item" data-id="3" data-title="Product 3" data-description="This is the third product." data-price="$20" data-url="checkout3.html">
                <img src="product3.jpg" alt="Product 3">
                <h3>Product 3</h3>
                <p>deskripsi singkat.</p>
            </div>
            <div class="product-item" data-id="4" data-title="Product 4" data-description="This is the fourth product." data-price="$25" data-url="checkout4.html">
                <img src="product4.jpg" alt="Product 4">
                <h3>Product 4</h3>
                <p>deskripsi singkat.</p>
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
    </script>
</body>
</html>
