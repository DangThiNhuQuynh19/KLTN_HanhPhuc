<?php
session_start();
include("Controllers/chosobenhandientu.php");
$p = new cHoSoBenhAnDienTu();
$mahoso = $_GET['id'];
$tbl = $p->getChiTietHSBADTOfTK( $mahoso);

if ($tbl === -1) {
    echo "<div class='alert alert-danger'>Lỗi kết nối CSDL.</div>";
    exit;
} elseif ($tbl === 0) {
    echo "<div class='alert alert-info'>Không tìm thấy hồ sơ bệnh án.</div>";
    exit;
}
$firstRow = $tbl->fetch_assoc();
$rows = [$firstRow];

while ($row = $tbl->fetch_assoc()) {
    $rows[] = $row;
}
$prescriptions = $p->getDonThuocByIDHS($mahoso);
$tests = $p->getXetNghiemByIDHS($mahoso);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết hồ sơ bệnh án</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 80px; }
        .card {
            max-width: 900px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .section-title {
            font-size: 24px;
            font-weight: bold;
            color: #3c1561;
            margin-bottom: 20px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }
        .info-group {
            margin-bottom: 15px;
        }
        .info-group label {
            font-weight: bold;
            color: #333;
        }
        .back-btn {
            margin-top: 30px;
        }
        .doctor-photo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #3c1561;
        }
        .prescription-table th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h2 class="text-center text-primary mb-4">📋 Chi tiết hồ sơ bệnh án</h2>

        <div class="section-title">🧑‍⚕️ Thông tin bác sĩ phụ trách</div>
        <div class="row align-items-center mb-4">
            <div class="col-md-3 text-center">
                <img src="Assets/img/<?= htmlspecialchars($firstRow['imgbs']) ?>" alt="Bác sĩ" class="doctor-photo">
            </div>
            <div class="col-md-9">
                <p><strong>Họ tên:</strong> <?= htmlspecialchars($firstRow['capbac']) ?> - <?= htmlspecialchars($firstRow['hoten']) ?></p>
                <p><strong>Chuyên khoa:</strong> <?= htmlspecialchars($firstRow['tenchuyenkhoa']) ?></p>
                <p><strong>SDT:</strong> <?= htmlspecialchars($firstRow['sdt']) ?></p>
            </div>
        </div>

        <div class="section-title">👤 Thông tin bệnh nhân</div>
        <div class="row">
            <div class="col-md-6 info-group">
                <label>Họ tên:</label> <?= htmlspecialchars($firstRow['hotenbenhnhan']) ?><br>
                <label>Giới tính:</label> <?= htmlspecialchars($firstRow['gioitinh']) ?><br>
                <label>Ngày sinh:</label> <?= htmlspecialchars($firstRow['ngaysinh']) ?><br>
                <label>Nghề nghiệp:</label> <?= htmlspecialchars($firstRow['nghenghiep']) ?><br>
                <label>CCCD:</label> <?= htmlspecialchars($firstRow['cccdbenhnhan']) ?><br>
                <label>Quan hệ:</label> <?= htmlspecialchars($firstRow['quanhe']) ?><br>
                <label>Tiền sử bệnh tật của gia đình:</label> <?= htmlspecialchars($firstRow['tiensubenhtatcuagiadinh']) ?><br>   
                <label>Tiền sử bệnh tật của bản thân:</label> <?= htmlspecialchars($firstRow['tiensubenhtatcuabenhnhan']) ?><br>               
            </div>
            <div class="col-md-6 info-group">
                <label>SĐT:</label> <?= htmlspecialchars($firstRow['sdtbenhnhan']) ?><br>
                <label>Email:</label> <?= htmlspecialchars($firstRow['email']) ?><br>
                <label>Dân tộc:</label> <?= htmlspecialchars($firstRow['dantoc']) ?><br>
                <label>Địa chỉ:</label> <?= htmlspecialchars($firstRow['sonha']). ', ' .
                                htmlspecialchars($firstRow['xa/phuong']). ', ' .
                                htmlspecialchars($firstRow['quan/huyen']). ', ' . 
                                htmlspecialchars($firstRow['tinh/thanhpho']) ?><br>
                <label>Nhóm máu:</label> <?= htmlspecialchars($firstRow['nhommau']) ?><br>   
                
            </div>
        </div>

        <div class="section-title">📅 Thông tin khám bệnh</div>
        <div class="info-group">
            <label>Ngày lập hồ sơ:</label> <?= htmlspecialchars($firstRow['ngaytao']) ?>
        </div>
        <div class="info-group">
            <label>Triệu chứng ban đầu:</label>
            <div class="border rounded p-2 bg-light"><?= nl2br(htmlspecialchars($firstRow['trieuchungbandau'])) ?></div>
        </div>
        <div class="info-group">
            <label>Chẩn đoán:</label>
            <div class="border rounded p-2 bg-light"><?= nl2br(htmlspecialchars($firstRow['chandoan'])) ?></div>
        </div>
        <div class="info-group">
            <label>Hướng điều trị:</label>
            <div class="border rounded p-2 bg-light"><?= nl2br(htmlspecialchars($firstRow['huongdieutri'])) ?></div>
        </div>
        <div class="info-group">
            <label>Kết luận:</label>
            <div class="border rounded p-2 bg-light"><?= nl2br(htmlspecialchars($firstRow['ketluan'])) ?></div>
        </div>


        <!-- Hiển thị Đơn thuốc -->
<div class="section-title">💊 Đơn thuốc</div>
<?php if ($prescriptions === -1) : ?>
    <div class="alert alert-danger">Lỗi kết nối cơ sở dữ liệu để lấy đơn thuốc.</div>
<?php elseif ($prescriptions === 0) : ?>
    <div class="alert alert-info">Không tìm thấy đơn thuốc cho hồ sơ này.</div>
<?php else : ?>
    <div class="info-group">
        <label>Ngày tạo đơn thuốc:</label> <?= htmlspecialchars($firstRow['ngaytaodonthuoc']) ?>
    </div>
    <div class="info-group">
        <label>Ghi chú đơn thuốc:</label> <?= htmlspecialchars($firstRow['ghichudonthuoc']) ?>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered prescription-table">
            <thead>
                <tr>
                    <th>Tên thuốc</th>
                    <th>Mô tả</th>
                    <th>Đơn vị</th>
                    <th>Số ngày uống</th>
                    <th>Liều dùng</th>
                    <th>Cách dùng</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($prescriptions as $prescription): ?>
                <tr>
                    <td><?= htmlspecialchars($prescription['tenthuoc']) ?></td>
                    <td><?= htmlspecialchars($prescription['mota']) ?></td>
                    <td><?= htmlspecialchars($prescription['donvi']) ?></td>
                    <td><?= htmlspecialchars($prescription['songayuong']) ?></td>
                    <td><?= htmlspecialchars($prescription['lieudung']) ?></td>
                    <td><?= htmlspecialchars($prescription['thoigianuong']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Hiển thị Xét nghiệm -->
<div class="section-title">🧪 Lịch xét nghiệm & Kết quả</div>
<?php if ($tests === -1) : ?>
    <div class="alert alert-danger">Lỗi kết nối cơ sở dữ liệu để lấy xét nghiệm.</div>
<?php elseif ($tests === 0) : ?>
    <div class="alert alert-info">Không tìm thấy xét nghiệm cho hồ sơ này.</div>
<?php else : ?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Loại xét nghiệm</th>
                    <th>Ngày xét nghiệm</th>
                    <th>Khung giờ</th>
                    <th>Trạng thái lịch xét nghiệm</th>
                    <th>Tên chỉ số xét nghiệm</th>
                    <th>Giá trị kết quả</th>
                    <th>Đơn vị</th>
                    <th>Nhận xét</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tests as $test): ?>
                    <tr>
                        <td><?= htmlspecialchars($test['tenloaixetnghiem']) ?></td>
                        <td><?= htmlspecialchars($test['ngayhen']) ?></td>
                        <td><?= htmlspecialchars($test['giobatdau']) ?> - <?= htmlspecialchars($test['gioketthuc']) ?></td>
                        <td><?= htmlspecialchars($test['trangthailichxetnghiem']) ?></td>
                        <td><?= htmlspecialchars($test['tenchisoxetnghiem']) ?></td>
                        <td><?= htmlspecialchars($test['giatriketqua']) ?></td>
                        <td><?= htmlspecialchars($test['donvikq']) ?></td>
                        <td><?= htmlspecialchars($test['nhanxet']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

        <a href="index.php?action=hosobenhandientu" class="btn btn-outline-primary back-btn">← Quay lại hồ sơ</a>
    </div>
</div>
</body>
</html>
