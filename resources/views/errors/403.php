<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .error-container {
            text-align: center;
            padding: 40px;
        }
        h1 {
            font-size: 72px;
            color: #f39c12;
            margin: 0;
        }
        h2 {
            color: #333;
            margin: 20px 0;
        }
        p {
            color: #666;
            margin-bottom: 30px;
        }
        a {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>403</h1>
        <h2>Access Forbidden</h2>
        <p>Maaf, Anda tidak memiliki akses ke halaman ini.</p>
        <a href="<?= APP_URL ?>">Kembali ke Beranda</a>
    </div>
</body>
</html>
