<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "db_ebookweb";

try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    if (!$conn) {
        throw new Exception("Could not connect to database: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    die($e->getMessage());
}

// Fetch book data
$sql = "SELECT id, title, description, price, image_url, date_published, date_added FROM books";
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}


?>