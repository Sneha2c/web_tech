<?php
session_start();
require_once 'db_connection.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);
    
    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            
            if ($remember) {
                setcookie('remember_user', $row['id'], time() + (86400 * 7), "/");
            }
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Exp 10</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            padding: 40px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        h1 { text-align: center; color: #667eea; }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }
        .checkbox input {
            width: auto;
            margin: 0;
        }
        .error { color: red; text-align: center; margin: 10px 0; }
        .nav { text-align: center; margin-top: 20px; }
        .nav a { color: #667eea; text-decoration: none; margin: 0 10px; }
        .cookie-info {
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🔐 Login</h1>
    <?php if ($error) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username or Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="checkbox">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember Me (7 days)</label>
        </div>
        <button type="submit">Login</button>
    </form>
    <div class="cookie-info">
        🍪 Check "Remember Me" to set a cookie
    </div>
    <div class="nav">
        <a href="register.php">Don't have an account? Register</a>
    </div>
</div>
</body>
</html>