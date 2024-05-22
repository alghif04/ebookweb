<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* CSS untuk popup */
        .popup, .about {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .popup img, .about img {
            display: block;
            margin: 0 auto;
            width: 50px; /* Sesuaikan ukuran gambar sesuai kebutuhan */
        }
        .popup h2, .about h2 {
            text-align: center;
        }
        .popup p, .about p {
            text-align: center;
        }
        .popup button, .about button {
            display: block;
            margin: 0 auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .popup button:hover, .about button:hover {
            background-color: #0056b3;
        }
        /* CSS untuk overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
    </style>
</head>
<body>
    
<!-- Popup apabila belum login -->
<div class="popup" id="popup"> 
    <img src="redx.png" alt="">
    <h2>Not logged in</h2>
    <p>You need to login to access this feature</p>
    <button type="button" onclick="closePopup()">OK</button>
</div>

<!-- Popup about -->
<div class="about" id="about"> 
    <h2>About</h2>
    <p>Web programming class e-book website project</p>
    <ul>
        <li>Muhammad Rohan Kasyfillah (22081010289)</li>
        <li>Farhat Ibad Al Ghifari (22081010290)</li>
        <li>Neo Ramadhani (22081010291)</li>
        <li>Zenryo Yudi Arnava Darva Mahendra (22081010292)</li>
        <li>Belia Putri Salsabila (22081010293)</li>
    </ul>
    <button type="button" onclick="closeAbout()">OK</button>
</div>

<!-- Overlay -->
<div class="overlay" id="overlay"></div>

<script>
    //Popup belum login
    let popup = document.getElementById("popup");
    let overlay = document.getElementById("overlay");

    function openPopup(event){
        event.preventDefault();
        popup.style.display = "block";
        overlay.style.display = "block";
    }

    function closePopup(){
        popup.style.display = "none";
        overlay.style.display = "none";
    }

    //Popup about
    let about = document.getElementById("about");

    function openAbout(event){
        event.preventDefault();
        about.style.display = "block";
        overlay.style.display = "block";
    }

    function closeAbout(){
        about.style.display = "none";
        overlay.style.display = "none";
    }

</script>
</body>
</html>
