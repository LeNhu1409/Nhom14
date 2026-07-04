<?php
$conn = new mysqli(getenv('DB_HOST') ?: 'mysql', getenv('DB_USER') ?: 'root', getenv('DB_PASS') ?: 'root', getenv('DB_NAME') ?: 'nhom14_mobile');
$conn->set_charset("utf8mb4");

$specs = [
    'iphone-16-pro-max' => "Chip: A18 Pro\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 6.9 inch Super Retina XDR\nCamera: 48MP + 12MP + 12MP\nPin: 4685 mAh\nHệ điều hành: iOS 18",
    'iphone-15-pro-max' => "Chip: A17 Pro\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 6.7 inch\nCamera: 48MP + 12MP\nPin: 4422 mAh\nHệ điều hành: iOS 17",
    'iphone-15' => "Chip: A16 Bionic\nRAM: 6GB\nBộ nhớ: 128GB\nMàn hình: 6.1 inch\nCamera: 48MP + 12MP\nPin: 3877 mAh\nHệ điều hành: iOS 17",
    'iphone-14-pro' => "Chip: A15 Bionic\nRAM: 6GB\nBộ nhớ: 128GB\nMàn hình: 6.1 inch\nCamera: 48MP + 12MP\nPin: 3200 mAh\nHệ điều hành: iOS 16",
    'iphone-14' => "Chip: A15 Bionic\nRAM: 6GB\nBộ nhớ: 128GB\nMàn hình: 6.1 inch\nCamera: 12MP + 12MP\nPin: 3227 mAh\nHệ điều hành: iOS 16",
    'iphone-13' => "Chip: A15 Bionic\nRAM: 4GB\nBộ nhớ: 128GB\nMàn hình: 6.1 inch\nCamera: 12MP + 12MP\nPin: 3227 mAh\nHệ điều hành: iOS 15",
    'iphone-12' => "Chip: A13 Bionic\nRAM: 4GB\nBộ nhớ: 128GB\nMàn hình: 6.1 inch\nCamera: 12MP + 12MP\nPin: 2815 mAh\nHệ điều hành: iOS 13",
    'iphone-11' => "Chip: A13 Bionic\nRAM: 4GB\nBộ nhớ: 64GB\nMàn hình: 6.1 inch\nCamera: 12MP\nPin: 3110 mAh\nHệ điều hành: iOS 13",
    'iphone-xr' => "Chip: A12 Bionic\nRAM: 3GB\nBộ nhớ: 64GB\nMàn hình: 6.1 inch\nCamera: 12MP\nPin: 2942 mAh\nHệ điều hành: iOS 12",
    'iphone-xs-max' => "Chip: A12 Bionic\nRAM: 4GB\nBộ nhớ: 64GB\nMàn hình: 6.5 inch\nCamera: 12MP + 12MP\nPin: 3174 mAh\nHệ điều hành: iOS 12",
    'iphone-xs' => "Chip: A12 Bionic\nRAM: 4GB\nBộ nhớ: 64GB\nMàn hình: 5.8 inch\nCamera: 12MP + 12MP\nPin: 2658 mAh\nHệ điều hành: iOS 12",
    'iphone-16-e' => "Chip: A16 Bionic\nRAM: 6GB\nBộ nhớ: 128GB\nMàn hình: 6.1 inch\nCamera: 48MP + 12MP\nPin: 3279 mAh\nHệ điều hành: iOS 18",
    'ipad-pro-12-9' => "Chip: M2\nRAM: 8GB\nBộ nhớ: 128GB\nMàn hình: 12.9 inch Liquid Retina XDR\nCamera: 12MP\nPin: 10758 mAh",
    'ipad-pro-11' => "Chip: M2\nRAM: 8GB\nBộ nhớ: 128GB\nMàn hình: 11 inch Liquid Retina\nCamera: 12MP\nPin: 7538 mAh",
    'ipad-air' => "Chip: M1\nRAM: 8GB\nBộ nhớ: 64GB\nMàn hình: 10.9 inch Liquid Retina\nCamera: 12MP\nPin: 7606 mAh",
    'ipad-10th-gen' => "Chip: A14 Bionic\nRAM: 4GB\nBộ nhớ: 64GB\nMàn hình: 10.9 inch Liquid Retina\nCamera: 12MP\nPin: 7606 mAh",
    'ipad-9th-gen' => "Chip: A13 Bionic\nRAM: 3GB\nBộ nhớ: 64GB\nMàn hình: 10.2 inch Retina\nCamera: 8MP\nPin: 8557 mAh",
    'ipad-mini' => "Chip: A15 Bionic\nRAM: 4GB\nBộ nhớ: 64GB\nMàn hình: 8.3 inch Liquid Retina\nCamera: 12MP\nPin: 5124 mAh",
    'ipad-pro-m4' => "Chip: M4\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 11 inch Ultra Retina XDR\nCamera: 12MP\nPin: 7606 mAh",
    'apple-watch-ultra-2' => "Chip: S9\nBộ nhớ: 64GB\nMàn hình: 49mm Always-On Retina\nPin: 18 giờ\nHệ điều hành: watchOS 10",
    'apple-watch-series-9' => "Chip: S9\nBộ nhớ: 64GB\nMàn hình: 41mm Always-On Retina\nPin: 18 giờ\nHệ điều hành: watchOS 10",
    'apple-watch-series-8' => "Chip: S8\nBộ nhớ: 32GB\nMàn hình: 45mm Always-On Retina\nPin: 18 giờ\nHệ điều hành: watchOS 9",
    'apple-watch-se-2023' => "Chip: S8\nBộ nhớ: 32GB\nMàn hình: 44mm Retina\nPin: 18 giờ\nHệ điều hành: watchOS 10",
    'apple-watch-se-2022' => "Chip: S8\nBộ nhớ: 32GB\nMàn hình: 40mm Retina\nPin: 18 giờ\nHệ điều hành: watchOS 9",
    'apple-watch-series-7' => "Chip: S7\nBộ nhớ: 32GB\nMàn hình: 45mm Always-On Retina\nPin: 18 giờ\nHệ điều hành: watchOS 8",
    'airpods-pro-2' => "Driver: 11mm\nChống ồn: ANC H2\nKết nối: Bluetooth 5.3\nPin tai nghe: 6 giờ\nPin hộp sạc: 30 giờ\nChống nước: IP54",
    'airpods-3rd-gen' => "Driver: 11mm\nKết nối: Bluetooth 5.0\nPin tai nghe: 6 giờ\nPin hộp sạc: 30 giờ\nChống nước: IPX4",
    'airpods-2nd-gen' => "Driver: 11mm\nKết nối: Bluetooth 5.0\nPin tai nghe: 5 giờ\nPin hộp sạc: 24 giờ",
    'airpods-4th-gen' => "Driver: 11mm\nChống ồn: ANC\nKết nối: Bluetooth 5.3\nPin tai nghe: 5 giờ\nPin hộp sạc: 30 giờ\nChống nước: IP54",
    'macbook-pro-14-m2' => "Chip: M2 Pro\nRAM: 16GB\nBộ nhớ: 512GB SSD\nMàn hình: 14.2 inch Liquid Retina XDR\nPin: 18 giờ",
    'macbook-pro-16-m2' => "Chip: M2 Pro\nRAM: 16GB\nBộ nhớ: 512GB SSD\nMàn hình: 16.2 inch Liquid Retina XDR\nPin: 22 giờ",
    'macbook-air-m2' => "Chip: M2\nRAM: 8GB\nBộ nhớ: 256GB SSD\nMàn hình: 13.6 inch Liquid Retina\nPin: 18 giờ",
    'macbook-air-m1' => "Chip: M1\nRAM: 8GB\nBộ nhớ: 256GB SSD\nMàn hình: 13.3 inch Retina\nPin: 18 giờ",
    'imac-24-m1' => "Chip: M1\nRAM: 8GB\nBộ nhớ: 256GB SSD\nMàn hình: 24 inch 4.5K Retina\nCamera: 1080p FaceTime HD",
    'mac-mini-m2' => "Chip: M2\nRAM: 8GB\nBộ nhớ: 256GB SSD\nKết nối: Wi-Fi 6E, Bluetooth 5.3",
    'dell-xps-13' => "CPU: Intel Core i7-1250U\nRAM: 16GB\nBộ nhớ: 512GB SSD\nMàn hình: 13.4 inch FHD+\nPin: 52Whr",
    'hp-spectre-x360' => "CPU: Intel Core i7-1255U\nRAM: 16GB\nBộ nhớ: 512GB SSD\nMàn hình: 13.5 inch 3K2K OLED\nPin: 66Whr",
    'lenovo-thinkpad-x1-carbon' => "CPU: Intel Core i7-1260P\nRAM: 16GB\nBộ nhớ: 512GB SSD\nMàn hình: 14 inch 2.8K OLED\nPin: 57Whr",
    'asus-zenbook-14' => "CPU: Intel Core i5-1240P\nRAM: 16GB\nBộ nhớ: 512GB SSD\nMàn hình: 14 inch 2.8K OLED\nPin: 75Whr",
    'acer-swift-3' => "CPU: Intel Core i5-1235U\nRAM: 8GB\nBộ nhớ: 256GB SSD\nMàn hình: 14 inch FHD IPS\nPin: 56Whr",
    'msi-prestige-14' => "CPU: Intel Core Ultra 7\nRAM: 32GB\nBộ nhớ: 1TB SSD\nMàn hình: 14 inch 2.8K OLED\nPin: 72Whr",
    'xiaomi-14' => "CPU: Snapdragon 8 Gen 3\nRAM: 12GB\nBộ nhớ: 256GB\nMàn hình: 6.36 inch AMOLED 120Hz\nCamera: 50MP + 50MP + 32MP\nPin: 4610 mAh",
    'oppo-find-x6-pro' => "CPU: Snapdragon 8 Gen 2\nRAM: 12GB\nBộ nhớ: 256GB\nMàn hình: 6.82 inch AMOLED 120Hz\nCamera: 50MP + 50MP + 64MP\nPin: 5000 mAh",
    'vivo-x90-pro' => "CPU: Dimensity 9200\nRAM: 12GB\nBộ nhớ: 256GB\nMàn hình: 6.78 inch AMOLED 120Hz\nCamera: 50MP + 12MP + 12MP\nPin: 4870 mAh",
    'realme-gt-3' => "CPU: Snapdragon 8+ Gen 1\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 6.74 inch AMOLED 144Hz\nCamera: 50MP + 50MP + 50MP\nPin: 5000 mAh",
    'nokia-x30' => "CPU: Snapdragon 695\nRAM: 6GB\nBộ nhớ: 128GB\nMàn hình: 6.43 inch AMOLED 90Hz\nCamera: 50MP + 13MP + 2MP\nPin: 4200 mAh",
    'oneplus-11' => "CPU: Snapdragon 8 Gen 2\nRAM: 16GB\nBộ nhớ: 256GB\nMàn hình: 6.7 inch AMOLED 120Hz\nCamera: 50MP + 48MP + 32MP\nPin: 4800 mAh",
    'samsung-galaxy-s24-ultra' => "CPU: Snapdragon 8 Gen 3\nRAM: 12GB\nBộ nhớ: 256GB\nMàn hình: 6.8 inch Dynamic AMOLED 120Hz\nCamera: 200MP + 10MP + 50MP\nPin: 5000 mAh",
    'samsung-galaxy-s24' => "CPU: Snapdragon 8 Gen 3\nRAM: 8GB\nBộ nhớ: 128GB\nMàn hình: 6.2 inch Dynamic AMOLED 120Hz\nCamera: 50MP + 10MP + 12MP\nPin: 4000 mAh",
    'samsung-galaxy-z-fold-5' => "CPU: Snapdragon 8 Gen 2\nRAM: 12GB\nBộ nhớ: 256GB\nMàn hình: 7.6 inch Foldable AMOLED 120Hz\nCamera: 50MP + 10MP + 12MP\nPin: 4400 mAh",
    'samsung-galaxy-z-flip-5' => "CPU: Snapdragon 8 Gen 2\nRAM: 8GB\nBộ nhớ: 256GB\nMàn hình: 6.7 inch Foldable AMOLED 120Hz\nCamera: 12MP + 10MP + 12MP\nPin: 3700 mAh",
    'samsung-galaxy-a54' => "CPU: Exynos 1380\nRAM: 8GB\nBộ nhớ: 128GB\nMàn hình: 6.4 inch Super AMOLED 120Hz\nCamera: 50MP + 12MP + 5MP\nPin: 5000 mAh",
    'samsung-galaxy-s23' => "CPU: Snapdragon 8 Gen 2\nRAM: 8GB\nBộ nhớ: 128GB\nMàn hình: 6.1 inch Dynamic AMOLED 120Hz\nCamera: 50MP + 10MP + 12MP\nPin: 3900 mAh",
    'sony-wh-1000xm5' => "Driver: 40mm\nChống ồn: ANC\nKết nối: Bluetooth 5.2\nPin: 30 giờ\nChống nước: IPX4\nCodec: LDAC, AAC",
    'bose-quietcomfort-45' => "Driver: 40mm\nChống ồn: ANC\nKết nối: Bluetooth 5.1\nPin: 24 giờ\nChống nước: IPX4",
    'jbl-flip-6' => "Driver: 50mm\nChống nước: IP67\nKết nối: Bluetooth 5.1\nPin: 12 giờ\nCông suất: 30W",
    'sony-wf-1000xm4' => "Driver: 6mm\nChống ồn: ANC\nKết nối: Bluetooth 5.2\nPin tai nghe: 8 giờ\nPin hộp sạc: 24 giờ\nCodec: LDAC, AAC",
    'harman-kardon-onyx-studio-7' => "Công suất: 50W\nKết nối: Bluetooth 4.2\nPin: 8 giờ\nChống nước: IPX4",
    'sennheiser-momentum-3' => "Driver: 42mm\nChống ồn: ANC\nKết nối: Bluetooth 5.2\nPin: 35 giờ\nCodec: aptX, AAC",
];

$stmt = $conn->prepare("UPDATE products SET specifications = ? WHERE slug = ?");
$count = 0;
foreach ($specs as $slug => $spec) {
    $stmt->bind_param("ss", $spec, $slug);
    $stmt->execute();
    $count++;
}
$stmt->close();
$conn->close();

echo "Done! Updated $count products.";
?>