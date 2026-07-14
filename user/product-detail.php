<?php
session_start();
$conn = new mysqli(getenv('DB_HOST') ?: 'mysql', getenv('DB_USER') ?: 'root', getenv('DB_PASS') ?: 'root', getenv('DB_NAME') ?: 'nhom14_mobile');
if ($conn->connect_error) die("Lỗi kết nối");
$conn->set_charset("utf8mb4");

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$product = null;

if ($slug) {
    $sql = "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.slug = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (!$product) { header("Location: /"); exit; }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | Nhóm 14 Mobile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; color: #222; }

        .header {
            background: #e60000; padding: 12px 24px;
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .header h2 { color: #fff; font-size: 20px; font-weight: 700; }
        .back-btn {
            color: #fff; text-decoration: none; font-size: 14px; font-weight: 600;
            background: rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 20px;
            display: flex; align-items: center; gap: 8px; transition: background 0.2s;
        }
        .back-btn:hover { background: rgba(255,255,255,0.35); }

        .qty-display {
            width: 50px; text-align: center; font-size: 16px; font-weight: 600;
            border-left: 2px solid #ddd; border-right: 2px solid #ddd;
            padding: 10px 0; display: block;
        }

        .breadcrumb {
            padding: 12px 40px; font-size: 13px; color: #666;
            background: white; border-bottom: 1px solid #eee;
        }
        .breadcrumb a { color: #e60000; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }

        .product-layout {
            display: grid; grid-template-columns: 1fr 1fr; gap: 40px;
            background: white; border-radius: 12px; padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        
        .product-image-section { display: flex; flex-direction: column; gap: 12px; }
        .main-img-wrap {
            background: #f8f8f8; border-radius: 10px; padding: 20px;
            display: flex; align-items: center; justify-content: center;
            min-height: 350px;
        }
        .main-img-wrap img { max-width: 100%; max-height: 320px; object-fit: contain; }

        /* ===== THÔNG TIN ===== */
        .product-info-section { display: flex; flex-direction: column; gap: 16px; }

        .category-tag {
            display: inline-block; background: #fff0f0; color: #e60000;
            font-size: 12px; font-weight: 600; padding: 4px 12px;
            border-radius: 20px; border: 1px solid #ffcccc;
        }

        .product-title { font-size: 26px; font-weight: 700; color: #111; line-height: 1.3; }

        .price-box { display: flex; align-items: center; gap: 16px; }
        .current-price { font-size: 32px; font-weight: 700; color: #e60000; }
        .original-price { font-size: 18px; color: #999; text-decoration: line-through; }

        .divider { height: 1px; background: #f0f0f0; }

        .product-desc { font-size: 14px; color: #555; line-height: 1.7; }

        .info-table { width: 100%; border-collapse: collapse; font-size: 14px; }
        .info-table tr { border-bottom: 1px solid #f5f5f5; }
        .info-table td { padding: 8px 4px; }
        .info-table td:first-child { color: #888; width: 40%; }
        .info-table td:last-child { font-weight: 600; color: #222; }

        
        .quantity-label { font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px; }
        .quantity-control {
            display: flex; align-items: center; gap: 0;
            border: 2px solid #ddd; border-radius: 8px; width: fit-content; overflow: hidden;
        }
        .qty-btn {
            background: #f5f5f5; border: none; padding: 8px 14px;
            font-size: 20px; cursor: pointer; color: #e60000;
            transition: background 0.2s; font-weight: 700; line-height: 1;
        }
        .qty-btn:hover { background: #fff0f0; }
        .qty-input {
            border: none; border-left: 2px solid #ddd; border-right: 2px solid #ddd;
            width: 50px; text-align: center; font-size: 16px; font-weight: 600;
            padding: 10px 0; outline: none;
            -webkit-appearance: none; -moz-appearance: textfield;
            appearance: none;
        }

        /* Ẩn spinner */
        .qty-input::-webkit-inner-spin-button,
        .qty-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* NÚT */
        .btn-group { display: flex; gap: 12px; }
        .btn-cart {
            flex: 1; padding: 14px; background: #e60000; color: white;
            border: none; border-radius: 8px; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: all 0.3s; display: flex; align-items: center;
            justify-content: center; gap: 8px;
        }
        .btn-cart:hover { background: #c00000; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(230,0,0,0.3); }
        .btn-buy {
            flex: 1; padding: 14px; background: #ff6600; color: white;
            border: none; border-radius: 8px; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: all 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-buy:hover { background: #e55a00; transform: translateY(-2px); }

        
        .toast {
            display: none; position: fixed; bottom: 30px; right: 30px;
            background: #4CAF50; color: white; padding: 14px 24px;
            border-radius: 8px; font-size: 14px; font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999;
        }
        .toast.show { display: block; animation: fadeUp 0.3s ease; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /*  MÔ TẢ CHI TIẾT  */
        .desc-section {
            background: white; border-radius: 12px; padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-top: 24px;
        }
        .desc-section h2 { font-size: 20px; font-weight: 700; margin-bottom: 16px; color: #111; }
        .desc-content { font-size: 14px; color: #555; line-height: 1.8; }

        @media (max-width: 768px) {
            .product-layout { grid-template-columns: 1fr; gap: 24px; }
            .breadcrumb { padding: 12px 20px; }
            .container { padding: 0 12px; }
            .btn-group { flex-direction: column; }
            .current-price { font-size: 26px; }
        }
    </style>
</head>
<body>

<div class="header">
    <a href="/" class="back-btn"><i class="fas fa-arrow-left"></i> Trang chủ</a>
    <h2>Nhóm 14 Mobile</h2>
    <div></div>
</div>

<div class="breadcrumb">
    <a href="/">Trang chủ</a> &rsaquo;
    <a href="products.php?category=<?php echo urlencode($product['category_name']); ?>"><?php echo htmlspecialchars($product['category_name']); ?></a> &rsaquo;
    <?php echo htmlspecialchars($product['name']); ?>
</div>

<div class="container">
    <div class="product-layout">

        
        <div class="product-image-section">
            <div class="main-img-wrap">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" id="main-img">
            </div>
        </div>

        
        <div class="product-info-section">
            <span class="category-tag"><?php echo htmlspecialchars($product['category_name']); ?></span>
            <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

            <div class="price-box">
                <span class="current-price"><?php echo number_format($product['price'] * 25000, 0, ',', '.'); ?>đ</span>
                <span class="original-price"><?php echo number_format($product['price'] * 25000 * 1.15, 0, ',', '.'); ?>đ</span>
            </div>

            <div class="divider"></div>

            <table class="info-table">
                <tr>
                <td>Tình trạng</td>
                <td style="color:<?php echo $product['is_old'] ? '#ff6600' : '#00aa44'; ?>">
                    <?php echo $product['is_old'] ? 'Hàng cũ' : 'Còn hàng'; ?>
                </td>
            </tr>
            <tr>
                <td>Danh mục</td>
                <td><?php echo htmlspecialchars($product['category_name']); ?></td>
            </tr>
            <tr>
                <td>Tồn kho</td>
                <td style="color:<?php echo $product['stock'] > 0 ? '#00aa44' : '#ff6600'; ?>">
                    <?php echo $product['stock'] > 0 ? $product['stock'] . ' sản phẩm' : 'Liên hệ'; ?>
                </td>
            </tr>
            </table>

            <?php if (!empty($product['specifications'])): ?>
            <div class="divider"></div>
            <div class="specs-box">
                <h3 style="font-size:15px; font-weight:700; margin-bottom:12px; color:#111;">
                    <i class="fas fa-microchip" style="color:#e60000; margin-right:6px;"></i> Cấu hình máy
                </h3>
                <table class="info-table">
                    <?php
                    $lines = explode("\n", trim($product['specifications'] ?? ''));
                    foreach ($lines as $line) {
                        $parts = explode(":", $line, 2);
                        if (count($parts) == 2) {
                            echo '<tr><td>' . htmlspecialchars(trim($parts[0])) . '</td><td>' . htmlspecialchars(trim($parts[1])) . '</td></tr>';
                        }
                    }
                    ?>
                </table>
            </div>
            <?php endif; ?>

            <?php if ($product['description']): ?>
            <p class="product-desc"><?php echo htmlspecialchars($product['description']); ?></p>
            <?php endif; ?>

            <div class="divider"></div>

            <div>
            <div class="quantity-label">Số lượng:</div>
            <div class="quantity-control">
                <button class="qty-btn" onclick="changeQty(-1)">−</button>
                <span class="qty-display" id="qty-display">1</span>
                <button class="qty-btn" onclick="changeQty(1)">+</button>
                <input type="hidden" id="qty" value="1">
            </div>
        </div>

            <div class="btn-group">
                <button class="btn-cart" onclick="addToCart()">
                    <i class="fas fa-shopping-bag"></i> Thêm vào giỏ
                </button>
                <button class="btn-buy" onclick="buyNow()">
                    <i class="fas fa-tag"></i> Mua ngay
                </button>
            </div>
        </div>
    </div>

    <!-- MÔ TẢ CHI TIẾT -->
    <div class="desc-section">
        <h2>Mô tả sản phẩm</h2>
        <div class="desc-content">
            <?php if ($product['description']): ?>
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            <?php else: ?>
                <p>Sản phẩm chính hãng, bảo hành 12 tháng. Liên hệ hotline <strong>012 3456 5678</strong> để biết thêm chi tiết.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
function changeQty(change) {
    const input = document.getElementById('qty');
    const display = document.getElementById('qty-display');
    let val = parseInt(input.value) + change;
    if (val < 1) val = 1;
    input.value = val;
    display.textContent = val;
}

function showToast(msg) {
    const t = document.getElementById('toast');
    t.innerHTML = '<i class="fas fa-check-circle" style="margin-right:8px;"></i>' + msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

function addToCart() {
    const qty = parseInt(document.getElementById('qty').value);
    const id = <?php echo $product['id']; ?>;
    const name = <?php echo json_encode($product['name']); ?>;
    let cart = JSON.parse(sessionStorage.getItem('cart')) || {};
    if (cart[id]) {
        cart[id].quantity += qty;
    } else {
        cart[id] = { name: name, quantity: qty };
    }
    sessionStorage.setItem('cart', JSON.stringify(cart));
    showToast('Đã thêm ' + qty + ' sản phẩm vào giỏ hàng!');
}

function buyNow() {
    addToCart();
    setTimeout(() => { window.location.href = '/'; }, 500);
}

document.getElementById('qty').addEventListener('input', function() {
    if (parseInt(this.value) < 1 || isNaN(parseInt(this.value))) this.value = 1;
});
</script>
</body>
</html>