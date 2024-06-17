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

        .sidebar {
            position: absolute;
            top: 0;
            left: 0;
            height: 100vh;
            width: 80px;
            background-color: #2F2D2D;
            padding: .4rem .8rem;
            transition: all 0.5s ease;
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
                <a href="<?php echo isset($_SESSION['username']) ? 'cart.php' : '#'; ?>" <?php echo !isset($_SESSION['username']) ? 'onclick="openPopup(event)"' : ''; ?>>
                    <i class='bx bxs-cart'></i>
                    <span class="nav-item">Cart</span>
                </a>
                <span class="tooltip">Cart</span>
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
</body>

<script>
    let btn = document.querySelector('#btn');
    let sidebar = document.querySelector('.sidebar');

    btn.onclick = function() {
        sidebar.classList.toggle('active');
    }
</script>

</html>
