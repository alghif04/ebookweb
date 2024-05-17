<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Readopolis</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .header {
            background-color: #122B6B;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .search-bar {
            display: flex;
        }
        .search-bar input[type="text"] {
            padding: 6px;
            border: 1px solid #5D639D;
            border-radius: 4px;
            background-color: #5D639D;
        }
        .search-bar button {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 6px 10px;
            margin-left: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1></h1>
        <div class="search-bar">
            <input type="text">
            <button>Search</button>
        </div>
    </div>
</body>
</html>
