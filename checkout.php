<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="cekout.css">
    <script>
        const bookPrices = {
            "book1": 100000,
            "book2": 150000,
            "book3": 200000
        };

        function calculateTotal() {
            const bookName = document.getElementById('book_name').value;
            const bookQuantity = parseInt(document.getElementById('book_quantity').value) || 0;
            const bookPrice = bookPrices[bookName] || 0;
            const totalPrice = bookQuantity * bookPrice;
            document.getElementById('total_price').innerText = `Total Harga: Rp ${totalPrice}`;
        }
    </script>
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
                <label for="book_name">Nama Buku:</label>
                <select id="book_name" name="book_name" onchange="calculateTotal()" required>
                    <option value="">Pilih Buku</option>
                    <option value="book1">Buku 1 - Rp 100000</option>
                    <option value="book2">Buku 2 - Rp 150000</option>
                    <option value="book3">Buku 3 - Rp 200000</option>
                </select>
            </div>
            <div class="form-group">
                <label for="book_quantity">Jumlah Buku:</label>
                <input type="number" id="book_quantity" name="book_quantity" min="1" onchange="calculateTotal()" required>
            </div>
            <div id="total_price">Total Harga: Rp 0</div>
            <div class="form-group">
                <label for="payment_method">Pembayaran:</label>
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
