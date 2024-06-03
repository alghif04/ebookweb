<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="cekout.css">
</head>
<body>
    <div class="checkout-container">
        <h1>Checkout</h1>
        <form action="process_checkout.php" method="POST">
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="payment_method">Metode Pembayaran:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="paypal">PayPal</option>
                    <option value="bank_transfer">Transfer Bank</option>
                </select>
            </div>
            <button type="submit">Bayar Sekarang</button>
        </form>
    </div>
</body>
</html>
