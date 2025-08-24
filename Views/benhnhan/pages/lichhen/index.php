<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
include_once('Controllers/cphieukhambenh.php');

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['tentk'])) {
    echo "<p>Bạn chưa đăng nhập hoặc thiếu thông tin tài khoản.</p>";
    exit;
}

$tentk = $_SESSION['user']['tentk'];
$pPhieu = new cPhieuKhamBenh();
$filter = $_GET['filter'] ?? null;
$currentDate = date('Y-m-d');

if ($filter === 'đã hủy') {
    $phieus = $pPhieu->getAllPhieuKhamBenhOfTK($tentk, $filter);
} elseif ($filter === 'đã khám') {
    $phieus = $pPhieu->getAllPhieuKhamBenhOfTK($tentk, $filter);
} elseif($filter === 'chưa khám') {
    $phieus = $pPhieu->getAllPhieuKhamBenhOfTK($tentk, $filter);
}else{
    $phieus = $pPhieu->getAllPhieuKhamBenhOfTK($tentk, $filter);
}

// Xử lý hủy lịch hẹn
if (isset($_GET['cancel_id'])) {
    $maphieukb = $_GET['cancel_id'];
    $phieu = $pPhieu->getPhieuKhamBenhOfIDPK($maphieukb);
    if ($phieu) {
        $ngaykham = $phieu['ngaykham'];
        if ($ngaykham == $currentDate) {
            echo "<script>alert('Không thể hủy lịch hẹn vì sắp tới giờ khám.');</script>";
        } else {
            $malichlamviec = $phieu['malichlamviec'];
            $result = $pPhieu->cancelPhieuKhamBenh($maphieukb);
            if ($result) {
                include_once('Controllers/clichlamviec.php');
                $pLichLamViec = new cLichLamViec();
                $pLichLamViec->updatelichlamviectrong($malichlamviec);
                echo "<script>alert('Lịch hẹn đã được hủy thành công.'); window.location.href='?action=lichhen';</script>";
            } else {
                echo "<script>alert('Lỗi khi hủy lịch hẹn. Vui lòng thử lại sau.');</script>";
            }
        }
    } else {
        echo "<script>alert('Không tìm thấy phiếu khám với mã này.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch hẹn khám bệnh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        padding-top: 100px;
        background-color: #f2f0f8;
    }

    h2 {
        color: #5c2d91;
        text-align: center;
        margin-bottom: 30px;
        font-weight: bold;
    }

    form {
        text-align: center;
        margin-bottom: 30px;
    }

    form label {
        font-weight: 500;
        font-size: 16px;
        color: #4b2354;
    }

    table {
        width: 92%;
        margin: auto;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 25px rgba(92, 45, 145, 0.12);
        overflow: hidden;
        border-collapse: collapse;
    }

    th {
        background-color: #512e6c;
        color: #ffffff;
        text-transform: uppercase;
        font-size: 14px;
        letter-spacing: 1px;
        padding: 14px;
        text-align: center; /* Added this line to center text */
    }

    td {
        padding: 14px;
        font-size: 15px;
        color: #333;
        text-align: center;
    }

    tr:nth-child(even) {
        background-color: #f7f3fa;
    }

    tr:hover {
        background-color: #eae0f8;
    }

    p {
        text-align: center;
        font-size: 18px;
        color: #c0392b;
        font-weight: 500;
    }

    .muted-text {
        color: #999;
        font-style: italic;
    }

    .btn-danger.btn-sm {
        padding: 5px 10px;
        font-size: 14px;
        border-radius: 6px;
    }

    .btn-primary.btn-sm {
        font-size: 14px;
        padding: 5px 12px;
        border-radius: 6px;
    }

    @media screen and (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead {
            display: none;
        }

        tr {
            margin-bottom: 15px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(92, 45, 145, 0.1);
        }

        td {
            text-align: left;
            padding-left: 40%;
            position: relative;
        }

        td::before {
            position: absolute;
            top: 14px;
            left: 16px;
            width: 35%;
            white-space: nowrap;
            font-weight: bold;
            color: #555;
        }

        td:nth-of-type(1)::before { content: "Mã Lịch Hẹn"; }
        td:nth-of-type(2)::before { content: "Bệnh Nhân"; }
        td:nth-of-type(3)::before { content: "Ngày Khám"; }
        td:nth-of-type(4)::before { content: "Thời Gian"; }
        td:nth-of-type(5)::before { content: "Khoa"; }
        td:nth-of-type(6)::before { content: "Bác Sĩ"; }
        td:nth-of-type(7)::before { content: "Hành động"; }
    }
</style>

</head>
<body>

<h2>Lịch Hẹn Khám Bệnh</h2>

<form method="get">
    <input type="hidden" name="action" value="lichhen">
    <label>
    <input type="radio" name="filter" value="chưa khám" <?= ($filter === 'chưa khám') ? 'checked' : '' ?>>
        Chưa khám
    </label>
    <label style="margin-left: 20px;">
    <input type="radio" name="filter" value="đã khám" <?= ($filter === 'đã khám') ? 'checked' : '' ?>>
        Đã khám
    </label>
    <label style="margin-left: 20px;">
    <input type="radio" name="filter" value="đã hủy" <?= ($filter === 'đã hủy') ? 'checked' : '' ?>>
        Đã hủy
    </label>
    <button type="submit" class="btn btn-primary btn-sm" style="margin-left: 20px;">Lọc</button>
</form>

<?php if ($phieus === -1): ?>
    <p>Lỗi kết nối dữ liệu.</p>
<?php elseif ($phieus === 0): ?>
    <p>Không có lịch hẹn nào được tìm thấy.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Mã Lịch Hẹn</th>
            <th>Bệnh Nhân</th>
            <th>Ngày Khám</th>
            <th>Thời Gian</th>
            <th>Khoa</th>
            <th>Bác Sĩ</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $phieus->fetch_assoc()): ?>
            <?php
            $trangthai = $row['trangthai'] ?? '';
            ?>
            <tr>
                <td><?= htmlspecialchars($row['maphieukb']) ?></td>
                <td><?= htmlspecialchars($row['hotenbenhnhan']) ?></td>
                <td><?= htmlspecialchars($row['ngaykham']) ?></td>
                <td><?= htmlspecialchars($row['giobatdau']) . ' - ' . htmlspecialchars($row['gioketthuc']) ?></td>
                <td><?= htmlspecialchars($row['tenchuyenkhoa']) ?></td>
                <td><?= htmlspecialchars($row['hotenbacsi']) ?></td>
                <td>
                    <?php
                    if ($trangthai === 'đã hủy') {
                        echo '<span class="muted-text">Đã hủy</span>';
                    } elseif ($trangthai === 'đã khám') {
                        echo '<span class="muted-text">Đã khám</span>';
                    } else {
                        echo '<a href="?action=lichhen&cancel_id=' . $row['maphieukb'] . '" class="btn btn-danger btn-sm"
                                onclick="return confirm(\'Bạn có chắc chắn muốn hủy lịch hẹn này?\');">Hủy lịch hẹn</a>';
                    }
                    ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>