<?php require 'dbconn.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="stylelogin.css">
</head>
<body>
    <div class="wrapper">
        <!-- Existing Login and Signup Forms -->

        <!-- Search Form -->
        <div class="form-wrapper search">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h2>Search User</h2>
                <div class="input-group">
                    <input type="text" id="search-username" name="search-username" required>
                    <label for="search-username">Username</label>
                </div>
                <input type="hidden" name="form_type" value="search">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Search Results -->
        <?php if (isset($search_results)): ?>
            <div class="search-results">
                <h2>Search Results:</h2>
                <ul>
                    <?php foreach ($search_results as $result): ?>
                        <li><?php echo htmlspecialchars($result['username']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <script src="script.js"></script>
</body>
</html>

<?php 
session_start();
require 'dbconn.php';

$search_results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type'])) {
        if ($_POST['form_type'] == "signup") {
            // Signup form submitted
            $username = filter_input(INPUT_POST, "signup-username", FILTER_SANITIZE_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, "signup-password", FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "signup-email", FILTER_SANITIZE_EMAIL);

            if (empty($username) || empty($password) || empty($email)) {
                echo '<script>alert("All fields are required")</script>';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO user_details (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hash);

                if ($stmt->execute()) {
                    echo '<script>alert("You are now registered!")</script>';
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            }
        } elseif ($_POST['form_type'] == "login") {
            // Login form submitted
            $username = filter_input(INPUT_POST, "login-username", FILTER_SANITIZE_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, "login-password", FILTER_SANITIZE_SPECIAL_CHARS);

            if (empty($username) || empty($password)) {
                echo '<script>alert("Both fields are required")</script>';
            } else {
                $stmt = $conn->prepare("SELECT * FROM user_details WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        echo '<script>alert("Login successful!")</script>';
                        $_SESSION['username'] = $username;

                        header("Location: indexLogin.php"); // Redirect
                        exit;
                    } else {
                        echo '<script>alert("Incorrect password")</script>';
                    }
                } else {
                    echo '<script>alert("Username not found")</script>';
                }

                $stmt->close();
            }
        } elseif ($_POST['form_type'] == "search") {
            // Search form submitted
            $search_username = filter_input(INPUT_POST, "search-username", FILTER_SANITIZE_SPECIAL_CHARS);

            if (empty($search_username)) {
                echo '<script>alert("Please enter a username to search")</script>';
            } else {
                $stmt = $conn->prepare("SELECT username FROM user_details WHERE username LIKE ?");
                $search_param = "%" . $search_username . "%";
                $stmt->bind_param("s", $search_param);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $search_results[] = $row;
                    }
                } else {
                    echo '<script>alert("No matching usernames found")</script>';
                }

                $stmt->close();
            }
        }
    }
}

mysqli_close($conn);
?>
