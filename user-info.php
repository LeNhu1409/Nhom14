<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
    echo "
    <div class='item' style='text-align: center;'>
        <span style='font-size: 13px;'>Chào, <strong>$username</strong></span>
        <a href='logout.php' style='color: red; text-decoration: none; font-size: 12px; display: block;'>Đăng Xuất</a>
    </div>";
} else {
    echo "
    <div class='item' style='text-align: center;'>
        <a href='login.php' style='color: inherit; text-decoration: none; font-size: 13px;'>
            <i class='fa fa-user'></i> Đăng Nhập
        </a>
    </div>";
}
?>