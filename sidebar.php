<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Readopolis</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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

    .sidebar {
        position: absolute;
        top: 0;
        left: 0;
        height: 100vh;
        width: 80px;
        background-color: #2F2D2D;
        padding: .4rem .8rem;
        transition: all 0.5s ease;
        z-index: 1000;
    }

    .sidebar.active ~ .main-content {
        left: 250px;
        width: calc(100% - 250px);
    }

    .sidebar.active {
        width: 250px;
    }

    .sidebar #btn {
        position: absolute;
        color: #fff;
        top: .4rem;
        left: 50%;
        font-size: 1.2rem;
        line-height: 50px;
        transform: translateX(-50%);
        cursor: pointer;
    }

    .sidebar.active #btn {
        left: 90%;
    }

    .sidebar .top .logo {
        color: #fff;
        display: flex;
        height: 50px;
        width: 100%;
        align-items: center;
        pointer-events: none;
        opacity: 0;
    }

    .sidebar.active .top .logo {
        opacity: 1;
    }

    .top.logo i {
        font-size: 2rem;
        margin-right: 5px;
    }

    .sidebar p {
        opacity: 0;
    }

    .sidebar.active p {
        opacity: 1;
    }

    .sidebar ul li {
        position: relative;
        list-style-type: none;
        height: 50px;
        width: 90%;
        margin: 0.8rem auto;
        line-height: 50px;
    }

    .sidebar ul li a {
        color: #fff;
        display: flex;
        align-items: center;
        text-decoration: none;
        border-radius: 0.8rem;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .sidebar ul li a:hover {
        background-color: #fff;
        color: #12171e;
    }

    .sidebar ul li a i {
        min-width: 50px;
        text-align: center;
        height: 50px;
        border-radius: 12px;
        line-height: 50px;
    }

    .sidebar .nav-item {
        opacity: 0;
    }

    .sidebar.active .nav-item {
        opacity: 1;
    }

    .sidebar ul li .tooltip {
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translate(10px, -50%);
        background-color: #2F2D2D;
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        opacity: 0;
        white-space: nowrap;
        pointer-events: none;
        transition: opacity 0.3s ease, transform 0.3s ease;
        z-index: 2000;
    }

    .sidebar ul li a:hover + .tooltip {
        opacity: 1;
        transform: translate(15px, -50%);
    }

    .sidebar.sidebar.active ul li .tooltip {
        display: none;
    }

    .username {
        font-size: 0.8em;
        display: block;
        margin-top: -30px;
        color: #a49a9a;
    }

    .footer {
        background-color: #000;
        color: #a49a9a;
        padding: 1rem;
        text-align: right;
        position: fixed;
        bottom: 0;
        width: 100%;
        box-shadow: 0 -1px 5px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .footer span {
        font-size: 0.8rem;
    }
</style>

</head>
<body>
<?php require 'popups.php'; ?>
    <div class="sidebar">
        <div class="top">
            <div class="logo">
                <i class='bx bxs-book-content'></i>
                <span>Readopolis</span>
            </div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <ul>
            <li>
                <a href="<?php 
                    if (!isset($_SESSION['username'])) {
                        echo 'index.php';
                    } else {
                        if ($_SESSION['is_admin'] == 1) {
                            echo 'indexAdmin.php';
                        } else {
                            echo 'indexLogin.php';
                        }
                    }
                ?>">
                    <i class='bx bxs-book'></i>
                    <span class="nav-item">Books</span>
                </a>
                <span class="tooltip">Books</span>
            </li>
            <li>
                <a href="<?php echo isset($_SESSION['username']) ? 'wishlist.php' : '#'; ?>" <?php echo !isset($_SESSION['username']) ? 'onclick="openPopup(event)"' : ''; ?>>
                    <i class='bx bx-list-ul'></i>
                    <span class="nav-item">Wishlist</span>
                </a>
                <span class="tooltip">Wishlist</span>
            </li>
            <li>
                <a href="#" onclick="openAbout(event)">
                    <i class='bx bx-info-circle'></i>
                    <span class="nav-item">About</span>
                </a>
                <span class="tooltip">About</span>
            </li>
            <li>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="profile.php">
                        <i class='bx bxs-user'></i>
                        <span class="nav-item">Profile</span>
                    </a>
                    <span class="tooltip">Profile</span>
                <?php endif; ?>
            </li>
            <li>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="logout.php">
                        <i class='bx bx-log-out'></i>
                        <span class="nav-item">
                            Logout
                            <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </span>
                    </a>
                    <span class="tooltip">Logout</span>
                <?php else: ?>
                    <a href="Login.php">
                        <i class='bx bx-log-in'></i>
                        <span class="nav-item">Login</span>
                    </a>
                    <span class="tooltip">Login</span>
                <?php endif; ?>
            </li>
        </ul>
    </div>

    <div class="footer">
        <span>Readopolis, made by group 9 of web development class</span>
    </div>

<script>
    let btn = document.querySelector('#btn');
    let sidebar = document.querySelector('.sidebar');

    btn.onclick = function() {
        sidebar.classList.toggle('active');
    }
</script>

</body>
</html>
