<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .popup {
        width: 400px;
        background: #fff;
        border-radius: 6px;
        position:absolute;
        top: 0;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.1);
        text-align: center;
        padding: 0 30px 30px;
        color: #333;
        visibility: hidden;
        transition: transform 0.4s, top 0.4s, visibility 0.4s;
        z-index: 1000;
    }

    .open-popup {
        visibility: visible;
        top: 50%;
        transform: translate(-50%, -50%) scale(1);
    }

    .popup img {
        width: 100px;
        margin-top: -50px;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .popup h2 {
        font-size: 38px;
        font-weight: 500;
        margin: 30px 0 10px;
    }

    .popup button {
        width: 100%;
        margin-top: 50px;
        padding: 10px 0;
        background: #eb5834;
        color: #fff;
        border: 0;
        outline: none;
        font-size: 18px;
        border-radius: 4px;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .about {
        width: 400px;
        max-width: 90%;
        background: #f7f7f7;
        border-radius: 10px;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.1);
        text-align: center;
        padding: 30px;
        color: #333;
        visibility: hidden;
        transition: transform 0.4s, top 0.4s, visibility 0.4s;
        z-index: 1001;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .open-about {
    visibility: visible;
    top: 50%;
    transform: translate(-50%, -50%) scale(1);
    }

    .about h2 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 20px;
    }

    .about p {
    font-size: 16px;
    margin-bottom: 30px;
    }

    .about ul {
    list-style-type: none;
    padding: 0;
    }

    .about li {
    font-size: 14px;
    margin-bottom: 10px;
    }

    .about button {
    width: 100%;
    padding: 12px 0;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    }

    .about button:hover {
    background-color: #45a049;
    }

        
    </style>
</head>
<body>
    
<!-- Popup apabila belum login -->
    <div class="popup" id="popup"> 
        <img src="redx.png" alt="">
        <h2>Not logged in</h2>
        <p>You need to login to access this feature</p>
        <button type="button" onclick= "closePopup()">OK</button>
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
        <button type="button" onclick= "closeAbout()">OK</button>
    </div>

<script>
    //Popup belum login
    let popup = document.getElementById("popup");
    function openPopup(event){
        event.preventDefault();
        popup.classList.add("open-popup");
    }
    function closePopup(){
        popup.classList.remove("open-popup");
    }

    //Popup about
    let about = document.getElementById("about");
    function openAbout(event){
        event.preventDefault();
        about.classList.add("open-about");
    }
    function closeAbout(){
        about.classList.remove("open-about");
    }

</script>
</body>
</html>