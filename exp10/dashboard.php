<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Exp 10</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            padding: 40px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        h1 { text-align: center; color: #667eea; }
        .info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .info p {
            margin: 10px 0;
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .btn-danger {
            background: #ff4757;
        }
        .nav { text-align: center; margin-top: 20px; }
        .nav a { margin: 0 10px; }
    </style>
</head>
<body>
<div class="container">
    <h1>👤 Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    
    <div class="info">
        <h3>📋 Session Information</h3>
        <p><strong>User ID:</strong> <?php echo $_SESSION['user_id']; ?></p>
        <p><strong>Username:</strong> <?php echo $_SESSION['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
        <p><strong>Session ID:</strong> <?php echo session_id(); ?></p>
        <p><strong>Login Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>
    
    <?php if (isset($_COOKIE['remember_user'])): ?>
    <div class="info">
        <h3>🍪 Cookie Information</h3>
        <p><strong>Remember Me Cookie:</strong> <?php echo $_COOKIE['remember_user']; ?></p>
        <p><strong>Expires:</strong> 7 days from login</p>
    </div>
    <?php endif; ?>
    
    <div style="text-align: center;">
        <a href="shop.php" class="btn">🛍️ Shop</a>
        <a href="cart.php" class="btn">🛒 Cart</a>
        <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
    </div>
    
    <div class="nav">
        <a href="../exp8/personal_form.php">← Exp 8</a>
        <a href="../exp9/add_product.php">Exp 9 →</a>
    </div>
</div>
</body>
</html>