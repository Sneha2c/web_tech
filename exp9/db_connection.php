<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "ecommerce_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    // Select the database
    $conn->select_db($database);
    
    // Create products table
    $table_sql = "CREATE TABLE IF NOT EXISTS products (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        product_name VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        quantity INT(11) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($table_sql) === TRUE) {
        // Check if table is empty and insert sample data
        $check = $conn->query("SELECT COUNT(*) as count FROM products");
        $row = $check->fetch_assoc();
        if ($row['count'] == 0) {
            $sample_products = [
                "INSERT INTO products (product_name, category, price, quantity, description) VALUES ('Matte Lipstick', 'Lipsticks', 499, 50, 'Long-lasting matte finish lipstick with rich pigmentation')",
                "INSERT INTO products (product_name, category, price, quantity, description) VALUES ('Liquid Foundation', 'Foundation', 899, 30, 'Full coverage liquid foundation for all skin types')",
                "INSERT INTO products (product_name, category, price, quantity, description) VALUES ('Kajal', 'Eyes', 199, 100, 'Smudge-proof kajal for intense eye definition')",
                "INSERT INTO products (product_name, category, price, quantity, description) VALUES ('Eyeshadow Palette', 'Eyes', 699, 25, '12-shade eyeshadow palette with matte and shimmer finish')",
                "INSERT INTO products (product_name, category, price, quantity, description) VALUES ('Compact Powder', 'Face', 299, 40, 'Oil-control compact powder for matte finish')"
            ];
            foreach ($sample_products as $sql) {
                $conn->query($sql);
            }
        }
    }
}
?>


