<?php
session_start();
include_once('Controllers/cBenhNhan.php');
include_once("Assets/config.php");
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['tentk'])) {
    echo "<p>Bạn chưa đăng nhập.</p>";
    exit;
}

$tentk = $_SESSION['user']['tentk'];
$pBenhNhan = new cBenhNhan();
$benhnhans = $pBenhNhan->getAllBenhNhanByTK($tentk);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Hồ sơ bệnh nhân</title>
    <style>
        :root {
            --custom-purple: rgb(85, 45, 125);
            --custom-purple-dark: rgb(70, 35, 110);
            --input-border: #ced4da;
            --input-focus: var(--custom-purple);
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
            margin: 0;
            padding-top: 30px;
        }

        h2 {
            text-align: center;
            color: #6c3483;
            margin-bottom: 30px;
        }

        table {
            width: 90%;
            max-width: 1100px;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(108, 52, 131, 0.2);
        }

        th, td {
            padding: 14px 16px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #3c1561;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #faf5ff;
        }

        tr:hover {
            background-color: #f3e5f5;
        }

        .action-buttons button {
            margin: 0 4px;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-edit {
            background-color: #9b59b6;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            display: inline-block;
            margin: 0 4px;
            transition: background-color 0.3s ease;
        }

        .btn-edit:hover {
            background-color: #884ea0;
        }
        .btn-delete {
            background-color: #e74c3c;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        p {
            text-align: center;
            font-size: 18px;
            color: #c0392b;
        }
        .btn-primary{
            background-color: var(--custom-purple);
            border-color: var(--custom-purple);
            border-radius: 50px;
            font-weight: 500;
            font-size: 16px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: var(--custom-purple-dark);
            border-color: var(--custom-purple-dark);
        }
        .them{
            margin-left: 35%;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h2>Hồ sơ bệnh nhân</h2>

<?php if (!empty($benhnhans)) : ?>
    <table>
        <thead>
            <tr>
                <th>Mã Bệnh Nhân</th>
                <th>Họ tên</th>
                <th>Ngày sinh</th>
                <th>Giới tính</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($benhnhans as $bn) : ?>
                <tr>
                    <td><?= htmlspecialchars($bn['mabenhnhan']) ?></td>
                    <td><?= htmlspecialchars($bn['hotenbenhnhan']) ?></td>
                    <td><?= htmlspecialchars($bn['ngaysinh']) ?></td>
                    <td><?= htmlspecialchars($bn['gioitinh'])?></td>
                    <td><?= htmlspecialchars(decryptData($bn['sdtbenhnhan'])) ?></td>
                    <td><?= htmlspecialchars($bn['sonha']) . ', ' . htmlspecialchars($bn['xa/phuong']) . ', ' . htmlspecialchars($bn['quan/huyen']) . ', ' . htmlspecialchars($bn['tinh/thanhpho']);?></td>
                    <td class="action-buttons">
                        <a href="?action=suahoso&mabenhnhan=<?= $bn['mabenhnhan'] ?>" class="btn-edit">Sửa</a>

                        <form action="?action=xoahoso&mabenhnhan=" method="post" style="display:inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa bệnh nhân này?');">
                            <input type="hidden" name="mabenhnhan" value="<?= $bn['mabenhnhan'] ?>">
                            <button type="submit" class="btn-delete">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
<?php else : ?>
    <p>Không tìm thấy hồ sơ bệnh nhân nào.</p>
<?php endif; ?>
<div class="them">
<a href="?action=taohoso" class="btn btn-primary"> + Tạo hồ sơ bệnh nhân mới</a>
</div>

</body>
</html>
