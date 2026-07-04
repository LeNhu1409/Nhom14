<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

require_once '../config/database.php';

// Lấy danh mục
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $slug = strtolower(str_replace(' ', '-', trim($_POST['name'])));
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $is_old = isset($_POST['is_old']) ? 1 : 0;
    $is_hot = isset($_POST['is_hot']) ? 1 : 0;

    // Xử lý tải lên hình ảnh
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../admin/images/';
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image = 'admin/images/' . $image_name;  // lưu vào DB dạng này
        }
    }

    // Thêm vào bảng products
    $stmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, description, price, image, is_old, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $name, $slug, $description, $price, $image, $is_old, $stock]);

    // Nếu là sản phẩm nổi bật, thêm vào hot_products
    if ($is_hot) {
        $stmt = $pdo->prepare("INSERT INTO hot_products (name, price, image_url, rating) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $price, $image, 5]);
    }

    header('Location: admin_dashboard.php?success=Sản phẩm đã được thêm');
    exit;
}

// Xử lý chỉnh sửa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $slug = strtolower(str_replace(' ', '-', trim($_POST['name'])));
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $is_old = isset($_POST['is_old']) ? 1 : 0;
    $is_hot = isset($_POST['is_hot']) ? 1 : 0;

    // Xử lý tải lên hình ảnh
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetchColumn();

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../img_sp/';
        $upload_dir = '../admin/images/';
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image = 'admin/images/' . $image_name;  // lưu vào DB dạng này
        }
    }

    // Cập nhật bảng products
    $stmt = $pdo->prepare("UPDATE products SET category_id = ?, name = ?, slug = ?, description = ?, price = ?, image = ?, is_old = ?, stock = ? WHERE id = ?");
    $stmt->execute([$category_id, $name, $slug, $description, $price, $image, $is_old, $stock, $id]);

    // Xử lý hot_products
    $stmt = $pdo->prepare("SELECT id FROM hot_products WHERE name = ?");
    $stmt->execute([$name]);
    $hot_product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($is_hot && !$hot_product) {
        $stmt = $pdo->prepare("INSERT INTO hot_products (name, price, image_url, rating) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $price, $image, 5]);
    } elseif (!$is_hot && $hot_product) {
        $stmt = $pdo->prepare("DELETE FROM hot_products WHERE id = ?");
        $stmt->execute([$hot_product['id']]);
    }

    header('Location: admin_dashboard.php?success=Sản phẩm đã được cập nhật');
    exit;
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete_product'])) {
    $id = $_GET['delete_product'];
    $stmt = $pdo->prepare("SELECT name FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $stmt = $pdo->prepare("DELETE FROM hot_products WHERE name = ?");
        $stmt->execute([$product['name']]);
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: admin_dashboard.php?success=Sản phẩm đã được xóa');
        exit;
    }
}

// Xử lý xóa khách hàng
if (isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin_dashboard.php?success=Khách hàng đã được xóa');
    exit;
}

// Phân trang sản phẩm
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;
$stmt = $pdo->query("SELECT COUNT(*) FROM products");
$totalProducts = $stmt->fetchColumn();
$totalPages = ceil($totalProducts / $perPage);

// Lấy danh sách sản phẩm
$stmt = $pdo->query("SELECT p.*, c.name AS category_name, h.id AS hot_id 
                     FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     LEFT JOIN hot_products h ON p.name = h.name 
                     LIMIT $perPage OFFSET $offset");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách khách hàng
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC LIMIT 100");  
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị Hệ Thống Bán Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            position: fixed;
            height: 100%;
        }
        .sidebar-header {
            padding: 20px;
            text-align: center;
        }
        .nav {
            list-style: none;
            padding: 0;
        }
        .nav li a {
            color: white;
            padding: 15px 20px;
            display: block;
            text-decoration: none;
        }
        .nav li a:hover {
            background-color: #495057;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
            background-color: #f8f9fa;
        }
        header {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat {
            background: white;
            padding: 20px;
            border-radius: 8px;
            flex: 1;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        img {
            max-width: 80px;
            height: auto;
        }
        .modal-content {
            border-radius: 8px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
        </div>
        <ul class="nav">
            <li><a href="#dashboard">Dashboard</a></li>
            <li><a href="#products">Quản lý sản phẩm</a></li>
            <li><a href="#customers">Quản lý khách hàng</a></li>
            <li><a href="manage_admins.php">Quản lý admin</a></li>
            <li><a href="#settings">Cài đặt</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h1>Quản Trị Hệ Thống Bán Hàng</h1>
            <div class="user-info">
                <span>Chào, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></span> |
                <a href="/index.php">Đăng xuất</a>
            </div>
        </header>

        <!-- Thông báo -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>

        <section id="dashboard" class="dashboard stats">
            <div class="stat">
                <h3>Khách truy cập</h3>
                <p>1578</p>
            </div>
            <div class="stat">
                <h3>Sản phẩm</h3>
                <p><?= $totalProducts ?></p>
            </div>
            <div class="stat">
                <h3>Khách hàng</h3>
                <p><?= count($users) ?></p>
            </div>
        </section>

        <section id="products" class="content-section">
            <h2>Quản lý Sản Phẩm</h2>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Thêm sản phẩm mới</button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Hiển thị trang chủ</th>
                        <th>Danh mục</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stt = $offset + 1;
                    foreach ($products as $row):
                    ?>
                    <tr>
                        <td><?= $stt++ ?></td>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img src="/admin/images/<?= htmlspecialchars(basename($row['image'])) ?>"
                                alt="<?= htmlspecialchars($row['name']) ?>" style="max-width:80px;">
                        <?php else: ?>
                            <span class="text-muted">Không có ảnh</span>
                        <?php endif; ?>
                    </td>
                        <td><?= $row['name'] ?></td>
                        <td><?= number_format($row['price'], 2) ?>đ</td>
                        <td>
                            <input type="checkbox" <?= $row['hot_id'] ? 'checked' : '' ?> disabled>
                        </td>
                        <td><?= $row['category_name'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal" 
                                    onclick="fillEditForm(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name']) ?>', '<?= htmlspecialchars($row['category_id']) ?>', 
                                    '<?= htmlspecialchars($row['description']) ?>', '<?= $row['price'] ?>', '<?= $row['stock'] ?>', 
                                    '<?= $row['is_old'] ?>', '<?= $row['hot_id'] ? 1 : 0 ?>')">Chỉnh sửa</button>
                            <a href="?delete_product=<?= $row['id'] ?>" class="btn btn-danger btn-sm" 
                               onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Phân trang -->
            <nav id="pagination-nav">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </section>

        <section id="customers" class="content-section">
            <h2>Quản lý Khách Hàng</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên người dùng</th>
                        <th>Email</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <a href="?delete_user=<?= $user['id'] ?>" class="btn btn-danger btn-sm" 
                               onclick="return confirm('Bạn có chắc muốn xóa khách hàng này?')">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>

    <!-- Modal thêm sản phẩm -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Thêm sản phẩm mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Danh mục</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Chọn danh mục</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Giá (VND)</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Tồn kho</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="is_old"> Là hàng cũ
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="is_hot"> Hiển thị trên trang chủ
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" name="add_product">Thêm sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa sản phẩm -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Chỉnh sửa sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_category_id" class="form-label">Danh mục</label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                <option value="">Chọn danh mục</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Giá (VND)</label>
                            <input type="number" class="form-control" id="edit_price" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_stock" class="form-label">Tồn kho</label>
                            <input type="number" class="form-control" id="edit_stock" name="stock" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="edit_is_old" name="is_old"> Là hàng cũ
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="edit_is_hot" name="is_hot"> Hiển thị trên trang chủ
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary" name="edit_product">Cập nhật sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillEditForm(id, name, category_id, description, price, stock, is_old, is_hot) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category_id').value = category_id;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('edit_is_old').checked = is_old == 1;
            document.getElementById('edit_is_hot').checked = is_hot == 1;
        }
    </script>
    <script>
    document.querySelectorAll('.pagination .page-link').forEach(link => {
        link.addEventListener('click', function() {
            sessionStorage.setItem('scrollToPagination', '1');
        });
    });

    window.addEventListener('load', function() {
        if (sessionStorage.getItem('scrollToPagination')) {
            const el = document.getElementById('pagination-nav');
            if (el) {
                const y = el.getBoundingClientRect().top + window.scrollY - 200;
                window.scrollTo(0, y);
            }
            sessionStorage.removeItem('scrollToPagination');
        }
    });
</script>
</body>
</html>