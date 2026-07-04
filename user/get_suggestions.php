<?php
header('Content-Type: application/json');

$conn = new mysqli(getenv('DB_HOST') ?: 'mysql', getenv('DB_USER') ?: 'root', getenv('DB_PASS') ?: 'root', getenv('DB_NAME') ?: 'nhom14_mobile');
if ($conn->connect_error) {
    die(json_encode(['success' => false]));
}
$conn->set_charset("utf8mb4");

$keyword = isset($_GET['q']) ? '%' . $_GET['q'] . '%' : '%';

$sql = "SELECT p.name, p.price, p.image, p.slug FROM products p 
        WHERE p.name LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode(['success' => true, 'data' => $products]);
$stmt->close();
$conn->close();
?>