<?php
include_once("Controllers/ctaikhoan.php");
include_once("Controllers/ctinhthanhpho.php");
include_once ("Controllers/cxaphuong.php");

$cthanhpho = new cTinhThanhPho();
$thanhpho_list = $cthanhpho->get_tinhthanhpho();

$cxaphuong = new cXaPhuong();
$xaphuong_list = $cxaphuong->get_xaphuong();
$message = "";

// Xử lý khi submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $sdt = $_POST['sdt'] ?? '';
    $hoten = $_POST['fullname'];
    $ngaysinh = $_POST['dob'];
    $cccd = $_POST['cccd'] ?? '';
    $gioitinh = $_POST['gender'];
    $nghenghiep = $_POST['job'];
    $job_other = $_POST['job_other'] ?? '';
    if ($nghenghiep === 'Khác' && !empty($job_other)) {
        $nghenghiep = $job_other;
    }
    $tiensucuagiadinh = $_POST['history_family'];
    $tiensucuabanthan = $_POST['history_my'];
    $sonha = $_POST['sonha'];
    $xa = $_POST['xa'];
    $tinh = $_POST['tinh'];
    $matkhau = $_POST['password'];
    $confirmMatkhau = $_POST['confirm-password'];

    $age = getAge($ngaysinh);

    // Validate cơ bản
    if ($age >= 16 && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email không hợp lệ.";
    } elseif ($age >= 16 && !preg_match("/^[0-9]{10}$/", $sdt)) {
        $message = "Số điện thoại không hợp lệ (10 số).";
    } elseif ($age >= 16 && !preg_match("/^[0-9]{9,12}$/", $cccd)) {
        $message = "Số CCCD không hợp lệ (9-12 số).";
    } elseif (!preg_match("/^[a-zA-ZÀ-ỹ\s]+$/u", $hoten)) {
        $message = "Họ tên chỉ được chứa chữ cái và khoảng trắng.";
    } elseif ($matkhau !== $confirmMatkhau) {
        $message = "Mật khẩu nhập lại không khớp.";
    } elseif (strlen($matkhau) < 6) {
        $message = "Mật khẩu phải từ 6 ký tự trở lên.";
    } else {
        // Nếu cần người giám hộ
        $guardian = null;
        $manguoigiamho =null;
        if ($age < 18 || $age > 60) {
            $manguoigiamho = "NGH_" . rand(10000000, 99999999);
            $gh_hoten = $_POST['gh_hoten'];
            $gh_dob = $_POST['gh_dob'];
            $gh_diachi = $_POST['gh_sonha'] ?? '' . ', ' . $_POST['gh_xa'] ?? '' . ', ' . $_POST['gh_tinh'] ?? '';
            $gh_sdt = $_POST['gh_sdt'];
            $gh_email = $_POST['gh_email'];
            $gh_cccd = $_POST['gh_cccd'];
            
            if (empty($_POST['gh_hoten']) || empty($_POST['gh_sdt']) || empty($_POST['gh_cccd'])) {
                $message = "Bạn cần nhập đầy đủ thông tin người giám hộ.";
            } elseif (!preg_match("/^[0-9]{10}$/", $_POST['gh_sdt'])) {
                $message = "Số điện thoại giám hộ không hợp lệ.";
            } elseif (!preg_match("/^[0-9]{9,12}$/", $_POST['gh_cccd'])) {
                $message = "Số CCCD giám hộ không hợp lệ (9-12 số).";
            }elseif(!filter_var($gh_email, FILTER_VALIDATE_EMAIL)){
                $message = "Email không hợp lệ.";
            }
        }

        // Nếu không có lỗi thì gọi controller
        if ($message == "") {
            $controller = new cTaiKhoan();
            
            // File upload function
            function uploadFile($fileInput){
                if(isset($_FILES[$fileInput]) && $_FILES[$fileInput]['error']==0){
                    $ext = pathinfo($_FILES[$fileInput]['name'], PATHINFO_EXTENSION);
                    if(!in_array(strtolower($ext), ['jpg','jpeg','png'])) return "";
                    $name = uniqid('upload_').'.'.$ext;
                    $path = 'Assets/img/cccd/'.$name;
                    if(move_uploaded_file($_FILES[$fileInput]['tmp_name'], $path))
                      return [
                        'path' => $path,
                        'name' => $name
                    ];
                }
                return "";
            }
            
            $mabenhnhan = "BN_" . rand(10000000, 99999999);

            $cccd_truoc_path = uploadFile('cccd_truoc');
            $cccd_sau_path = uploadFile('cccd_sau');
            $birth_cert_path = uploadFile('birth_cert');
            $gh_cccd_truoc_path = uploadFile('gh_cccd_truoc');
            $gh_cccd_sau_path = uploadFile('gh_cccd_sau');
            
            // Validate required file uploads based on age
            if ($age >= 16) {
                if (empty($cccd_truoc_path) || empty($cccd_sau_path)) {
                    $message = "Vui lòng upload đầy đủ ảnh CCCD mặt trước và mặt sau.";
                }
            } else {
                if (empty($birth_cert_path)) {
                    $message = "Vui lòng upload ảnh giấy khai sinh.";
                }
            }
            
            // Validate guardian file uploads if needed
            if (($age < 18 || $age > 60) && empty($message)) {
                if (empty($gh_cccd_truoc_path) || empty($gh_cccd_sau_path)) {
                    $message = "Vui lòng upload đầy đủ ảnh CCCD người giám hộ.";
                }
            }
            
            $cccd_truoc_name = is_array($cccd_truoc_path) ? $cccd_truoc_path['name'] : '';
            $cccd_sau_name = is_array($cccd_sau_path) ? $cccd_sau_path['name'] : '';
            $birth_cert_name = is_array($birth_cert_path) ? $birth_cert_path['name'] : '';
            $gh_cccd_truoc_name = is_array($gh_cccd_truoc_path) ? $gh_cccd_truoc_path['name'] : '';
            $gh_cccd_sau_name = is_array($gh_cccd_sau_path) ? $gh_cccd_sau_path['name'] : '';
            
            if (empty($message)) {
                $result = $controller->dangkytk($mabenhnhan, $email, $hoten, $ngaysinh, $sdt, $cccd, $cccd_truoc_name, $birth_cert_name, $cccd_sau_name, $gioitinh, $nghenghiep, $tiensucuagiadinh, $tiensucuabanthan, $sonha, $xa, $tinh, $matkhau, $manguoigiamho, $gh_hoten, $gh_dob, $gh_diachi, $gh_sdt,$gh_email, $gh_cccd, $gh_cccd_truoc_name, $gh_cccd_sau_name);
                
                if ($result === "email_ton_tai") {
                    $message = "Email đã tồn tại.";
                } elseif ($result === true) {
                    echo "<script>
                    Swal.fire({
                        title: 'Thành công!',
                        text: 'Đăng ký tài khoản thành công!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location='?action=dangnhap';
                        }
                    });
                    </script>";
                    exit();
                } else {
                    $message = $result ? $result : "Đã có lỗi xảy ra khi tạo tài khoản.";
                }
            }
        }
    }
}

function getAge($dob) {
    $dobTimestamp = strtotime($dob);
    $today = strtotime(date("Y-m-d"));
    return floor(($today - $dobTimestamp) / (365.25*24*60*60));
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - Bệnh viện Hạnh Phúc</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 20px 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 100px;
        }
        .form-container {
            padding: 40px;
        }

        .form-section {
            margin-bottom: 30px;
            padding: 25px;
            border: 2px solid #f0f0f0;
            border-radius: 15px;
            background: #fafafa;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #667eea;
            font-size: 22px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }

        .required {
            color: #e74c3c;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            background: #f9f9f9;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-label:hover {
            border-color: #667eea;
            background: #f0f4ff;
        }

        .file-upload-label i {
            font-size: 20px;
            color: #667eea;
        }

        .image-preview {
            margin-top: 15px;
            max-width: 200px;
            max-height: 150px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: none;
        }

        .guardian-section {
            background: #fff3cd;
            border: 2px solid #ffeaa7;
            border-radius: 15px;
            padding: 25px;
            margin-top: 20px;
            display: none;
        }

        .guardian-section .section-title {
            color: #856404;
        }

        .guardian-section .section-title i {
            color: #f39c12;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        .age-info {
            background: #e3f2fd;
            border: 2px solid #bbdefb;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
            display: none;
        }

        .age-info.show {
            display: block;
        }

        .age-info i {
            color: #1976d2;
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .header {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }

        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <?php if($message != ""): ?>
                <script>
                    Swal.fire({
                        title: 'Thông báo',
                        text: '<?php echo $message; ?>',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>
            <?php endif; ?>
            <h2 style ="margin-bottom: 10px;">Đăng ký tài khoản</h2>
            <form method="POST" enctype="multipart/form-data" id="registrationForm">
                <!-- Thông tin cá nhân -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        Thông tin cá nhân
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Họ và tên <span class="required">*</span></label>
                            <input type="text" name="fullname" required value="<?php echo $_POST['fullname'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Giới tính <span class="required">*</span></label>
                            <select name="gender" required>
                                <option value="">-- Chọn giới tính --</option>
                                <option value="Nam" <?php echo ($_POST['gender'] ?? '') == 'Nam' ? 'selected' : ''; ?>>Nam</option>
                                <option value="Nữ" <?php echo ($_POST['gender'] ?? '') == 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                                <option value="Khác" <?php echo ($_POST['gender'] ?? '') == 'Khác' ? 'selected' : ''; ?>>Khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ngày sinh <span class="required">*</span></label>
                        <input type="date" name="dob" id="dob" required 
                               max="<?php echo date('Y-m-d'); ?>" 
                               min="<?php echo date('Y-m-d', strtotime('-120 years')); ?>" 
                               onchange="toggleGuardian()" 
                               value="<?php echo $_POST['dob'] ?? ''; ?>">
                        <div id="age-display" class="age-info"></div>
                    </div>
                </div>

                <!-- Thông tin liên hệ -->
                <div class="form-section" id="contact-section">
                    <div class="section-title">
                        <i class="fas fa-phone"></i>
                        Thông tin liên hệ
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" id="email" value="<?php echo $_POST['email'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại <span class="required">*</span></label>
                            <input type="text" name="sdt" id="sdt" placeholder="0123456789" value="<?php echo $_POST['sdt'] ?? ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Giấy tờ tùy thân -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-id-card"></i>
                        Giấy tờ tùy thân
                    </div>

                    <!-- CCCD cho người >= 16 tuổi -->
                    <div id="cccd-section">
                        <div class="form-group">
                            <label>Số CCCD/CMND <span class="required">*</span></label>
                            <input type="text" name="cccd" id="cccd" placeholder="Nhập 9-12 số" value="<?php echo $_POST['cccd'] ?? ''; ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>CCCD mặt trước <span class="required">*</span></label>
                                <div class="file-upload">
                                    <input type="file" name="cccd_truoc" accept="image/*" onchange="previewImage(this, 'preview-truoc')">
                                    <div class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Chọn ảnh CCCD mặt trước</span>
                                    </div>
                                </div>
                                <img id="preview-truoc" class="image-preview">
                            </div>
                            <div class="form-group">
                                <label>CCCD mặt sau <span class="required">*</span></label>
                                <div class="file-upload">
                                    <input type="file" name="cccd_sau" accept="image/*" onchange="previewImage(this, 'preview-sau')">
                                    <div class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Chọn ảnh CCCD mặt sau</span>
                                    </div>
                                </div>
                                <img id="preview-sau" class="image-preview">
                            </div>
                        </div>
                    </div>

                    <!-- Giấy khai sinh cho trẻ em < 16 tuổi -->
                    <div id="birth-cert-section" class="hidden">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i>
                            Trẻ em dưới 16 tuổi cần cung cấp giấy khai sinh thay vì CCCD
                        </div>
                        <div class="form-group">
                            <label>Giấy khai sinh <span class="required">*</span></label>
                            <div class="file-upload">
                                <input type="file" name="birth_cert" accept="image/*" onchange="previewImage(this, 'preview-birth')">
                                <div class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Chọn ảnh giấy khai sinh</span>
                                </div>
                            </div>
                            <img id="preview-birth" class="image-preview">
                        </div>
                    </div>
                </div>

                <!-- Thông tin nghề nghiệp -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-briefcase"></i>
                        Thông tin nghề nghiệp
                    </div>
                    
                    <div class="form-group">
                        <label>Nghề nghiệp <span class="required">*</span></label>
                        <select name="job" id="job" onchange="toggleOtherJob()" required>
                            <option value="">-- Chọn nghề nghiệp --</option>
                            <option value="Học sinh" <?php echo ($_POST['job'] ?? '') == 'Học sinh' ? 'selected' : ''; ?>>Học sinh</option>
                            <option value="Sinh viên" <?php echo ($_POST['job'] ?? '') == 'Sinh viên' ? 'selected' : ''; ?>>Sinh viên</option>
                            <option value="Công nhân" <?php echo ($_POST['job'] ?? '') == 'Công nhân' ? 'selected' : ''; ?>>Công nhân</option>
                            <option value="Nông dân" <?php echo ($_POST['job'] ?? '') == 'Nông dân' ? 'selected' : ''; ?>>Nông dân</option>
                            <option value="Nhân viên văn phòng" <?php echo ($_POST['job'] ?? '') == 'Nhân viên văn phòng' ? 'selected' : ''; ?>>Nhân viên văn phòng</option>
                            <option value="Kinh doanh tự do" <?php echo ($_POST['job'] ?? '') == 'Kinh doanh tự do' ? 'selected' : ''; ?>>Kinh doanh tự do</option>
                            <option value="Bác sĩ" <?php echo ($_POST['job'] ?? '') == 'Bác sĩ' ? 'selected' : ''; ?>>Bác sĩ</option>
                            <option value="Y tá/Điều dưỡng" <?php echo ($_POST['job'] ?? '') == 'Y tá/Điều dưỡng' ? 'selected' : ''; ?>>Y tá/Điều dưỡng</option>
                            <option value="Kỹ sư" <?php echo ($_POST['job'] ?? '') == 'Kỹ sư' ? 'selected' : ''; ?>>Kỹ sư</option>
                            <option value="Giáo viên" <?php echo ($_POST['job'] ?? '') == 'Giáo viên' ? 'selected' : ''; ?>>Giáo viên</option>
                            <option value="Nghỉ hưu" <?php echo ($_POST['job'] ?? '') == 'Nghỉ hưu' ? 'selected' : ''; ?>>Nghỉ hưu</option>
                            <option value="Khác" <?php echo ($_POST['job'] ?? '') == 'Khác' ? 'selected' : ''; ?>>Khác</option>
                        </select>
                    </div>

                    <div class="form-group hidden" id="job-other-group">
                        <label>Nghề nghiệp khác <span class="required">*</span></label>
                        <input type="text" name="job_other" id="job-other" placeholder="Nhập nghề nghiệp cụ thể" value="<?php echo $_POST['job_other'] ?? ''; ?>">
                    </div>
                </div>

                <!-- Tiền sử bệnh -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-notes-medical"></i>
                        Tiền sử bệnh
                    </div>
                    
                    <div class="form-group">
                        <label>Tiền sử bệnh của bản thân</label>
                        <textarea name="history_my" placeholder="Mô tả các bệnh đã mắc, thuốc đang sử dụng, dị ứng..."><?php echo $_POST['history_my'] ?? ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Tiền sử bệnh của gia đình</label>
                        <textarea name="history_family" placeholder="Mô tả các bệnh di truyền, bệnh mãn tính trong gia đình..."><?php echo $_POST['history_family'] ?? ''; ?></textarea>
                    </div>
                </div>

                <!-- Địa chỉ -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Địa chỉ
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tỉnh/Thành phố <span class="required">*</span></label>
                            <select name="tinh" id="tinh" onchange="loadXaPhuong()" required>
                                <option value="">-- Chọn tỉnh/thành phố --</option>
                                <?php foreach($thanhpho_list as $i): ?>
                                    <option value="<?php echo $i['matinhthanhpho']; ?>" <?php echo ($_POST['tinh'] ?? '') == $i['matinhthanhpho'] ? 'selected' : ''; ?>>
                                        <?php echo $i['tentinhthanhpho']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Xã/Phường <span class="required">*</span></label>
                            <select name="xa" id="xa" required>
                                <option value="">-- Chọn xã/phường --</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Số nhà, tên đường <span class="required">*</span></label>
                        <input type="text" name="sonha" placeholder="Ví dụ: 123 Nguyễn Văn A" required value="<?php echo $_POST['sonha'] ?? ''; ?>">
                    </div>
                </div>

                <!-- Mật khẩu -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-lock"></i>
                        Mật khẩu
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Mật khẩu <span class="required">*</span></label>
                            <input type="password" name="password" required placeholder="Tối thiểu 6 ký tự">
                        </div>
                        <div class="form-group">
                            <label>Nhập lại mật khẩu <span class="required">*</span></label>
                            <input type="password" name="confirm-password" required placeholder="Nhập lại mật khẩu">
                        </div>
                    </div>
                </div>

                <!-- Thông tin người giám hộ -->
                <div class="guardian-section" id="guardian-info">
                    <div class="section-title">
                        <i class="fas fa-user-shield"></i>
                        Thông tin người giám hộ
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Bệnh nhân dưới 18 tuổi hoặc trên 60 tuổi cần có người giám hộ
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Họ tên người giám hộ <span class="required">*</span></label>
                            <input type="text" name="gh_hoten" value="<?php echo $_POST['gh_hoten'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Ngày sinh <span class="required">*</span></label>
                            <input type="date" name="gh_dob" value="<?php echo $_POST['gh_dob'] ?? ''; ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Số điện thoại <span class="required">*</span></label>
                            <input type="text" name="gh_sdt" placeholder="0988467345" value="<?php echo $_POST['gh_sdt'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Email<span class="required">*</span></label>
                            <input type="text" name="gh_email" placeholder="bac@gmail.com" value="<?php echo $_POST['gh_email'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Số CCCD <span class="required">*</span></label>
                            <input type="text" name="gh_cccd" placeholder="Nhập 9-12 số" value="<?php echo $_POST['gh_cccd'] ?? ''; ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Tỉnh/Thành phố <span class="required">*</span></label>
                            <select name="gh_tinh" id="gh_tinh" onchange="gh_loadXaPhuong()">
                                <option value="">-- Chọn tỉnh/thành phố --</option>
                                <?php foreach($thanhpho_list as $i): ?>
                                    <option value="<?php echo $i['matinhthanhpho']; ?>" <?php echo ($_POST['gh_tinh'] ?? '') == $i['matinhthanhpho'] ? 'selected' : ''; ?>>
                                        <?php echo $i['tentinhthanhpho']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Xã/Phường <span class="required">*</span></label>
                            <select name="gh_xa" id="gh_xa">
                                <option value="">-- Chọn xã/phường --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Added missing address field for guardian -->
                    <div class="form-group">
                        <label>Số nhà, tên đường <span class="required">*</span></label>
                        <input type="text" name="gh_sonha" placeholder="Ví dụ: 123 Nguyễn Văn A" value="<?php echo $_POST['gh_sonha'] ?? ''; ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>CCCD mặt trước <span class="required">*</span></label>
                            <div class="file-upload">
                                <input type="file" name="gh_cccd_truoc" accept="image/*" onchange="previewImage(this, 'preview-gh-truoc')">
                                <div class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Chọn ảnh CCCD mặt trước</span>
                                </div>
                            </div>
                            <img id="preview-gh-truoc" class="image-preview">
                        </div>
                        <div class="form-group">
                            <label>CCCD mặt sau <span class="required">*</span></label>
                            <div class="file-upload">
                                <input type="file" name="gh_cccd_sau" accept="image/*" onchange="previewImage(this, 'preview-gh-sau')">
                                <div class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Chọn ảnh CCCD mặt sau</span>
                                </div>
                            </div>
                            <img id="preview-gh-sau" class="image-preview">
                        </div>
                    </div>
                </div>

                <button type="submit" name="dangky" class="submit-btn">
                    <i class="fas fa-user-plus"></i>
                    Đăng ký tài khoản
                </button>
            </form>
        </div>
    </div>

    <script>
        // Dữ liệu xã phường
        const xaphuongs = <?php echo json_encode($xaphuong_list); ?>;

        function toggleGuardian() {
            const dobInput = document.getElementById("dob");
            if (!dobInput.value) return;

            const birth = new Date(dobInput.value);
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }

            // Hiển thị thông tin tuổi
            const ageDisplay = document.getElementById("age-display");
            ageDisplay.innerHTML = `<i class="fas fa-birthday-cake"></i> Tuổi: ${age}`;
            ageDisplay.classList.add("show");

            // Các elements cần điều khiển
            const contactSection = document.getElementById("contact-section");
            const cccdSection = document.getElementById("cccd-section");
            const birthCertSection = document.getElementById("birth-cert-section");
            const guardianInfo = document.getElementById("guardian-info");
            const emailInput = document.getElementById("email");
            const sdtInput = document.getElementById("sdt");
            const cccdInput = document.getElementById("cccd");

            if (age < 16) {
                // Trẻ em dưới 16 tuổi
                contactSection.style.display = "none";
                cccdSection.classList.add("hidden");
                birthCertSection.classList.remove("hidden");
                
                // Bỏ required cho email, sdt, cccd
                emailInput.removeAttribute("required");
                sdtInput.removeAttribute("required");
                cccdInput.removeAttribute("required");
            } else {
                // Từ 16 tuổi trở lên
                contactSection.style.display = "block";
                cccdSection.classList.remove("hidden");
                birthCertSection.classList.add("hidden");
                
                // Thêm required cho email, sdt, cccd
                emailInput.setAttribute("required", "required");
                sdtInput.setAttribute("required", "required");
                cccdInput.setAttribute("required", "required");
            }

            // Hiển thị thông tin giám hộ
            if (age < 18 || age > 60) {
                guardianInfo.style.display = "block";
                setGuardianRequired(true);
            } else {
                guardianInfo.style.display = "none";
                setGuardianRequired(false);
            }
        }

        function setGuardianRequired(required) {
            const guardianInputs = document.querySelectorAll('#guardian-info input[name^="gh_"], #guardian-info select[name^="gh_"]');
            guardianInputs.forEach(input => {
                if (required) {
                    input.setAttribute("required", "required");
                } else {
                    input.removeAttribute("required");
                }
            });
        }

        function previewImage(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            
            if (!file) {
                preview.style.display = "none";
                return;
            }

            if (!file.type.startsWith("image/")) {
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Vui lòng chọn đúng định dạng ảnh (jpg, png, jpeg)',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                input.value = "";
                preview.style.display = "none";
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(file);
        }

        function toggleOtherJob() {
            const jobSelect = document.getElementById("job");
            const jobOtherGroup = document.getElementById("job-other-group");
            const jobOtherInput = document.getElementById("job-other");
            
            if (jobSelect.value === "Khác") {
                jobOtherGroup.classList.remove("hidden");
                jobOtherInput.setAttribute("required", "required");
            } else {
                jobOtherGroup.classList.add("hidden");
                jobOtherInput.removeAttribute("required");
                jobOtherInput.value = "";
            }
        }

        function loadXaPhuong() {
            const tinhSelect = document.getElementById("tinh");
            const xaSelect = document.getElementById("xa");
            const mathanhpho = tinhSelect.value;

            xaSelect.innerHTML = '<option value="">-- Chọn xã/phường --</option>';
            
            if (!mathanhpho) return;

            const xaphuongs_matinh = xaphuongs.filter(p => p.matinhthanhpho === mathanhpho);
            xaphuongs_matinh.forEach(h => {
                const option = document.createElement('option');
                option.value = h.maxaphuong;
                option.textContent = h.tenxaphuong;
                xaSelect.appendChild(option);
            });
        }

        function gh_loadXaPhuong() {
            const tinhSelect = document.getElementById("gh_tinh");
            const xaSelect = document.getElementById("gh_xa");
            const mathanhpho = tinhSelect.value;

            xaSelect.innerHTML = '<option value="">-- Chọn xã/phường --</option>';
            
            if (!mathanhpho) return;

            const xaphuongs_matinh = xaphuongs.filter(p => p.matinhthanhpho === mathanhpho);
            xaphuongs_matinh.forEach(h => {
                const option = document.createElement('option');
                option.value = h.maxaphuong;
                option.textContent = h.tenxaphuong;
                xaSelect.appendChild(option);
            });
        }

        // Khởi tạo khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            // Nếu có giá trị ngày sinh từ POST, trigger toggleGuardian
            const dobInput = document.getElementById("dob");
            if (dobInput.value) {
                toggleGuardian();
            }
            
            // Nếu có giá trị job từ POST, trigger toggleOtherJob
            const jobSelect = document.getElementById("job");
            if (jobSelect.value === "Khác") {
                toggleOtherJob();
            }
        });
    </script>
</body>
</html>
