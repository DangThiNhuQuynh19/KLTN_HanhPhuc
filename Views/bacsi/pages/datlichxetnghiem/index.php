<?php
    require 'vendor/autoload.php';
    use Endroid\QrCode\Builder\Builder;
    use Endroid\QrCode\Writer\PngWriter;
    include_once("Assets/config.php");

    include_once('Controllers/cbenhnhan.php');
    include_once('Controllers/cbacsi.php');
    include_once('Controllers/cloaixetnghiem.php');
    include_once('Controllers/ckhunggioxetnghiem.php');
    include_once('Controllers/chosobenhandientu.php');
    include_once('Controllers/clichxetnghiem.php');
    
    // Khởi tạo các đối tượng controller
    $cbenhnhan = new cBenhNhan();
    $clichxetnghiem = new cLichXetNghiem();
    $cloaixetnghiem = new cLoaiXetNghiem();
    $ckhunggioxetnghiem = new cKhungGioXetNghiem();
    $chosobenhandientu = new cHoSoBenhAnDienTu();
    $cbacsi = new cBacSi();
    $bacsi = $cbacsi->getBacSiByTenTK($_SESSION['user']['tentk']);
    $benhnhan_list = $cbenhnhan->get_benhnhan_mabacsi($bacsi['mabacsi']);
    $danhmucxetnghiem = $cloaixetnghiem->get_danhmucxetnghiem();
    $message = '';
    $messageType = 'success';

    if (isset($_POST['btnxacnhan'])) {
        if (!empty($_POST['patientId']) && !empty($_POST['test']) && !empty($_POST['appointmentDate']) && !empty($_POST['appointmentTime']) && !empty($_POST['hoso'])) {
            // Tạo tên file duy nhất (dựa theo thời gian)
            $filename = 'qr_' . time() . '.png'; // Ví dụ: qr_1716634452.png
            $savePath = 'Assets/img/' . $filename;

            $kg= $ckhunggioxetnghiem->get_khunggioxetnghiem_makhunggio($_POST['appointmentTime']);
            $loai =$cloaixetnghiem->get_loaixetnghiem_maloaixetnghiem($_POST['test']);
            $bn_id=$cbenhnhan->getbenhnhanbyid($_POST['patientId']);
            // Tạo QR code
            $builder = new Builder(
                writer: new PngWriter(),
                data: $data = "Họ tên: " . $_POST['patientName'] . "\n" .
                "SĐT: " . $_POST['patientPhone'] . "\n" .
                "Tên xét nghiệm: ".$loai[0]['tenloaixetnghiem']. "\n" .
                "Ngày xét nghiệm: " . $_POST['appointmentDate'] . "\n" .
                "Giờ xét nghiệm: " . $kg[0]['giobatdau']. "\n" .
                "Bác sĩ đặt lịch: ".$bacsi[0]['hoten']
            );
            $result = $builder->build();
            file_put_contents($savePath, $result->getString());
            if ($clichxetnghiem->create_lichxetnghiem($_POST['patientId'],$_POST['test'],$_POST['appointmentDate'],$_POST['appointmentTime'],'Đã đặt lịch',$_POST['hoso'], $filename)) {
                $message = '<strong>Thành công!</strong> Bạn đã đặt lịch xét nghiệm thành công.';
                $messageType = 'success';
            } else {
                $message = '<strong>Thất bại!</strong> Đặt lịch xét nghiệm không thành công. Vui lòng thử lại.';
                $messageType = 'error';
            }
        } else {
            $message = '<strong>Lỗi!</strong> Vui lòng điền đầy đủ thông tin.';
            $messageType = 'error';
        }
        
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lịch Xét Nghiệm - Bệnh Viện Hạnh Phúc</title>
    <!-- Thêm các file CSS của bạn ở đây -->
</head>
<body>
    <main class="container">
        <div class="content-header">
            <div class="back-button">
                <a href="index.php" class="btn-icon">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>Đặt Lịch Xét Nghiệm</h1>
            </div>
        </div>

        <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
            <div>
                <?php echo $message; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Lưu ý:</strong> Vui lòng đặt lịch xét nghiệm trước ít nhất 24 giờ. Đối với các xét nghiệm đặc biệt, bạn có thể được yêu cầu chuẩn bị trước (như nhịn ăn, uống nhiều nước, v.v.).
            </div>
        </div>
        
        <div class="content-header"> 
            <a href="?action=taohoso" style="float:right; margin-bottom: 5px;" class="btn-primary btn-small">Tạo hồ sơ</a>
        </div>
        
        <form action="" method="post" id="appointmentForm" onsubmit="return validateForm()">
            <div class="card">
                <div class="card-header">
                    <h2>Thông Tin Bệnh Nhân</h2>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="patientId">Mã bệnh nhân</label>
                                <select name="patientId" id="patientId" required>
                                    <option value="">-- Chọn bệnh nhân --</option>
                                    <?php foreach ($benhnhan_list as $i): ?>
                                    <option value="<?php echo $i['mabenhnhan']; ?>"><?php echo $i['mabenhnhan'] . ' - ' . $i['hotenbenhnhan']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label for="hoso">Hồ sơ bệnh nhân</label>
                                <select name="hoso" id="hoso" required>
                                    <option value="">-- Chọn hồ sơ --</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label for="patientName">Họ và tên</label>
                                <input type="text" id="patientName" name="patientName" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="patientDob">Ngày sinh</label>
                                <input type="text" id="patientDob" name="patientDob" readonly>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="patientGender">Giới tính</label>
                                <input type="text" id="patientGender" name="patientGender" readonly>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="patientPhone">Số điện thoại</label>
                                <input type="text" id="patientPhone" name="patientPhone" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Chọn Xét Nghiệm</h2>
                </div>
                <div class="card-body">
                    <div class="test-categories">
                        <?php foreach ($danhmucxetnghiem as $index => $i): ?>
                        <div class="category-item">
                            <div class="category-header <?php echo $index === 0 ? 'active' : ''; ?>" onclick="toggleCategory(this)">
                                <h3><?php echo $i['tendanhmuc']; ?></h3>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <?php
                                $chitietdanhmuc = $cloaixetnghiem->get_chitietdanhmuc_madanhmuc($i['madanhmuc']);
                            ?>
                            <div class="category-tests" <?php echo $index === 0 ? 'style="display: block;"' : ''; ?>>
                                <?php foreach ($chitietdanhmuc as $j): ?>
                                <div class="test-item">
                                    <input type="radio" name="test" value="<?php echo $j['maloaixetnghiem']; ?>" id="test_<?php echo $j['maloaixetnghiem']; ?>" class="test-checkbox">
                                    <label for="test_<?php echo $j['maloaixetnghiem']; ?>" class="test-info">
                                        <div class="test-name"><?php echo $j['tenloaixetnghiem']; ?></div>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2>Thông Tin Lịch Hẹn</h2>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="appointmentDate">Ngày xét nghiệm</label>
                                <input type="date" id="appointmentDate" name="appointmentDate" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="appointmentTime">Giờ xét nghiệm</label>
                                <select id="appointmentTime" name="appointmentTime" required>
                                    <option value="">-- Chọn giờ --</option>
                                </select>
                                <div id="timeSlotLoading" style="display: none; margin-top: 5px;">
                                    <i class="fas fa-spinner fa-spin"></i> Đang tải khung giờ...
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Ghi chú</label>
                        <textarea name="notes" id="notes" rows="4" placeholder="Nhập các yêu cầu đặc biệt hoặc thông tin bổ sung..."></textarea>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; margin-top: 24px;">
                <button type="reset" class="btn-outline" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Làm mới
                </button>
                <button type="submit" name="btnxacnhan" class="btn-primary" id="submitButton">
                    <i class="fas fa-calendar-check"></i> Xác nhận đặt lịch
                </button>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-content">
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> Bệnh Viện Hạnh Phúc. Tất cả các quyền được bảo lưu.
            </div>
            <div class="footer-links">
                <a href="about.php">Về chúng tôi</a>
                <a href="privacy.php">Chính sách bảo mật</a>
                <a href="terms.php">Điều khoản sử dụng</a>
                <a href="contact.php">Liên hệ</a>
            </div>
        </div>
    </footer>

    <script>
        // Hàm kiểm tra form trước khi submit
        function validateForm() {
            const patientId = document.getElementById('patientId').value;
            const hoso = document.getElementById('hoso').value;
            const test = document.querySelector('input[name="test"]:checked')?.value;
            const appointmentDate = document.getElementById('appointmentDate').value;
            const appointmentTime = document.getElementById('appointmentTime').value;
            
            if (!patientId) {
                alert('Vui lòng chọn bệnh nhân.');
                return false;
            }
            
            if (!hoso) {
                alert('Vui lòng chọn hồ sơ bệnh nhân.');
                return false;
            }
            
            if (!test) {
                alert('Vui lòng chọn loại xét nghiệm.');
                return false;
            }
            
            if (!appointmentDate) {
                alert('Vui lòng chọn ngày xét nghiệm.');
                return false;
            }
            
            if (!appointmentTime) {
                alert('Vui lòng chọn giờ xét nghiệm.');
                return false;
            }
            
            return confirm('Bạn có chắc chắn muốn đặt lịch xét nghiệm này không?');
        }
        
        // Hàm làm mới form
        function resetForm() {
            document.getElementById('patientName').value = '';
            document.getElementById('patientDob').value = '';
            document.getElementById('patientGender').value = '';
            document.getElementById('patientPhone').value = '';
            
            const hosoSelect = document.getElementById('hoso');
            hosoSelect.innerHTML = '<option value="">-- Chọn hồ sơ --</option>';
            
            const timeSelect = document.getElementById('appointmentTime');
            timeSelect.innerHTML = '<option value="">-- Chọn giờ --</option>';
            
            document.querySelectorAll('input[name="test"]').forEach(radio => {
                radio.checked = false;
            });
        }
        
        function toggleCategory(element) {
            element.classList.toggle('active');
            const categoryTests = element.nextElementSibling;
            if (categoryTests.style.display === 'block') {
                categoryTests.style.display = 'none';
            } else {
                categoryTests.style.display = 'block';
            }
        }

        document.getElementById('patientId').addEventListener('change', function() {
            const patientId = this.value;

            document.getElementById('patientName').value = '';
            document.getElementById('patientDob').value = '';
            document.getElementById('patientGender').value = '';
            document.getElementById('patientPhone').value = '';
            
            const hosoSelect = document.getElementById('hoso');
            hosoSelect.innerHTML = '<option value="">-- Chọn hồ sơ --</option>';

            if (!patientId) return;

            const patients = <?php echo json_encode($benhnhan_list); ?>;
            const patient = patients.find(p => p.mabenhnhan === patientId);

            const hosos = <?php echo json_encode($chosobenhandientu->get_hsba()); ?>;
            const patientHosos = hosos.filter(h => h.mabenhnhan === patientId);

            if (patient) {
                document.getElementById('patientName').value = patient.hotenbenhnhan;
                document.getElementById('patientDob').value = patient.ngaysinh;
                document.getElementById('patientGender').value = patient.gioitinh;
                document.getElementById('patientPhone').value = patient.sdtbenhnhan;
            }

            patientHosos.forEach(h => {
                const option = document.createElement('option');
                option.value = h.mahoso;
                option.textContent = `${h.mahoso} - ${h.ghichu} - ${h.ngaytao}`;
                hosoSelect.appendChild(option);
            });
        });

        document.querySelectorAll('input[name="test"]').forEach(radio => {
            radio.addEventListener('change', updateTimeSlots);
        });
        
        document.getElementById('appointmentDate').addEventListener('change', updateTimeSlots);
        function updateTimeSlots() {
            const selectedTest = document.querySelector('input[name="test"]:checked')?.value;
            const selectedDate = document.getElementById('appointmentDate').value;
            
            const timeSlotLoading = document.getElementById('timeSlotLoading');
            const timeSelect = document.getElementById('appointmentTime');
            
            timeSelect.innerHTML = '<option value="">-- Chọn giờ --</option>';
            
            if (!selectedTest || !selectedDate) return;
            
            timeSlotLoading.style.display = 'block';
            
            const lichxetnghiem = <?php echo json_encode($clichxetnghiem->get_lichxetnghiem()); ?>;
            const lichxetnghiem_selected = lichxetnghiem.filter(h => 
               (h.ngayhen === selectedDate) && (h.maloaixetnghiem === selectedTest)
            );
            
            const selected_makhunggio = lichxetnghiem_selected.map(h => h.makhunggio);
            const khunggioxetnghiem = <?php echo json_encode($ckhunggioxetnghiem->get_khunggioxetnghiem()); ?>;
    
            const khunggioxetnghiem_slot = khunggioxetnghiem.filter(h => 
                !selected_makhunggio.includes(h.makhunggioxetnghiem)
            );
            timeSlotLoading.style.display = 'none';
            
            if (khunggioxetnghiem_slot.length > 0) {
                khunggioxetnghiem_slot.forEach(h => {
                    const option = document.createElement('option');
                    option.value = h.makhunggioxetnghiem;
                    option.textContent = h.giobatdau;
                    timeSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "Không có khung giờ trống cho ngày này";
                option.disabled = true;
                timeSelect.appendChild(option);
                
                alert("Không có khung giờ trống cho ngày này. Vui lòng chọn ngày khác.");
            }
        }
    </script>
</body>
</html>