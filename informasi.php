<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Halaman informasi dan Pembelian</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .book-container{
                background: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0,0,0,0,0.1);
                width: 300px;
                text-align: center;
            }
            .book-container img{
                max-width: 100%;
                border-radius: 5px;
            }
            .book-details{
                margin-top: 20px;
            }
            .book-details h2{
                margin: 0 0 10px;
            }
            .book-details p{
                margin: 5px;
                color: #555;
            }
            .checkout.button{
                margin: 5px 0;
                color: #555;
            }
            .checkout.button button{
                background-color: #28a745;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
            }
            .checkout.butto button :hover{
                background-color: #218838;
            }
        </style>
    </head>
    <body>
        <div class="book-container">
            <img src="buku.jpg"alt="Cover Buku">
            <div class="book-details">
                <h2>Judul Buku </h2>
                <p>Detail atau isi dari ringkasan buku tersebut dengan ini anda bisa mengisikan isi dari ringkasan atau detail dari buku tersebut</p>
                <p>Penulis :Nama Penulis</p>
                <p>harga :Rp.......</p>
            </div>
            <div class="checkout-button">
                <form action="checkout.php" method="POST">
                    <input type="hidden"name="book_id"value="1">
                    <button type="submit">Checkout</button>
                </form>
            </div>
        </div>
    </body>
</html>
