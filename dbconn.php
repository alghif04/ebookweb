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
?>