<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LMS SMKK SDM' ?></title>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            font-size: 1.5rem;
        }
        .navbar nav a {
            color: white;
            text-decoration: none;
            margin-left: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .navbar nav a:hover {
            background-color: #34495e;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .content {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .footer {
            text-align: center;
            padding: 2rem;
            background-color: #2c3e50;
            color: white;
            margin-top: 2rem;
        }
        .flash-message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .flash-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .flash-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .flash-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .flash-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <h1><?= APP_NAME ?></h1>
        <nav>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <a href="<?= APP_URL ?>/<?= $_SESSION['role'] ?>">Dashboard</a>
                <a href="<?= APP_URL ?>/logout">Logout</a>
            <?php else: ?>
                <a href="<?= APP_URL ?>/login">Login</a>
                <a href="<?= APP_URL ?>/register">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="container">
        <?php if (session_status() === PHP_SESSION_ACTIVE): ?>
            <?php if (isset($_SESSION['flash']['success'])): ?>
                <div class="flash-message flash-success">
                    <?= e($_SESSION['flash']['success']) ?>
                    <?php unset($_SESSION['flash']['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="flash-message flash-error">
                    <?= e($_SESSION['flash']['error']) ?>
                    <?php unset($_SESSION['flash']['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash']['warning'])): ?>
                <div class="flash-message flash-warning">
                    <?= e($_SESSION['flash']['warning']) ?>
                    <?php unset($_SESSION['flash']['warning']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash']['info'])): ?>
                <div class="flash-message flash-info">
                    <?= e($_SESSION['flash']['info']) ?>
                    <?php unset($_SESSION['flash']['info']); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <main class="content">
            <?= $content ?>
        </main>
    </div>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
    </footer>

    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
