<?php
session_start();
include 'dbconn.php';
include 'functions.php';

$title = isset($_GET['title']) ? $_GET['title'] : '';
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';

$sql = "SELECT books.id, books.title, books.description, books.price, books.image_url, books.date_published, books.date_added 
        FROM books 
        LEFT JOIN book_genres ON books.id = book_genres.book_id 
        WHERE 1=1";

if (!empty($title)) {
    $sql .= " AND books.title LIKE ?";
    $titleParam = "%" . $title . "%";
}

if (!empty($genre)) {
    $sql .= " AND book_genres.genre_id = ?";
}

$stmt = $conn->prepare($sql);
if (!empty($title) && !empty($genre)) {
    $stmt->bind_param("si", $titleParam, $genre);
} elseif (!empty($title)) {
    $stmt->bind_param("s", $titleParam);
} elseif (!empty($genre)) {
    $stmt->bind_param("i", $genre);
}

$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Readopolis</title>
    <style>
        /* Add your CSS styles here */
        .search-box {
            margin-bottom: 20px;
        }
        .search-box form {
            display: flex;
            align-items: center;
        }
        .search-box input, .search-box select, .search-box button {
            margin-right: 10px;
            padding: 5px;
            font-size: 1rem;
        }
        .product-item {
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <div class="search-box">
        <form action="search.php" method="get">
            <input type="text" name="title" placeholder="Search by title" value="<?php echo htmlspecialchars($title); ?>">
            <select name="genre">
                <option value="">All Genres</option>
                <?php
                $sqlGenres = "SELECT id, name FROM genres";
                $resultGenres = $conn->query($sqlGenres);
                if ($resultGenres->num_rows > 0) {
                    while ($row = $resultGenres->fetch_assoc()) {
                        $selected = ($row['id'] == $genre) ? 'selected' : '';
                        echo '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['name']) . '</option>';
                    }
                }
                ?>
            </select>
            <button type="submit">Search</button>
            <button type="button" id="reset-button">Reset</button>
        </form>
    </div>
    <div class="product-catalog">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                displayBook($row, $conn, isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
            }
        } else {
            echo '<p>No books found.</p>';
        }
        ?>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const productCatalog = document.querySelector('.product-catalog');
    const resetButton = document.getElementById('reset-button');

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

    resetButton.addEventListener('click', function () {
        <?php if (isset($_SESSION['username'])) { ?>
            window.location.href = 'indexLogin.php';
        <?php } else { ?>
            window.location.href = 'index.php';
        <?php } ?>
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
<?php
$conn->close();
?>
