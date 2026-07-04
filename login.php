<?php
// ===== TOÀN BỘ PHP XỬ LÝ LÊN ĐẦU FILE =====
session_start();
ob_start();

$servername = getenv('DB_HOST') ?: 'mysql';
$username_db = getenv('DB_USER') ?: 'root';
$password_db = getenv('DB_PASS') ?: 'root';
$dbname = getenv('DB_NAME') ?: 'nhom14_mobile';

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$alert = "";

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['username'] = $user['username'];
      header("Location: index.php");
      exit();
    } else {
      $alert = "Sai tên đăng nhập hoặc mật khẩu.";
    }
  } else {
    $alert = "Sai tên đăng nhập hoặc mật khẩu.";
  }
  $stmt->close();
}

if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  if ($stmt->get_result()->num_rows > 0) {
    $alert = "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
  } else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
      $alert = "Email đã tồn tại. Vui lòng sử dụng email khác.";
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $username, $email, $hashed_password);
      $alert = $stmt->execute() ? "Đăng ký thành công!" : "Lỗi: " . $conn->error;
    }
  }
  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng Nhập / Đăng Ký</title>
  <style>
  body { 
  font-family: Arial, sans-serif; 
  background: #f2f2f2; 
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh; 
  margin: 0;
}

.form-container {
  width: 320px;
  margin: 60px auto; 
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  justify-content: center; 
  align-items: center; 
}

h2 { 
  text-align: center; 
  margin-bottom: 20px; 
}

input[type="text"], input[type="password"], input[type="email"] {
  width: 100%;
  max-width: 300px; 
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 6px;
}

button {
  width: 100%;
  padding: 10px;
  background: #e60000;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
}

.toggle-link {
  text-align: center;
  margin-top: 10px;
  color: blue;
  cursor: pointer;
}

.form-box { 
  display: none; 
}

.form-box.active { 
  display: block; 
}

  </style>
</head>
<body>

<?php if ($alert): ?>
<script>alert('<?= addslashes($alert) ?>');</script>
<?php endif; ?>

<div class="form-container">
  <!-- Form đăng nhập -->
  <div id="login-form" class="form-box active">
    <h2>Đăng Nhập</h2>
    <form method="POST">
      <input type="text" name="username" placeholder="Tên đăng nhập" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <button type="submit" name="login">Đăng Nhập</button>
    </form>
    <div class="toggle-link" onclick="toggleForm()">Chưa có tài khoản? Đăng ký</div>
  </div>

  <!-- Form đăng ký -->
  <div id="register-form" class="form-box">
    <h2>Đăng Ký</h2>
    <form method="POST">
      <input type="text" name="username" placeholder="Tên người dùng" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <button type="submit" name="register">Đăng Ký</button>
    </form>
    <div class="toggle-link" onclick="toggleForm()">Đã có tài khoản? Đăng nhập</div>
  </div>
</div>

<script>
function toggleForm() {
  document.getElementById("login-form").classList.toggle("active");
  document.getElementById("register-form").classList.toggle("active");
}
</script>
</body>
</html>
