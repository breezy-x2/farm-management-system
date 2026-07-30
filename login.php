<?php
session_start();
require_once 'config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest Hub - Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .back-home { padding: 25px 60px; }
        .back-home a { color: #666; text-decoration: none; font-size: 14px; }
        .back-home a:hover { color: #333; }

        .auth-box {
            width: 400px;
            max-width: 90%;
            margin: 50px auto;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 35px;
            text-align: center;
        }

        .auth-box h2 { color: #555; font-size: 28px; margin-bottom: 8px; }
        .auth-subtitle { color: #777; font-size: 14px; margin-bottom: 25px; }

        .auth-error {
            background-color: #eeeeee;
            color: #555;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .auth-box form { text-align: left; }
        .auth-box label { display: block; font-size: 14px; margin-bottom: 7px; font-weight: 600; color: #555; }
        .auth-box input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 18px;
            font-size: 14px;
        }
        .auth-box input:focus { outline: none; border-color: #777; }

        .btn-primary {
            display: block;
            width: 100%;
            background-color: #666;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
        }
        .btn-primary:hover { background-color: #444; }

        .auth-switch { margin-top: 18px; font-size: 14px; color: #777; }
        .auth-switch a { color: #555; font-weight: bold; text-decoration: none; }
        .auth-switch a:hover { text-decoration: underline; }

        .login-footer {
            background-color: #666;
            color: white;
            text-align: center;
            padding: 30px 20px;
            margin-top: 80px;
        }

        @media (max-width: 500px) {
            .back-home { padding: 20px; }
            .auth-box { margin: 30px auto; padding: 25px; }
            .auth-box h2 { font-size: 24px; }
        }
    </style>
</head>
<body>

<div class="back-home">
    <a href="index.html"> Back to home</a>
</div>

<section class="auth-box">
    <h2>Farmer Login</h2>
    <p class="auth-subtitle">Login to access your Harvest Hub account</p>

    <?php if ($error): ?>
        <p class="auth-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <button type="submit" class="btn-primary">Login</button>
    </form>

    <p class="auth-switch">No account yet? <a href="register.php">Register</a></p>
</section>

<footer class="login-footer">
    <p>Harvest Hub — school project, 2026</p>
</footer>

</body>
</html>
