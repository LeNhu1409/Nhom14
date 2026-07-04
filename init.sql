SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
DROP database if exists nhom14_mobile;
CREATE DATABASE nhom14_mobile;
USE nhom14_mobile;
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL, -- Tên danh mục (iPhone, iPad, Tin tức, v.v.)
    slug VARCHAR(100) NOT NULL UNIQUE, -- Đường dẫn thân thiện (iphone, ipad, tin-tuc)
    icon VARCHAR(50), -- Icon Font Awesome (mobile-alt, pencil, newspaper)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL, -- Tên sản phẩm (iPhone 15, MacBook Air)
    slug VARCHAR(255) NOT NULL UNIQUE, -- Đường dẫn thân thiện (iphone-15, macbook-air)
    description TEXT, -- Mô tả sản phẩm
    specifications TEXT, -- Cấu hình sản phẩm (mỗi dòng dạng key:value)
    price DECIMAL(10, 2) NOT NULL, -- Giá sản phẩm
    image VARCHAR(255), -- Đường dẫn ảnh sản phẩm
    is_old BOOLEAN DEFAULT FALSE, -- Trạng thái hàng cũ (TRUE nếu là hàng cũ)
    stock INT DEFAULT 0, -- Số lượng tồn kho
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL, -- Tiêu đề bài viết
    slug VARCHAR(255) NOT NULL UNIQUE, -- Đường dẫn thân thiện
    content TEXT NOT NULL, -- Nội dung bài viết
    image VARCHAR(255), -- Ảnh đại diện bài viết
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày đăng
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS promotions  (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL, -- Tên khuyến mãi (Khuyến mãi tháng, Combo ưu đãi)
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT, -- Mô tả khuyến mãi
    discount_percentage DECIMAL(5, 2), -- Phần trăm giảm giá (nếu có)
    start_date DATE NOT NULL, -- Ngày bắt đầu
    end_date DATE NOT NULL, -- Ngày kết thúc
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
DROP TABLE IF EXISTS trade_ins;
CREATE TABLE trade_ins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL, -- Tên sản phẩm thu cũ (iPhone 12, Samsung Galaxy S21)
    estimated_value DECIMAL(10, 2) NOT NULL, -- Giá trị ước tính
    product_condition VARCHAR(100), -- Tình trạng sản phẩm (Tốt, Hỏng nhẹ)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL, -- Đường dẫn ảnh
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
CREATE TABLE hot_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    old_price DECIMAL(10, 2),
    discount_percent INT,
    image_url VARCHAR(255),
    installment_info VARCHAR(255),
    rating INT,
    configuration TEXT
);

-- Thêm danh mục
INSERT IGNORE INTO categories (id, name, slug, icon) VALUES
(1, 'iPhone', 'iphone', 'mobile-alt'),
(2, 'iPad', 'ipad', 'pencil'),
(3, 'Watch', 'watch', 'clock'),
(4, 'AirPods', 'airpods', 'headphones'),
(5, 'Mac', 'mac', 'desktop'),
(6, 'Laptop', 'laptop', 'laptop'),
(7, 'Điện thoại', 'dien-thoai', 'phone'),
(8, 'Samsung', 'samsung', 'android'),
(9, 'Âm thanh', 'am-thanh', 'volume-up'),
(10, 'Phụ kiện', 'phu-kien', 'plug'),
(11, 'Gia dụng & Đời sống', 'gia-dung-doi-song', 'tv'),
(12, 'Hàng cũ', 'hang-cu', 'refresh'),
(13, 'Thu cũ', 'thu-cu', 'exchange'),
(14, 'Kèo thơm', 'keo-thom', 'gift'),
(15, 'Tin tức', 'tin-tuc', 'newspaper');

-- iPhone (category_id = 1)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(1, 'iPhone 16 Pro Max', 'iphone-16-pro-max', 1299.99, '/Nhom14/admin/images/iphone-16-pro-max-sa-mac-thumb.jpg', FALSE),
(1, 'iPhone 15 Pro Max', 'iphone-15-pro-max', 1099.99, '/Nhom14/admin/images/iphone-15-pro-max-blue.jpg', FALSE),
(1, 'iPhone 16 e', 'iphone-16-e', 599.99, '/Nhom14/admin/images/iphone-16e-white-thumb.jpg', FALSE),
(1, 'iPhone 15', 'iphone-15', 899.99, '/Nhom14/admin/images/iphone-15-xanh.jpg', FALSE),
(1, 'iPhone 14 Pro', 'iphone-14-pro', 999.99, '/Nhom14/admin/images/iphone-14-pro-vang.jpg', FALSE),
(1, 'iPhone 14', 'iphone-14', 799.99 , '/Nhom14/admin/images/iPhone-14-plus.jpg', FALSE),
(1, 'iPhone 13', 'iphone-13', 699.99, '/Nhom14/admin/images/iphone-13-starlight.jpg', FALSE),
(1, 'iPhone 12', 'iphone-12', 599.99, '/Nhom14/admin/images/iphone-12-tim.jpg', FALSE),
(1, 'iPhone 11', 'iphone-11', 399.99, '/Nhom14/admin/images/iphone-11-trang.jpg', FALSE),
(1, 'iPhone XR', 'iphone-xr', 199.99, '/Nhom14/admin/images/600_iphone_xr_do_xtsmart.jpg', FALSE),
(1, 'iPhone Xs Max', 'iphone-xs-max', 199.99, '/Nhom14/admin/images/iphone-xs-max-gold-600x600.jpg', FALSE),
(1, 'iPhone Xs', 'iphone-xs', 159.99, '/Nhom14/admin/images/iphone-xs-vang-600x600-600x600.jpg', FALSE);

-- iPad (category_id = 2)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(2, 'iPad Pro 12.9-inch', 'ipad-pro-12-9', 1199.99, '/Nhom14/admin/images/ipad-pro-13-select-wifi-spacegray.jpg', FALSE),
(2, 'iPad Pro 11-inch', 'ipad-pro-11', 999.99, '/Nhom14/admin/images/ipad-air-11-inch-m2-lte-blue.jpg', FALSE),
(2, 'iPad Air', 'ipad-air', 749.99, '/Nhom14/admin/images/ipad-air-5-wifi-pink.jpg', FALSE),
(2, 'iPad 10th Gen', 'ipad-10th-gen', 599.99, '/Nhom14/admin/images/iPad-Gen-10-sliver.jpg', FALSE),
(2, 'iPad 9th Gen', 'ipad-9th-gen', 499.99, '/Nhom14/admin/images/iPad-9-wifi-trang.jpg', FALSE),
(2, 'iPad Mini', 'ipad-mini', 649.99, '/Nhom14/admin/images/ipad-mini-6.jpg', FALSE),
(2, 'iPad Pro M4', 'ipad-pro-m4', 899.99, '/Nhom14/admin/images/ipad-pro-11-inch-m4-wifi-black.jpg', FALSE);

-- Watch (category_id = 3)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(3, 'Apple Watch Ultra 2', 'apple-watch-ultra-2', 799.99, '/Nhom14/admin/images/apw-ultra-2-tim.jpg', FALSE),
(3, 'Apple Watch Series 9', 'apple-watch-series-9', 499.99, '/Nhom14/admin/images/apple-watch-series-9-gps-41mm.jpg', FALSE),
(3, 'Apple Watch Series 8', 'apple-watch-series-8', 449.99, '/Nhom14/admin/images/apple-watch-series-8-gps-45mm-den.jpg', FALSE),
(3, 'Apple Watch SE 2023', 'apple-watch-se-2023', 299.99, '/Nhom14/admin/images/apple-watch-se-2-gps-44mm-vien-nhom-day-cao-su-bac.jpg', FALSE),
(3, 'Apple Watch SE 2022', 'apple-watch-se-2022', 249.99, '/Nhom14/admin/images/apple-watch-se-2-40mm-gps-den.jpg', FALSE),
(3, 'Apple Watch Series 7', 'apple-watch-series-7', 399.99, '/Nhom14/admin/images/watch-45-alum-midnight.jpg', FALSE);

-- AirPods (category_id = 4)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(4, 'AirPods Pro 2', 'airpods-pro-2', 249.99, '/Nhom14/admin/images/tai-nghe-airpods-pro-2022.jpg', FALSE),
(4, 'AirPods 3rd Gen', 'airpods-3rd-gen', 179.99, '/Nhom14/admin/images/tai-nghe-airpods-3-2021.jpg', FALSE),
(4, 'AirPods 2nd Gen', 'airpods-2nd-gen', 129.99, '/Nhom14/admin/images/airpods-2-hop-sac-khong-day.jpg', FALSE),
(4, 'AirPods 4th Gen', 'airpods-4th-gen', 189.99, '/Nhom14/admin/images/tai_nghe_airpods_4.jpg', FALSE);

-- Mac (category_id = 5)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(5, 'MacBook Pro 14-inch M2', 'macbook-pro-14-m2', 1999.99, '/Nhom14/admin/images/7074_pro_4__01.jpg', FALSE),
(5, 'MacBook Pro 16-inch M2', 'macbook-pro-16-m2', 2499.99, '/Nhom14/admin/images/apple-macbook-air.jpg', FALSE),
(5, 'MacBook Air M2', 'macbook-air-m2', 1299.99, '/Nhom14/admin/images/macbook-air-m2.jpg', FALSE),
(5, 'MacBook Air M1', 'macbook-air-m1', 999.99, '/Nhom14/admin/images/Macbook-air-m1.jpg', FALSE),
(5, 'iMac 24-inch M1', 'imac-24-m1', 1499.99, '/Nhom14/admin/images/imac-24-inch-m4-xanh-duong.jpg', FALSE),
(5, 'Mac Mini M2', 'mac-mini-m2', 799.99, '/Nhom14/admin/images/mac-mini-m2-z16k.jpg', FALSE);

-- Laptop (category_id = 6)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(6, 'Dell XPS 13', 'dell-xps-13', 1399.99, '/Nhom14/admin/images/dell-xps-13-9320-i5.jpg', FALSE),
(6, 'HP Spectre x360', 'hp-spectre-x360', 1299.99, '/Nhom14/admin/images/hp-spectre-x360-13.jpg', FALSE),
(6, 'Lenovo ThinkPad X1 Carbon', 'lenovo-thinkpad-x1-carbon', 1499.99, '/Nhom14/admin/images/lenovo-thinkpad-x1-carbon-gen-10-i7.jpg', FALSE),
(6, 'ASUS ZenBook 14', 'asus-zenbook-14', 1199.99, '/Nhom14/admin/images/asus-zenbook-14-oled-i5.jpg', FALSE),
(6, 'Acer Swift 3', 'acer-swift-3', 899.99, '/Nhom14/admin/images/acer-swift-3-sf314-512-56qn-i5.jpg', FALSE),
(6, 'MSI Prestige 14', 'msi-prestige-14', 1299.99, '/Nhom14/admin/images/msi-prestige-14-ai-studio-c1udxg-ultra-7.jpg', FALSE);

-- Điện thoại (category_id = 7)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(7, 'Xiaomi 14', 'xiaomi-14', 799.99, '/Nhom14/admin/images/xiaomi-14-green-thumbnew.jpg', FALSE),
(7, 'OPPO Find X6 Pro', 'oppo-find-x6-pro', 899.99, '/Nhom14/admin/images/oppo-find-x6-pro-1.jpg', FALSE),
(7, 'Vivo X90 Pro', 'vivo-x90-pro', 849.99, '/Nhom14/admin/images/vivo-x90-pro-1.jpg', FALSE),
(7, 'Realme GT 3', 'realme-gt-3', 699.99, '/Nhom14/admin/images/realme-gt-neo3.jpg', FALSE),
(7, 'Nokia X30', 'nokia-x30', 599.99, '/Nhom14/admin/images/nokia-x30-600x600.jpg', FALSE),
(7, 'OnePlus 11', 'oneplus-11', 799.99, '/Nhom14/admin/images/oneplus-11-600x600.jpg', FALSE);

-- Samsung (category_id = 8)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(8, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 1299.99, '/Nhom14/admin/images/samsung-galaxy-s24-ultra.jpg', FALSE),
(8, 'Samsung Galaxy S24', 'samsung-galaxy-s24', 999.99, '/Nhom14/admin/images/600_crop_samsung-galaxy-s24-128gb-my-thumb-600x600.jpg', FALSE),
(8, 'Samsung Galaxy Z Fold 5', 'samsung-galaxy-z-fold-5', 1799.99, '/Nhom14/admin/images/samsung-galaxy-z-fold5-gia-re-3.jpg', FALSE),
(8, 'Samsung Galaxy Z Flip 5', 'samsung-galaxy-z-flip-5', 1099.99, '/Nhom14/admin/images/samsung-galaxy-z-flip-5.jpg', FALSE),
(8, 'Samsung Galaxy A54', 'samsung-galaxy-a54', 499.99, '/Nhom14/admin/images/samsung-galaxy-a54-5g-tim.jpg', FALSE),
(8, 'Samsung Galaxy S23', 'samsung-galaxy-s23', 899.99, '/Nhom14/admin/images/samsung-galaxy-s23.jpg', FALSE);

-- Âm thanh (category_id = 9)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(9, 'Sony WH-1000XM5', 'sony-wh-1000xm5', 399.99, '/Nhom14/admin/images/tai-nghe-bluetooth-chup-tai-sony-wh1000xm5.jpg', FALSE),
(9, 'Bose QuietComfort 45', 'bose-quietcomfort-45', 349.99, '/Nhom14/admin/images/bose-comfort-45-14.jpg', FALSE),
(9, 'JBL Flip 6', 'jbl-flip-6', 129.99, '/Nhom14/admin/images/JBL-Flip6-Red-A.jpg', FALSE),
(9, 'Sony WF-1000XM4', 'sony-wf-1000xm4', 279.99, '/Nhom14/admin/images/wf-1000xm4_800x450.jpg', FALSE),
(9, 'Harman Kardon Onyx Studio 7', 'harman-kardon-onyx-studio-7', 299.99, '/Nhom14/admin/images/loa-Harman-kardon-ONYX-MINI--Trang.jpg', FALSE),
(9, 'Sennheiser Momentum 3', 'sennheiser-momentum-3', 249.99, '/Nhom14/admin/images/Sennheiser-Momentum-3-White-A.jpg', FALSE);

-- Phụ kiện (category_id = 10)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(10, 'Cáp sạc USB-C Anker', 'cap-sac-usb-c-anker', 19.99, '/Nhom14/admin/images/cap-2-in-1-type-c-type-c-1-2m-140w-anker.jpg', FALSE),
(10, 'Ốp lưng iPhone 15 Spigen', 'op-lung-iphone-15-spigen', 29.99, '/Nhom14/admin/images/op-lung-iphone-15-pro-max-nhua-cung-vien-deo-spigen-ultra.jpg', FALSE),
(10, 'Pin dự phòng 10000mAh Xiaomi', 'pin-du-phong-10000mah-xiaomi', 39.99, '/Nhom14/admin/images/pin-sac-du-phong-10000mah-type-c-pd-qc-3-0-22-5w-xiaomi-lite.jpg', FALSE),
(10, 'Kính cường lực iPhone 15', 'kinh-cuong-luc-iphone-15', 15.99, '/Nhom14/admin/images/mieng-dan-kinh-cuong-luc-iphone-15-pro-max.jpg', FALSE),
(10, 'Chuột Logitech MX Master 3', 'chuot-logitech-mx-master-3', 99.99, '/Nhom14/admin/images/chuot-bluetooth-logitech-mx-master-3s-4.jpg', FALSE),
(10, 'Bàn phím cơ Keychron K8', 'ban-phim-co-keychron-k8', 89.99, '/Nhom14/admin/images/mo-ta-keychron-k8-pro-thinkpro-1-thinkpro.jpg', FALSE);

-- Gia dụng & Đời sống (category_id = 11)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(11, 'Máy lọc không khí Xiaomi', 'may-loc-khong-khi-xiaomi', 199.99, '/Nhom14/admin/images/screenshot2022-09-28at11-59-18-jpg_1280x1005-800.jpg', FALSE),
(11, 'Quạt điều hòa Kangaroo', 'quat-dieu-hoa-kangaroo', 149.99, '/Nhom14/admin/images/quat-dieu-hoa-kangaroo-kg50f99-1-700x467.jpg', FALSE),
(11, 'Đèn bàn LED Philips', 'den-ban-led-philips', 59.99, '/Nhom14/admin/images/Led-Kapler.jpg', FALSE),
(11, 'Máy hút bụi Deerma', 'may-hut-bui-deerma', 89.99, '/Nhom14/admin/images/may-hut-bui-cam-tay-deerma-dx118c-pro-251023-033910-600x600.jpg', FALSE),
(11, 'Nồi chiên không dầu Lock&Lock', 'noi-chien-khong-dau-locklock', 129.99, '/Nhom14/admin/images/noi-chien-khong-dau-locknlock-ejf996blk-55-lit-220623-040336-600x600.jpg', FALSE),
(11, 'Máy xay sinh tố Philips', 'may-xay-sinh-to-philips', 79.99, '/Nhom14/admin/images/philips-hr2221-00-225092.jpg', FALSE);

-- Hàng cũ (category_id = 12)
INSERT IGNORE INTO products (category_id, name, slug, price, image, is_old) VALUES
(12, 'iPhone 13 Cũ', 'iphone-13-cu', 599.99, '/Nhom14/admin/images/iphone-13-pink_1648800188_1.jpg', TRUE),
(12, 'iPhone 12 Pro Cũ', 'iphone-12-pro-cu', 549.99, '/Nhom14/admin/images/ip-12-pro-cu.jpg', TRUE),
(12, 'Samsung Galaxy S22 Cũ', 'samsung-galaxy-s22-cu', 499.99, '/Nhom14/admin/images/samsung-galaxy-s22-ultra-512gb.jpg', TRUE),
(12, 'MacBook Air M1 Cũ', 'macbook-air-m1-cu', 899.99, '/Nhom14/admin/images/macbook-air-m1-2020-gray-600x600.jpg', TRUE),
(12, 'iPad Pro 11-inch Cũ', 'ipad-pro-11-cu', 699.99, '/Nhom14/admin/images/ipad-pro-11-inch-2020-xam.jpg', TRUE),
(12, 'Apple Watch Series 7 Cũ', 'apple-watch-series-7-cu', 349.99, '/Nhom14/admin/images/apple_watch_s7_lte_45mm.jpg', TRUE);

INSERT IGNORE INTO trade_ins (category_id, product_name, estimated_value, product_condition) VALUES
(13, 'iPhone 12', 300.00, 'Tốt'),
(13, 'iPhone 11 Pro', 250.00, 'Hỏng nhẹ'),
(13, 'Samsung Galaxy S21', 280.00, 'Tốt'),
(13, 'Samsung Galaxy Note 20', 220.00, 'Hỏng nhẹ'),
(13, 'iPad Air 4', 200.00, 'Tốt'),
(13, 'MacBook Pro 2019', 600.00, 'Tốt'),
(13, 'Apple Watch Series 6', 150.00, 'Hỏng nhẹ');

INSERT IGNORE INTO promotions (category_id, title, slug, description, discount_percentage, start_date, end_date) VALUES
(14, 'Giảm giá iPhone 15', 'giam-gia-iphone-15', 'Giảm 10% cho iPhone 15', 10.00, '2026-06-15', '2026-07-15'),
(14, 'Combo MacBook và AirPods', 'combo-macbook-airpods', 'Mua MacBook tặng AirPods', 0.00, '2026-06-15', '2026-07-15'),
(14, 'Giảm giá Samsung S24', 'giam-gia-samsung-s24', 'Giảm 15% cho Galaxy S24', 15.00, '2026-06-15', '2026-07-15'),
(14, 'Ưu đãi Watch Series 9', 'uu-dai-watch-series-9', 'Giảm 20% cho Apple Watch Series 9', 20.00, '2026-06-15', '2026-07-15'),
(14, 'Khuyến mãi iPad Pro', 'khuyen-mai-ipad-pro', 'Giảm 10% cho iPad Pro', 10.00, '2026-06-15', '2026-07-15'),
(14, 'Mua 2 tặng 1 Phụ kiện', 'mua-2-tang-1-phu-kien', 'Mua 2 phụ kiện tặng 1', 0.00, '2026-06-15', '2026-07-15');

INSERT IGNORE INTO news (category_id, title, slug, content, image) VALUES
(15, 'iPhone 17 ra mắt', 'iphone-17-ra-mat', 'Apple vừa giới thiệu iPhone 17 với nhiều cải tiến vượt bậc.', 'images/news-iphone17.jpg'),
(15, 'iOS 19 chính thức', 'ios-19-chinh-thuc', 'iOS 19 mang lại nhiều tính năng mới cho người dùng.', 'images/news-ios19.jpg'),
(15, 'MacBook M4 lộ diện', 'macbook-m4-lo-dien', 'MacBook M4 dự kiến sẽ ra mắt vào cuối năm 2026.', 'images/news-macbookm4.jpg');

-- Thêm sản phẩm mẫu vào hot_products

INSERT IGNORE INTO hot_products (name, image_url, price, old_price, discount_percent, installment_info, rating, configuration) VALUES('iPhone 16 128GB - Chính hãng VN/A', 'img_sp/iphone-16-xanh-mong.png', 18790000, 22990000, 19, 'Lãi suất 0% Trả trước 0đ Phí 0đ', 5, 'Chip A18, 6GB RAM, 128GB Storage, 6.1" Super Retina XDR Display'),
('iPhone 16 Plus 128GB - Chính hãng VN/A', 'img_sp/iphone-16-plus-xanh.jpg', 21890000, 25990000, 16, 'Lãi suất 0% Trả trước 0đ Phí 0đ', 5, 'Chip A18, 6GB RAM, 128GB Storage, 6.7" Super Retina XDR Display'),
('iPhone 16 Pro Max 256GB - Chính hãng VN/A', 'img_sp/iphone-16-pro-max-sa-mac.jpg', 30690000, 34990000, 13, 'Thu cũ đổi mới tặng AirPods', 5, 'Chip A18 Pro, 8GB RAM, 256GB Storage, 6.9" Super Retina XDR Display'),
('iPhone 15 128GB - Chính hãng VN/A', 'img_sp/iphone-15-pro-gold.jpg', 17790000, 20990000, 15, 'Trả góp 0% qua thẻ tín dụng', 5, 'Chip A16 Bionic, 6GB RAM, 128GB Storage, 6.1" Super Retina XDR Display'),
('iPhone 15 Pro 128GB - Chính hãng VN/A', 'img_sp/iphone-15-pro-white.jpg', 23490000, 27990000, 16, 'Thu cũ đổi mới giá tốt', 5, 'Chip A17 Pro, 8GB RAM, 128GB Storage, 6.1" Super Retina XDR Display'),
('Samsung Galaxy S24 Ultra 256GB', 'img_sp/samsung-galaxy-s24-ultra.jpg', 27990000, 31990000, 13, 'Trả góp 0% 12 tháng', 5, 'Snapdragon 8 Gen 3, 12GB RAM, 256GB Storage, 6.8" Dynamic AMOLED 2X'),
('Samsung Galaxy Z Fold5 512GB', 'img_sp/samsung-galaxy-z-fold5.jpg', 37990000, 42990000, 12, 'Tặng kèm bao da cao cấp', 5, 'Snapdragon 8 Gen 2, 12GB RAM, 512GB Storage, 7.6" Foldable Dynamic AMOLED'),
('Samsung Galaxy A55 5G 256GB', 'img_sp/samsung-galaxy-a55.jpg', 10290000, 11990000, 14, 'Giảm thêm khi thanh toán VNPAY', 5, 'Exynos 1480, 8GB RAM, 256GB Storage, 6.6" Super AMOLED'),
('Samsung Galaxy S23 FE 128GB', 'img_sp/samsung-galaxy-s23.jpg', 12890000, 14990000, 14, 'Thu cũ đổi mới trợ giá 1 triệu', 5, 'Exynos 2200, 8GB RAM, 128GB Storage, 6.4" Dynamic AMOLED'),
('Samsung Galaxy M14 5G 128GB', 'img_sp/samsung-galaxy-m14-5g.jpg', 3990000, 4990000, 20, 'Trả góp 0% - Ưu đãi online', 5, 'Exynos 1330, 4GB RAM, 128GB Storage, 6.6" PLS LCD'),
('Xiaomi 14 Ultra 512GB', 'img_sp/xiaomi-14-ultra-white.jpg', 26990000, 28990000, 7, 'Tặng tai nghe Buds 4 Pro', 5, 'Snapdragon 8 Gen 3, 16GB RAM, 512GB Storage, 6.73" AMOLED'),
('Xiaomi 14 256GB', 'img_sp/xiaomi-14-white.jpg', 19990000, 21990000, 9, 'Thu cũ đổi mới giảm thêm 500K', 5, 'Snapdragon 8 Gen 3, 12GB RAM, 256GB Storage, 6.36" AMOLED'),
('Xiaomi Redmi Note 13 Pro+ 5G', 'img_sp/xiaomi-redmi-note-13-pro-plus-white.jpg', 13990000, 16990000, 17, 'Giảm 500K khi mua kèm gói BH', 5, 'Dimensity 7200 Ultra, 12GB RAM, 512GB Storage, 6.67" AMOLED'),
('Xiaomi Redmi 12 128GB', 'img_sp/xiaomi-redmi-12-xanh-duong.jpg', 3290000, 3990000, 17, 'Mua online giảm thêm 5%', 5, 'Helio G88, 4GB RAM, 128GB Storage, 6.79" IPS LCD'),
('Xiaomi 13T Pro 512GB', 'img_sp/xiaomi-13t-pro-xanh.jpg', 14990000, 17990000, 17, 'Thu cũ đổi mới trợ giá 1 triệu', 5, 'Dimensity 9200+, 12GB RAM, 512GB Storage, 6.67" AMOLED'),
('iPad Pro M2 11-inch 128GB WiFi', 'img_sp/ipad-pro-m2-11-wifi-xam.jpg', 19990000, 22990000, 13, 'Trả góp 0%', 5, 'Apple M2, 8GB RAM, 128GB Storage, 11" Liquid Retina Display'),
('iPad Gen 10 64GB WiFi', 'img_sp/iPad-Gen-10-Pink.jpg', 8990000, 11990000, 25, 'Ưu đãi sinh viên', 5, 'A14 Bionic, 4GB RAM, 64GB Storage, 10.9" Liquid Retina Display'),
('iPad Air 5 64GB WiFi', 'img_sp/ipad-air-5-wifi-cellular-pink.jpg', 13990000, 16990000, 18, 'Giảm thêm khi mua kèm Apple Pencil', 5, 'Apple M1, 8GB RAM, 64GB Storage, 10.9" Liquid Retina Display'),
('iPad Mini 6 64GB WiFi', 'img_sp/ipad-mini-6-wifi-starlight.jpg', 10990000, 13990000, 21, 'Giảm 500K khi mua kèm phụ kiện', 5, 'A15 Bionic, 4GB RAM, 64GB Storage, 8.3" Liquid Retina Display'),
('iPad Gen 9 10.2-inch 128GB', 'img_sp/iPad-9-5G-den.jpg', 23490000, 26990000, 13, 'Giảm giá sốc - có hàng giao ngay', 5, 'A13 Bionic, 3GB RAM, 128GB Storage, 10.2" Retina Display');

-- ============================================
-- Cập nhật cấu hình cho từng sản phẩm (specifications)
-- ============================================

-- ===== iPhone =====
UPDATE products SET specifications = 'Chip: A18 Pro\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 6.9" Super Retina XDR\nCamera sau: 48MP + 48MP + 12MP\nPin: 4685 mAh' WHERE slug = 'iphone-16-pro-max';
UPDATE products SET specifications = 'Chip: A17 Pro\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 6.7" Super Retina XDR\nCamera sau: 48MP + 12MP + 12MP\nPin: 4422 mAh' WHERE slug = 'iphone-15-pro-max';
UPDATE products SET specifications = 'Chip: A18\nRAM: 6GB\nBộ nhớ: 128GB\nMàn hình: 6.1" Super Retina XDR\nCamera sau: 48MP\nPin: 3961 mAh' WHERE slug = 'iphone-16-e';
UPDATE products SET specifications = 'Chip: A16 Bionic\nRAM: 6GB\nBộ nhớ: 128GB\nMàn hình: 6.1" Super Retina XDR\nCamera sau: 48MP + 12MP\nPin: 3349 mAh' WHERE slug = 'iphone-15';
UPDATE products SET specifications = 'Chip: A16 Bionic\nRAM: 6GB\nBộ nhớ: 128GB\nMàn hình: 6.1" Super Retina XDR\nCamera sau: 48MP + 12MP\nPin: 3200 mAh' WHERE slug = 'iphone-14-pro';
UPDATE products SET specifications = 'Chip: A15 Bionic\nRAM: 6GB\nBộ nhớ: 128GB\nMàn hình: 6.1" Super Retina XDR\nCamera sau: 12MP + 12MP\nPin: 3279 mAh' WHERE slug = 'iphone-14';
UPDATE products SET specifications = 'Chip: A15 Bionic\nRAM: 4GB\nBộ nhớ: 128GB\nMàn hình: 6.1" Super Retina XDR\nCamera sau: 12MP + 12MP\nPin: 3227 mAh' WHERE slug = 'iphone-13';
UPDATE products SET specifications = 'Chip: A14 Bionic\nRAM: 4GB\nBộ nhớ: 128GB\nMàn hình: 6.1" Super Retina XDR\nCamera sau: 12MP + 12MP\nPin: 2815 mAh' WHERE slug = 'iphone-12';
UPDATE products SET specifications = 'Chip: A13 Bionic\nRAM: 4GB\nBộ nhớ: 128GB\nMàn hình: 6.1" Liquid Retina\nCamera sau: 12MP + 12MP\nPin: 3110 mAh' WHERE slug = 'iphone-11';
UPDATE products SET specifications = 'Chip: A12 Bionic\nRAM: 3GB\nBộ nhớ: 64GB\nMàn hình: 6.1" Liquid Retina\nCamera sau: 12MP\nPin: 2942 mAh' WHERE slug = 'iphone-xr';
UPDATE products SET specifications = 'Chip: A12 Bionic\nRAM: 4GB\nBộ nhớ: 64GB\nMàn hình: 6.5" Super Retina\nCamera sau: 12MP + 12MP\nPin: 3174 mAh' WHERE slug = 'iphone-xs-max';
UPDATE products SET specifications = 'Chip: A12 Bionic\nRAM: 4GB\nBộ nhớ: 64GB\nMàn hình: 5.8" Super Retina\nCamera sau: 12MP + 12MP\nPin: 2658 mAh' WHERE slug = 'iphone-xs';

-- ===== iPad =====
UPDATE products SET specifications = 'Chip: Apple M4\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 12.9" Tandem OLED\nKết nối: WiFi 6E' WHERE slug = 'ipad-pro-12-9';
UPDATE products SET specifications = 'Chip: Apple M2\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 11" Liquid Retina\nKết nối: WiFi + Cellular' WHERE slug = 'ipad-pro-11';
UPDATE products SET specifications = 'Chip: Apple M1\nRAM: 8GB\nBộ nhớ: 128GB\nMàn hình: 10.9" Liquid Retina\nKết nối: WiFi' WHERE slug = 'ipad-air';
UPDATE products SET specifications = 'Chip: A14 Bionic\nRAM: 4GB\nBộ nhớ: 64GB\nMàn hình: 10.9" Liquid Retina\nKết nối: WiFi' WHERE slug = 'ipad-10th-gen';
UPDATE products SET specifications = 'Chip: A13 Bionic\nRAM: 3GB\nBộ nhớ: 64GB\nMàn hình: 10.2" Retina\nKết nối: WiFi' WHERE slug = 'ipad-9th-gen';
UPDATE products SET specifications = 'Chip: A15 Bionic\nRAM: 4GB\nBộ nhớ: 64GB\nMàn hình: 8.3" Liquid Retina\nKết nối: WiFi' WHERE slug = 'ipad-mini';
UPDATE products SET specifications = 'Chip: Apple M4\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 11" Ultra Retina XDR\nKết nối: WiFi 6E' WHERE slug = 'ipad-pro-m4';

-- ===== Watch =====
UPDATE products SET specifications = 'Vi xử lý: S9 SiP\nMàn hình: 49mm LTPO OLED\nPin: 36 giờ\nKết nối: GPS + Cellular\nChống nước: 100m' WHERE slug = 'apple-watch-ultra-2';
UPDATE products SET specifications = 'Vi xử lý: S9 SiP\nMàn hình: 41mm LTPO OLED\nPin: 18 giờ\nKết nối: GPS\nChống nước: 50m' WHERE slug = 'apple-watch-series-9';
UPDATE products SET specifications = 'Vi xử lý: S8 SiP\nMàn hình: 45mm LTPO OLED\nPin: 18 giờ\nKết nối: GPS\nChống nước: 50m' WHERE slug = 'apple-watch-series-8';
UPDATE products SET specifications = 'Vi xử lý: S8 SiP\nMàn hình: 44mm OLED\nPin: 18 giờ\nKết nối: GPS\nChống nước: 50m' WHERE slug = 'apple-watch-se-2023';
UPDATE products SET specifications = 'Vi xử lý: S8 SiP\nMàn hình: 40mm OLED\nPin: 18 giờ\nKết nối: GPS\nChống nước: 50m' WHERE slug = 'apple-watch-se-2022';
UPDATE products SET specifications = 'Vi xử lý: S7 SiP\nMàn hình: 45mm LTPO OLED\nPin: 18 giờ\nKết nối: GPS\nChống nước: 50m' WHERE slug = 'apple-watch-series-7';

-- ===== AirPods =====
UPDATE products SET specifications = 'Chip: H2\nChống ồn chủ động: Có\nPin: 6 giờ (nghe nhạc)\nChống nước: IPX4' WHERE slug = 'airpods-pro-2';
UPDATE products SET specifications = 'Chip: H1\nChống ồn chủ động: Không\nPin: 6 giờ (nghe nhạc)\nChống nước: IPX4' WHERE slug = 'airpods-3rd-gen';
UPDATE products SET specifications = 'Chip: H1\nChống ồn chủ động: Không\nPin: 5 giờ (nghe nhạc)\nChống nước: Không' WHERE slug = 'airpods-2nd-gen';
UPDATE products SET specifications = 'Chip: H2\nChống ồn chủ động: Có\nPin: 5 giờ (nghe nhạc)\nChống nước: IP54' WHERE slug = 'airpods-4th-gen';

-- ===== Mac =====
UPDATE products SET specifications = 'Chip: Apple M2 Pro\nRAM: 16GB\nỔ cứng: 512GB SSD\nMàn hình: 14.2" Liquid Retina XDR' WHERE slug = 'macbook-pro-14-m2';
UPDATE products SET specifications = 'Chip: Apple M2 Pro\nRAM: 16GB\nỔ cứng: 1TB SSD\nMàn hình: 16.2" Liquid Retina XDR' WHERE slug = 'macbook-pro-16-m2';
UPDATE products SET specifications = 'Chip: Apple M2\nRAM: 8GB\nỔ cứng: 256GB SSD\nMàn hình: 13.6" Liquid Retina' WHERE slug = 'macbook-air-m2';
UPDATE products SET specifications = 'Chip: Apple M1\nRAM: 8GB\nỔ cứng: 256GB SSD\nMàn hình: 13.3" Retina' WHERE slug = 'macbook-air-m1';
UPDATE products SET specifications = 'Chip: Apple M1\nRAM: 8GB\nỔ cứng: 256GB SSD\nMàn hình: 24" 4.5K Retina' WHERE slug = 'imac-24-m1';
UPDATE products SET specifications = 'Chip: Apple M2\nRAM: 8GB\nỔ cứng: 256GB SSD\nCổng: Thunderbolt 4, USB-C' WHERE slug = 'mac-mini-m2';

-- ===== Laptop =====
UPDATE products SET specifications = 'CPU: Intel Core i5-1240P\nRAM: 16GB\nỔ cứng: 512GB SSD\nMàn hình: 13.4" FHD+' WHERE slug = 'dell-xps-13';
UPDATE products SET specifications = 'CPU: Intel Core i7-1255U\nRAM: 16GB\nỔ cứng: 512GB SSD\nMàn hình: 13.5" OLED' WHERE slug = 'hp-spectre-x360';
UPDATE products SET specifications = 'CPU: Intel Core i7-1270P\nRAM: 16GB\nỔ cứng: 512GB SSD\nMàn hình: 14" WUXGA' WHERE slug = 'lenovo-thinkpad-x1-carbon';
UPDATE products SET specifications = 'CPU: Intel Core i5-1240P\nRAM: 16GB\nỔ cứng: 512GB SSD\nMàn hình: 14" OLED' WHERE slug = 'asus-zenbook-14';
UPDATE products SET specifications = 'CPU: Intel Core i5-1240P\nRAM: 8GB\nỔ cứng: 512GB SSD\nMàn hình: 14" FHD' WHERE slug = 'acer-swift-3';
UPDATE products SET specifications = 'CPU: Intel Core Ultra 7\nRAM: 16GB\nỔ cứng: 1TB SSD\nMàn hình: 14" 2.8K OLED' WHERE slug = 'msi-prestige-14';

-- ===== Điện thoại =====
UPDATE products SET specifications = 'Chip: Snapdragon 8 Gen 3\nRAM: 12GB\nBộ nhớ: 256GB\nMàn hình: 6.36" AMOLED\nPin: 4610 mAh' WHERE slug = 'xiaomi-14';
UPDATE products SET specifications = 'Chip: Snapdragon 8 Gen 2\nRAM: 16GB\nBộ nhớ: 256GB\nMàn hình: 6.82" AMOLED\nPin: 5000 mAh' WHERE slug = 'oppo-find-x6-pro';
UPDATE products SET specifications = 'Chip: Snapdragon 8 Gen 2\nRAM: 12GB\nBộ nhớ: 256GB\nMàn hình: 6.78" AMOLED\nPin: 4870 mAh' WHERE slug = 'vivo-x90-pro';
UPDATE products SET specifications = 'Chip: Snapdragon 8+ Gen 1\nRAM: 12GB\nBộ nhớ: 256GB\nMàn hình: 6.74" AMOLED\nPin: 4600 mAh' WHERE slug = 'realme-gt-3';
UPDATE products SET specifications = 'Chip: Snapdragon 695\nRAM: 6GB\nBộ nhớ: 128GB\nMàn hình: 6.67" AMOLED\nPin: 4200 mAh' WHERE slug = 'nokia-x30';
UPDATE products SET specifications = 'Chip: Snapdragon 8 Gen 2\nRAM: 16GB\nBộ nhớ: 256GB\nMàn hình: 6.7" AMOLED\nPin: 5000 mAh' WHERE slug = 'oneplus-11';

-- ===== Samsung =====
UPDATE products SET specifications = 'Chip: Snapdragon 8 Gen 3\nRAM: 12GB\nBộ nhớ: 256GB\nMàn hình: 6.8" Dynamic AMOLED 2X\nPin: 5000 mAh' WHERE slug = 'samsung-galaxy-s24-ultra';
UPDATE products SET specifications = 'Chip: Exynos 2400\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 6.2" Dynamic AMOLED 2X\nPin: 4000 mAh' WHERE slug = 'samsung-galaxy-s24';
UPDATE products SET specifications = 'Chip: Snapdragon 8 Gen 2\nRAM: 12GB\nBộ nhớ: 512GB\nMàn hình: 7.6" Foldable Dynamic AMOLED\nPin: 4400 mAh' WHERE slug = 'samsung-galaxy-z-fold-5';
UPDATE products SET specifications = 'Chip: Snapdragon 8 Gen 2\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 6.7" Foldable Dynamic AMOLED\nPin: 3700 mAh' WHERE slug = 'samsung-galaxy-z-flip-5';
UPDATE products SET specifications = 'Chip: Exynos 1380\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 6.4" Super AMOLED\nPin: 5000 mAh' WHERE slug = 'samsung-galaxy-a54';
UPDATE products SET specifications = 'Chip: Snapdragon 8 Gen 2\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 6.1" Dynamic AMOLED 2X\nPin: 3900 mAh' WHERE slug = 'samsung-galaxy-s23';

-- ===== Âm thanh =====
UPDATE products SET specifications = 'Chống ồn chủ động: Có\nPin: 30 giờ\nKết nối: Bluetooth 5.2\nMic đàm thoại: 4 mic' WHERE slug = 'sony-wh-1000xm5';
UPDATE products SET specifications = 'Chống ồn chủ động: Có\nPin: 24 giờ\nKết nối: Bluetooth 5.1\nMic đàm thoại: 6 mic' WHERE slug = 'bose-quietcomfort-45';
UPDATE products SET specifications = 'Công suất: 30W\nPin: 12 giờ\nChống nước: IP67\nKết nối: Bluetooth 5.1' WHERE slug = 'jbl-flip-6';
UPDATE products SET specifications = 'Chống ồn chủ động: Có\nPin: 8 giờ (tai nghe) + 24 giờ (hộp sạc)\nChống nước: IPX4\nKết nối: Bluetooth 5.2' WHERE slug = 'sony-wf-1000xm4';
UPDATE products SET specifications = 'Công suất: 60W\nPin: 8 giờ\nChống nước: IPX7\nKết nối: Bluetooth 5.0' WHERE slug = 'harman-kardon-onyx-studio-7';
UPDATE products SET specifications = 'Chống ồn chủ động: Có\nPin: 17 giờ\nKết nối: Bluetooth 5.0\nMic đàm thoại: 4 mic' WHERE slug = 'sennheiser-momentum-3';

-- ===== Phụ kiện =====
UPDATE products SET specifications = 'Chuẩn: USB-C to USB-C\nCông suất: 100W\nChiều dài: 1.2m' WHERE slug = 'cap-sac-usb-c-anker';
UPDATE products SET specifications = 'Chất liệu: Nhựa cứng viền dẻo\nTương thích: iPhone 15 Pro Max\nChống sốc: Có' WHERE slug = 'op-lung-iphone-15-spigen';
UPDATE products SET specifications = 'Dung lượng: 10000mAh\nCông suất sạc: 22.5W\nCổng: USB-C, USB-A' WHERE slug = 'pin-du-phong-10000mah-xiaomi';
UPDATE products SET specifications = 'Độ cứng: 9H\nĐộ trong suốt: 99.9%\nTương thích: iPhone 15 Pro Max' WHERE slug = 'kinh-cuong-luc-iphone-15';
UPDATE products SET specifications = 'Kết nối: Bluetooth/USB Receiver\nPin: 70 ngày\nSố nút: 7 nút tùy chỉnh' WHERE slug = 'chuot-logitech-mx-master-3';
UPDATE products SET specifications = 'Switch: Gateron Blue/Brown\nKết nối: Bluetooth + Wired\nLayout: 87 phím (TKL)' WHERE slug = 'ban-phim-co-keychron-k8';

-- ===== Gia dụng & Đời sống =====
UPDATE products SET specifications = 'Diện tích lọc: 48m²\nCDM: 320 m³/h\nBộ lọc: HEPA H13' WHERE slug = 'may-loc-khong-khi-xiaomi';
UPDATE products SET specifications = 'Công suất: 65W\nDung tích bình nước: 4L\nChế độ: 3 tốc độ gió' WHERE slug = 'quat-dieu-hoa-kangaroo';
UPDATE products SET specifications = 'Công suất: 8W\nSố mức sáng: 5 mức\nCổng sạc: USB-C' WHERE slug = 'den-ban-led-philips';
UPDATE products SET specifications = 'Công suất hút: 120AW\nDung tích: 0.6L\nPin: 45 phút' WHERE slug = 'may-hut-bui-deerma';
UPDATE products SET specifications = 'Dung tích: 5.5L\nCông suất: 1400W\nChế độ nấu: 8 chế độ có sẵn' WHERE slug = 'noi-chien-khong-dau-locklock';
UPDATE products SET specifications = 'Công suất: 600W\nDung tích cối: 1.5L\nSố tốc độ: 2 tốc độ' WHERE slug = 'may-xay-sinh-to-philips';

-- ===== Hàng cũ =====
UPDATE products SET specifications = 'Chip: A15 Bionic\nRAM: 4GB\nBộ nhớ: 128GB\nTình trạng: 95% pin, không trầy xước' WHERE slug = 'iphone-13-cu';
UPDATE products SET specifications = 'Chip: A14 Bionic\nRAM: 6GB\nBộ nhớ: 128GB\nTình trạng: 90% pin, có trầy nhẹ' WHERE slug = 'iphone-12-pro-cu';
UPDATE products SET specifications = 'Chip: Exynos 2200\nRAM: 8GB\nBộ nhớ: 256GB\nTình trạng: 92% pin, đẹp 99%' WHERE slug = 'samsung-galaxy-s22-cu';
UPDATE products SET specifications = 'Chip: Apple M1\nRAM: 8GB\nỔ cứng: 256GB SSD\nTình trạng: 98% pin, như mới' WHERE slug = 'macbook-air-m1-cu';
UPDATE products SET specifications = 'Chip: Apple M2\nRAM: 8GB\nBộ nhớ: 128GB\nTình trạng: 97% pin, đẹp 99%' WHERE slug = 'ipad-pro-11-cu';
UPDATE products SET specifications = 'Vi xử lý: S7 SiP\nMàn hình: 45mm LTPO OLED\nTình trạng: Pin còn tốt, dây đeo mới' WHERE slug = 'apple-watch-series-7-cu';
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS admins;
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
);

INSERT INTO admins (username, password, email) 
VALUES ('admin3','$2y$10$.4Hx3g5okuKHiSfpDvSygu9yszY3wnJ5cHLa.GtevsAk0DPtL4Rf.', 'admin@example.com');

UPDATE categories SET slug = 'audio' WHERE slug = 'am-thanh';
UPDATE categories SET slug = 'accessories' WHERE slug = 'phu-kien';
UPDATE categories SET slug = 'home-lifestyle' WHERE slug = 'gia-dung-doi-song';
UPDATE categories SET slug = 'refurbished' WHERE slug = 'hang-cu';
