<?php
include_once("Controllers/cchuyengia.php");
include_once("Controllers/clichkham.php");

// Kiểm tra id chuyên gia
if (!isset($_GET['id'])) {
    echo "Không tìm thấy chuyên gia.";
    exit;
}
$machuyengia = $_GET['id'];
$ngay = isset($_GET['ngay']) ? $_GET['ngay'] : date('Y-m-d'); // Nếu chưa chọn ngày thì lấy ngày hôm nay
// Lấy thông tin chuyên gia
$cChuyenGia = new cChuyenGia();
$chuyengia = $cChuyenGia->getChuyenGiaById($machuyengia);

if (!$chuyengia || $chuyengia->num_rows === 0) {
    echo "Không tìm thấy thông tin chuyên gia.";
    exit;
}
$row = $chuyengia->fetch_assoc();
// Đặt múi giờ chính xác
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Lấy giờ hiện tại
$gioHienTai = date('H:i:s'); // Giờ hiện tại dưới định dạng H:i:s
$ngayHienTai = date('Y-m-d'); // Ngày hiện tại

$cLichKham = new cLichKham();
$lichkham = $cLichKham->getLichKhamOfChuyenGiaByNgay($ngay, $machuyengia, $gioHienTai);

?>

<style>
.container {
    max-width: 1000px;
    margin: auto;
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-top: 100px;
}
.doctor-header {
    display: flex;
    gap: 30px;
    align-items: flex-start;
    margin-bottom: 20px;
}
.doctor-header img {
    width: 220px;
    border-radius: 12px;
    border: 1px solid #ccc;
    object-fit: cover;
}
.doctor-info {
    flex: 1;
}
.doctor-info h2 {
    margin-top: 0;
    color: #3c1561;
    font-size: 26px;
}
.doctor-info p {
    margin: 8px 0;
    color: #444;
    line-height: 1.6;
}
.date-picker {
    margin-top: 20px;
}
.date-picker input[type="date"], .date-picker input[type="time"] {
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
.shift-list {
    margin-top: 20px;
}
.shift-list h3 {
    margin-bottom: 10px;
    color: #3c1561;
}
.shift-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.shift-buttons a {
    display: inline-block;
    padding: 10px 20px;
    background: #3c1561;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: background 0.3s;
}
.shift-buttons a:hover {
    background: #6b409c;
}
.shift-group{
    margin-top: 20px;
}
.shift-group h4 {
    font-size: 18px;
    font-weight: normal; /* chữ đen bình thường */
    color: #000; /* màu đen */
    margin-bottom: 15px; /* cách xa khung giờ */
}
@media (max-width: 768px) {
    .doctor-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .doctor-info {
        text-align: center;
    }
}
#toggle-mota-button {
    background: none;
    border: none;
    color: #7b1fa2;
    font-size: 16px;
    font-weight: normal;
    cursor: pointer;
    text-decoration: underline;
    padding: 5px 10px;
    transition: color 0.3s ease;
}
#toggle-mota-button:hover {
    color: #4a148c;
    text-decoration: underline;
}
</style>

<div class="container">
    <!-- Thông tin chuyên gia -->
    <div class="doctor-header">
        <img src="Assets/img/<?php echo htmlspecialchars($row['imgcg']); ?>" alt="Ảnh chuyên gia">
        <div class="doctor-info">
            <h2><?php echo htmlspecialchars($row['capbac']) . ' ' . htmlspecialchars($row['hoten']); ?></h2>
            <p><strong>Lĩnh vực:</strong> <?php echo htmlspecialchars($row['tenlinhvuc']); ?></p>
            <p><strong>Thông tin mô tả:</strong></p>

            <!-- Nội dung mô tả thu gọn -->
            <div id="short-description">
                <?php
                    $motangan = mb_substr(strip_tags($row['motacg']), 0, 800); // Lấy 200 ký tự đầu
                    echo nl2br(htmlspecialchars($motangan)) . '...';
                ?>
            </div>

            <!-- Nội dung mô tả đầy đủ -->
            <div id="full-description" style="display: none;">
                <?php
                    echo nl2br(htmlspecialchars($row['motacg']));
                    echo '<br><br>';
                    echo nl2br(htmlspecialchars($row['gioithieucg']));
                ?>
            </div>

            <!-- Nút xem thêm -->
            <button id="toggle-mota-button">Xem thêm</button>
        </div>

    </div>

    <!-- Form chọn ngày và giờ -->
    <form method="get" id="form-ngay" class="date-picker">
        <input type="hidden" name="action" value="chitietchuyengia">
        <input type="hidden" name="id" value="<?php echo $machuyengia; ?>">
        <input type="hidden" name="giohientai" id="giohientai" value="">

        <label for="ngay">Chọn ngày khám:</label>
        <input type="date" name="ngay" id="ngay" value="<?php echo $ngay; ?>" min="<?php echo date('Y-m-d'); ?>" onchange="updateTimeAndSubmit();">
    </form>

    <script>
        function updateTimeAndSubmit() {
            var now = new Date();
            var gio = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0') + ':00';
            document.getElementById('giohientai').value = gio;
            document.getElementById('form-ngay').submit();
        }
    </script>

<div class="shift-list">
    <h3>Danh sách ca làm việc:</h3>
    <?php
    if ($lichkham === false || $lichkham->num_rows === 0) {
        echo "<p>Không có ca làm trong ngày này.</p>";
    } else {
        $caOnline = [];
        $caOffline = [];

        while ($rowCa = $lichkham->fetch_assoc()) {
            $macalamviec = $rowCa['macalamviec'];
            $giobatdau = date('H:i', strtotime($rowCa['giobatdau']));
            $gioketthuc = date('H:i', strtotime($rowCa['gioketthuc']));
            $hinhthuc = $rowCa['hinhthuclamviec']; // 0 = offline, 1 = online

            $link = "";
            if ($ngay == $ngayHienTai) {
                if ($giobatdau >= $gioHienTai) {
                    $link = '<a href="index.php?action=datlichkham&idcg=' . $machuyengia . '&ngay=' . $ngay . '&ca=' . $macalamviec . '">' . $giobatdau . ' - ' . $gioketthuc . '</a>';
                } else {
                    $link = "<p>Ca này đã qua.</p>";
                }
            } else {
                $link = '<a href="index.php?action=datlichkham&idcg=' . $machuyengia . '&ngay=' . $ngay . '&ca=' . $macalamviec . '">' . $giobatdau . ' - ' . $gioketthuc . '</a>';
            }

            // Phân loại
            if ($hinhthuc == "online") {
                $caOnline[] = $link;
            } else {
                $caOffline[] = $link;
            }
        }

        // Hiển thị Online
        echo "<div class='shift-group'>";
        echo "<h4>Khám Online</h4>";
        echo '<div class="shift-buttons">';
        if (empty($caOnline)) {
            echo "<p>Không có ca online.</p>";
        } else {
            foreach ($caOnline as $ca) {
                echo $ca;
            }
        }
        echo "</div></div>";

        // Hiển thị Offline
        echo "<div class='shift-group'>";
        echo "<h4>Khám tại Bệnh viện</h4>";
        echo '<div class="shift-buttons">';
        if (empty($caOffline)) {
            echo "<p>Không có ca offline.</p>";
        } else {
            foreach ($caOffline as $ca) {
                echo $ca;
            }
        }
        echo "</div></div>";
    }
    ?>
</div>

</div>
</div>
<script>
    const toggleButton = document.getElementById('toggle-mota-button');
    const shortDesc = document.getElementById('short-description');
    const fullDesc = document.getElementById('full-description');

    toggleButton.addEventListener('click', function() {
        if (fullDesc.style.display === "none") {
            fullDesc.style.display = "block";
            shortDesc.style.display = "none";
            toggleButton.textContent = "Thu gọn";
        } else {
            fullDesc.style.display = "none";
            shortDesc.style.display = "block";
            toggleButton.textContent = "Xem thêm";
        }
    });
</script>
