<?php
require_once 'config.php';

function get_all_products() {
    global $conn;
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_product_by_id($id) {
    global $conn;
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function add_product($name, $description, $price, $image_path, $buy_now_link) {
    global $conn;
    $sql = "INSERT INTO products (name, description, price, image_path, buy_now_link) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $name, $description, $price, $image_path, $buy_now_link);
    return $stmt->execute();
}

function update_product($id, $name, $description, $price, $buy_now_link) {
    global $conn;
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, buy_now_link = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $name, $description, $price, $buy_now_link, $id);
    return $stmt->execute();
}

function update_product_image($id, $image_path) {
    global $conn;
    $sql = "UPDATE products SET image_path = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $image_path, $id);
    return $stmt->execute();
}

function delete_product($id) {
    global $conn;
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function get_admin_user($username) {
    global $conn;
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>