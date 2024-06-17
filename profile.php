<?php
session_start();
require 'dbconn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the database
$userID = $_SESSION['user_id'];
$sql = "SELECT * FROM user_details WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = htmlspecialchars($user['username']);
    $email = htmlspecialchars($user['email']);
    $displayName = htmlspecialchars($user['display_name']);
} else {
    echo "User details not found!";
    exit();
}

// Handle profile update including payment information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == "update_profile") {
    $newDisplayName = filter_input(INPUT_POST, "display_name", FILTER_SANITIZE_SPECIAL_CHARS);

    // Update the display name in the database
    $sqlUpdateProfile = "UPDATE user_details SET display_name = ? WHERE user_id = ?";
    $stmtUpdateProfile = $conn->prepare($sqlUpdateProfile);
    $stmtUpdateProfile->bind_param("si", $newDisplayName, $userID);
    $stmtUpdateProfile->execute();

    // Check if adding a new card
    if (isset($_POST['add_card'])) {
        if (countUserCards($conn, $userID) < 3) {
            $cardNumber = filter_input(INPUT_POST, "card_number", FILTER_SANITIZE_SPECIAL_CHARS);
            $expirationDate = $_POST['expiration_date'];
            $cvv = filter_input(INPUT_POST, "cvv", FILTER_SANITIZE_SPECIAL_CHARS);
            $billingAddress = filter_input(INPUT_POST, "billing_address", FILTER_SANITIZE_SPECIAL_CHARS);

            // Insert card information into the database
            $sqlInsertCard = "INSERT INTO user_cards (user_id, card_number, expiration_date, cvv, billing_address) VALUES (?, ?, ?, ?, ?)";
            $stmtInsertCard = $conn->prepare($sqlInsertCard);
            $stmtInsertCard->bind_param("issis", $userID, $cardNumber, $expirationDate, $cvv, $billingAddress);
            $stmtInsertCard->execute();
            updateSessionUserCards($conn, $userID);
            
        } else {
            echo '<script> alert ("You have reached the maximum limit of 3 cards")</script>';
        }
    }

    // Check if deleting a card
    if (isset($_POST['delete_card'])) {
        $cardID = $_POST['card_id'];

        // Delete card from the database
        $sqlDeleteCard = "DELETE FROM user_cards WHERE card_id = ? AND user_id = ?";
        $stmtDeleteCard = $conn->prepare($sqlDeleteCard);
        $stmtDeleteCard->bind_param("ii", $cardID, $userID);
        $stmtDeleteCard->execute();
        unset($_SESSION['user_cards'][$cardIndexToRemove]);
        updateSessionUserCards($conn, $userID);
    }

    // Redirect to profile.php after processing form data
    header("Location: profile.php");
    exit();
}

// Function to count the number of cards for a user
function countUserCards($conn, $userID) {
    $sqlCountCards = "SELECT COUNT(*) AS card_count FROM user_cards WHERE user_id = ?";
    $stmtCountCards = $conn->prepare($sqlCountCards);
    $stmtCountCards->bind_param("i", $userID);
    $stmtCountCards->execute();
    $resultCountCards = $stmtCountCards->get_result();
    $row = $resultCountCards->fetch_assoc();
    return $row['card_count'];
}
function updateSessionUserCards($conn, $userID) {
    $sqlGetCards = "SELECT * FROM user_cards WHERE user_id = ?";
    $stmtGetCards = $conn->prepare($sqlGetCards);
    $stmtGetCards->bind_param("i", $userID);
    $stmtGetCards->execute();
    $resultCards = $stmtGetCards->get_result();

    $userCards = [];
    if ($resultCards->num_rows > 0) {
        while ($row = $resultCards->fetch_assoc()) {
            $userCards[] = $row;
        }
    }

    $_SESSION['user_cards'] = $userCards;
}
// Retrieve user's card information from the database
$sqlGetCards = "SELECT * FROM user_cards WHERE user_id = ?";
$stmtGetCards = $conn->prepare($sqlGetCards);
$stmtGetCards->bind_param("i", $userID);
$stmtGetCards->execute();
$resultCards = $stmtGetCards->get_result();

$userCards = [];
if ($resultCards->num_rows > 0) {
    while ($row = $resultCards->fetch_assoc()) {
        $userCards[] = $row;
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2, h3 {
            color: #333;
            text-align: center;
        }
        form {
            text-align: center;
        }
        form label {
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }
        form input[type="text"], form input[type="date"], form textarea, form button, form input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .button-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        .button-container button:hover {
            background-color: #0056b3;
        }
        .cards-container ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
        }
        .cards-container ul li {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8f9fa;
            display: inline-block;
        }
        .cards-container ul li form {
            display: inline;
        }
        .cards-container ul li form input[type="submit"] {
            background-color: #dc3545;
        }
        .cards-container ul li form input[type="submit"]:hover {
            background-color: #bd2130;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="container">
        <h1>Welcome, <?php echo $username; ?>!</h1>
        <h2>Profile Information</h2>
        <form action="profile.php" method="POST">
            <input type="hidden" name="form_type" value="update_profile">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" disabled>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?php echo $email; ?>" disabled>
            <div class="button-container">
                <!-- Add Card Button -->
                <?php if (countUserCards($conn, $userID) < 3) { ?>
                    <button type="button" onclick="showAddCardForm()">+ Add Card for Payment</button>
                <?php } else {
                    echo '<span style="color: red;">Maximum limit of 3 cards reached</span>';
                } ?>
            </div>
            <!-- Add Card Form (Initially Hidden) -->
            <div id="addCardForm" style="display: none;">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" required>
                <label for="expiration_date">Expiration Date:</label>
                <input type="date" id="expiration_date" name="expiration_date" required>
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>
                <label for="billing_address">Billing Address:</label>
                <textarea id="billing_address" name="billing_address" required></textarea>
                <input type="hidden" name="add_card" value="1">
                <input type="submit" value="Submit Card">
            </div>
        </form>
        <!-- Display User's Cards -->
        <div class="cards-container">
            <?php if (!empty($userCards)) { ?>
                <h3>Your Cards:</h3>
                <ul>
                    <?php foreach ($userCards as $card) { ?>
                        <li>
                            Card Number: <?php echo $card['card_number']; ?><br>
                            Expiration Date: <?php echo $card['expiration_date']; ?><br>
                            CVV: <?php echo $card['cvv']; ?><br>
                            Billing Address: <?php echo $card['billing_address']; ?><br>
                            <form action="profile.php" method="POST">
                                <input type="hidden" name="form_type" value="update_profile">
                                <input type="hidden" name="card_id" value="<?php echo $card['card_id']; ?>">
                                <input type="hidden" name="delete_card" value="1">
                                <input type="submit" value="Delete Card">
                            </form>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
    </div>


<script>
document.getElementById("add_card_button").addEventListener("click", function() {
    document.getElementById("payment_info").style.display = "block";
});

        function showAddCardForm() {
            document.getElementById('addCardForm').style.display = 'block';
        }
</script>

</body>
</html>