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
    $_SESSION['display_name'] = $newDisplayName;

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
        /* Your CSS styles here */
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <h2>Profile Information</h2>
    <form action="profile.php" method="POST">
    <input type="hidden" name="form_type" value="update_profile">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo $username; ?>" disabled><br><br>
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?php echo $email; ?>" disabled><br><br>
    <label for="display_name">Display Name:</label>
    <input type="text" id="display_name" name="display_name" value="<?php echo $displayName; ?>"><br><br>
    <button type="submit">Update Display Name</button>
    </form>

    <!-- Add Card Button -->
    <?php if (countUserCards($conn, $userID) < 3) { ?>
    <button type="button" onclick="showAddCardForm()">+ Add Card for Payment</button>
    <?php } else {
        echo '<span style="color: red;">Maximum limit of 3 cards reached</span>';
    } ?>

    <!-- Add Card Form (Initially Hidden) -->
    <div id="addCardForm" style="display: none;">
        <label for="card_number">Card Number:</label>
        <input type="text" id="card_number" name="card_number" required><br><br>
        <label for="expiration_date">Expiration Date:</label>
        <input type="date" id="expiration_date" name="expiration_date" required><br><br>
        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" required><br><br>
        <label for="billing_address">Billing Address:</label>
        <textarea id="billing_address" name="billing_address" required></textarea><br><br>
        <input type="hidden" name="add_card" value="1">
        <input type="submit" value="Submit Card">
    </div>
</form>


<!-- Display User's Cards -->
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
