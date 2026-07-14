<?php
session_start(); 

$conn = new mysqli(getenv('DB_HOST') ?: 'mysql', getenv('DB_USER') ?: 'root', getenv('DB_PASS') ?: 'root', getenv('DB_NAME') ?: 'nhom14_mobile');
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$sql = "SELECT * FROM hot_products";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhóm 14 Mobile - Cửa hàng điện thoại uy tín</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --primary: #e60000;
  --primary-dark: #c00000;
  --text-dark: #222;
  --text-light: #666;
  --border: #ddd;
  --bg: #f5f5f5;
  --white: #fff;
  --shadow: 0 2px 8px rgba(0,0,0,0.1);
  --shadow-lg: 0 4px 16px rgba(0,0,0,0.15);
}

html { scroll-behavior: smooth; }

body {
  background: var(--bg);
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  color: var(--text-dark);
}

/* ========== TOP BAR ========== */
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 40px;
  background: var(--white);
  border-bottom: 1px solid var(--border);
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: var(--shadow);
}

.top-bar .left,
.top-bar .center,
.top-bar .right {
  display: flex;
  align-items: center;
}

.top-bar .left {
  gap: 12px;
  flex: 0 0 auto;
}

.top-bar .logo {
  height: 48px;
  width: auto;
  object-fit: contain;
}

.top-bar .apple-logo {
  height: 36px;
  width: auto;
}

.top-bar .center {
  flex: 1;
  gap: 20px;
  margin: 0 30px;
  min-width: 300px;
  justify-content: center;
}

.top-bar .promo-title {
  color: var(--primary);
  font-size: 22px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  white-space: nowrap;
}

.search-box {
  display: flex;
  align-items: center;
  border: 2px solid var(--border);
  border-radius: 20px;
  background: var(--white);
  flex: 1;
  max-width: 550px;
  transition: all 0.3s ease;
  position: relative;
}

.search-box:focus-within {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(230,0,0,0.1);
}

.search-box input {
  border: none;
  padding: 10px 16px;
  width: 100%;
  outline: none;
  font-size: 14px;
  background: transparent;
}

.search-box input::placeholder {
  color: #999;
}

.search-box button {
  background: transparent;
  border: none;
  color: var(--text-light);
  padding: 0 16px;
  cursor: pointer;
  font-size: 16px;
  transition: color 0.3s ease;
}

.search-box button:hover {
  color: var(--primary);
}

.top-bar .right {
  gap: 30px;
  flex: 0 0 auto;
  display: flex;
  align-items: center;
}

.top-bar .item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  color: var(--text-dark);
  font-size: 11px;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  font-weight: 500;
  line-height: 1.4;
}

.top-bar .item i {
  font-size: 20px;
  margin-bottom: 4px;
  color: var(--primary);
  transition: transform 0.3s ease;
}

.top-bar .item:hover {
  color: var(--primary);
}

.top-bar .item:hover i {
  transform: scale(1.15);
}

.top-bar .item a {
  text-decoration: none;
  color: inherit;
  display: flex;
  flex-direction: column;
  align-items: center;
}

#cart-items {
  position: absolute;
  top: -6px;
  right: -8px;
  background: var(--primary);
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 10px;
  font-weight: 700;
  border: 2px solid white;
}

/* ========== MENU BAR ========== */
.menu-bar {
  background: var(--primary);
  overflow-x: auto;
  overflow-y: hidden;
  white-space: nowrap;
  -webkit-overflow-scrolling: touch;
  box-shadow: var(--shadow);
}

.menu-bar::-webkit-scrollbar {
  height: 4px;
}

.menu-bar::-webkit-scrollbar-thumb {
  background: rgba(255,255,255,0.3);
  border-radius: 2px;
}

.menu-bar ul {
  display: flex;
  align-items: stretch;
  justify-content: space-evenly;
  padding: 0;
  margin: 0;
  list-style: none;
  width: 100%;
}

.menu-bar ul li a {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 12px 14px;
  color: white;
  text-decoration: none;
  font-size: 11px;
  white-space: nowrap;
  transition: all 0.3s ease;
  border-bottom: 3px solid transparent;
  min-height: 56px;
}

.menu-bar ul li a i {
  font-size: 18px;
  margin-bottom: 4px;
}

.menu-bar ul li a:hover {
  background-color: rgba(0,0,0,0.1);
  border-bottom-color: #ffeb3b;
  transform: translateY(-1px);
}

/* ========== SLIDER ========== */
.slider {
  position: relative;
  width: 100%;
  height: 420px;
  overflow: hidden;
  background: var(--bg);
  border-radius: 0;
}

.slides {
  display: flex;
  width: 100%;
  height: 100%;
  transition: transform 0.5s ease-in-out;
}

.slide {
  flex: 0 0 100%;
  display: none;
  width: 100%;
  height: 100%;
}

.slide.active {
  display: block;
}

.slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 0;
  display: block;
}

.prev, .next {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0,0,0,0.45);
  color: white;
  border: none;
  padding: 12px 16px;
  cursor: pointer;
  font-size: 18px;
  z-index: 10;
  transition: all 0.3s ease;
  border-radius: 4px;
}

.prev:hover, .next:hover {
  background: rgba(0,0,0,0.7);
  transform: translateY(-50%) scale(1.1);
}

.prev {
  left: 20px;
}

.next {
  right: 20px;
}

/* ========== FEATURED PRODUCTS ========== */
.featured-products {
  padding: 40px;
  background: var(--white);
  margin: 20px 0;
}

.featured-products h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  position: relative;
  padding-bottom: 16px;
}

.featured-products h2::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 4px;
  background: linear-gradient(90deg, var(--primary), #ff6666);
  border-radius: 2px;
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(10, 1fr);
  gap: 14px;
  max-width: 1400px;
  margin: 0 auto;
}

.product-grid .product-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  text-align: center;
  padding: 16px;
  transition: all 0.2s ease;
  cursor: pointer;
}

.product-grid .product-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  transform: translateY(-3px);
  border-color: #eee;
}

.product-grid .product-card img {
  width: 100%;
  height: 120px;
  object-fit: contain;
  margin-bottom: 12px;
  background: var(--bg);
  border-radius: 6px;
}

.product-grid .product-card h3 {
  font-size: 13px;
  color: var(--text-dark);
  margin: 8px 0;
  line-height: 1.4;
  font-weight: 600;
}

/* ========== HOT PRODUCTS ========== */
.hot-products-title {
  color: var(--text-dark);
  font-size: 32px;
  font-weight: 700;
  text-align: center;
  margin-bottom: 30px;
  position: relative;
  padding-bottom: 14px;
}

.hot-products-title::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 4px;
  background: linear-gradient(90deg, var(--primary), #ff6666);
  border-radius: 2px;
}

.hot-products-container {
  background: linear-gradient(135deg, rgba(230,0,0,0.05) 0%, var(--white) 100%);
  padding: 40px;
  border-radius: 12px;
  margin: 40px auto;
  max-width: 1400px;
  box-shadow: var(--shadow);
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 18px;
}

.products-grid .product-card {
  background: var(--white);
  border-radius: 8px;
  padding: 16px;
  text-align: center;
  position: relative;
  border: 1px solid var(--border);
  transition: all 0.3s ease;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  height: 100%;
  overflow: hidden;
}

.products-grid .product-card:hover {
  box-shadow: var(--shadow-lg);
  border-color: var(--primary);
  transform: translateY(-6px);
}

.discount-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  background: var(--primary);
  color: white;
  padding: 6px 12px;
  font-size: 12px;
  font-weight: 700;
  border-radius: 4px;
  z-index: 5;
  box-shadow: 0 2px 6px rgba(230,0,0,0.3);
}

.products-grid .product-card img {
  width: 100%;
  height: 160px;
  object-fit: contain;
  margin-bottom: 12px;
  background: var(--bg);
  border-radius: 6px;
  padding: 10px;
  transition: transform 0.3s ease;
}

.products-grid .product-card:hover img {
  transform: scale(1.05);
}

.product-name {
  font-size: 14px;
  font-weight: 600;
  margin: 10px 0;
  color: var(--text-dark);
  line-height: 1.4;
  min-height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-grow: 1;
}

.price {
  color: var(--primary);
  font-weight: 700;
  font-size: 18px;
  margin: 8px 0;
}

.old-price {
  text-decoration: line-through;
  color: var(--text-light);
  font-size: 13px;
  margin-bottom: 8px;
}

.installment {
  margin: 8px 0;
  font-size: 12px;
  color: var(--text-light);
  line-height: 1.4;
  font-style: italic;
}

.stars {
  color: #ffc107;
  margin: 8px 0;
  font-size: 12px;
  letter-spacing: 1px;
}

/* ========== MESSAGES & POPUPS ========== */
.success-message {
  display: none;
  position: fixed;
  top: 30px;
  right: 30px;
  background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
  color: white;
  padding: 14px 24px;
  border-radius: 6px;
  box-shadow: var(--shadow-lg);
  font-size: 13px;
  z-index: 9999;
  animation: slideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  font-weight: 500;
}

.success-message.show {
  display: block;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(400px) scale(0.8);
  }
  to {
    opacity: 1;
    transform: translateX(0) scale(1);
  }
}

.cart-popup {
  display: none;
  position: absolute;
  width: 360px;
  background: var(--white);
  border: 1px solid var(--border);
  padding: 18px;
  box-shadow: var(--shadow-lg);
  z-index: 1000;
  border-radius: 8px;
  top: 100%;
  right: 0;
  margin-top: 12px;
  animation: slideDown 0.3s ease;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.cart-popup h3 {
  margin: 0 0 14px;
  font-size: 16px;
  font-weight: 700;
  color: var(--text-dark);
  border-bottom: 2px solid var(--primary);
  padding-bottom: 12px;
}

#cart-list {
  list-style: none;
  margin: 0 0 14px;
  padding: 0;
  max-height: 320px;
  overflow-y: auto;
}

#cart-list li {
  list-style: none;
  margin-bottom: 12px;
  padding: 12px;
  border: 1px solid var(--border);
  font-size: 12px;
  background: var(--bg);
  border-radius: 6px;
  color: var(--text-dark);
  line-height: 1.5;
}

.product-actions {
  margin-top: 8px;
  display: flex;
  gap: 6px;
}

.product-actions button {
  background: var(--primary);
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 600;
  font-size: 10px;
  flex: 1;
  transition: all 0.2s ease;
}

.product-actions button:hover {
  background: var(--primary-dark);
  transform: translateY(-1px);
}

#checkout-btn {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  width: 100%;
  margin-bottom: 10px;
  font-weight: 600;
  transition: all 0.3s ease;
  font-size: 13px;
  box-shadow: 0 2px 8px rgba(230,0,0,0.2);
}

#checkout-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(230,0,0,0.3);
}

.close-btn {
  position: absolute;
  top: 12px;
  right: 12px;
  background: none;
  border: none;
  font-size: 22px;
  color: var(--text-light);
  cursor: pointer;
  transition: all 0.3s ease;
}

.close-btn:hover {
  color: var(--primary);
  transform: scale(1.2);
}

/* ========== MODAL ========== */
.modal {
  display: none;
  position: fixed;
  z-index: 1001;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.55);
  backdrop-filter: blur(3px);
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.modal-content {
  background: var(--white);
  margin: 5% auto;
  padding: 32px;
  border-radius: 12px;
  width: 85%;
  max-width: 750px;
  position: relative;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
  animation: slideUp 0.3s ease;
}

@keyframes slideUp {
  from {
    transform: translateY(50px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.close {
  color: var(--text-light);
  position: absolute;
  top: 16px;
  right: 20px;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
}

.close:hover {
  color: var(--primary);
  transform: scale(1.2);
}

.product-details-container {
  display: flex;
  gap: 32px;
}

.product-details-container img {
  max-width: 280px;
  width: 100%;
  height: auto;
  border-radius: 8px;
  background: var(--bg);
  padding: 20px;
  object-fit: contain;
}

.product-info {
  flex: 1;
}

.product-info h3 {
  font-size: 26px;
  margin-bottom: 16px;
  color: var(--text-dark);
  font-weight: 700;
}

.product-info .price {
  color: var(--primary);
  font-size: 26px;
  font-weight: 700;
  margin-bottom: 8px;
}

.product-info .old-price {
  text-decoration: line-through;
  color: var(--text-light);
  font-size: 16px;
  margin-bottom: 16px;
}

.product-info .config {
  margin-bottom: 16px;
  font-size: 14px;
  color: var(--text-light);
  line-height: 1.6;
}

.quantity-control {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
}

.quantity-control button {
  background: var(--primary);
  color: white;
  border: none;
  padding: 8px 14px;
  cursor: pointer;
  border-radius: 6px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.quantity-control button:hover {
  background: var(--primary-dark);
  transform: translateY(-2px);
}

.quantity-control input {
  width: 70px;
  text-align: center;
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 8px;
  font-size: 14px;
  font-weight: 600;
}

.add-to-cart-btn {
  width: 100%;
  padding: 12px 20px;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(230,0,0,0.2);
}

.add-to-cart-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(230,0,0,0.3);
}

/* ========== FOOTER ========== */
.footer {
  background: #1a1a1a;
  color: #fff;
  padding: 40px 0 20px;
  font-size: 12px;
  margin-top: 40px;
}

.footer-container {
  max-width: 1400px;
  margin: auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 40px;
  padding: 0 40px;
}

.footer-column h3,
.footer-column h4 {
  font-weight: 700;
  margin-bottom: 14px;
  font-size: 13px;
  color: #fff;
}

.footer-column p {
  margin-bottom: 8px;
  line-height: 1.6;
  color: #bbb;
  font-size: 12px;
}

.footer-column ul {
  list-style: none;
  padding: 0;
  margin: 14px 0;
}

.footer-column ul li {
  margin-bottom: 8px;
  color: #bbb;
  cursor: pointer;
  font-size: 12px;
  transition: color 0.3s ease;
}

.footer-column ul li:hover {
  color: var(--primary);
}

.footer-bottom {
  text-align: center;
  margin-top: 30px;
  font-size: 11px;
  color: #888;
  border-top: 1px solid rgba(255,255,255,0.1);
  padding-top: 20px;
  padding: 20px 40px 0;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 1200px) {
  .products-grid {
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
  }
}

@media (max-width: 992px) {
  .top-bar {
    padding: 12px 30px;
  }

  .search-box {
    max-width: 280px;
  }

  .top-bar .right {
    gap: 20px;
  }

  .products-grid {
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 16px;
  }
}

@media (max-width: 768px) {
  .top-bar {
    flex-wrap: wrap;
    gap: 12px;
    padding: 12px 20px;
  }

  .top-bar .center {
    width: 100%;
    margin: 10px 0 0;
    flex: 1 1 100%;
  }

  .top-bar .right {
    width: 100%;
    justify-content: space-around;
    gap: 16px;
  }

  .menu-bar ul {
    padding: 0 20px;
  }

  .slider {
    height: 300px;
  }

  .featured-products {
    padding: 24px;
  }

  .featured-products h2 {
    font-size: 22px;
  }

  .hot-products-container {
    padding: 24px;
    margin: 24px auto;
  }

  .modal-content {
    width: 90%;
    padding: 20px;
  }

  .product-details-container {
    flex-direction: column;
    gap: 20px;
  }

  .product-details-container img {
    max-width: 100%;
  }

  .footer-container {
    padding: 0 20px;
    gap: 30px;
  }

  .cart-popup {
    width: calc(100vw - 20px);
    max-width: 100%;
  }
}

.quantity-control input[type=number]::-webkit-inner-spin-button,
.quantity-control input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.quantity-control input[type=number] {
    -moz-appearance: textfield;
}

@media (max-width: 480px) {
  .top-bar {
    padding: 10px 16px;
    flex-direction: column;
    gap: 10px;
  }

  .top-bar .logo {
    height: 40px;
  }

  .top-bar .promo-title {
    font-size: 14px;
  }

  .top-bar .item {
    font-size: 10px;
  }

  .top-bar .item i {
    font-size: 18px;
  }

  .slider {
    height: 220px;
  }

  .prev, .next {
    padding: 8px 12px;
    font-size: 16px;
  }

  .featured-products {
    padding: 16px;
  }

  .featured-products h2 {
    font-size: 20px;
  }

  .featured-products h2::after,
  .hot-products-title::after {
    width: 40px;
  }

  .hot-products-title {
    font-size: 20px;
  }

  .products-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
  }

  .hot-products-container {
    padding: 16px;
    margin: 16px auto;
  }

  .modal-content {
    width: 95%;
    padding: 16px;
  }

  .footer-container {
    padding: 0 16px;
  }

  .top-bar .center {
    margin: 8px 0 0;
  }

  .search-box {
    max-width: 100%;
  }
}
</style>
<body>
<div class="top-bar">
    <div class="left">
        <img src="/img_sp/Nhom14.png"alt="Logo" class="logo">
        <img src="/img_sp/apple.png" alt="Apple" class="apple-logo">
    </div>
    <div class="center">
        <h1 class="promo-title">DÙNG TRƯỚC TRẢ SAU</h1>
        <div class="search-box" style="position:relative;">
            <input type="text" id="search-input" placeholder="Tìm iPhone, iPad, Mac...">
            <button><i class="fa fa-search"></i></button>
            <div id="search-results" style="display:none; position:absolute; top:calc(100% + 8px); left:0; right:0; background:white; border:1px solid #ddd; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); z-index:999; max-height:400px; overflow-y:auto;"></div>
        </div>
    </div>
    <div class="right">
        <div class="item"><i class="fa fa-phone"></i> Hotline<br>1234567</div>
        <div class="item" id="cart-button" style="position: relative;">
            <i class="fa fa-shopping-cart"></i> 
            Giỏ Hàng 
            <span id="cart-items">0</span>
        </div>
        <div class="item"><i class="fa fa-truck"></i> Kiểm Tra<br>Đơn Hàng</div>
        <div class="item"><a href="/admin/admin_login.php"><i class="fa fa-home"></i> Hệ Thống<br>Cửa Hàng</a></div>
        <?php include 'user-info.php'; ?> 
    </div>
</div>

<div id="successMessage" class="success-message">
  <i class="fa fa-check-circle"></i> Sản phẩm đã được thêm vào giỏ hàng!
</div>

<div id="cart-popup" class="cart-popup">
  <h3>Giỏ Hàng Của Bạn</h3>
  <ul id="cart-list"></ul>
  <button id="checkout-btn">Thanh Toán Ngay</button>
  <button id="close-cart" class="close-btn">✕</button>
</div>

<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="product-details"></div>
    </div>
</div>

<nav class="menu-bar">
    <ul>
      <?php
        $menuItems = [
          ["icon" => "mobile-alt", "label" => "iPhone", "category" => "iPhone"],
          ["icon" => "tablet-alt", "label" => "iPad", "category" => "iPad"],
          ["icon" => "clock", "label" => "Watch", "category" => "Watch"],
          ["icon" => "headphones-alt", "label" => "AirPods", "category" => "AirPods"],
          ["icon" => "laptop", "label" => "Mac", "category" => "Mac"],
          ["icon" => "laptop", "label" => "Laptop", "category" => "Laptop"],
          ["icon" => "android", "label" => "Samsung", "category" => "Samsung"],
          ["icon" => "volume-up", "label" => "Âm thanh", "category" => "Audio"],
          ["icon" => "plug", "label" => "Phụ kiện", "category" => "Accessories"],
          ["icon" => "tv", "label" => "Gia dụng", "category" => "Appliances"],
          ["icon" => "history", "label" => "Hàng cũ", "category" => "Used"],
          ["icon" => "exchange-alt", "label" => "Thu cũ", "category" => "TradeIn"],
          ["icon" => "gift", "label" => "Kèo thơm", "href" => "keo-thom.php"],
          ["icon" => "newspaper", "label" => "Tin tức", "category" => ""]
        ];

        foreach ($menuItems as $item) {
          $href = isset($item["href"]) ? $item["href"] : ($item["category"] ? "products.php?category=" . urlencode($item["category"]) : "#");
          echo '<li><a href="' . $href . '"><i class="fa fa-' . $item["icon"] . '"></i> ' . $item["label"] . '</a></li>';
        }
      ?>
    </ul>
</nav>

<section class="slider">
  <div class="slides">
    <img src="/img_sp/slide-ip-12-pro-max-2023-6835.png?v=2"class="slide active"alt="Slide 1">
    <img src="/img_sp/black-friday-sale-banner-free-vector.jpg" class="slide" alt="Slide 2">
    <img src="/img_sp/banner.jpg" class="slide" alt="Slide 3">
  </div>
  <button class="prev">&#10094;</button>
  <button class="next">&#10095;</button>
</section>
 
<section class="featured-products">
  <h2>Sản Phẩm Nổi Bật</h2>
  <div class="product-grid">
    <div class="product-card"><img src="/img_sp/iPhone16prm.jpg" alt="iPhone 16 ProMax"><h3>iPhone 16 ProMax</h3></div>
    <div class="product-card"><img src="/img_sp/iphone-16e.jpg" alt="iPhone 16e"><h3>iPhone 16e</h3></div>
    <div class="product-card"><img src="/img_sp/nokia.png" alt="Nokia"><h3>Nokia</h3></div>
    <div class="product-card"><img src="/img_sp/ipad-a16.webp" alt="iPad (A16)"><h3>iPad (A16)</h3></div>
    <div class="product-card"><img src="/img_sp/iPadmini7.jpeg" alt="iPad mini 7"><h3>iPad mini 7</h3></div>
    <div class="product-card"><img src="/img_sp/iPadAirM3.webp" alt="iPad Air M3"><h3>iPad Air M3</h3></div>
    <div class="product-card"><img src="/img_sp/iPadProM4.jpg" alt="iPad Pro M4"><h3>iPad Pro M4</h3></div>
    <div class="product-card"><img src="/img_sp/iMacM4.jpg" alt="iMac M4"><h3>iMac M4</h3></div>
    <div class="product-card"><img src="/img_sp/Laptop.png" alt="Laptop"><h3>Laptop</h3></div>
    <div class="product-card"><img src="/img_sp/airpods-4.jpg" alt="AirPods 4"><h3>AirPods 4</h3></div>
    <div class="product-card"><img src="/img_sp/Loa-bluetooth.jpg" alt="Loa"><h3>Loa</h3></div>
    <div class="product-card"><img src="/img_sp/iPhoneCu.webp" alt="iPhone Cũ"><h3>iPhone Cũ</h3></div>
    <div class="product-card"><img src="/img_sp/GalaxyS25Series.jpg" alt="Galaxy S25 Series"><h3>Galaxy S25 Series</h3></div>
    <div class="product-card"><img src="/img_sp/GalaxyASeries.webp" alt="Galaxy A Series"><h3>Galaxy A Series</h3></div>
    <div class="product-card"><img src="/img_sp/AppleWatchS10.jpg" alt="Apple Watch S10"><h3>Apple Watch S10</h3></div>
    <div class="product-card"><img src="/img_sp/MacBookProM4.jpg" alt="MacBook Pro M4"><h3>MacBook Pro M4</h3></div>
    <div class="product-card"><img src="/img_sp/MayLocKhongKhi.jpg" alt="Máy Lọc Không Khí"><h3>Máy Lọc Không Khí</h3></div>
    <div class="product-card"><img src="/img_sp/BinhGiuNhiet.jpg" alt="Bình Giữ Nhiệt"><h3>Bình Giữ Nhiệt L & L</h3></div>
    <div class="product-card"><img src="/img_sp/cap-type-c-lightning-1m.jpeg" alt="Phụ Kiện"><h3>Dây sạc điện thoại</h3></div>
    <div class="product-card"><img src="/img_sp/sony-wh.jpg" alt="Tai Nghe"><h3>Tai Nghe</h3></div>
  </div>
</section>

<div class="hot-products-container">
    <h2 class="hot-products-title">🔥 HOT DEALS - SALE SỐC</h2>
    <div class="products-grid">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="product-card" onclick="showProductDetails(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                <?php if($row['discount_percent']): ?>
                    <div class="discount-badge">-<?php echo $row['discount_percent']; ?>%</div>
                <?php endif; ?>
                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
                <div class="price"><?php echo number_format($row['price'], 0, ',', '.'); ?>đ</div>
                <?php if($row['old_price']): ?>
                    <div class="old-price"><?php echo number_format($row['old_price'], 0, ',', '.'); ?>đ</div>
                <?php endif; ?>
                <div class="installment"><i class="fas fa-tag" style="color:#e60000; margin-right:4px;"></i> <?php echo htmlspecialchars($row['installment_info'] ?? ''); ?></div>
                <div class="stars">
                    <?php for ($i = 0; $i < $row['rating']; $i++) echo '⭐'; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<footer class="footer">
  <div class="footer-container">
    <div class="footer-column">
      <h3>Nhóm 14 Mobile</h3>
      <p><strong>Thành viên Tập đoàn Nhom14</strong></p>
      <p><i class="fa fa-envelope" style="color:#e60000; margin-right:6px;"></i> Nhom14@gmail.com</p>
      <p><i class="fa fa-phone" style="color:#e60000; margin-right:6px;"></i> 012 3456 5678</p>
      <p><i class="fa fa-building" style="color:#e60000; margin-right:6px;"></i> Trường Đại Học GTVT</p>
    </div>
    <div class="footer-column">
      <h4>Về Nhóm 14 Mobile</h4>
      <ul>
        <li>Giới thiệu</li>
        <li>Tuyển dụng</li>
        <li>Chính sách bảo mật</li>
        <li>Điều khoản sử dụng</li>
      </ul>
      <h4>Hỗ Trợ Khách Hàng</h4>
      <ul>
        <li>Tra cứu đơn hàng</li>
        <li>Giao nhận hàng</li>
        <li>Khuyến mãi</li>
        <li>Bảo hành & Bảo trì</li>
      </ul>
    </div>
    <div class="footer-column">
        <h4><i class="fa fa-credit-card" style="color:#e60000; margin-right:6px;"></i> Phương Thức Thanh Toán</h4>
        <p style="margin-top: 8px;">Visa • Mastercard • MoMo • ZaloPay • VNPay</p>
        <h4 style="margin-top: 16px;"><i class="fa fa-truck" style="color:#e60000; margin-right:6px;"></i> Vận Chuyển</h4>
        <p>Grab • Ahamove • Giao hàng toàn quốc</p>
    </div>
  </div>
  <div class="footer-bottom">
    © 2026 Nhóm 14 Mobile. Tất cả quyền được bảo lưu. | Chứng nhận Bộ Công Thương
  </div>
</footer>

<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;

function showSlide(index) {
  slides.forEach(slide => slide.classList.remove('active'));
  slides[index].classList.add('active');
}

function nextSlide() {
  currentSlide = (currentSlide + 1) % totalSlides;
  showSlide(currentSlide);
}

function prevSlide() {
  currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
  showSlide(currentSlide);
}

document.querySelector('.next').addEventListener('click', nextSlide);
document.querySelector('.prev').addEventListener('click', prevSlide);
setInterval(nextSlide, 5000);

const cartItemsCount = document.getElementById('cart-items');
const successMessage = document.getElementById('successMessage');
const cartPopup = document.getElementById('cart-popup');
const cartList = document.getElementById('cart-list');
const closeCartBtn = document.getElementById('close-cart');
const checkoutBtn = document.getElementById('checkout-btn');
let cart = JSON.parse(sessionStorage.getItem('cart')) || {};

function updateCartDisplay() {
    cartList.innerHTML = '';
    for (const id in cart) {
        const item = cart[id];
        const li = document.createElement('li');
        li.innerHTML = `
            <strong>${item.name}</strong><br>
            Số lượng: ${item.quantity}
            <div class="product-actions">
                <button class="decrease" data-id="${id}">−</button>
                <button class="increase" data-id="${id}">+</button>
                <button class="delete" data-id="${id}">Xóa</button>
            </div>
        `;
        cartList.appendChild(li);
    }
    const total = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
    cartItemsCount.textContent = total;
    sessionStorage.setItem('cart', JSON.stringify(cart));
}

function openCartPopup() {
    const cartButton = document.getElementById('cart-button');
    const rect = cartButton.getBoundingClientRect();
    cartPopup.style.display = 'block';
    cartPopup.style.position = 'absolute';
    cartPopup.style.top = `${rect.bottom + window.scrollY}px`;
    cartPopup.style.left = `${rect.right - cartPopup.offsetWidth + window.scrollX}px`;
}

document.getElementById('cart-button').addEventListener('click', () => {
    if (cartPopup.style.display === 'block') {
        cartPopup.style.display = 'none';
    } else {
        openCartPopup();
    }
});

window.addEventListener('scroll', () => {
    if (cartPopup.style.display === 'block') {
        openCartPopup();
    }
});

closeCartBtn.addEventListener('click', () => {
    cartPopup.style.display = 'none';
});

document.addEventListener('click', (e) => {
  const cartButton = document.getElementById('cart-button');
  if (!cartPopup.contains(e.target) && !cartButton.contains(e.target)) {
    cartPopup.style.display = 'none';
  }
});

cartList.addEventListener('click', e => {
    e.stopPropagation();
    const id = e.target.dataset.id;
    if (!id) return;

    if (e.target.classList.contains('increase')) {
        cart[id].quantity += 1;
    } else if (e.target.classList.contains('decrease')) {
        if (cart[id].quantity > 1) {
            cart[id].quantity -= 1;
        }
    } else if (e.target.classList.contains('delete')) {
        delete cart[id];
    }
    updateCartDisplay();
});

checkoutBtn.addEventListener('click', () => {
    alert('Bạn đang chuyển đến trang thanh toán!');
});

document.addEventListener('DOMContentLoaded', () => {
    updateCartDisplay();
});

function showProductDetails(product) {
    const modal = document.getElementById('productModal');
    const productDetails = document.getElementById('product-details');
    const safeName = product.name.replace(/'/g, "\\'").replace(/"/g, "\\\"");

    productDetails.innerHTML = `
        <div class="product-details-container">
            <img src="${product.image_url}" alt="${safeName}">
            <div class="product-info">
                <h3>${safeName}</h3>
                <div class="price">${Number(product.price).toLocaleString()}đ</div>
                ${product.old_price ? `<div class="old-price">${Number(product.old_price).toLocaleString()}đ</div>` : ''}
                <div class="config">${product.configuration || 'Chưa có thông tin'}</div>
                <div class="installment"><i class="fas fa-tag" style="color:#e60000; margin-right:4px;"></i> ${product.installment_info || 'Không có ưu đãi'}</div>
                <div class="quantity-control">
                  <button onclick="changeQuantity(-1)">−</button>
                  <input type="number" id="quantity" value="1" min="1" style="-webkit-appearance: none; -moz-appearance: textfield;">
                  <button onclick="changeQuantity(1)">+</button>
              </div>
                <button class="add-to-cart-btn" onclick="addToCartFromModal(${product.id}, '${safeName}')">Thêm Vào Giỏ Hàng</button>
            </div>
        </div>
    `;

    modal.style.display = 'block';

    document.querySelector('.close').onclick = () => {
        modal.style.display = 'none';
    };

    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
}

function changeQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    let quantity = parseInt(quantityInput.value);
    quantity += change;
    if (quantity < 1) quantity = 1;
    quantityInput.value = quantity;
}

function addToCartFromModal(id, name) {
    const quantity = parseInt(document.getElementById('quantity').value);

    if (cart[id]) {
        cart[id].quantity += quantity;
    } else {
        cart[id] = { name: name, quantity: quantity };
    }

    updateCartDisplay();
    successMessage.classList.add('show');
    setTimeout(() => successMessage.classList.remove('show'), 3000);
    document.getElementById('productModal').style.display = 'none';
}


const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');

searchInput.addEventListener('input', function() {
    const q = this.value.trim();
    if (q.length < 2) {
        searchResults.style.display = 'none';
        return;
    }
    fetch(`get_suggestions.php?q=${encodeURIComponent(q)}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                searchResults.innerHTML = data.data.map(p => `
                    <div onclick="window.location.href='product-detail.php?slug=${p.slug}'"
                        style="padding:10px 16px; cursor:pointer; border-bottom:1px solid #f0f0f0; display:flex; align-items:center; gap:12px;"
                        onmouseover="this.style.background='#f5f5f5'" 
                        onmouseout="this.style.background='white'">
                        <img src="${p.image}" style="width:40px;height:40px;object-fit:contain;" onerror="this.src='img_sp/Nhom14.png'">
                        <div style="font-size:13px;font-weight:600;">${p.name}</div>
                    </div>
                `).join('');
                searchResults.style.display = 'block';
            } else {
                searchResults.innerHTML = '<div style="padding:12px 16px;color:#999;font-size:13px;">Không tìm thấy sản phẩm</div>';
                searchResults.style.display = 'block';
            }
        });
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.search-box')) {
        searchResults.style.display = 'none';
    }
});

</script>


</body>
</html>
