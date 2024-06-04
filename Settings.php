<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #333;
            padding: 20px;
            color: #fff;
        }

        .sidebar h2 {
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .sidebar .section {
            margin-bottom: 20px;
        }

        .sidebar label {
            display: block;
            margin-bottom: 5px;
        }

        .sidebar select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #666;
            background-color: #444;
            color: #fff;
        }

        .sidebar .btn-save {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .sidebar .btn-save:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Settings</h2>

    <div class="section">
        <label for="mode">Mode:</label>
        <select id="mode">
            <option value="dark">Dark</option>
            <option value="light">Light</option>
            <option value="warm">Warm</option>
        </select>
    </div>

    <div class="section">
        <label for="paymentMethod">Payment Method:</label>
        <select id="paymentMethod">
            <option value="bank">Transfer Bank</option>
            <option value="gopay">Gopay</option>
        </select>
    </div>

    <button class="btn-save">Save Settings</button>
</div>

<script>
    document.querySelector('.btn-save').addEventListener('click', function() {
        const mode = document.getElementById('mode').value;
        const paymentMethod = document.getElementById('paymentMethod').value;
        alert('Settings saved!\nMode: ' + mode + '\nPayment Method: ' + paymentMethod);
    });
</script>

</body>
</html>
