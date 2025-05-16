<?php
require_once '../includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        $target_dir = "../uploads/";
        $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES["image"]["name"]));
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $_POST['name'], $_POST['description'], $_POST['price'], $target_file);
            $stmt->execute();
            $success = "Product added successfully!";
        } else {
            $error = "Error uploading image!";
        }
    }
    
    if (isset($_POST['update_order_link'])) {
        $new_link = $_POST['order_link'];
        $conn->query("UPDATE settings SET order_link='$new_link' WHERE id=1");
        $success = "Order link updated!";
    }
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $product = $conn->query("SELECT image_path FROM products WHERE id=$id")->fetch_assoc();
    if ($product && file_exists($product['image_path'])) {
        unlink($product['image_path']);
    }
    $conn->query("DELETE FROM products WHERE id=$id");
    $success = "Product deleted successfully!";
}

$products = $conn->query("SELECT * FROM products");
$order_link = getOrderLink();
?>
<!DOCTYPE html>
<html lang="ku">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        :root {
            --primary: #4a6bff;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: var(--dark);
            color: white;
            padding: 1rem 0;
        }
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        .sidebar-menu li a {
            display: block;
            padding: 12px 1rem;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }
        .sidebar-menu li a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .main-content {
            flex: 1;
            padding: 1.5rem;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .card-header {
            padding: 1rem;
            background-color: var(--light);
            border-bottom: 1px solid #eee;
        }
        .card-body {
            padding: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;}
        textarea.form-control {
            min-height: 100px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        .btn-primary:hover {
            background-color: #3a56d4;
        }
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .product-card {
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
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
        }
        .product-info {
            padding: 1rem;
        }
        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .image-preview {
            max-width: 150px;
            max-height: 150px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>فەرمانڕەوایی</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#products"><i class="fas fa-box"></i> کاڵاکان</a></li>
                <li><a href="#settings"><i class="fas fa-cog"></i> ڕێکخستنەکان</a></li>
                <li><a href="?logout"><i class="fas fa-sign-out-alt"></i> چوونەدەرەوە</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <h1>داشبۆردی ئەدمین</h1>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <section id="products" class="card">
                <div class="card-header">
                    <h2>بەڕێوەبردنی کاڵاکان</h2>
                </div>
                <div class="card-body">
                    <h3>زیادکردنی کاڵای نوێ</h3>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="add_product" value="1">
                        <div class="form-group">
                            <label>ناوی کاڵا</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>پێناسە</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>نرخ</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                        <div class="form-group"></div><label>وێنە</label>
                            <input type="file" name="image" class="form-control" required>
                            <img id="image-preview" class="image-preview" src="#" alt="وێنەی پێشبینیکراو">
                        </div>
                        <button type="submit" class="btn btn-primary">زیادکردن</button>
                    </form>
                    
                    <h3 style="margin-top: 2rem;">لیستی کاڵاکان</h3>
                    <div class="product-grid">
                        <?php while ($product = $products->fetch_assoc()): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>">
                            </div>
                            <div class="product-info">
                                <h4><?php echo $product['name']; ?></h4>
                                <p>$<?php echo number_format($product['price'], 2); ?></p>
                                <a href="?delete=<?php echo $product['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">سڕینەوە</a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
            
            <section id="settings" class="card">
                <div class="card-header">
                    <h2>ڕێکخستنەکان</h2>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="update_order_link" value="1">
                        <div class="form-group">
                            <label>لینکی داواکاری</label>
                            <input type="text" name="order_link" class="form-control" value="<?php echo $order_link; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">پاشەکەوتکردن</button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script>
        // Image preview functionality
        document.querySelector('input[name="image"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>