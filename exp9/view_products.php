<?php
require_once 'db_connection.php';

// Fetch all products
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
$total_products = $result->num_rows;

// Calculate total value
$value_result = $conn->query("SELECT SUM(price * quantity) as total_value FROM products");
$total_value = $value_result->fetch_assoc()['total_value'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products - Exp 9</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #11998e;
        }
        
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .product-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-card h3 {
            color: #11998e;
            margin-bottom: 10px;
        }
        
        .price {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b6b;
            margin: 10px 0;
        }
        
        .category {
            display: inline-block;
            background: #f0f0f0;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .in-stock {
            color: #11998e;
            font-weight: bold;
        }
        
        .out-of-stock {
            color: #ff4757;
            font-weight: bold;
        }
        
        .delete-btn {
            background: #ff4757;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }
        
        .delete-btn:hover {
            background: #ff6b6b;
        }
        
        .nav-links {
            text-align: center;
            margin-top: 30px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            padding: 10px 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 25px;
            display: inline-block;
        }
        
        .product-id {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
        }
        
        .description {
            color: #666;
            font-size: 14px;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📦 Product Catalog</h1>
        <p>Products stored in MySQL Database</p>
    </div>
    
    <div class="stats">
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_products; ?></div>
            <div class="stat-label">Total Products</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">₹<?php echo number_format($total_value, 2); ?></div>
            <div class="stat-label">Inventory Value</div>
        </div>
    </div>
    
    <div class="products-grid">
        <?php if ($total_products > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="product-id">ID: #<?php echo $row['id']; ?></div>
                    <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                    <span class="category"><?php echo htmlspecialchars($row['category']); ?></span>
                    <div class="price">₹<?php echo number_format($row['price'], 2); ?></div>
                    <div class="description">
                        <?php echo htmlspecialchars($row['description'] ?: 'No description available'); ?>
                    </div>
                    <div>
                        <?php if ($row['quantity'] > 0): ?>
                            <span class="in-stock">✓ In Stock (<?php echo $row['quantity']; ?> units)</span>
                        <?php else: ?>
                            <span class="out-of-stock">✗ Out of Stock</span>
                        <?php endif; ?>
                    </div>
                    <small style="color: #999;">Added: <?php echo $row['created_at']; ?></small>
                    <form method="POST" action="delete_product.php" style="margin-top: 10px;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete" class="delete-btn">🗑️ Delete Product</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="product-card" style="text-align: center; grid-column: 1/-1;">
                <h3>No Products Yet</h3>
                <p>Click "Add Product" to add your first product!</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="nav-links">
        <a href="add_product.php">➕ Add New Product</a>
        <a href="../exp8/personal_form.php">← Exp 8</a>
        <a href="../exp10/register.php">Exp 10 →</a>
    </div>
</div>

</body>
</html>