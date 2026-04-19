<?php
session_start();
require_once 'db_connection.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $product_name = trim($_POST['product_name']);
    $category = $_POST['category'];
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $description = trim($_POST['description']);
    
    // Validation
    $errors = [];
    
    if (empty($product_name) || strlen($product_name) < 3) {
        $errors[] = "Product name must be at least 3 characters";
    }
    
    if (empty($category)) {
        $errors[] = "Please select a category";
    }
    
    if ($price <= 0) {
        $errors[] = "Price must be greater than 0";
    }
    
    if ($quantity < 0) {
        $errors[] = "Quantity cannot be negative";
    }
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO products (product_name, category, price, quantity, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $product_name, $category, $price, $quantity, $description);
        
        if ($stmt->execute()) {
            $message = "Product added successfully!";
            $message_type = "success";
        } else {
            $message = "Error: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    } else {
        $message = implode("<br>", $errors);
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Exp 9</title>
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
            max-width: 600px;
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
        
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .nav-links {
            text-align: center;
            margin-top: 20px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            padding: 8px 16px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            display: inline-block;
        }
        
        .required {
            color: #ff4757;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>➕ Add New Product</h1>
        <p>Experiment 9 - Store products in MySQL database</p>
    </div>
    
    <div class="form-card">
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Product Name <span class="required">*</span></label>
                <input type="text" name="product_name" required>
            </div>
            
            <div class="form-group">
                <label>Category <span class="required">*</span></label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option>Lipsticks</option>
                    <option>Foundation</option>
                    <option>Eyes</option>
                    <option>Face</option>
                    <option>Skincare</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Price (₹) <span class="required">*</span></label>
                <input type="number" name="price" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label>Quantity <span class="required">*</span></label>
                <input type="number" name="quantity" min="0" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" placeholder="Product description..."></textarea>
            </div>
            
            <button type="submit" name="submit" class="btn">Add Product</button>
        </form>
    </div>
    
    <div class="nav-links">
        <a href="view_products.php">📋 View Products</a>
        <a href="../exp8/personal_form.php">← Exp 8</a>
        <a href="../exp10/register.php">Exp 10 →</a>
    </div>
</div>

</body>
</html>
