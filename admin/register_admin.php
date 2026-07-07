<?php
session_start();
$error = '';
$success = '';

$servername = getenv('DB_HOST') ?: 'mysql';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'root';
$dbname = getenv('DB_NAME') ?: 'nhom14_mobile';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nhận thông tin đăng ký
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Kiểm tra mật khẩu và xác nhận mật khẩu có khớp không
    if ($pass !== $confirm_pass) {
        $error = "Mật khẩu và xác nhận mật khẩu không khớp!";
    } else {
        // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

        // Kiểm tra xem tên đăng nhập đã tồn tại chưa
        $sql = "SELECT * FROM admins WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Tên đăng nhập đã tồn tại!";
        } else {
            // Thêm tài khoản mới vào cơ sở dữ liệu
            $sql = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $user, $email, $hashed_password);

            if ($stmt->execute()) {
                $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
            } else {
                $error = "Đăng ký thất bại! Vui lòng thử lại.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký Quản trị</title>
  <style>
body {
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
  background: url('path_to_your_animation.gif') no-repeat center center fixed;
  background-size: cover;
  position: relative;
}

/* Lớp nền trắng mờ phủ toàn bộ */
.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.4);
  z-index: 0;
}

/* Container đăng ký */
.register-container {
  position: relative;
  z-index: 1;
  width: 380px;
  margin: 100px auto;
  padding: 40px 30px;
  background-color: rgba(255, 255, 255, 0.92);
  border-radius: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.25);
}

.register-container h2 {
  text-align: center;
  color: #d32f2f;
  margin-bottom: 30px;
}

/* Định dạng các input */
.input-group {
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
}

.input-group input {
  width: 92%;
  max-width: 300px;
  padding: 10px;
  font-size: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

/* Nút đăng ký */
.register-container button {
  width: 100%;
  padding: 12px;
  background-color: #d32f2f;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.register-container button:hover {
  background-color: #b71c1c;
}

/* Đoạn văn bản ở dưới */
.register-container p {
  text-align: center;
  margin-top: 15px;
}

.register-container p a {
  color: #d32f2f;
  text-decoration: none;
}

.register-container p a:hover {
  text-decoration: underline;
}

/* Thông báo lỗi và thành công */
.message {
  text-align: center;
  font-size: 14px;
  margin-bottom: 10px;
}

.message.error {
  color: red;
}

.message.success {
  color: green;
}

  </style>
</head>
<body>

  <div class="register-container">
    <h2>Đăng ký tài khoản quản trị</h2>
    <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p class='message success'>$success</p>"; ?>

    <form method="post">
      <div class="input-group">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
      </div>
      <div class="input-group">
        <input type="email" name="email" placeholder="Email" required>
      </div>
      <div class="input-group">
        <input type="password" name="password" placeholder="Mật khẩu" required>
      </div>
      <div class="input-group">
        <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
      </div>
      <button type="submit">Đăng ký</button>
    </form>
    <p>Đã có tài khoản? <a href="admin_login.php">Đăng nhập ngay</a></p>
  </div>

</body>
</html>
