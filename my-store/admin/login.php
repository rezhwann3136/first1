<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['password'] === '3136') {
    $_SESSION['admin_logged_in'] = true;
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ku">
<head>
    <meta charset="UTF-8">
    <title>چوونەژوورەوەی ئەدمین</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #4a6bff, #3a56d4);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
        }
        .login-container h1 {
            color: #333;
            margin-bottom: 1.5rem;
        }
        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-container button {
            background: #4a6bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .login-container button:hover {
            background: #3a56d4;
        }
        .error {
            color: #dc3545;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>چوونەژوورەوەی ئەدمین</h1>
        <form method="POST">
            <input type="password" name="password" placeholder="پاسۆرد" required>
            <button type="submit">چوونەژوورەوە</button>
        </form>
    </div>
</body>
</html>