<?php
$conn = new mysqli(getenv('DB_HOST') ?: 'mysql', getenv('DB_USER') ?: 'root', getenv('DB_PASS') ?: 'root', getenv('DB_NAME') ?: 'nhom14_mobile');
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

while ($row = $result->fetch_assoc()) {
    echo '<div style="border:1px solid #ccc; padding:10px; margin:10px;">';
    echo '<img src="' . $row['image_url'] . '" width="200"><br>';
    echo '<strong>' . $row['name'] . '</strong><br>';
    echo 'Giá: ' . number_format($row['price']) . ' VNĐ<br>';
    echo '<a href="hot_product_detail.php?id=' . $row['id'] . '">Xem chi tiết</a>';
    echo '</div>';
}
?>