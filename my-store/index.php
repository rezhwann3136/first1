<?php
require_once 'includes/config.php';
$products = $conn->query("SELECT * FROM products");
$order_link = getOrderLink();
?>
<!DOCTYPE html>
<html lang="ku">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وێبسایتی فرۆشتن</title>
    <style>
        :root {
            --primary: #4a6bff;
            --dark: #343a40;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
        }
        header {
            background: linear-gradient(135deg, var(--primary), #3a56d4);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .admin-link {
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .admin-link:hover {
            background-color: rgba(255,255,255,0.2);
            text-decoration: none;
        }
        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://via.placeholder.com/1920x600') center/cover;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        .hero-content h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .products-section {
            padding: 3rem 0;
        }
        .section-title {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--dark);
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }
        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .product-image {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .product-card:hover .product-image img {
            transform: scale(1.05);
        }
        .product-info {
            padding: 1.5rem;
        }
        .product-info h3 {
            margin-bottom: 0.5rem;
        }
        .price {
            color: var(--primary);
            font-weight: bold;
            font-size: 1.2rem;
            margin: 0.5rem 0;
        }
        .order-btn {
            display: block;
            background: var(--primary);
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.3s;
        }
        .order-btn:hover {
            background: #3a56d4;
        }
        footer {
            background: var(--dark);
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 2rem;
        }
        @media (max-width: 768px) {.hero {
                height: 300px;
            }
            .hero-content h1 {
                font-size: 2rem;
            }
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">فرۆشتن</div>
            <a href="admin/login.php" class="admin-link">چوونەژوورەوەی ئەدمین</a>
        </div>
    </header>
    
    <section class="hero">
        <div class="hero-content">
            <h1>بەخێربێن بۆ وێبسایتەکەمان</h1>
            <p>باشترین کاڵاکان بە باشترین نرخ</p>
        </div>
    </section>
    
    <section class="products-section">
        <div class="container">
            <h2 class="section-title">کاڵاکانمان</h2>
            
            <div class="products-grid">
                <?php while ($product = $products->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo $product['name']; ?></h3>
                        <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                        <p><?php echo substr($product['description'], 0, 50); ?>...</p>
                        <a href="<?php echo $order_link; ?>" class="order-btn">داواکردن</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    
    <footer>
        <div class="container">
            <p>&copy; 2023 وێبسایتی فرۆشتن. هەموو مافەکان پارێزراون.</p>
        </div>
    </footer>
</body>
</html>