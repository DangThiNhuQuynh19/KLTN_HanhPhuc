<?php
// Dùng __DIR__ để include chính xác
include_once("Controllers/cchuyenkhoa.php");

// Gọi controller để lấy dữ liệu
$c = new cChuyenKhoa();
$ds = $c->getAllChuyenKhoa();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách chuyên khoa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: white;
            margin-top:100px;
        }
        h1 {
            text-align: center;
            color: #3c1561;
            margin-top: 30px;
        }
        .chuyen-khoa-container {
            max-width: 1100px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            padding: 0 20px;
        }
        .khoa {
            padding-top: 5px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .khoa:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(60, 21, 97, 0.2);
        }
        .khoa img {
            width: 150px;
            margin-left: 30%;
            object-fit: cover;
            padding-top: 10px;
        }
        .khoa-content {
            padding: 15px;
        }
        .khoa-content h3 {
            color: #3c1561;
            margin-bottom: 5px;
            font-size: 20px;
            text-align: center;
        }
        .xem-them {
            display: block;
            text-align: center;
            padding-top:10px;
            color: #3c1561;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s;
        }
        .xem-them:hover {
            color: #6f42c1;
        }
    </style>
</head>
<body>

    <h1>Danh sách chuyên khoa</h1>

    <div class="chuyen-khoa-container">
        <?php 
        if (is_int($ds) && $ds == -1) {
            echo "<p style='text-align:center; color:red;'>Lỗi kết nối dữ liệu.</p>";
        } elseif (is_int($ds) && $ds == 0) {
            echo "<p style='text-align:center;'>Không có chuyên khoa nào.</p>";
        } else {
            while ($row = $ds->fetch_assoc()) {
                echo '<div class="khoa">';
                echo '<img src="Assets/img/' . htmlspecialchars($row['img']) . '" alt="Ảnh chuyên khoa">';
                echo '<div class="khoa-content">';
                echo '<h3>' . htmlspecialchars($row['tenchuyenkhoa']) . '</h3>';
                echo '<a href="?action=chitietchuyenkhoa&id=' . htmlspecialchars($row['machuyenkhoa']) . '" class="xem-them">Xem thêm</a>'; 
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>

</body>
</html>
