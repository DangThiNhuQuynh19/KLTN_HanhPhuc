<?php
include_once("Controllers/cbacsi.php");
include_once("Controllers/cchuyenkhoa.php");

$cBacSi = new cBacSi();
$cChuyenKhoa = new cChuyenKhoa();

// Lấy danh sách chuyên khoa
$dsKhoa = $cChuyenKhoa->getAllChuyenKhoa();

// Kiểm tra các điều kiện lọc
if (!empty($_GET['name']) && !empty($_GET['khoa'])) {
    // Nếu có cả tên và khoa
    $ds = $cBacSi->getBacSiByTenAndKhoa(trim($_GET['name']), $_GET['khoa']);
} elseif (!empty($_GET['name'])) {
    // Chỉ tìm theo tên
    $ds = $cBacSi->getBacSiByName(trim($_GET['name']));
} elseif (!empty($_GET['khoa'])) {
    // Chỉ lọc theo khoa
    $ds = $cBacSi->getBacSiByKhoa($_GET['khoa']);
} else {
    // Không lọc gì cả
    $ds = $cBacSi->getAllBacSi();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách bác sĩ</title>
    <style>
        body{
            margin-top:100px;
        }
        .textsearch{
            border-radius: 15px; 
            width:220px;
            height:25px; 
            padding-left: 5px;
            border-color:rgb(60, 21, 97);
        }
        .textsearch::placeholder {
            color:rgb(60, 21, 97);
            opacity: 1; 
        }

        .btnsearch{
            width: 80px;
            height: 30px;
            border-radius: 10px; 
            color: rgb(60, 21, 97);
            font-size:14px;
        }
        .search{
            padding-left: 40%;
        }
        body {
            font-family: Arial, sans-serif;
            background: white;
        }
        h1 {
            text-align: center;
            color: #3c1561;
            margin-top: 30px;
        }

        .doctor-card {
            display: flex;
            gap: 25px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin: 30px auto;
            max-width: 1100px;
            align-items: flex-start;
        }

        .doctor-img img {
            width: 180px;
            border-radius: 10px;
            border: 1px solid #ddd;
            object-fit: cover;
        }

        .doctor-info {
            flex: 1;
        }

        .doctor-name {
            font-size: 22px;
            font-weight: bold;
            color: #222;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .doctor-position,
        .doctor-hospital {
            font-style: italic;
            color: #666;
            margin-bottom: 6px;
        }

        .doctor-desc {
            margin: 10px 0 20px;
            color: #444;
            line-height: 1.6;
        }
        .doctor-buttons {
            text-align: right; /* Align the button to the right */
        }

        .doctor-buttons a {
            text-decoration: none;
            padding: 10px 18px;
            margin-right: 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            background-color: #3c1561; /* Set the button color to purple */
            color: #fff; /* White text color */
        }

        .btn-primary {
            background-color: #1a237e;
            color: #fff;
        }

        .btn-secondary {
            background-color: #0288d1;
            color: #fff;
        }

        @media (max-width: 768px) {
            .doctor-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .doctor-img img {
                margin-bottom: 15px;
            }
            .doctor-buttons a {
                display: inline-block;
                margin: 10px 5px 0;
            }
        }
        .search-forms{
            padding-left: 30%;
        }
    </style>
</head>
<body>

<div class="search-forms">
    <!-- Form tìm kiếm bác sĩ và lọc theo khoa -->
    <form method="GET" action="index.php">
        <input type="hidden" name="action" value="bacsi">
        <input class="textsearch" type="text" name="name" placeholder=" Nhập tên bác sĩ..." 
            value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>">
        <select name="khoa" class="textsearch">
            <option value="">-- Chọn chuyên khoa --</option>
            <?php
                if ($dsKhoa && $dsKhoa->num_rows > 0) {
                    while ($row = $dsKhoa->fetch_assoc()) {
                        $selected = (isset($_GET['khoa']) && $row['machuyenkhoa'] == $_GET['khoa']) ? "selected" : "";
                        echo "<option value='{$row['machuyenkhoa']}' $selected>".htmlspecialchars($row['tenchuyenkhoa'])."</option>";
                    }
                }
            ?>
        </select>
        <button class="btnsearch" type="submit">Tìm kiếm</button>
    </form>
</div>

<h1>Danh sách bác sĩ</h1>

<?php 
    if (is_int($ds) && $ds == -1) {
        echo "<p style='text-align:center; color:red;'>Lỗi kết nối dữ liệu.</p>";
    } elseif (is_int($ds) && $ds == 0) {
        echo "<p style='text-align:center;'>Không có bác sĩ nào.</p>";
    } else {
        while ($row = $ds->fetch_assoc()) {
?>
    <div class="doctor-card">
        <div class="doctor-img">
            <img src="Assets/img/<?php echo htmlspecialchars($row['imgbs']); ?>" alt="Ảnh bác sĩ">
        </div>
        <div class="doctor-info">
            <h2 class="doctor-name">
                <?php echo htmlspecialchars($row['capbac']) . ' ' . htmlspecialchars($row['hoten']); ?>
            </h2>
            <p class="doctor-position"><?php echo htmlspecialchars($row['tenchuyenkhoa']); ?></p>
            <p class="doctor-desc">
                <?php
                    $desc = strip_tags($row['motabs']);
                    echo strlen($desc) > 300 ? substr($desc, 0, 300) . '...' : $desc;
                ?>
            </p>
            <div class="doctor-buttons" >
                <a href="?action=chitietbacsi&id=<?php echo $row['mabacsi']; ?>" class="btn-secondary">XEM CHI TIẾT</a>
            </div>
        </div>
    </div>
<?php
        }
    }
?>
</body>
</html>
