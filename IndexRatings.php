<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rate Ebook</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        form {
            text-align: center;
        }

        .star-rating {
            direction: rtl;
            display: inline-block;
            padding: 20px;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            font-size: 30px;
            color: #ddd;
            cursor: pointer;
        }

        .star-rating input[type="radio"]:checked ~ label {
            color: #f5b301;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f5b301;
        }
    </style>
</head>
<body>
    <h1>Rate This Ebook</h1>
    <form action="submit_rating.php" method="post">
        <div>
            <label for="ebook_id">Ebook ID:</label>
            <input type="number" id="ebook_id" name="ebook_id" required><br><br>

            <label for="user_id">User ID:</label>
            <input type="number" id="user_id" name="user_id" required><br><br>

            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" required><label for="star5" title="5 stars">&#9733;</label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars">&#9733;</label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars">&#9733;</label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars">&#9733;</label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star">&#9733;</label>
            </div><br><br>
            
            <input type="submit" value="Submit Rating">
        </div>
    </form>
</body>
</html>
