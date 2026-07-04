<?php
$servername = getenv('DB_HOST') ?: 'mysql';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'root';
$dbname = getenv('DB_NAME') ?: 'nhom14_mobile';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Kiểm tra giỏ hàng
if (empty($cart)) {
    echo '<p>Giỏ hàng của bạn đang trống.</p>';
    exit;
}

// Lấy sản phẩm từ cơ sở dữ liệu
$productIds = array_keys($cart);
$ids = implode(',', $productIds);

$sql = "SELECT * FROM hot_products WHERE id IN ($ids)";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    echo '<p>Không tìm thấy sản phẩm nào trong cơ sở dữ liệu.</p>';
    exit;
}

echo '<h1>Giỏ Hàng</h1>';
echo '<table border="1">';
echo '<tr><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th><th>Thành tiền</th></tr>';

while ($row = $result->fetch_assoc()) {
    $quantity = intval($cart[$row['id']]); // Chuyển đổi số lượng thành kiểu integer
    $price = floatval($row['price']); // Chuyển đổi giá thành kiểu float
    $totalPrice = $price * $quantity;

    echo "<tr>
        <td>{$row['name']}</td>
        <td>" . number_format($price, 0, ',', '.') . "đ</td>
        <td>{$quantity}</td>
        <td>" . number_format($totalPrice, 0, ',', '.') . "đ</td>
    </tr>";
}

echo '</table>';
