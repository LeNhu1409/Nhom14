<?php
session_start();
$conn = new mysqli(getenv('DB_HOST') ?: 'mysql', getenv('DB_USER') ?: 'root', getenv('DB_PASS') ?: 'root', getenv('DB_NAME') ?: 'nhom14_mobile');
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Lấy danh mục từ URL
$category_slug = isset($_GET['category']) ? $_GET['category'] : '';
$category_name = '';
$products = [];

// Kiểm tra danh mục hợp lệ
if ($category_slug) {
    $sql_category = "SELECT id, name FROM categories WHERE slug = ?";
    $stmt_category = $conn->prepare($sql_category);
    $stmt_category->bind_param("s", $category_slug);
    $stmt_category->execute();
    $result_category = $stmt_category->get_result();

    if ($result_category->num_rows > 0) {
        $category = $result_category->fetch_assoc();
        $category_id = $category['id'];
        $category_name = $category['name'];

        $sql_products = "SELECT p.id, p.name, p.slug, p.description, p.price, p.image, p.is_old 
                         FROM products p 
                         WHERE p.category_id = ? 
                         ORDER BY p.created_at DESC";
        $stmt_products = $conn->prepare($sql_products);
        $stmt_products->bind_param("i", $category_id);
        $stmt_products->execute();
        $result_products = $stmt_products->get_result();

        while ($row = $result_products->fetch_assoc()) {
            $sql_images = "SELECT image_url FROM product_images WHERE product_id = ? LIMIT 3";
            $stmt_images = $conn->prepare($sql_images);
            $stmt_images->bind_param("i", $row['id']);
            $stmt_images->execute();
            $result_images = $stmt_images->get_result();
            $images = [$row['image']];
            while ($img = $result_images->fetch_assoc()) {
                $images[] = $img['image_url'];
            }
            $row['images'] = $images;
            $products[] = $row;
        }
        $stmt_images->close();
        $stmt_products->close();
    }
    $stmt_category->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm - <?php echo htmlspecialchars($category_name); ?> | Nhóm 14 Mobile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .header {
            background-color: #e60000;
            padding: 12px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header h2 {
            font-size: 20px;
            color: #fff;
            font-weight: 700;
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            background: rgba(255,255,255,0.2);
            padding: 6px 14px;
            border-radius: 20px;
            transition: background 0.2s ease;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.35);
        }

        .cart-icon {
            position: relative;
            font-size: 22px;
            cursor: pointer;
            color: #fff;
        }

        .cart-icon #cart-items {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #ffeb3b;
            color: #e60000;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .cart-icon #cart-items {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            color: #333;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            max-width: 100%;
            height: 200px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .product-card h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-card .price {
            color: red;
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .product-card .is-old {
            color: #999;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .product-card .buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .product-card button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .product-card .add-to-cart {
            background-color: #e74c3c;
            color: white;
        }

        .product-card .add-to-cart:hover {
            background-color: #c0392b;
        }

        .product-card .view-details {
            background-color: #3498db;
            color: white;
        }

        .product-card .view-details:hover {
            background-color: #2980b9;
        }

        .no-products {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin-top: 40px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 800px;
            position: relative;
        }

        .close {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .product-details-container {
            display: flex;
            gap: 20px;
        }

        .product-info {
            flex: 1;
        }

        .product-info h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .product-info .price {
            color: red;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-info .description {
            font-size: 14px;
            color: #333;
            margin-bottom: 15px;
        }

        .product-info .detailed-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .quantity-control button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .quantity-control button:hover {
            background-color: #c0392b;
        }

        .quantity-control input {
            width: 50px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 5px;
        }

        .product-images {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .main-image img {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: 8px;
        }

        .thumbnail-images {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 5px 0;
        }

        .thumbnail-images img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .thumbnail-images img:hover,
        .thumbnail-images img.active {
            border-color: #3498db;
        }

        .add-to-cart-modal {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-modal:hover {
            background-color: #c0392b;
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            z-index: 2000;
            display: none;
        }

        .cart-popup {
            display: none;
            position: fixed;
            top: 50px;
            right: 20px;
            width: 350px;
            max-height: 400px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            overflow-y: auto;
        }

        .cart-popup-content {
            padding: 15px;
        }

        .cart-popup h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .cart-item {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-name {
            font-size: 14px;
            color: #333;
            flex: 1;
            margin-right: 10px;
        }

        .cart-item-quantity {
            font-size: 14px;
            color: #666;
            margin-right: 10px;
        }

        .cart-item-price {
            font-size: 14px;
            color: red;
            font-weight: bold;
            margin-right: 10px;
        }

        .cart-item-actions {
            display: flex;
            gap: 5px;
        }

        .cart-item-remove,
        .cart-item-checkout {
            font-size: 12px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 4px;
            border: none;
        }

        .cart-item-remove {
            color: #e74c3c;
            background-color: transparent;
        }

        .cart-item-remove:hover {
            color: #c0392b;
        }

        .cart-item-checkout {
            background-color: #3498db;
            color: white;
        }

        .cart-item-checkout:hover {
            background-color: #2980b9;
        }

        .cart-empty {
            font-size: 14px;
            color: #666;
            text-align: center;
            padding: 20px;
        }

        .cart-popup-close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            color: #aaa;
            cursor: pointer;
        }

        .cart-popup-close:hover {
            color: #000;
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            .modal-content {
                width: 90%;
            }

            .product-details-container {
                flex-direction: column;
                align-items: center;
            }

            .main-image img {
                max-height: 200px;
            }

            .thumbnail-images img {
                width: 60px;
                height: 60px;
            }

            .cart-popup {
                width: 90%;
                right: 5%;
                top: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
    <a href="/" class="back-btn">
        <i class="fas fa-arrow-left"></i> Trang chủ
    </a>
    <h2>Nhóm 14 Mobile</h2>
    <div class="cart-icon" onclick="toggleCartPopup()">
        <i class="fas fa-shopping-cart"></i>
        <span id="cart-items">0</span>
    </div>
</div>

    <div class="container">
        <h1>Sản phẩm <?php echo htmlspecialchars($category_name); ?></h1>
        <?php if (empty($products)): ?>
            <p class="no-products">Không có sản phẩm nào trong danh mục này.</p>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="price"><?php echo number_format($product['price'] * 25000, 0, ',', '.'); ?>đ</div>
                        <?php if ($product['is_old']): ?>
                            <div class="is-old">Hàng đã qua sử dụng</div>
                        <?php endif; ?>
                        <div class="buttons">
                            <button class="view-details" onclick="window.location.href='product-detail.php?slug=<?php echo $product['slug']; ?>'">Xem chi tiết</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal chi tiết sản phẩm -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close">×</span>
            <div id="product-details"></div>
        </div>
    </div>

    <!-- Popup giỏ hàng -->
    <div id="cartPopup" class="cart-popup">
        <span class="cart-popup-close" onclick="toggleCartPopup()">×</span>
        <div class="cart-popup-content">
            <h3>Giỏ hàng</h3>
            <div id="cart-items-list"></div>
        </div>
    </div>

    <!-- Toast thông báo -->
    <div id="toast" class="toast"></div>

    <script>
        let cart = JSON.parse(sessionStorage.getItem('cart')) || {};

        function updateCartDisplay() {
            const cartItemsCount = document.getElementById('cart-items');
            const cartItemsList = document.getElementById('cart-items-list');
            const total = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
            cartItemsCount.textContent = total;

            console.log('Cart:', cart); // Debug

            if (Object.keys(cart).length === 0) {
                cartItemsList.innerHTML = '<p class="cart-empty">Giỏ hàng trống</p>';
            } else {
                cartItemsList.innerHTML = Object.entries(cart).map(([id, item]) => {
                    const price = parseFloat(item.price) || 0;
                    const totalPrice = price * item.quantity;
                    return `
                        <div class="cart-item">
                            <span class="cart-item-name">${item.name}</span>
                            <span class="cart-item-quantity">x${item.quantity}</span>
                            <span class="cart-item-price">$${Number(totalPrice).toLocaleString()}</span>
                            <div class="cart-item-actions">
                                <span class="cart-item-remove" onclick="removeFromCart(${id})">Xóa</span>
                                <button class="cart-item-checkout" onclick="checkoutItem(${id})">Thanh toán</button>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            sessionStorage.setItem('cart', JSON.stringify(cart));
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        function addToCart(id, name, price) {
            price = parseFloat(price) || 0;
            if (cart[id]) {
                cart[id].quantity += 1;
            } else {
                cart[id] = { name: name, quantity: 1, price: price };
            }
            updateCartDisplay();
            showToast('Sản phẩm đã được thêm vào giỏ hàng!');
        }

        function removeFromCart(id) {
            delete cart[id];
            updateCartDisplay();
            showToast('Sản phẩm đã được xóa khỏi giỏ hàng!');
        }

        function checkoutItem(id) {
            const item = cart[id];
            const price = parseFloat(item.price) || 0;
            alert(`Đang thanh toán ${item.quantity} x ${item.name} với tổng giá $${Number(price * item.quantity).toLocaleString()}`);
            delete cart[id];
            updateCartDisplay();
            showToast('Thanh toán thành công!');
        }

        function toggleCartPopup() {
            const cartPopup = document.getElementById('cartPopup');
            cartPopup.style.display = cartPopup.style.display === 'block' ? 'none' : 'block';
        }

        function showProductDetails(product) {
            const modal = document.getElementById('productModal');
            const productDetails = document.getElementById('product-details');
            const safeName = product.name.replace(/'/g, "\\'").replace(/"/g, "\\\"");

            let thumbnails = product.images.map((img, index) => `
                <img src="${img}" alt="${safeName}" class="${index === 0 ? 'active' : ''}" onclick="changeMainImage(this, '${img}')">
            `).join('');

            productDetails.innerHTML = `
                <div class="product-details-container">
                    <div class="product-info">
                        <h3>${safeName}</h3>
                        <div class="price">${Number(product.price * 25000).toLocaleString('vi-VN')}đ</div>
                        <div class="description">${product.description || 'Chưa có mô tả'}</div>
                        <div class="detailed-description">${product.detailed_description || 'Chưa có mô tả chi tiết'}</div>
                        <div class="quantity-control">
                            <button onclick="changeQuantity(-1)">-</button>
                            <input type="number" id="quantity" value="1" min="1">
                            <button onclick="changeQuantity(1)">+</button>
                        </div>
                        <button class="add-to-cart-modal" onclick="addToCartFromModal(${product.id}, '${safeName}', ${product.price})">Thêm vào giỏ hàng</button>
                    </div>
                    <div class="product-images">
                        <div class="main-image">
                            <img src="${product.image}" alt="${safeName}" id="main-image">
                        </div>
                        <div class="thumbnail-images">
                            ${thumbnails}
                        </div>
                    </div>
                </div>
            `;

            modal.style.display = 'block';

            document.querySelector('.close').onclick = () => {
                modal.style.display = 'none';
            };

            window.onclick = (event) => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };
        }

        function changeMainImage(thumbnail, src) {
            document.querySelectorAll('.thumbnail-images img').forEach(img => img.classList.remove('active'));
            thumbnail.classList.add('active');
            document.getElementById('main-image').src = src;
        }

        function changeQuantity(change) {
            const quantityInput = document.getElementById('quantity');
            let quantity = parseInt(quantityInput.value);
            quantity += change;
            if (quantity < 1) quantity = 1;
            quantityInput.value = quantity;
        }

        function addToCartFromModal(id, name, price) {
            price = parseFloat(price) || 0;
            const quantity = parseInt(document.getElementById('quantity').value);
            if (cart[id]) {
                cart[id].quantity += quantity;
            } else {
                cart[id] = { name: name, quantity: quantity, price: price };
            }
            updateCartDisplay();
            showToast('Sản phẩm đã được thêm vào giỏ hàng!');
            document.getElementById('productModal').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            for (const id in cart) {
                if (!cart[id].price || isNaN(parseFloat(cart[id].price))) {
                    delete cart[id];
                }
            }
            sessionStorage.setItem('cart', JSON.stringify(cart));
            updateCartDisplay();

            window.addEventListener('click', (event) => {
                const cartPopup = document.getElementById('cartPopup');
                const cartIcon = document.querySelector('.cart-icon');
                if (!cartPopup.contains(event.target) && !cartIcon.contains(event.target)) {
                    cartPopup.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html> 