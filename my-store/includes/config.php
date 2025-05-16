<?php
$conn = new mysqli('localhost', 'username', 'password', 'database_name');
session_start();
$conn = new mysqli('localhost', 'root', '', 'store_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create tables with proper relationships
$conn->query("CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_link VARCHAR(255) DEFAULT '#'
)");

// Initialize admin account (password: 3136)
$conn->query("INSERT IGNORE INTO admin (username, password) VALUES ('admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')");
$conn->query("INSERT IGNORE INTO settings (id, order_link) VALUES (1, '#')");

// Create uploads directory if not exists
if (!file_exists('../uploads')) {
    mkdir('../uploads', 0777, true);
}

function getOrderLink() {
    global $conn;
    $result = $conn->query("SELECT order_link FROM settings WHERE id=1");
    return $result->fetch_assoc()['order_link'];
}
?>