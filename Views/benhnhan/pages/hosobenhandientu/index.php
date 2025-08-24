<?php
session_start();
include("Controllers/chosobenhandientu.php");
$p = new cHoSoBenhAnDienTu();

if (!isset($_SESSION['user']) || empty($_SESSION['user']['tentk'])) {
    echo "<div class='alert alert-warning'>Bạn chưa đăng nhập hoặc thiếu thông tin tài khoản.</div>";
    exit;
}

$tentk = $_SESSION['user']['tentk'];
$tbl = $p->getAllHSBADTOfTK($tentk);

if ($tbl === -1) {
    echo "<div class='alert alert-danger'>Lỗi kết nối cơ sở dữ liệu!</div>";
    exit;
} elseif ($tbl === 0) {
    echo "<div class='alert alert-info'>Không có hồ sơ bệnh án nào!</div>";
    exit;
} else {
    $patients = [];
    while ($row = $tbl->fetch_assoc()) {
        $mabenhnhan = $row['mabenhnhan'];
        if (!isset($patients[$mabenhnhan])) {
            $patients[$mabenhnhan] = [
                'hotenbenhnhan' => $row['hotenbenhnhan'],
                'gioitinh' => $row['gioitinh'],
                'ngaysinh' => $row['ngaysinh'],
                'nghenghiep' => $row['nghenghiep'],
                'dantoc' => $row['dantoc'],
                'email' => $row['email'],
                'quanhe' => $row['quanhe'],
                'sdtbenhnhan' => $row['sdtbenhnhan'],
                'tinh/thanhpho' => $row['tinh/thanhpho'],
                'quan/huyen' => $row['quan/huyen'],
                'xa/phuong' => $row['xa/phuong'],
                'sonha' => $row['sonha'],
                'hoso' => []
            ];
        }
        $patients[$mabenhnhan]['hoso'][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ bệnh án</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { padding-top: 100px; }
        .bg-primary { background-color: #3c1561 !important; }
        .text-primary { color: #3c1561 !important; }
        h4.mb-4 {
            color: #3c1561;
            font-weight: bold;
            font-size: 36px;
            text-align: center;
            margin-top: 50px;
        }
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        .card-body { padding: 30px; }
        .card-title {
            font-size: 24px;
            font-weight: bold;
            color: #3c1561;
        }
        .doctor-info {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-top: 30px;
        }
        .doctor-info img {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #3c1561;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .doctor-info div {
            flex-grow: 1;
        }
        .doctor-info h6 {
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }
        .doctor-info p { margin-bottom: 10px; }
        .btn-outline-primary {
            border-color: #3c1561;
            color: #3c1561;
            transition: all 0.3s ease;
        }
        .btn-outline-primary:hover {
            background-color: #3c1561;
            color: white;
        }
        @media (max-width: 576px) {
            .doctor-info {
                flex-direction: column;
                align-items: flex-start;
            }
            .doctor-info img {
                margin-bottom: 15px;
            }
            .container h4 {
                font-size: 28px;
            }
            .card-body { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4">📋 Danh sách hồ sơ bệnh án</h4>

    <?php foreach ($patients as $mabenhnhan => $patient): ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center text-primary mb-3">🧑‍⚕️ Thông tin bệnh nhân</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Họ tên:</strong> <?= htmlspecialchars($patient['hotenbenhnhan']) ?></p>
                        <p><strong>Giới tính:</strong> <?= htmlspecialchars($patient['gioitinh']) ?></p>
                        <p><strong>Ngày sinh:</strong> <?= htmlspecialchars($patient['ngaysinh']) ?></p>
                        <p><strong>Nghề nghiệp:</strong> <?= htmlspecialchars($patient['nghenghiep']) ?></p>
                       
                    </div>
                    <div class="col-md-6">
                        <p><strong>Email:</strong> <?= htmlspecialchars($patient['email']) ?></p>
                        <p><strong>SĐT:</strong> <?= htmlspecialchars($patient['sdtbenhnhan']) ?></p>
                        <p><strong>Địa chỉ:</strong>
                            <?= htmlspecialchars($patient['sonha']). ', ' .
                                htmlspecialchars($patient['xa/phuong']). ', ' .
                                htmlspecialchars($patient['quan/huyen']). ', ' .
                                htmlspecialchars($patient['tinh/thanhpho']) ?>
                        </p>
                        <p><strong>Quan hệ:</strong> <?= htmlspecialchars($patient['quanhe']) ?></p>
                    </div>
                </div>
                <hr>
                <p class="text-center text-white bg-primary py-2 px-3 rounded">Danh sách hồ sơ bệnh án</p>

                <?php foreach ($patient['hoso'] as $row): ?>
                    <div class="doctor-info">
                        <img src="Assets/img/<?= htmlspecialchars($row['imgbs']) ?>" alt="ảnh bác sĩ">
                        <div>
                            <h6 class="mb-1"><?= htmlspecialchars($row['capbac']) ?> - <?= strtoupper(htmlspecialchars($row['hoten'])) ?></h6>
                            <p class="mb-1"><strong>Chuyên khoa:</strong> <?= strtoupper(htmlspecialchars($row['tenchuyenkhoa'])) ?></p>
                            <p class="mb-1"><strong>Ngày lập hồ sơ:</strong> <?= htmlspecialchars($row['ngaytao']) ?></p>
                            <p class="mb-1"><strong>Chẩn đoán:</strong> <?= htmlspecialchars($row['chandoan']) ?></p>
                            <a href="?action=chitiethosobenhandientu&id=<?= htmlspecialchars($row['machitiethoso']) ?>" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                    <hr>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
