<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Readopolis</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-content {
            position: relative;
            background-color: #eee;
            min-height: 100vh;
            top: 0;
            left: 80px;
            transition: all 0.5s ease;
            width: calc(100% - 80px);
            padding: 1rem;
        }

        .sort-box {
            display: flex;
            justify-content: flex-start;
            padding: 10px;
            border-bottom: 1px solid #333;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .SortContainer {
            display: inline-block;
            margin-right: 20px;
            vertical-align: middle;
            position: relative;
        }

        .SortLabel a {
            text-decoration: none;
            color: #333;
            position: relative;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .SortLabel a:hover {
            color: #555;
        }

        .SortLabel a::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .SortLabel a:hover::after,
        .SortLabel a:focus::after {
            opacity: 1;
        }

        .SortLabel {
            font-size: 1.2rem;
            font-weight: bold;
            margin-right: 5px;
            z-index: 2;
        }

        .SortValue {
            font-size: 1.2rem;
        }


    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="sort-box">
            <span class="SortLabel">Sort by:</span>
            <div class="SortContainer">
                <span class="SortLabel"><a href="#">Title</a></span>
            </div>
            <div class="SortContainer">
                <span class="SortLabel"><a href="#">Date Published</a></span>
            </div>
            <div class="SortContainer">
                <span class="SortLabel"><a href="#">Date Added</a></span>
            </div>
        </div>
    </div>
</body>
</html>
