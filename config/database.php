<?php
$host     = getenv('DB_HOST') ?: 'mysql';
$dbname   = getenv('DB_NAME') ?: 'nhom14_mobile';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'root';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die('Kết nối thất bại: ' . $e->getMessage());
}