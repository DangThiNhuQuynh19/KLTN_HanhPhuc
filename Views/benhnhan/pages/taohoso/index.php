<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once("Controllers/cbenhnhan.php");
    include_once("Assets/config.php");

    // Lấy dữ liệu POST
    $hoten = $_POST['hotenbenhnhan'] ?? '';
    $ngaysinh = $_POST['ngaysinh'] ?? '';
    $gioitinh = $_POST['gioitinh'] ?? '';
    $nghenghiep = $_POST['nghenghiep'] ?? '';
    $cccd_raw = $_POST['cccdbenhnhan'] ?? '';
    $sdt_raw = $_POST['sdtbenhnhan'] ?? '';
    $email_raw = $_POST['email'] ?? '';
    $dantoc = $_POST['dantoc'] ?? '';
    $tinh = $_POST['tinh'] ?? '';
    $quan = $_POST['quan'] ?? '';
    $xa = $_POST['xa'] ?? '';
    $sonha = $_POST['sonha'] ?? '';
    $quanhe = $_POST['quanhe'] ?? '';
    $tiensuGD_raw = $_POST['tiensubenhtatcuagiadinh'] ?? '';
    $tiensuBT_raw = $_POST['tiensubenhtatcuabenhnhan'] ?? '';
    $nhommau = $_POST['nhommau'] ?? '';
    $tentk = $_SESSION['user']['tentk'] ?? null;

    // Kiểm tra dữ liệu ở dạng raw
    $errors = [];
    if (!preg_match("/^[\p{L}\s]+$/u", $hoten)) {
        $errors[] = "Họ tên chỉ được chứa chữ cái và khoảng trắng.";
    }
    if (strtotime($ngaysinh) >= time()) {
        $errors[] = "Ngày sinh phải nhỏ hơn ngày hiện tại.";
    }
    if ($cccd_raw !== 'Chưa có' && !preg_match("/^\d{12}$/", $cccd_raw)) {
        $errors[] = "CCCD phải gồm 12 chữ số hoặc ghi là 'Chưa có' nếu bệnh nhân dưới 16 tuổi.";
    }
    if (!preg_match("/^0\d{9}$/", $sdt_raw)) {
        $errors[] = "Số điện thoại phải bắt đầu bằng 0 và có 10 chữ số.";
    }
    if (!filter_var($email_raw, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không đúng định dạng.";
    }

    if (count($errors) > 0) {
        $errorMessage = implode("\\n", $errors);
        echo "<script>
            alert('Lỗi nhập liệu:\\n$errorMessage');
            window.history.back();
        </script>";
        exit();
    }

    // Nếu dữ liệu hợp lệ thì mã hóa
    $cccd = encryptData($cccd_raw);
    $sdt = encryptData($sdt_raw);
    $email = encryptData($email_raw);
    $tiensuGD = encryptData($tiensuGD_raw);
    $tiensuBT = encryptData($tiensuBT_raw);
    
    
    // Nếu có lỗi, thông báo
    if (count($errors) > 0) {
        $errorMessage = implode("\\n", $errors);
        echo "<script>
            alert('Lỗi nhập liệu:\\n$errorMessage');
            window.history.back();
        </script>";
        exit();
    }

    // Nếu đã đăng nhập, thực hiện thêm hồ sơ
    if ($tentk) {
        $p = new cBenhNhan();
        $kq = $p->insertbenhnhan($hoten, $ngaysinh, $gioitinh, $nghenghiep, $cccd,
                                 $dantoc, $email, $sdt, $tinh, $quan, $xa, $sonha, $quanhe,
                                 $tiensuGD, $tiensuBT, $nhommau, $tentk);
        if ($kq) {
            echo "<script>alert('Tạo hồ sơ thành công'); window.location.href='?action=caidat';</script>";
        } else {
            echo "<script>alert('Tạo hồ sơ thất bại'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Bạn chưa đăng nhập.'); window.location.href='?action=dangnhap';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Hồ Sơ Bệnh Nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding-top: 70px; /* Tăng khoảng cách trên cùng để tránh bị header che */
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .col-md-6, .col-md-4, .col-md-12 {
            flex: 1;
            min-width: 240px;
        }
        .form-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-control, .form-select, .form-textarea {
            width: 100%;  
            padding: 12px; 
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .form-control:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #007bff;
        }
        .btn {
            background-color: #3c1561;
            color: #fff;
            padding: 12px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .form-textarea {
            resize: vertical;
        }
        .col-md-6 {
            flex: 0 0 48%; 
        }
        .col-md-4 {
            flex: 0 0 31%; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="text-align:center; color: #3c1561;">Tạo Hồ Sơ Mới</h2>
        <form action="" method="POST">
            <div class="row g-3" id="formFields">
                <div class="col-md-6">
                    <label for="hotenbenhnhan" class="form-label">Họ Tên Bệnh Nhân</label>
                    <input type="text" class="form-control" id="hotenbenhnhan" name="hotenbenhnhan" value="" required>
                </div>

                <div class="col-md-6">
                    <label for="ngaysinh" class="form-label">Ngày Sinh</label>
                    <input type="date" class="form-control" id="ngaysinh" name="ngaysinh" value="" required>
                </div>

                <div class="col-md-6">
                    <label for="gioitinh" class="form-label">Giới Tính</label>
                    <select class="form-select" id="gioitinh" name="gioitinh" required>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="nghenghiep" class="form-label">Nghề Nghiệp</label>
                    <input type="text" class="form-control" id="nghenghiep" name="nghenghiep" value="" required>
                </div>

                <div class="col-md-6">
                    <label for="cccdbenhnhan" class="form-label">CCCD</label>
                    <input type="text" class="form-control" id="cccdbenhnhan" name="cccdbenhnhan" value="" required>
                </div>

                <div class="col-md-6">
                    <label for="dantoc" class="form-label">Dân Tộc</label>
                    <input type="text" class="form-control" id="dantoc" name="dantoc" value="" required>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="" required>
                </div>

                <div class="col-md-6">
                    <label for="sdtbenhnhan" class="form-label">SĐT Bệnh Nhân</label>
                    <input type="text" class="form-control" id="sdtbenhnhan" name="sdtbenhnhan" value="" required>
                </div>

                <div class="col-md-6">
                    <label for="tinh" class="form-label">Tỉnh/Thành Phố</label>
                    <input type="text" class="form-control" id="tinh" name="tinh" value="" required>
                </div>

                <div class="col-md-6">
                    <label for="quan" class="form-label">Quận/Huyện</label>
                    <input type="text" class="form-control" id="quan" name="quan" value="" required>
                </div>

                <div class="col-md-6">
                    <label for="xa" class="form-label">Xã/Phường</label>
                    <input type="text" class="form-control" id="xa" name="xa" value="" required>
                </div>

                <div class="col-md-12">
                    <label for="sonha" class="form-label">Số Nhà</label>
                    <input type="text" class="form-control" id="sonha" name="sonha" value="" required>
                </div>
                <div class="col-md-6">
                    <label for="quanhe" class="form-label">Quan hệ</label>
                    <select class="form-select" id="quanhe" name="quanhe" required>
                        <option value="Ông">Ông</option>
                        <option value="Bà">Bà</option>
                        <option value="Bố">Bố</option>
                        <option value="Mẹ">Mẹ</option>
                        <option value="Anh">Anh</option>
                        <option value="Chị">Chị</option>
                        <option value="Em">Em</option>
                        <option value="Con">Con</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="tiensubenhtatgiadinh" class="form-label">Tiền Sử Bệnh Gia Đình</label>
                    <textarea class="form-control" id="tiensubenhtatcuagiadinh" name="tiensubenhtatcuagiadinh" rows="2"></textarea>
                </div>

                <div class="col-md-6">
                    <label for="tiensubenhbandau" class="form-label">Tiền Sử Bệnh Bản Thân</label>
                    <textarea class="form-control" id="tiensubenhtatcuabenhnhan" name="tiensubenhtatcuabenhnhan" rows="2"></textarea>
                </div>

                <div class="col-md-6">
                    <label for="nhommau" class="form-label">Nhóm Máu</label>
                    <select class="form-select" id="nhommau" name="nhommau" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                </div>

                <div class="col-md-12" >
                    <button type="submit" class="btn">Tạo mới</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
<script>
    document.getElementById('ngaysinh').addEventListener('change', function () {
        const ngaysinh = this.value; // yyyy-mm-dd
        if (ngaysinh) {
            const namSinh = parseInt(ngaysinh.split('-')[0]); // lấy năm sinh
            const namHienTai = new Date().getFullYear();
            const tuoi = namHienTai - namSinh;
            const ngheNghiepInput = document.getElementById('nghenghiep');

            if (tuoi < 18) {
                ngheNghiepInput.value = 'Còn nhỏ';
                ngheNghiepInput.setAttribute('readonly', true);
            } else {
                ngheNghiepInput.value = '';
                ngheNghiepInput.removeAttribute('readonly');
            }
        }
    });

    document.getElementById('ngaysinh').addEventListener('change', function () {
        const dob = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        const cccdInput = document.getElementById('cccdbenhnhan');

        if (age < 16) {
            cccdInput.value = 'Chưa có';
            cccdInput.readOnly = true; // Khóa ô nhập
        } else {
            cccdInput.value = '';
            cccdInput.readOnly = false;
        }
    });
</script>