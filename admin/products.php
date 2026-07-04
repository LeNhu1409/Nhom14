<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location:admin_login.php');
    exit;
}

require_once '../config/database.php'; 

$stmt = $pdo->query("SELECT * FROM products LIMIT 20");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Quản lý sản phẩm</title></head>
<body>
  <h2>Danh sách sản phẩm</h2>
  <table border="1" cellpadding="10">
    <tr>
      <th>ID</th><th>Tên</th><th>Giá</th><th>Hành động</th>
    </tr>
    <?php foreach ($products as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= $p['name'] ?></td>
        <td><?= number_format($p['price']) ?>đ</td>
        <td><a href="#">Sửa</a> | <a href="#">Xóa</a></td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
