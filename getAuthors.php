<?php
// Include database connection
require 'dbconn.php';

// Check if 'name' parameter is set in the URL
if (isset($_GET['name'])) {
    $name = $_GET['name'];

    // Fetch authors from the database matching the input name
    $sql = "SELECT name FROM authors WHERE name LIKE '%$name%'";
    $result = $conn->query($sql);

    $authors = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $authors[] = ['name' => $row['name']];
        }
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($authors);
}
?>
