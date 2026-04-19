<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$products = [
    1 => ['name' => 'Matte Lipstick', 'price' => 499, 'image' => '💄'],
    2 => ['name' => 'Liquid Foundation', 'price' => 899, 'image' => '🧴'],
    3 => ['name' => 'Kajal', 'price' => 199, 'image' => '✏️'],
    4 => ['name' => 'Eyeshadow Palette', 'price' => 699, 'image' => '🎨'],
];

if (isset($_GET['add'])) {
    $id = $_GET['add'];
    if (isset($products[$id])) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $products[$id]['name'],
                'price' => $products[$id]['price'],
                'quantity' => 1,
                'image' => $products[$id]['image']
            ];
        }
    }
    header("Location: shop.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shop - Exp 10</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff6b6b, #ff8e53);
            min-height: 100vh;
            padding: 40px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .cart-info {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .product {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .product-image {
            font-size: 60px;
        }
        .price {
            font-size: 24px;
            color: #ff6b6b;
            font-weight: bold;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #ff6b6b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .nav {
            text-align: center;
            margin-top: 20px;
        }
        .nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🛍️ Glamora Store</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
    </div>
    
    <div class="cart-info">
        <span>🛒 Cart Items: <?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?></span>
        <a href="cart.php" class="btn">View Cart →</a>
    </div>
    
    <div class="products">
        <?php foreach ($products as $id => $product): ?>
            <div class="product">
                <div class="product-image"><?php echo $product['image']; ?></div>
                <h3><?php echo $product['name']; ?></h3>
                <div class="price">₹<?php echo $product['price']; ?></div>
                <a href="?add=<?php echo $id; ?>" class="btn">Add to Cart</a>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>