<?php
session_start();

// Product prices
$product_prices = [
    'Matte Lipstick' => 499,
    'Liquid Foundation' => 899,
    'Kajal' => 199,
    'Eyeshadow Palette' => 699,
    'Compact Powder' => 299,
    'Mascara' => 599
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exp 8 - E-commerce Checkout (PHP Form)</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 700px;
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
        
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .form-section h3 {
            color: #ff6b6b;
            margin-bottom: 20px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
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
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
        }
        
        .error-input {
            border-color: #ff4757 !important;
        }
        
        .error-message {
            color: #ff4757;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            cursor: pointer;
        }
        
        .required {
            color: #ff4757;
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
        
        .success-message {
            background: #00b894;
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .order-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .order-summary p {
            margin: 8px 0;
        }
        
        .total {
            font-size: 24px;
            font-weight: bold;
            color: #ff6b6b;
            text-align: right;
            margin-top: 10px;
        }
        
        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🛍️ Complete Your Order</h1>
        <p>Experiment 8 - PHP Form Handling & Validation</p>
    </div>
    
    <div class="form-card">
        <?php
        // Initialize variables
        $errors = [];
        $success = false;
        $firstname = $lastname = $email = $phone = $address = $city = $pincode = $category = $instructions = "";
        $quantity = 1;
        
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            
            $firstname = trim($_POST['firstname'] ?? '');
            $lastname = trim($_POST['lastname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $pincode = trim($_POST['pincode'] ?? '');
            $category = $_POST['category'] ?? '';
            $quantity = (int)($_POST['quantity'] ?? 1);
            $instructions = trim($_POST['instructions'] ?? '');
            
            // Validation
            if (empty($firstname) || strlen($firstname) < 2) {
                $errors['firstname'] = "Valid first name required";
            }
            
            if (empty($lastname) || strlen($lastname) < 2) {
                $errors['lastname'] = "Valid last name required";
            }
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Valid email required";
            }
            
            if (empty($phone) || !preg_match("/^[6-9][0-9]{9}$/", $phone)) {
                $errors['phone'] = "Valid 10-digit mobile number required";
            }
            
            if (empty($address)) {
                $errors['address'] = "Address required";
            }
            
            if (empty($city)) {
                $errors['city'] = "City required";
            }
            
            if (empty($pincode) || !preg_match("/^[0-9]{6}$/", $pincode)) {
                $errors['pincode'] = "Valid 6-digit PIN code required";
            }
            
            if ($quantity < 1 || $quantity > 10) {
                $errors['quantity'] = "Quantity must be between 1 and 10";
            }
            
            if (empty($errors)) {
                $success = true;
                $price = $product_prices[$category] ?? 499;
                $subtotal = $price * $quantity;
                $shipping = ($subtotal > 999) ? 0 : 50;
                $total = $subtotal + $shipping;
                $order_number = 'ORD' . date('Ymd') . rand(1000, 9999);
                
                $order_data = [
                    'order_number' => $order_number,
                    'name' => $firstname . ' ' . $lastname,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => "$address, $city - $pincode",
                    'product' => $category,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'shipping' => $shipping,
                    'total' => $total,
                    'date' => date('Y-m-d H:i:s')
                ];
                
                setcookie('last_order_number', $order_number, time() + (86400 * 30), "/");
                $_SESSION['last_order'] = $order_data;
            }
        }
        
        if ($success):
        ?>
            <div class="success-message">
                ✅ Order Placed Successfully!
            </div>
            <h2 style="text-align: center;">Thank you, <?php echo htmlspecialchars($firstname); ?>!</h2>
            
            <div class="order-summary">
                <h3>Order Summary:</h3>
                <p><strong>Order Number:</strong> <?php echo $order_number; ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($address . ', ' . $city . ' - ' . $pincode); ?></p>
                <p><strong>Product:</strong> <?php echo htmlspecialchars($category); ?></p>
                <p><strong>Quantity:</strong> <?php echo $quantity; ?></p>
                <p><strong>Price per item:</strong> ₹<?php echo number_format($price, 2); ?></p>
                <p><strong>Subtotal:</strong> ₹<?php echo number_format($subtotal, 2); ?></p>
                <p><strong>Shipping:</strong> <?php echo ($shipping == 0) ? 'FREE' : '₹' . number_format($shipping, 2); ?></p>
                <p class="total"><strong>Total: ₹<?php echo number_format($total, 2); ?></strong></p>
                <p><small>Order placed on: <?php echo date('Y-m-d H:i:s'); ?></small></p>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="ecommerce_form.php" class="btn" style="text-decoration: none; display: inline-block;">Place Another Order</a>
            </div>
            
        <?php else: ?>
        
        <form method="POST" action="">
            <div class="form-section">
                <h3>👤 Personal Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" class="<?php echo isset($errors['firstname']) ? 'error-input' : ''; ?>">
                        <?php if (isset($errors['firstname'])): ?>
                            <span class="error-message"><?php echo $errors['firstname']; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" class="<?php echo isset($errors['lastname']) ? 'error-input' : ''; ?>">
                        <?php if (isset($errors['lastname'])): ?>
                            <span class="error-message"><?php echo $errors['lastname']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="<?php echo isset($errors['email']) ? 'error-input' : ''; ?>">
                    <?php if (isset($errors['email'])): ?>
                        <span class="error-message"><?php echo $errors['email']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label>Phone <span class="required">*</span></label>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="<?php echo isset($errors['phone']) ? 'error-input' : ''; ?>">
                    <?php if (isset($errors['phone'])): ?>
                        <span class="error-message"><?php echo $errors['phone']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-section">
                <h3>📦 Shipping Address</h3>
                <div class="form-group">
                    <label>Address <span class="required">*</span></label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" class="<?php echo isset($errors['address']) ? 'error-input' : ''; ?>">
                    <?php if (isset($errors['address'])): ?>
                        <span class="error-message"><?php echo $errors['address']; ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>City <span class="required">*</span></label>
                        <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>" class="<?php echo isset($errors['city']) ? 'error-input' : ''; ?>">
                        <?php if (isset($errors['city'])): ?>
                            <span class="error-message"><?php echo $errors['city']; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>PIN Code <span class="required">*</span></label>
                        <input type="text" name="pincode" value="<?php echo htmlspecialchars($pincode); ?>" class="<?php echo isset($errors['pincode']) ? 'error-input' : ''; ?>">
                        <?php if (isset($errors['pincode'])): ?>
                            <span class="error-message"><?php echo $errors['pincode']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3>🛒 Order Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Product</label>
                        <select name="category">
                            <option value="Matte Lipstick" <?php echo ($category == 'Matte Lipstick') ? 'selected' : ''; ?>>💄 Matte Lipstick - ₹499</option>
                            <option value="Liquid Foundation" <?php echo ($category == 'Liquid Foundation') ? 'selected' : ''; ?>>🧴 Liquid Foundation - ₹899</option>
                            <option value="Kajal" <?php echo ($category == 'Kajal') ? 'selected' : ''; ?>>✏️ Kajal - ₹199</option>
                            <option value="Eyeshadow Palette" <?php echo ($category == 'Eyeshadow Palette') ? 'selected' : ''; ?>>🎨 Eyeshadow Palette - ₹699</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1" max="10" class="<?php echo isset($errors['quantity']) ? 'error-input' : ''; ?>">
                        <?php if (isset($errors['quantity'])): ?>
                            <span class="error-message"><?php echo $errors['quantity']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Special Instructions</label>
                    <textarea name="instructions" rows="3"><?php echo htmlspecialchars($instructions); ?></textarea>
                </div>
            </div>
            
            <button type="submit" name="submit" class="btn">Place Order 🚀</button>
        </form>
        
        <?php endif; ?>
    </div>
    
    <div class="nav-links">
        <a href="personal_form.php">📧 Contact Form</a>
    </div>
</div>

</body>
</html>