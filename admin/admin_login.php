<?php
session_start();
require_once '../config/database.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ghi log đầu vào để debug
    error_log("Đăng nhập: Username = $username, Password = $password");

    // Lấy thông tin admin từ cơ sở dữ liệu
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ghi log dữ liệu admin
        error_log("Admin: " . print_r($admin, true));

        if ($admin && password_verify($password, $admin['password'])) {
            // Thiết lập session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            // Ghi log session
            error_log("Session thiết lập: " . print_r($_SESSION, true));
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $error = "Sai tài khoản hoặc mật khẩu!";
            error_log("Đăng nhập thất bại: Sai tài khoản hoặc mật khẩu");
        }
    } catch (PDOException $e) {
        $error = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
        error_log("Lỗi cơ sở dữ liệu: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Quản trị</title>
    <style>
        /* Nền ảnh động cho toàn trang */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('admin/images/9bc27292880429.5e569ff84e4d0.gif') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
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

        /* Container đăng nhập */
        .login-container {
            position: relative;
            z-index: 1;
            width: 380px;
            padding: 40px 30px;
            background-color: rgba(255, 255, 255, 0.92);
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        .login-container h2 {
            color: #d32f2f;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: bold;
        }

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
            outline: none;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #d32f2f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .login-container button:hover {
            background-color: #b71c1c;
        }

        .error {
            color: red;
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="login-container">
        <h2>ĐĂNG NHẬP QUẢN TRỊ</h2>
        <?php if (!empty($error)): ?>
            <p class='error'><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="input-group">
                <input type="text" name="username" placeholder="Tên đăng nhập" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Mật khẩu" required>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>
</html>