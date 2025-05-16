<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("INSERT INTO orders (product_id, customer_name, customer_contact, order_details) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $_POST['product_id'], $_POST['name'], $_POST['contact'], $_POST['details']);
    $stmt->execute();
    
    header('Location: index.php?ordered=1');
    exit;
}

$product_id = $_GET['product_id'] ?? 0;
$product = $conn->query("SELECT * FROM products WHERE id=$product_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ku">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داواکردنی کاڵا</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .order-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        .order-container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
        }
        .product-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
        }
        .product-info h3 {
            margin-top: 0;
        }
        .product-price {
            color: #4a6bff;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group textarea {
            min-height: 100px;
        }
        .btn-submit {
            background: #4a6bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background: #3a56d4;
        }
    </style>
</head>
<body>
    <div class="order-container">
        <h1>داواکردنی کاڵا</h1>
        
        <?php if ($product): ?>
        <div class="product-info">
            <h3><?php echo $product['name']; ?></h3>
            <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
        </div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            
            <div class="form-group">
                <label for="name">ناو</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="contact">ژمارەی پەیوەندی</label>
                <input type="text" id="contact" name="contact" required>
            </div>
            
            <div class="form-group">
                <label for="details">زانیاری زیاتر</label>
                <textarea id="details" name="details"></textarea>
            </div>
            
            <button type="submit" class="btn-submit">ناردنی داواکاری</button>
        </form>
    </div>
</body>
</html>