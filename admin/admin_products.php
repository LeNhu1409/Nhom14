<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

require_once '../config/database.php';

// Fetch categories for the dropdown
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle add product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $slug = strtolower(str_replace(' ', '-', trim($_POST['name'])));
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $is_old = isset($_POST['is_old']) ? 1 : 0;

    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../img_sp/';
        $upload_dir = '../admin/images/';
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image = 'admin/images/' . $image_name;  // lưu vào DB dạng này
        }
    }

    // Insert product
    $stmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, description, price, image, is_old, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $name, $slug, $description, $price, $image, $is_old, $stock]);

    header('Location: admin_products.php?success=Product added successfully');
    exit;
}

// Handle delete product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin_products.php?success=Product deleted successfully');
    exit;
}

// Handle toggle is_old
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE products SET is_old = NOT is_old WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin_products.php?success=Product visibility toggled');
    exit;
}

// Fetch products with category names
$stmt = $pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id LIMIT 20");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-warning {
            background-color: #ffc107;
            color: black;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            color: white;
        }
        .alert-success {
            background-color: #28a745;
        }
        img {
            max-width: 50px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Quản lý sản phẩm</h2>

        <!-- Success Message -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>

        <!-- Add Product Form -->
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="category_id">Danh mục</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Chọn danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Tên sản phẩm</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="price">Giá (VND)</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="stock">Tồn kho</label>
                <input type="number" id="stock" name="stock" value="0">
            </div>
            <div class="form-group">
                <label for="image">Ảnh sản phẩm</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_old"> Là hàng cũ (ẩn khỏi trang web)
                </label>
            </div>
            <button type="submit" name="add_product" class="btn btn-primary">Thêm sản phẩm</button>
        </form>

        <!-- Product List -->
        <table>
            <tr>
                <th>ID</th>
                <th>Danh mục</th>
                <th>Tên</th>
                <th>Giá</th>
                <th>Ảnh</th>
                <th>Tồn kho</th>
                <th>Hiển thị</th>
                <th>Hành động</th>
            </tr>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id']) ?></td>
                    <td><?= htmlspecialchars($p['category_name']) ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= number_format($p['price'], 2) ?>đ</td>
                    <td>
                        <?php if ($p['image']): ?>
                            <img src="../<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['stock']) ?></td>
                    <td>
                        <input type="checkbox" 
                               onchange="window.location='?toggle=<?= $p['id'] ?>'" 
                               <?= !$p['is_old'] ? 'checked' : '' ?>>
                    </td>
                    <td class="actions">
                        <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn btn-warning">Sửa</a>
                        <a href="?delete=<?= $p['id'] ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>