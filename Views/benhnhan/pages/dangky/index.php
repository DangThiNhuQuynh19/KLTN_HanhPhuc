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
    $email = $_POST['email'];
    $hoten = $_POST['fullname'];
    $ngaysinh = $_POST['dob'];
    $cccd = $_POST['cccd'];
    $gioitinh = $_POST['gender'];
    $nghenghiep = $_POST['job'];
    $tiensu = $_POST['history'];
    $sonha = $_POST['sonha'];
    $xa = $_POST['xa'];
    $tinh = $_POST['tinh'];
    $matkhau = $_POST['password'];
    $confirmMatkhau = $_POST['confirm-password'];

    // File upload
    function uploadFile($fileInput){
      if(isset($_FILES[$fileInput]) && $_FILES[$fileInput]['error']==0){
          $ext = pathinfo($_FILES[$fileInput]['name'], PATHINFO_EXTENSION);
          if(!in_array(strtolower($ext), ['jpg','jpeg','png'])) return "";
          $path = 'Assets/img/'.uniqid('cccd_').'.'.$ext;
          if(move_uploaded_file($_FILES[$fileInput]['tmp_name'], $path)) return $path;
      }
      return "";
    }

    $cccd_truoc_path = uploadFile('cccd_truoc');
    $cccd_sau_path = uploadFile('cccd_sau');
    $birth_cert_path = uploadFile('birth_cert');
    $gh_cccd_truoc_path = uploadFile('gh_cccd_truoc');
    $gh_cccd_sau_path = uploadFile('gh_cccd_sau');

    // Validate cơ bản
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email không hợp lệ.";
    } elseif (!preg_match("/^[0-9]{9,12}$/", $cccd)) {
        $message = "Số CCCD không hợp lệ (9-12 số).";
    } elseif (!preg_match("/^[a-zA-ZÀ-ỹ\s]+$/u", $hoten)) {
        $message = "Họ tên chỉ được chứa chữ cái và khoảng trắng.";
    } elseif ($matkhau !== $confirmMatkhau) {
        $message = "Mật khẩu nhập lại không khớp.";
    } elseif (strlen($matkhau) < 6) {
        $message = "Mật khẩu phải từ 6 ký tự trở lên.";
    } else {
        $age = getAge($ngaysinh);

        // Nếu cần người giám hộ
        $guardian = null;
        if ($age < 18 || $age > 60) {
            $guardian = [
                "hoten" => $_POST['gh_hoten'],
                "ngaysinh" => $_POST['gh_dob'],
                "diachi" => $_POST['gh_diachi'],
                "sdt" => $_POST['gh_sdt'],
                "cccd" => $_POST['gh_cccd'],
                "cccd_truoc" => $_FILES['gh_cccd_truoc']['name'],
                "cccd_sau" => $_FILES['gh_cccd_sau']['name']
            ];

            if (empty($guardian['hoten']) || empty($guardian['sdt']) || empty($guardian['cccd'])) {
                $message = "Bạn cần nhập đầy đủ thông tin người giám hộ.";
            } elseif (!preg_match("/^[0-9]{10}$/", $guardian['sdt'])) {
                $message = "Số điện thoại giám hộ không hợp lệ.";
            }
        }

        // Nếu không có lỗi thì gọi controller
        if ($message == "") {
            $controller = new cTaiKhoan();
            $result = $controller->dangkytk_full($email, $hoten, $ngaysinh, $cccd, $gioitinh, $nghenghiep, $tiensu, $sonha, $xa, $huyen, $tinh, $matkhau, $cccd_truoc, $cccd_sau, $guardian);

            if ($result === "email_ton_tai") {
                $message = "Email đã tồn tại.";
            } elseif ($result === true) {
                echo "<script>alert('Đăng ký thành công!');window.location='?dangnhap';</script>";
                exit();
            } else {
                $message = "Đã có lỗi xảy ra khi tạo tài khoản.";
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
<style>
  .register-box{margin:auto;margin-top:100px;background:#fff;padding:20px;border-radius:10px;width:95%;max-width:600px}
  label{display:block;margin-top:10px;font-weight:500}
  input,select,textarea{width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;margin-top:5px}
  button{margin-top:20px;padding:12px;width:100%;background:#6f42c1;color:#fff;border:none;border-radius:8px;cursor:pointer}
  .cccd-preview {
    margin-top: 10px;
    width: 150px;      /* chiều rộng ảnh */
    height: auto;      /* tự động theo tỷ lệ */
    border: 1px solid #ccc;
    border-radius: 6px;
    display: block;
  }
</style>
<script>
  function toggleGuardian() {
    let dob = document.getElementById("dob").value;
    if (!dob) return;

    let birth = new Date(dob);
    let today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    let m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;

    const cccdSection = document.getElementById("cccd-section");
    const birthCertSection = document.getElementById("birth-cert-section");
    const guardianInfo = document.getElementById("guardian-info");

    if (age < 16) {
        // Trẻ em => giấy khai sinh
        cccdSection.style.display = "none";
        contact.style.display ="none";
        birthCertSection.style.display = "block";
    } else {
        // >=16 tuổi => CCCD
        cccdSection.style.display = "block";
        contact.style.display ="block";
        birthCertSection.style.display = "none";
    }
    // Thông tin giám hộ (<18 hoặc >60)
    guardianInfo.style.display = (age < 18 || age > 60) ? "block" : "none";
  }
  function previewImage(input, previewId) {
    const file = input.files[0];
    if (!file) return;

    // Kiểm tra loại file có phải ảnh
    if (!file.type.startsWith("image/")) {
        alert("Vui lòng chọn đúng định dạng ảnh (jpg, png...)");
        input.value = "";
        return;
    }

    // Tạo link tạm cho ảnh và gán vào img
      const img = document.getElementById(previewId);
      img.src = URL.createObjectURL(file);
      img.style.display = "block";

      // Giải phóng URL khi đổi ảnh (tránh rò rỉ bộ nhớ)
      img.onload = function() {
          URL.revokeObjectURL(img.src);
      };
  }

  // Chặn console.log in file
  (function() {
      const originalLog = console.log;
      console.log = function(...args) {
          if (args.some(arg => arg instanceof File)) {
              return; // bỏ qua nếu log file
          }
          originalLog.apply(console, args);
      };
  })();

  function toggleOtherJob() {
    let jobSelect = document.getElementById("job");
    let jobOther = document.getElementById("job-other");
    if (jobSelect.value === "Khác") {
        jobOther.style.display = "block";
        jobOther.required = true;
    } else {
        jobOther.style.display = "none";
        jobOther.required = false;
    }
  }

  function loadXaPhuong() {
    const tinhSelect = document.getElementById("tinh");
    const xaSelect = document.getElementById("xa");
    const mathanhpho = tinhSelect.value;

    // Xóa các option cũ
    xaSelect.innerHTML = '<option value="">--Chọn Xã/Phường--</option>';
    const xaphuongs = <?php echo json_encode($xaphuong_list); ?>;
    const xaphuongs_matinh = xaphuongs.filter(p => p.matinhthanhpho === mathanhpho);

    xaphuongs_matinh.forEach(h => {
        const option = document.createElement('option');
        option.value = h.maxaphuong;
        option.textContent = `${h.tenxaphuong}`;
        xaSelect.appendChild(option);
    });
  }

  
  function gh_loadXaPhuong() {
    const tinhSelect = document.getElementById("gh_tinh");
    const xaSelect = document.getElementById("gh_xa");
    const mathanhpho = tinhSelect.value;

    // Xóa các option cũ
    xaSelect.innerHTML = '<option value="">--Chọn Xã/Phường--</option>';
    const xaphuongs = <?php echo json_encode($xaphuong_list); ?>;
    const xaphuongs_matinh = xaphuongs.filter(p => p.matinhthanhpho === mathanhpho);

    xaphuongs_matinh.forEach(h => {
        const option = document.createElement('option');
        option.value = h.maxaphuong;
        option.textContent = `${h.tenxaphuong}`;
        xaSelect.appendChild(option);
    });
  }
</script>
<div class="register-box">
  <h2>Đăng ký tài khoản</h2>
  <?php if($message!=""): ?>
    <script>alert("<?php echo $message;?>");</script>
  <?php endif; ?>
  <form method="POST" enctype="multipart/form-data">
    <label>Họ tên:</label>
    <input type="text" name="fullname" required>

    <label>Giới tính:</label>
    <select name="gender" required>
      <option value="">--Chọn--</option>
      <option value="Nam">Nam</option>
      <option value="Nữ">Nữ</option>
      <option value="Khác">Khác</option>
    </select>

    <label>Ngày sinh:</label>
      <input type="date" name="dob" id="dob" 
      required 
      max="<?php echo date('Y-m-d'); ?>" 
      min="<?php echo date('Y-m-d', strtotime('-120 years')); ?>"
      onchange="toggleGuardian()">

    <div id="contact">
      <label>Email:</label>
      <input type="email" name="email" required>

      <label>Số Điện Thoại:</label>
      <input type="sdt" name="sdt" required>
    </div>
    
    <!-- Nhập CCCD -->
    <div id="cccd-section">
      <label>Số CCCD:</label>
      <input type="text" name="cccd">

      <label>CCCD mặt trước:</label>
      <input type="file" name="cccd_truoc" accept="image/*" onchange="previewImage(this, 'preview-truoc')">
      <img id="preview-truoc" class="cccd-preview" style="display:none"/>

      <label>CCCD mặt sau:</label>
      <input type="file" name="cccd_sau" accept="image/*" onchange="previewImage(this, 'preview-sau')">
      <img id="preview-sau" class="cccd-preview" style="display:none"/>
    </div>

    <!-- Giấy khai sinh -->
    <div id="birth-cert-section" style="display:none;">
      <label>Giấy khai sinh:</label>
      <input type="file" name="birth_cert" accept="image/*" onchange="previewImage(this, 'preview-birth')">
      <img id="preview-birth" class="cccd-preview" style="display:none"/>
    </div>

    <label>Nghề nghiệp:</label>
    <select name="job" id="job" onchange="toggleOtherJob()">
      <option value="">--Chọn nghề nghiệp--</option>
      <option value="Học sinh">Học sinh</option>
      <option value="Sinh viên">Sinh viên</option>
      <option value="Công nhân">Công nhân</option>
      <option value="Nông dân">Nông dân</option>
      <option value="Nhân viên văn phòng">Nhân viên văn phòng</option>
      <option value="Kinh doanh tự do">Kinh doanh tự do</option>
      <option value="Bác sĩ">Bác sĩ</option>
      <option value="Y tá/Điều dưỡng">Y tá/Điều dưỡng</option>
      <option value="Kỹ sư">Kỹ sư</option>
      <option value="Giáo viên">Giáo viên</option>
      <option value="Nghỉ hưu">Nghỉ hưu</option>
      <option value="Khác">Khác</option>
    </select>

    <input type="text" name="job_other" id="job-other" placeholder="Nhập nghề nghiệp khác" style="display:none; margin-top:10px"/>

    <label>Tiền sử bệnh:</label>
    <textarea name="history"></textarea>

    <label>Tỉnh/Thành phố:</label>
    <select name="tinh" id="tinh" onchange="loadXaPhuong()">
      <option value="">--Chọn tỉnh/thành phố--</option>
      <?php
        foreach($thanhpho_list as $i){
          echo '<option value="'.$i['matinhthanhpho'].'">'.$i['tentinhthanhpho'].'</option>';
        }
      ?>
    </select>
    <label>Xã/Phường:</label>
    <select name="xa" id="xa">
        <option value="">--Chọn Xã/Phường--</option>
    </select>
    <label>Số nhà:</label>
    <input type="text" name="sonha">
    
    <label>Mật khẩu:</label>
    <input type="password" name="password" required>
    <label>Nhập lại mật khẩu:</label>
    <input type="password" name="confirm-password" required>

    <!-- Thông tin giám hộ -->
    <div id="guardian-info" style="display:none; margin-top:20px; border:1px solid #ccc; padding:10px;border-radius:6px">
      <h3>Thông tin người giám hộ</h3>
      <label>Họ tên:</label>
      <input type="text" name="gh_hoten">
      <label>Ngày sinh:</label>
      <input type="date" name="gh_dob">
      <label>Tỉnh/Thành phố:</label>
      <select name="gh_tinh" id="gh_tinh" onchange="gh_loadXaPhuong()">
        <option value="">--Chọn tỉnh/thành phố--</option>
        <?php
          foreach($thanhpho_list as $i){
            echo '<option value="'.$i['matinhthanhpho'].'">'.$i['tentinhthanhpho'].'</option>';
          }
        ?>
      </select>
      <label>Xã/Phường:</label>
      <select name="gh_xa" id="gh_xa">
          <option value="">--Chọn Xã/Phường--</option>
      </select>
      <label>Số điện thoại:</label>
      <input type="text" name="gh_sdt">
      <label>Số CCCD</label>
      <input type="text" name="gh_cccd">
      <label>CCCD mặt trước</label>
      <input type="file" name="gh_cccd_truoc" accept="image/*" onchange="previewImage(this, 'preview-gh-truoc')">
      <img id="preview-gh-truoc" class="cccd-preview" style="display:none"/>

      <label>CCCD mặt sau:</label>
      <input type="file" name="gh_cccd_sau" accept="image/*" onchange="previewImage(this, 'preview-gh-sau')">
      <img id="preview-gh-sau" class="cccd-preview" style="display:none"/>
    </div>

    <button type="submit">Đăng ký</button>
  </form>
</div>