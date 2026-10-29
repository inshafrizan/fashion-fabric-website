<?php
// Start session if not already started (needed for login system)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration (XAMPP localhost)
$host = 'localhost';
$username = 'root';
$password = '';                    // Default empty in XAMPP
$database = 'fashionfabrics';      // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 (best for emojis, special characters, full Unicode support)
$conn->set_charset("utf8mb4");

// Create tables and default admin only if they don't exist
createTablesIfNotExist($conn);

function createTablesIfNotExist($conn) {
    // 1. Users Table
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        business_name VARCHAR(100),
        user_type ENUM('customer', 'admin') DEFAULT 'customer',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // 2. Fabrics Table (you already have this, but adding here for completeness)
    $sql_fabrics = "CREATE TABLE IF NOT EXISTS fabrics (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        type VARCHAR(50) NOT NULL,
        price_per_meter DECIMAL(10,2) NOT NULL,
        available_meters DECIMAL(10,2) NOT NULL,
        status ENUM('available', 'out_of_stock', 'discontinued') DEFAULT 'available',
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // 3. Orders Table
    $sql_orders = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT DEFAULT 0,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        business_name VARCHAR(100),
        fabric_type VARCHAR(100) NOT NULL,
        quantity DECIMAL(10,2) NOT NULL,
        color_preference VARCHAR(100),
        notes TEXT,
        status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // Execute all table creations
    $conn->query($sql_users);
    $conn->query($sql_fabrics);
    $conn->query($sql_orders);

    // Create default admin account if no admin exists
    $result = $conn->query("SELECT id FROM users WHERE user_type = 'admin' LIMIT 1");
    if ($result->num_rows === 0) {
        $default_password = 'admin123'; // Change this later!
        $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone, user_type) VALUES (?, ?, ?, ?, ?, 'admin')");
        $stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $phone);

        $username = 'admin';
        $email = 'admin@fashionfabric.lk';
        $full_name = 'Administrator';
        $phone = '+94123456789';

        $stmt->execute();
        $stmt->close();

        // Optional: Log this for security
        error_log("Default admin created: Username: admin | Password: admin123 (CHANGE IMMEDIATELY!)");
    }
}
?>