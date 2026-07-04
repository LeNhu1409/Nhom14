<?php
session_start();
$conn = new mysqli(getenv('DB_HOST') ?: 'mysql', getenv('DB_USER') ?: 'root', getenv('DB_PASS') ?: 'root', getenv('DB_NAME') ?: 'nhom14_mobile');
$conn->set_charset("utf8mb4");

$sql = "SELECT * FROM promotions WHERE end_date >= CURDATE() ORDER BY start_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kèo Thơm - Ưu Đãi Hot | Nhóm 14 Mobile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }

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
            display: flex; align-items: center; gap: 8px;
        }
        .back-btn:hover { background: rgba(255,255,255,0.35); }

        .hero {
            background: linear-gradient(135deg, #e60000 0%, #ff6600 100%);
            padding: 50px 20px; text-align: center; color: white;
        }
        .hero h1 { font-size: 36px; font-weight: 800; margin-bottom: 12px; }
        .hero p { font-size: 16px; opacity: 0.9; }

        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }

        .section-title {
            font-size: 22px; font-weight: 700; color: #111;
            margin-bottom: 20px; padding-bottom: 12px;
            border-bottom: 3px solid #e60000; display: flex; align-items: center; gap: 10px;
        }

        .promo-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px; margin-bottom: 40px;
        }

        .promo-card {
            background: white; border-radius: 12px; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s;
        }
        .promo-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }

        .promo-header {
            background: linear-gradient(135deg, #e60000, #ff4444);
            padding: 20px; color: white; position: relative;
        }
        .promo-header h3 { font-size: 18px; font-weight: 700; margin-bottom: 8px; }
        .promo-header p { font-size: 13px; opacity: 0.9; line-height: 1.5; }

        .discount-tag {
            position: absolute; top: 16px; right: 16px;
            background: #ffeb3b; color: #e60000;
            font-size: 20px; font-weight: 800;
            padding: 8px 14px; border-radius: 8px;
        }

        .promo-body { padding: 16px; }
        .promo-date {
            display: flex; align-items: center; gap: 6px;
            font-size: 13px; color: #666; margin-bottom: 12px;
        }
        .promo-date i { color: #e60000; }

        .promo-btn {
            display: block; text-align: center;
            background: #e60000; color: white;
            padding: 10px; border-radius: 8px;
            text-decoration: none; font-weight: 600; font-size: 14px;
            transition: background 0.2s;
        }
        .promo-btn:hover { background: #c00000; }

        /* Hot deals từ hot_products */
        .hot-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px; margin-bottom: 40px;
        }
        .hot-card {
            background: white; border-radius: 10px; padding: 16px;
            text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s; position: relative;
        }
        .hot-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .hot-card img { width: 100%; height: 140px; object-fit: contain; margin-bottom: 10px; }
        .hot-card .name { font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #222; line-height: 1.4; }
        .hot-card .price { font-size: 16px; font-weight: 700; color: #e60000; }
        .hot-card .old-price { font-size: 12px; color: #999; text-decoration: line-through; }
        .badge {
            position: absolute; top: 10px; left: 10px;
            background: #e60000; color: white;
            font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 4px;
        }
        .installment { font-size: 11px; color: #666; margin-top: 6px; font-style: italic; }
    </style>
</head>
<body>

<div class="header">
    <a href="/" class="back-btn"><i class="fas fa-arrow-left"></i> Trang chủ</a>
    <h2>Nhóm 14 Mobile</h2>
    <div></div>
</div>

<div class="hero">
    <h1><i class="fas fa-fire"></i> Kèo Thơm Hôm Nay</h1>
    <p>Săn deal siêu hời - Giảm giá cực sốc mỗi ngày</p>
</div>

<div class="container">

    <!-- KHUYẾN MÃI ĐANG DIỄN RA -->
    <div class="section-title">
        <i class="fas fa-tags" style="color:#e60000;"></i> Chương Trình Khuyến Mãi
    </div>
    <div class="promo-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($promo = $result->fetch_assoc()): ?>
            <div class="promo-card">
                <div class="promo-header">
                    <h3><?php echo htmlspecialchars($promo['title']); ?></h3>
                    <p><?php echo htmlspecialchars($promo['description'] ?? ''); ?></p>
                    <?php if ($promo['discount_percentage'] > 0): ?>
                    <div class="discount-tag">-<?php echo $promo['discount_percentage']; ?>%</div>
                    <?php endif; ?>
                </div>
                <div class="promo-body">
                    <div class="promo-date">
                        <i class="fas fa-calendar-alt"></i>
                        <?php echo date('d/m/Y', strtotime($promo['start_date'])); ?> -
                        <?php echo date('d/m/Y', strtotime($promo['end_date'])); ?>
                    </div>
                    <a href="/" class="promo-btn">Mua ngay <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color:#666;">Hiện chưa có khuyến mãi nào.</p>
        <?php endif; ?>
    </div>

    <!-- HOT DEALS -->
    <?php
    $hot = $conn->query("SELECT * FROM hot_products ORDER BY discount_percent DESC LIMIT 12");
    ?>
    <div class="section-title">
        <i class="fas fa-bolt" style="color:#e60000;"></i> Flash Sale - Giảm Sốc
    </div>
    <div class="hot-grid">
        <?php while ($h = $hot->fetch_assoc()): ?>
        <div class="hot-card">
            <?php if ($h['discount_percent']): ?>
            <div class="badge">-<?php echo $h['discount_percent']; ?>%</div>
            <?php endif; ?>
            <img src="<?php echo htmlspecialchars($h['image_url']); ?>" alt="<?php echo htmlspecialchars($h['name']); ?>">
            <div class="name"><?php echo htmlspecialchars($h['name']); ?></div>
            <div class="price"><?php echo number_format($h['price'], 0, ',', '.'); ?>đ</div>
            <?php if ($h['old_price']): ?>
            <div class="old-price"><?php echo number_format($h['old_price'], 0, ',', '.'); ?>đ</div>
            <?php endif; ?>
            <div class="installment"><i class="fas fa-tag" style="color:#e60000;margin-right:4px;"></i><?php echo htmlspecialchars($h['installment_info'] ?? ''); ?></div>
        </div>
        <?php endwhile; ?>
    </div>

</div>
</body>
</html>