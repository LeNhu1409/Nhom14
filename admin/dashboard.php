<?php
$servername = getenv('DB_HOST') ?: 'mysql';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'root';
$dbname = getenv('DB_NAME') ?: 'nhom14_mobile';

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thực hiện truy vấn SQL để lấy sản phẩm
$sql = "SELECT * FROM products"; // Thay đổi 'products' thành tên bảng của bạn
$result = $conn->query($sql); // Lưu kết quả truy vấn vào biến $result
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quản Trị Hệ Thống Bán Hàng Điện Tử</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      margin: 0;
    }

    .sidebar {
      inline-size: 250px;
      position: fixed;
      inset-block-start: 0;
      inset-inline-start: 0;
      block-size: 100%;
      background-color: #2c3e50;
      color: white;
      padding-block-start: 20px;
    }

    .sidebar .sidebar-header {
      text-align: center;
      padding: 10px;
    }

    .sidebar .nav {
      list-style-type: none;
      padding: 0;
    }

    .sidebar .nav li {
      padding: 10px 20px;
    }

    .sidebar .nav li a {
      color: white;
      text-decoration: none;
      display: block;
    }

    .sidebar .nav li a:hover {
      background-color: #34495e;
    }

    .main-content {
      margin-inline-start: 250px;
      padding: 20px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      background-color: #ecf0f1;
    }

    .user-info a {
      text-decoration: none;
      color: #e74c3c;
    }

    .content-section {
      margin-block-start: 20px;
    }

    .content-section h2 {
      color: #2c3e50;
    }

    .stats {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
    }

    .stat {
      background-color: #ecf0f1;
      padding: 20px;
      border-radius: 5px;
      inline-size: 30%;
      text-align: center;
    }

    table {
      inline-size: 100%;
      border-collapse: collapse;
      margin-block-start: 20px;
    }

    table th,
    table td {
      padding: 10px;
      text-align: center;
      border: 1px solid #ddd;
    }

    table th {
      background-color: #3498db;
      color: white;
    }

    .add-product {
      background-color: #27ae60;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-block-end: 20px;
    }

    .add-product:hover {
      background-color: #2ecc71;
    }

    button {
      padding: 8px 14px;
      margin-inline-end: 8px;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-edit {
      background-color: #3498db;
      color: white;
    }

    .btn-edit:hover {
      animation: pulse 1s infinite;
    }

    .btn-delete {
      background-color: #e74c3c;
      color: white;
    }

    .btn-delete:hover {
      animation: shake 0.5s;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    @keyframes shake {
      0% { transform: translateX(0); }
      20% { transform: translateX(-3px); }
      40% { transform: translateX(3px); }
      60% { transform: translateX(-3px); }
      80% { transform: translateX(3px); }
      100% { transform: translateX(0); }
    }

  .dashboard {
  display: flex;
  gap: 20px;
  margin: 20px 0;
  flex-wrap: wrap;
}

.dashboard .stat {
  flex: 1;
  background-color: #2980b9;
  color: white;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
  min-width: 180px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.dashboard .stat h3 {
  margin: 0 0 10px;
  font-size: 18px;
}

.dashboard .stat p {
  margin: 0;
  font-size: 24px;
  font-weight: bold;
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
      <li><a href="#orders">Quản lý đơn hàng</a></li>
      <li><a href="#customers">Quản lý khách hàng</a></li>
      <li><a href="#reports">Báo cáo</a></li>
      <li><a href="#settings">Cài đặt</a></li>
    </ul>
  </div>

  <div class="main-content">
  <header>
    <div class="header">
      <h1>Quản Trị Hệ Thống Bán Hàng</h1>
      <div class="user-info">
        <span>Chào, Quản trị viên</span> |
        <a href="/Nhom14/index.php">Đăng xuất</a>
      </div>
    </div>
  </header>

  <!-- ✅ Bảng điều khiển -->
  <section id="dashboard" class="dashboard stats">
    <div class="stat">
      <h3>Khách truy cập</h3>
      <p>1578</p> <!-- Có thể lấy từ database/log thực tế -->
    </div>
    <div class="stat">
      <h3>Sản phẩm</h3>
      <p><?= $result->num_rows ?></p>
    </div>
    <div class="stat">
      <h3>Đơn hàng</h3>
      <p>456</p>
    </div>
  </section>

  <!-- ✅ Quản lý sản phẩm -->
  <section id="products" class="content-section">
    <h2>Quản lý Sản Phẩm</h2>
    <button class="add-product">Thêm sản phẩm mới</button>
    <table>
      <thead>
        <tr>
          <th>STT</th>
          <th>Hình ảnh</th>
          <th>Tên sản phẩm</th>
          <th>Hiển thị trang chủ</th>
          <th>Danh mục</th>
          <th>Tác vụ</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stt = 1;
        $result->data_seek(0); // Reset con trỏ kết quả nếu trước đó đã duyệt
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $stt++ ?></td>
          <td><img src="<?= htmlspecialchars(str_replace('/Nhom14', '', $row['image'])) ?>"alt="<?= htmlspecialchars($row['name']) ?>" width="80"></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><input type="checkbox" name="homepage[]" <?= rand(0,1) ? 'checked' : '' ?>></td>
          <td><input type="checkbox" name="category[]" <?= rand(0,1) ? 'checked' : '' ?>></td>
          <td>
            <button class="btn-edit">Chỉnh sửa</button>
            <button class="btn-delete">✖</button>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </section>
</div>


<?php $conn->close(); // Đóng kết nối ?>

</body>
</html>
