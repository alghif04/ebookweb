<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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