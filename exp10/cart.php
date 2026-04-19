<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Remove item
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// Update quantities
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $id => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
    header("Location: cart.php");
    exit();
}

$cart = $_SESSION['cart'];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart - Exp 10</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff6b6b, #ff8e53);
            min-height: 100vh;
            padding: 40px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
        }
        h1 { text-align: center; color: #ff6b6b; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .quantity-input {
            width: 60px;
            padding: 5px;
        }
        .remove-link {
            color: red;
            text-decoration: none;
        }
        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #ff6b6b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .empty {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .nav {
            text-align: center;
            margin-top: 20px;
        }
        .nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            background: rgba(0,0,0,0.2);
            padding: 8px 16px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🛒 Your Cart</h1>
    
    <?php if (empty($cart)): ?>
        <div class="empty">
            <h3>Your cart is empty</h3>
            <a href="shop.php" class="btn">Continue Shopping</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th></th>
                </tr>
                <?php foreach ($cart as $id => $item): ?>
                <tr>
                    <td><?php echo $item['image']; ?> <?php echo $item['name']; ?></td>
                    <td>₹<?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <input type="number" name="quantity[<?php echo $id; ?>]" 
                               value="<?php echo $item['quantity']; ?>" 
                               min="0" max="99" class="quantity-input">
                    </td>
                    <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td><a href="?remove=<?php echo $id; ?>" class="remove-link">Remove</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
            
            <div class="total">
                Grand Total: ₹<?php echo number_format($total, 2); ?>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" name="update" class="btn">Update Cart</button>
                <a href="checkout.php" class="btn">Proceed to Checkout →</a>
            </div>
        </form>
    <?php endif; ?>
    
    <div class="nav">
        <a href="shop.php">Continue Shopping</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>