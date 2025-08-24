<?php
require 'vendor/autoload.php';
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

include_once("Assets/config.php");
include_once('Controllers/cbenhnhan.php');
include_once('Controllers/cchuyenkhoa.php');
include_once('Controllers/chosobenhandientu.php');
include_once('Controllers/cdonthuoc.php');
include_once('Controllers/cthuoc.php');
include_once('Controllers/cchitietdonthuoc.php');
include_once('Controllers/cchitiethoso.php');
include_once('Controllers/cbacsi.php');
include_once('Controllers/clichxetnghiem.php');
include_once('Controllers/cketquaxetnghiem.php');
include_once('Controllers/cloaixetnghiem.php');
include_once('Controllers/ckhunggioxetnghiem.php');

$ckhunggioxetnghiem = new cKhungGioXetNghiem();
$cbacsi = new cBacSi();
$chosobenhandientu = new cHoSoBenhAnDienTu();
$cchitietdongthuoc = new cChiTietDonThuoc();
$cchitiethoso = new cChiTietHoSo();
$cbenhnhan = new cBenhnhan();
$cchuyenkhoa = new cChuyenKhoa();
$cdonthuoc = new cDonThuoc();
$cthuoc = new cThuoc();
$clichxetnghiem = new cLichXetNghiem();
$cketquaxetnghiem = new cKetQuaXetNghiem();
$cloaixetnghiem = new cLoaiXetNghiem();
$mahoso = $_GET['mahoso'];
$thuoc = $cthuoc->get_thuoc();

$loaixetnghiem = $cloaixetnghiem-> get_loaixetnghiem();
$benhnhan = $chosobenhandientu->get_benhnhan_mahoso($mahoso);
$chitiethoso = $chosobenhandientu->getDonThuocByIDHS($mahoso);
$bacsi = $cbacsi->getBacSiByTenTK($_SESSION['user']['tentk']);
$chuyenkhoa = $cchuyenkhoa->get_chuyenkhoa_mabacsi($bacsi['mabacsi']);
$hoso = $chosobenhandientu->get_hoso_mahoso($mahoso);
$lichxetnghiem = $clichxetnghiem->get_lichxetnghiem_mahoso($mahoso);
$donthuoc = $cdonthuoc->get_donthuoc_mahoso($mahoso);
$chitiethoso_mahoso = $cchitiethoso->get_chitiethoso_mahoso($mahoso);
$message = "";

// Xử lý form khi submit
if(isset($_POST['btnupdate'])) {
    if(isset($_POST['medications']) && !empty($_POST['medications'])){
        // Tạo đơn thuốc mới
        if($cdonthuoc->create_donthuoc($_POST['ghichu'])){
            $donthuoc = $cdonthuoc->get_donthuoc_new();
            $madonthuoc=$donthuoc[0]['madonthuoc'];
            foreach($_POST['medications'] as $thuoc){
                $cchitietdongthuoc->create_chitietdonthuoc(
                    $madonthuoc,
                    $thuoc['mathuoc'],
                    $thuoc['lieudung'],
                    $thuoc['thoigianuong'],
                    $thuoc['songayuong']  
                );
            }
        }
    }
    if (!empty($benhnhan[0]['mabenhnhan']) && !empty($_POST['test']) && !empty($_POST['appointmentDate']) && !empty($_POST['appointmentTime']) && !empty($mahoso)) {
        // Tạo tên file duy nhất (dựa theo thời gian)
        $filename = 'qr_' . time() . '.png'; // Ví dụ: qr_1716634452.png
        $savePath = 'Assets/img/' . $filename;

        $kg= $ckhunggioxetnghiem->get_khunggioxetnghiem_makhunggio($_POST['appointmentTime']);
        $loai =$cloaixetnghiem->get_loaixetnghiem_maloaixetnghiem($_POST['test']);
        $bn_id=$cbenhnhan->getbenhnhanbyid($benhnhan[0]['mabenhnhan']);
        // Tạo QR code
        $builder = new Builder(
            writer: new PngWriter(),
            data: $data = "Họ tên: " . $benhnhan[0]['mabenhnhan'] . "\n" .
            "SĐT: " . decryptData($benhnhan[0]['sdtbenhnhan']) . "\n" .
            "Tên xét nghiệm: ".$loai[0]['tenloaixetnghiem']. "\n" .
            "Ngày xét nghiệm: " . $_POST['appointmentDate'] . "\n" .
            "Giờ xét nghiệm: " . $kg[0]['giobatdau']. "\n" .
            "Bác sĩ đặt lịch: ".$bacsi['hoten']
        );
        $result = $builder->build();
        file_put_contents($savePath, $result->getString());
        if ($clichxetnghiem->create_lichxetnghiem($benhnhan[0]['mabenhnhan'],$_POST['test'],$_POST['appointmentDate'],$_POST['appointmentTime'],'Đã đặt lịch',$mahoso,$filename)) {
            $message = '<strong>Thành công!</strong> Cập nhật hồ sơ thành công';
            $messageType = 'success';
        } else {
            $message = '<strong>Thất bại!</strong> Cập nhật hồ sơ thất bại vui lòng thử lại.';
            $messageType = 'error';
        }
    }
    if($cchitiethoso->create_chitiethoso($mahoso,$bacsi['mabacsi'],$_POST['trieuchung'],$_POST['chandoan'],$_POST['huongdieutri'],$madonthuoc,$_POST['ketluan']) ){
        $message = '<strong>Thành công!</strong> Cập nhật hồ sơ thành công';
        echo '<script>
            alert("Thành công! Cập nhật hồ sơ thành công");
            window.location.href = "?action=chitiethoso&mahoso=' . $mahoso . '";
        </script>';
        exit();
    }
    else{
        $message = '<strong>Thất bại!</strong> Cập nhật hồ sơ thất bại vui lòng thử lại.';
        echo '<script>
            alert("Thất bại!Cập nhật hồ sơ thất bại vui lòng thử lại");
            window.location.href = "?action=chitiethoso&mahoso=' . $mahoso . '";
        </script>';
        exit();
    }  

}
?>
<link rel="stylesheet" href="Views/bacsi/assets/css/csschitiethoso.css">
    <main class="container"> 
        <div class="content-header">
            <div class="back-button">
                <a href="?action=chitietbenhnhan&id=<?php echo $benhnhan[0]['mabenhnhan']; ?>" class="btn-icon">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>Chi Tiết Hồ Sơ Bệnh Án</h1>
            </div>
            
            <div class="action-buttons">
                <a href="javascript:window.print()" class="btn-small">
                    <i class="fas fa-print"></i> In hồ sơ
                </a>
            </div>
        </div>
        
        <?php if(!empty($message)): ?>
            <div class="alert alert-success">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <?php if (empty($mahoso)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Lưu ý!</strong> Vui lòng chọn hồ sơ bệnh nhân để xem.
                </div>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <a href="?action=benhnhan" class="btn-primary">
                    <i class="fas fa-user-injured"></i> Chọn hồ sơ
                </a>
            </div>
        <?php else: ?>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-user-circle"></i>
                    Thông Tin Bệnh Nhân
                </h2>
            </div>
            <div class="card-body">
                <div class="patient-info">
                    <div class="patient-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="patient-details">
                        <h3 class="patient-name"><?php echo $benhnhan[0]['hotenbenhnhan']; ?></h3>
                        <div class="patient-id"><?php echo $benhnhan[0]['mabenhnhan']; ?></div>
                        
                        <div class="patient-data">
                            <div class="patient-data-item">
                                <div class="data-label">Ngày sinh</div>
                                <div class="data-value"><?php echo $benhnhan[0]['ngaysinh']; ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Giới tính</div>
                                <div class="data-value"><?php echo $benhnhan[0]['gioitinh']; ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Nghề nghiệp</div>
                                <div class="data-value"><?php echo $benhnhan[0]['nghenghiep']; ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Nhóm máu</div>
                                <div class="data-value"><?php echo $benhnhan[0]['nhommau']; ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Tiền sử bệnh tật của bản thân</div>
                                <div class="data-value"><?php echo $benhnhan[0]['tiensubenhtatcuabenhnhan']?decryptData( $benhnhan[0]['tiensubenhtatcuabenhnhan']) :"Không có"; ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Tiền sử bệnh tật của gia đình</div>
                                <div class="data-value"><?php echo $benhnhan[0]['tiensubenhtatcuagiadinh']?decryptData($benhnhan[0]['tiensubenhtatcuagiadinh']):"Không có"; ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Địa chỉ</div>
                                <div class="data-value"><?php echo $benhnhan[0]['sonha'].','.$benhnhan[0]['quan/huyen'].','.$benhnhan[0]['xa/phuong'].','.$benhnhan[0]['tinh/thanhpho']; ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Số điện thoại</div>
                                <div class="data-value"><?php echo decryptData($benhnhan[0]['sdtbenhnhan']); ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Email</div>
                                <div class="data-value"><?php echo decryptData($benhnhan[0]['email']); ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">CCCD</div>
                                <div class="data-value"><?php echo $benhnhan[0]['cccdbenhnhan']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-file-medical"></i>
                    Thông Tin Hồ Sơ
                </h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Mã hồ sơ</div>
                            <div class="info-value"><?php echo $mahoso; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Ngày tạo</div>
                            <div class="info-value"><?php echo $benhnhan[0]['ngaytao']; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Khoa phòng</div>
                            <div class="info-value"><?php echo isset($chitiethoso_mahoso[0]['tenchuyenkhoa']) ? $chitiethoso_mahoso[0]['tenchuyenkhoa'] : $chuyenkhoa['tenchuyenkhoa']; ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Bác sĩ tạo hồ sơ </div>
                            <div class="info-value"> <?php echo $hoso[0]['hoten']; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email bác sĩ</div>
                            <div class="info-value"><?php echo $hoso[0]['emailbs']; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Số điện thoại bác sĩ</div>
                            <div class="info-value"><?php echo $hoso[0]['sdt']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(isset($chitiethoso_mahoso[0]['tenchuyenkhoa']) && $chitiethoso_mahoso[0]['tenchuyenkhoa'] == $chuyenkhoa['tenchuyenkhoa']): ?>
                <button type="button" class="btn-small btn-primary" onclick="openUpdateRecordModal()">
                    <i class="fas fa-edit"></i> Cập nhật hồ sơ
                </button>
        <?php endif; ?>    

        <div class="tabs">
            <div class="tab-header">
                <a href="#lab-tests" class="tab-link" onclick="openTab(event, 'lab-tests')">Xét nghiệm</a>
                <a href="#diagnosis" class="tab-link active" onclick="openTab(event, 'diagnosis')">Chẩn đoán & Hướng điều trị</a>
                <a href="#medications" class="tab-link" onclick="openTab(event, 'medications')">Thuốc điều trị</a>
            </div>
            <div id="lab-tests" class="tab-content">
                <div class="card">
                    <div class="card-body no-padding">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Ngày hẹn</th>
                                    <th>Giờ khám</th>
                                    <th>Xét nghiệm</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($lichxetnghiem):?>
                                    <?php foreach ($lichxetnghiem as $i): ?>
                                    <tr>
                                        <td><?php echo $i['ngayhen']; ?></td>
                                        <td><?php echo $i['giobatdau']; ?></td>
                                        <td><?php echo $i['tenloaixetnghiem']; ?></td>
                                        <td><?php echo $i['trangthailichxetnghiem']; ?></td>
                                        <td>
                                            <?php if ($i['trangthailichxetnghiem'] == "Đã hoàn thành"): 
                                                $ketqua = $cketquaxetnghiem->get_ketquaxetnghiem($i['malichxetnghiem']);
                                                $ketquaJson = json_encode($ketqua);
                                            ?>
                                                <button class="btn-small btn-primary" onclick='openXetNghiemPopup(<?php echo $ketquaJson; ?>)'>
                                                    Xem kết quả
                                                </button>
                                            <?php elseif ($i['trangthailichxetnghiem'] == "Đã hủy"): ?>
                                                <button class="btn-small btn-secondary" disabled>Đã hủy</button>
                                            <?php else: ?>
                                                <button class="btn-small btn-secondary" disabled>Chờ kết quả</button>
                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" style="text-align:center; color:gray;">Chưa có xét nghiệm</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="diagnosis" class="tab-content active">
                <div class="card">
                    <div class="card-body no-padding">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Ngày khám</th>
                                    <th>Bác sĩ</th>
                                    <th>Triệu chứng ban đầu</th>
                                    <th>Kết luận</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (isset($chitiethoso_mahoso) && is_array($chitiethoso_mahoso)):
                                    foreach ($chitiethoso_mahoso as $cd): 
                                        $chitiet = $cchitiethoso->get_chitiethoso_machitiethoso($cd['machitiethoso']);
                                        $chitietJson = json_encode($chitiet);
                                ?>
                                <tr>
                                    <td><?php echo $cd['ngaykham']; ?></td>
                                    <td><?php echo $cd['hoten']; ?></td>
                                    <td><?php echo $cd['trieuchungbandau']; ?></td>
                                    <td><?php echo $cd['ketluan']; ?></td>
                                    <td>
                                        <button class="btn-small btn-primary" onclick='openChuanDoanPopup(<?php echo $chitietJson;?>)'>
                                            Xem chi tiết
                                        </button>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Medications Tab -->
            <div id="medications" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Thuốc điều trị</h2>
                    </div>
                    <div class="card-body no-padding">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên Đơn thuốc</th>
                                    <th>Ngày tạo đơn</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $dem = 1; ?>
                                <?php foreach ($donthuoc as $i): ?>
                                <tr>
                                    <td><?php echo $dem; ?></td>
                                    <td><?php echo $i['ghichudonthuoc']; ?></td>
                                    <td><?php echo $i['ngaytaodonthuoc']; ?></td>
                                    <td>
                                        <?php
                                            $chitietdonthuoc = $cchitietdongthuoc->get_chitietdonthuoc_madonthuoc($i['madonthuoc']);
                                            $chitietdonthuocJson = json_encode($chitietdonthuoc);
                                        ?>
                                        <button class="btn-small btn-primary" onclick='openchitietdonthuoc(<?php echo $chitietdonthuocJson; ?>)'>
                                            Chi tiết
                                        </button>    
                                    </td>
                                    <?php $dem++; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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

    <!-- Modal Xét nghiệm -->
    <div id="modalxetnghiem" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeXetNghiemPopup()">&times;</span>
            <h2>Kết quả xét nghiệm</h2>
            <!-- Bảng kết quả xét nghiệm -->
            <div id="bangketqua">
                <table class="data-table" id="resultTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên chỉ số</th>
                            <th>Kết quả</th>
                            <th>Đơn vị</th>
                            <th>Khoảng tham chiếu</th>
                            <th>Nhận xét</th>
                        </tr>
                    </thead>
                    <tbody id="resultTableBody">
                        <!-- Dữ liệu sẽ được thêm vào đây bằng JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <div style="display: flex; justify-content: flex-end; margin-top: 20px; gap: 10px;">
                <button type="button" class="btn-outline" onclick="closeXetNghiemPopup()">
                    <i class="fas fa-times"></i> Đóng
                </button>
                <button type="button" class="btn-primary" onclick="printResults()">
                    <i class="fas fa-print"></i> In kết quả
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Chi tiết đơn thuốc -->
    <div id="modalchitietdonthuoc" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closechitietdonthuoc()">&times;</span>
            <h2>Đơn thuốc</h2>
            <!-- Bảng chi tiết đơn thuốc -->
            <div id="bangdonthuoc">
                <table class="data-table" id="resultTable">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên thuốc</th>
                            <th>Dạng bào chế</th>
                            <th>Liều dùng</th>
                            <th>Thời gian uống</th>
                            <th>Số ngày uống</th>
                        </tr>
                    </thead>
                    <tbody id="chitietid">
                        <!-- Dữ liệu sẽ được thêm vào đây bằng JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <div style="display: flex; justify-content: flex-end; margin-top: 20px; gap: 10px;">
                <button type="button" class="btn-outline" onclick="closechitietdonthuoc()">
                    <i class="fas fa-times"></i> Đóng
                </button>
                <button type="button" class="btn-primary" onclick="printDonThuoc()">
                    <i class="fas fa-print"></i> In đơn thuốc
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Chẩn đoán và Hướng điều trị -->
    <div id="modalchuandoan" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeChuanDoanPopup()">&times;</span>
            <h2>Chi tiết chẩn đoán và hướng điều trị</h2>
            
            <div class="info-row">
                <div class="info-label">Ngày khám</div>
                <div class="info-value" id="cd-ngaykham"></div>
            </div>
            <div class="info-row">
                <div class="info-label">Bác sĩ</div>
                <div class="info-value" id="cd-bacsi"></div>
            </div>
            <div class="info-row">
                <div class="info-label">Triệu chứng ban đầu</div>
                <div class="info-value" id="cd-trieuchung"></div>
            </div>
            <div class="info-row">
                <div class="info-label">Chẩn đoán</div>
                <div class="info-value" id="cd-chandoan"></div>
            </div>
            <div class="info-row">
                <div class="info-label">Hướng điều trị</div>
                <div class="info-value" id="cd-kehoachdieutri"></div>
            </div>
            <div class="info-row">
                <div class="info-label">Kết luận</div>
                <div class="info-value" id="cd-ketluan"></div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 20px; gap: 10px;">
                <button type="button" class="btn-outline" onclick="closeChuanDoanPopup()">
                    <i class="fas fa-times"></i> Đóng
                </button>
                <button type="button" class="btn-primary" onclick="printChuanDoan()">
                    <i class="fas fa-print"></i> In chi tiết
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Cập nhật hồ sơ -->
    <div id="modalcapnhathoso" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateRecordModal()">&times;</span>
            <h2>Cập nhật hồ sơ bệnh án</h2>
            <form action="" method="post" id="prescriptionForm">
                <div class="tabs update-tabs">
                    <div class="tab-header">
                        <a href="#update-prescription" class="update-tab-link active" onclick="openUpdateTab(event, 'update-prescription')">Thêm đơn thuốc</a>
                        <a href="#update-test" class="update-tab-link" onclick="openUpdateTab(event, 'update-test')">Thêm lịch xét nghiệm</a>
                        <a href="#update-diagnosis" class="update-tab-link" onclick="openUpdateTab(event, 'update-diagnosis')">Thêm chẩn đoán</a>
                        <div  style="display: flex; justify-content: flex-end; margin-top: 20px; gap: 10px; margin-bottom: 20px; margin-left: 150px;">
                            <button type="submit" name="btnupdate" class="btn-small btn-primary">
                                <i class="fas fa-edit"></i> Cập nhật hồ sơ
                            </button>
                        </div>
                    </div>
                    
                    <!-- Tab thêm đơn thuốc -->
                    <div id="update-prescription" class="update-tab-content active">
                            <input type="hidden" name="action" value="taodonthuoc">
                            <input type="hidden" name="mahoso" value="<?php echo $mahoso; ?>">
                            
                            <div class="medication-form">
                                <h3 class="form-title">Thông tin thuốc</h3>
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="tenthuoc">Tên thuốc</label>
                                            <select name="tenthuoc" id="tenthuoc" placeholder="Nhập tên thuốc" required>
                                                <?php
                                                    foreach($thuoc as $i){
                                                        echo '<option value="'.$i['mathuoc'].'">'.$i['tenthuoc'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="soluong">Số lượng</label>
                                            <input type="number" id="soluong" name="soluong" value="" placeholder="Số lượng">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="lieudung">Liều dùng</label>
                                            <input type="text" id="lieudung" name="lieudung" placeholder="Ví dụ: 3 lần/ngày">
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="songayuong">Số ngày uống</label>
                                            <input type="text" id="songayuong" name="songayuong" placeholder="Ví dụ: 7 ngày">
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="thoigianuong">Thời gian uống</label>
                                            <input type="text" id="thoigianuong" name="thoigianuong" placeholder="Ví dụ: Sau ăn">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ghichu">Ghi chú đơn thuốc</label>
                                    <input type="text" id="ghichu" name="ghichu" placeholder="Ghi chú về đơn thuốc">
                                </div>
                                <div style="text-align: right; margin-top: 15px;">
                                    <button type="button" class="btn-primary" onclick="addMedicationToList()">
                                        <i class="fas fa-plus"></i> Thêm vào đơn thuốc
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Bảng danh sách thuốc -->
                            <div id="bangthuoc" style="display: none;">
                                <h3>Danh sách thuốc trong đơn</h3>
                                <table class="medication-table" id="medicationTable">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên thuốc</th>
                                            <th>Số lượng</th>
                                            <th>Liều dùng</th>
                                            <th>Số ngày uống</th>
                                            <th>Thời gian uống</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="medicationTableBody">
                                        <!-- Danh sách thuốc sẽ được thêm vào đây -->
                                    </tbody>
                                </table>
                            </div>
                        <div id="medicationsContainer"></div>
                    </div>
                    
                    <!-- Tab thêm lịch xét nghiệm -->
                    <div id="update-test" class="update-tab-content">
                            <input type="hidden" name="action" value="themlichxetnghiem">
                            <input type="hidden" name="mahoso" value="<?php echo $mahoso; ?>">
                            
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="test">Loại xét nghiệm</label>
                                        <select name="test" id="test" >
                                            <option value="">--Chọn xét nghiệm---</option>
                                            <?php
                                                foreach($loaixetnghiem as $i){
                                                    echo '<option value="'.$i['maloaixetnghiem'].'">'.$i['tenloaixetnghiem'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="appointmentDate">Ngày xét nghiệm</label>
                                        <input type="date" id="appointmentDate" name="appointmentDate"  min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="appointmentTime">Giờ xét nghiệm</label>
                                        <select id="appointmentTime" name="appointmentTime" >
                                            <option value="">-- Chọn giờ --</option>
                                        </select>
                                        <div id="timeSlotLoading" style="display: none; margin-top: 5px;">
                                            <i class="fas fa-spinner fa-spin"></i> Đang tải khung giờ...
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                            <div class="form-group">
                                <label for="ghichuxetnghiem">Ghi chú</label>
                                <textarea id="ghichuxetnghiem" name="ghichuxetnghiem" rows="3" placeholder="Ghi chú về xét nghiệm..."></textarea>
                            </div>
                    </div>
                    
                    <!-- Tab thêm chẩn đoán -->
                    <div id="update-diagnosis" class="update-tab-content">
                            <input type="hidden" name="action" value="themchuandoan">
                            <input type="hidden" name="mahoso" value="<?php echo $mahoso; ?>">
                            
                            <div class="form-group">
                                <label for="trieuchung">Triệu chứng ban đầu</label>
                                <textarea id="trieuchung" name="trieuchung" rows="3" placeholder="Mô tả triệu chứng ban đầu của bệnh nhân..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="chandoan">Chẩn đoán</label>
                                <textarea id="chandoan" name="chandoan" rows="3" placeholder="Chẩn đoán của bác sĩ..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="huongdieutri">Hướng điều trị</label>
                                <textarea id="huongdieutri" name="huongdieutri" rows="3" placeholder="Hướng điều trị cho bệnh nhân..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="ketluan">Kết luận</label>
                                <textarea id="ketluan" name="ketluan" rows="3" placeholder="Kết luận của bác sĩ..." required></textarea>
                            </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif ;?>
    <style>
        /* Thêm CSS cho modal cập nhật hồ sơ */
        .update-tabs .tab-header {
            margin-bottom: 5px;
        }
        
        .update-tab-link {
            padding: 10px 15px;
            margin-right: 5px;
            border-radius: 4px 4px 0 0;
            background-color: #f5f5f5;
            color: #333;
            text-decoration: none;
            border-bottom: 2px solid transparent;
        }
        
        .update-tab-link.active {
            background-color: #fff;
            border-bottom: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .update-tab-content {
            display: none;
            padding: 20px;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .update-tab-content.active {
            display: block;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
    </style>

    <script>
        // Khai báo biến toàn cục để lưu danh sách thuốc
        let medications = [];
        
        // Tab functionality
        function openTab(evt, tabId) {
            // Hide all tab content
            var tabContents = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active");
            }
            
            // Remove active class from all tab links
            var tabLinks = document.getElementsByClassName("tab-link");
            for (var i = 0; i < tabLinks.length; i++) {
                tabLinks[i].classList.remove("active");
            }
            
            // Show the current tab and add active class to the button
            document.getElementById(tabId).classList.add("active");
            evt.currentTarget.classList.add("active");
        }

        // Tab functionality for update modal
        function openUpdateTab(evt, tabId) {
            // Hide all tab content
            var tabContents = document.getElementsByClassName("update-tab-content");
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active");
            }
            
            // Remove active class from all tab links
            var tabLinks = document.getElementsByClassName("update-tab-link");
            for (var i = 0; i < tabLinks.length; i++) {
                tabLinks[i].classList.remove("active");
            }
            
            // Show the current tab and add active class to the button
            document.getElementById(tabId).classList.add("active");
            evt.currentTarget.classList.add("active");
        }

        // Initialize the first tab as active
        document.addEventListener("DOMContentLoaded", function() {
            // Already handled by the PHP code setting the first tab as active
            
            // Thêm event listener cho form để đảm bảo dữ liệu được gửi đi
            const form = document.getElementById("prescriptionForm");
            
            if (form) {
                form.addEventListener("submit", function(e) {
                    // Nếu đang ở tab đơn thuốc và có thuốc trong danh sách
                    const activeTab = document.querySelector(".update-tab-content.active");
                    if (activeTab && activeTab.id === "update-prescription") {
                        // Kiểm tra xem có thuốc nào trong danh sách không
                        if (medications.length === 0) {
                            e.preventDefault(); // Ngăn form submit
                            alert("Vui lòng thêm ít nhất một loại thuốc vào đơn!");
                            return false;
                        }
                        
                        // Đảm bảo hidden inputs đã được cập nhật
                        updateMedicationInputs();
                    }
                });
            }
        });

        // Mở modal cập nhật hồ sơ
        function openUpdateRecordModal() {
            document.getElementById("modalcapnhathoso").style.display = "block";
        }

        // Đóng modal cập nhật hồ sơ
        function closeUpdateRecordModal() {
            document.getElementById("modalcapnhathoso").style.display = "none";
        }

        // Mở modal xem kết quả xét nghiệm
        function openXetNghiemPopup(ketquaxetnghiem) {
            console.log("Dữ liệu nhận được:", ketquaxetnghiem); // Debug
            
            // Hiển thị modal
            document.getElementById("modalxetnghiem").style.display = "block";
            
            // Lấy tham chiếu đến tbody của bảng
            const tableBody = document.getElementById("resultTableBody");
            
            // Xóa nội dung cũ
            tableBody.innerHTML = "";
            
            try {
                // Kiểm tra xem ketquaxetnghiem có phải là mảng và có phần tử không
                if (Array.isArray(ketquaxetnghiem) && ketquaxetnghiem.length > 0) {
                    // Hiển thị bảng kết quả
                    document.getElementById("bangketqua").style.display = "block";
                    
                    // Thêm dữ liệu vào bảng
                    ketquaxetnghiem.forEach((ketqua, index) => {
                        const row = document.createElement("tr");
                        
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${ketqua.tenchisoxetnghiem || ''}</td>
                            <td>${ketqua.giatriketqua || ''}</td>
                            <td>${ketqua.donvikq || '-'}</td>
                            <td>${ketqua.khoangthamchieu || '-'}</td>
                            <td>${ketqua.nhanxet || '-'}</td>
                        `;
                        
                        tableBody.appendChild(row);
                    });
                } else {
                    // Nếu không có dữ liệu, hiển thị thông báo
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">
                                Không có dữ liệu kết quả xét nghiệm
                            </td>
                        </tr>
                    `;
                    document.getElementById("bangketqua").style.display = "block";
                }
            } catch (error) {
                console.error("Lỗi khi xử lý dữ liệu:", error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: red;">
                            Đã xảy ra lỗi khi hiển thị dữ liệu. Vui lòng thử lại.
                        </td>
                    </tr>
                `;
                document.getElementById("bangketqua").style.display = "block";
            }
        }

        // Đóng modal xét nghiệm
        function closeXetNghiemPopup() {
            document.getElementById("modalxetnghiem").style.display = "none";
        }

        // Mở modal chi tiết đơn thuốc
        function openchitietdonthuoc(chitietdonthuoc) {
            console.log("Chi tiết đơn thuốc:", chitietdonthuoc); // Debug
            
            document.getElementById("modalchitietdonthuoc").style.display = "block";
            
            const tableBody = document.getElementById("chitietid");
            
            tableBody.innerHTML = "";
            
            try {
                // Kiểm tra xem chitietdonthuoc có phải là mảng và có phần tử không
                if (Array.isArray(chitietdonthuoc) && chitietdonthuoc.length > 0) {
                    // Hiển thị bảng kết quả
                    document.getElementById("bangdonthuoc").style.display = "block";
                    
                    // Thêm dữ liệu vào bảng
                    chitietdonthuoc.forEach((i, index) => {
                        const row = document.createElement("tr");
                        
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${i.tenthuoc || ''}</td>
                            <td>${i.dangbaoche || ''}</td>
                            <td>${i.lieudung || '-'}</td>
                            <td>${i.thoigianuong || '-'}</td>
                            <td>${i.songayuong || '-'}</td>
                        `;
                        
                        tableBody.appendChild(row);
                    });
                } else {
                    // Nếu không có dữ liệu, hiển thị thông báo
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">
                                Không có dữ liệu chi tiết đơn thuốc
                            </td>
                        </tr>
                    `;
                    document.getElementById("bangdonthuoc").style.display = "block";
                }
            } catch (error) {
                console.error("Lỗi khi xử lý dữ liệu:", error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: red;">
                            Đã xảy ra lỗi khi hiển thị dữ liệu. Vui lòng thử lại.
                        </td>
                    </tr>
                `;
                document.getElementById("bangdonthuoc").style.display = "block";
            }
        }

        // Đóng modal chi tiết đơn thuốc
        function closechitietdonthuoc() {
            document.getElementById("modalchitietdonthuoc").style.display = "none";
        }

        // In kết quả xét nghiệm
        function printResults() {
            const printContents = document.getElementById("bangketqua").innerHTML;
            const originalContents = document.body.innerHTML;
            
            document.body.innerHTML = `
                <div style="padding: 20px;">
                    <h1 style="text-align: center; margin-bottom: 20px;">Kết Quả Xét Nghiệm</h1>
                    ${printContents}
                </div>
            `;
            
            window.print();
            document.body.innerHTML = originalContents;
            
            // Khôi phục lại các event listener sau khi in
            setTimeout(function() {
                // Khởi tạo lại các event listener
                document.querySelectorAll('.tab-link').forEach(function(tab) {
                    tab.addEventListener('click', function(event) {
                        const tabId = this.getAttribute('href').substring(1);
                        openTab(event, tabId);
                    });
                });
                
                // Đóng modal khi click bên ngoài
                window.onclick = function(event) {
                    const modal = document.getElementById("modalxetnghiem");
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                    
                    const modalDonThuoc = document.getElementById("modalchitietdonthuoc");
                    if (event.target === modalDonThuoc) {
                        modalDonThuoc.style.display = "none";
                    }
                };
            }, 100);
        }

        // In đơn thuốc
        function printDonThuoc() {
            const printContents = document.getElementById("bangdonthuoc").innerHTML;
            const originalContents = document.body.innerHTML;
            
            document.body.innerHTML = `
                <div style="padding: 20px;">
                    <h1 style="text-align: center; margin-bottom: 20px;">Đơn Thuốc</h1>
                    ${printContents}
                </div>
            `;
            
            window.print();
            document.body.innerHTML = originalContents;
            
            // Khôi phục lại các event listener sau khi in
            setTimeout(function() {
                // Khởi tạo lại các event listener
                document.querySelectorAll('.tab-link').forEach(function(tab) {
                    tab.addEventListener('click', function(event) {
                        const tabId = this.getAttribute('href').substring(1);
                        openTab(event, tabId);
                    });
                });
                
                // Đóng modal khi click bên ngoài
                window.onclick = function(event) {
                    const modal = document.getElementById("modalxetnghiem");
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                    
                    const modalDonThuoc = document.getElementById("modalchitietdonthuoc");
                    if (event.target === modalDonThuoc) {
                        modalDonThuoc.style.display = "none";
                    }
                };
            }, 100);
        }

        // Mở modal chi tiết chẩn đoán
        function openChuanDoanPopup(chitiet) {
            console.log("Chi tiết chẩn đoán:", chitiet); // Debug
            
            // Hiển thị modal
            document.getElementById("modalchuandoan").style.display = "block";
            
            // Cập nhật thông tin cơ bản
            document.getElementById("cd-ngaykham").textContent = chitiet[0].ngaykham;
            document.getElementById("cd-bacsi").textContent = chitiet[0].hoten|| "-";
            document.getElementById("cd-trieuchung").textContent = chitiet[0].trieuchungbandau|| "-";
            document.getElementById("cd-chandoan").textContent = chitiet[0].chandoan || "-";
            document.getElementById("cd-kehoachdieutri").textContent = chitiet[0].huongdieutri || "-";
            document.getElementById("cd-ketluan").textContent = chitiet[0].ketluan;
        }

        // Đóng modal chi tiết chẩn đoán
        function closeChuanDoanPopup() {
            document.getElementById("modalchuandoan").style.display = "none";
        }

        // In chi tiết chẩn đoán
        function printChuanDoan() {
            const ngaykham = document.getElementById("cd-ngaykham").textContent;
            const bacsi = document.getElementById("cd-bacsi").textContent;
            const trieuchung = document.getElementById("cd-trieuchung").textContent;
            const chandoan = document.getElementById("cd-chandoan").textContent;
            const kehoachdieutri = document.getElementById("cd-kehoachdieutri").textContent;
            const ketluan = document.getElementById("cd-ketluan").textContent;
            
            const originalContents = document.body.innerHTML;
            
            document.body.innerHTML = `
                <div style="padding: 20px;">
                    <h1 style="text-align: center; margin-bottom: 20px;">Chi Tiết Chẩn Đoán và Hướng Điều Trị</h1>
                    
                    <div style="margin-bottom: 10px;">
                        <div style="font-weight: bold;">Ngày khám:</div>
                        <div>${ngaykham}</div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <div style="font-weight: bold;">Bác sĩ:</div>
                        <div>${bacsi}</div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <div style="font-weight: bold;">Triệu chứng ban đầu:</div>
                        <div>${trieuchung}</div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <div style="font-weight: bold;">Chẩn đoán:</div>
                        <div>${chandoan}</div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <div style="font-weight: bold;">Hướng điều trị:</div>
                        <div>${kehoachdieutri}</div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <div style="font-weight: bold;">Kết luận:</div>
                        <div>${ketluan}</div>
                    </div>
                </div>
            `;
            
            window.print();
            document.body.innerHTML = originalContents;
            
            // Khôi phục lại các event listener sau khi in
            setTimeout(function() {
                // Khởi tạo lại các event listener
                document.querySelectorAll('.tab-link').forEach(function(tab) {
                    tab.addEventListener('click', function(event) {
                        const tabId = this.getAttribute('href').substring(1);
                        openTab(event, tabId);
                    });
                });
                
                // Đóng modal khi click bên ngoài
                window.onclick = function(event) {
                    const modal = document.getElementById("modalxetnghiem");
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                    
                    const modalDonThuoc = document.getElementById("modalchitietdonthuoc");
                    if (event.target === modalDonThuoc) {
                        modalDonThuoc.style.display = "none";
                    }
                    
                    const modalChuanDoan = document.getElementById("modalchuandoan");
                    if (event.target === modalChuanDoan) {
                        modalChuanDoan.style.display = "none";
                    }
                };
            }, 100);
        }

        // Thêm hàm này để xóa form sau khi thêm thuốc
        function clearMedicationForm() {
            document.getElementById("tenthuoc").selectedIndex = 0;
            document.getElementById("soluong").value = "";
            document.getElementById("lieudung").value = "";
            document.getElementById("songayuong").value = "";
            document.getElementById("thoigianuong").value = "";
            document.getElementById("ghichu").value = "";
        }

        // Thêm thuốc vào danh sách
        function addMedicationToList() {
            // Lấy thông tin thuốc từ form
            const tenthuocSelect = document.getElementById("tenthuoc");
            const tenthuoc = tenthuocSelect.options[tenthuocSelect.selectedIndex].text;
            const mathuoc = tenthuocSelect.value;
            const soluong = document.getElementById("soluong").value;
            const lieudung = document.getElementById("lieudung").value;
            const songayuong = document.getElementById("songayuong").value;
            const thoigianuong = document.getElementById("thoigianuong").value;
            const ghichu = document.getElementById("ghichu").value;
            
            // Kiểm tra các trường bắt buộc
            if (!mathuoc || !soluong) {
                alert("Vui lòng nhập đầy đủ tên thuốc và số lượng!");
                return;
            }
            
            // Thêm thuốc vào mảng
            medications.push({
                mathuoc,
                tenthuoc,
                soluong,
                lieudung,
                songayuong,
                thoigianuong,
                ghichu
            });
            
            // Hiển thị bảng danh sách thuốc
            document.getElementById("bangthuoc").style.display = "block";
            
            // Cập nhật bảng danh sách thuốc
            updateMedicationTable();
            
            // Cập nhật hidden inputs
            updateMedicationInputs();
            
            // Xóa dữ liệu trong form để nhập thuốc mới
            clearMedicationForm();
        }
        
        // Cập nhật bảng danh sách thuốc
        function updateMedicationTable() {
            const tableBody = document.getElementById("medicationTableBody");
            tableBody.innerHTML = "";
            
            medications.forEach((med, index) => {
                const row = document.createElement("tr");
                
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${med.tenthuoc|| '-'}</td>
                    <td>${med.soluong|| '-'}</td>
                    <td>${med.lieudung || '-'}</td>
                    <td>${med.songayuong || '-'}</td>
                    <td>${med.thoigianuong || '-'}</td>
                    <td class="action-buttons">
                        <button type="button" class="btn-small btn-danger" onclick="removeMedication(${index})">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </td>
                `;
                
                tableBody.appendChild(row);
            });
        }
        
        // Thêm hàm mới để cập nhật hidden inputs
        function updateMedicationInputs() {
            const container = document.getElementById("medicationsContainer");
            container.innerHTML = "";
            
            // Thêm các thuốc
            medications.forEach((med, index) => {
                container.innerHTML += `
                    <input type="hidden" name="medications[${index}][mathuoc]" value="${med.mathuoc}">
                    <input type="hidden" name="medications[${index}][tenthuoc]" value="${med.tenthuoc}">
                    <input type="hidden" name="medications[${index}][soluong]" value="${med.soluong}">
                    <input type="hidden" name="medications[${index}][lieudung]" value="${med.lieudung || ''}">
                    <input type="hidden" name="medications[${index}][songayuong]" value="${med.songayuong || ''}">
                    <input type="hidden" name="medications[${index}][thoigianuong]" value="${med.thoigianuong || ''}">
                `;
                
                // Thêm ghi chú nếu có
                if (med.ghichu) {
                    container.innerHTML += `<input type="hidden" name="ghichu" value="${med.ghichu}">`;
                }
            });
        }
        
        // Xóa thuốc khỏi danh sách
        function removeMedication(index) {
            medications.splice(index, 1);
            
            // Cập nhật bảng danh sách thuốc
            updateMedicationTable();
            
            // Cập nhật hidden inputs
            updateMedicationInputs();
            
            // Ẩn bảng nếu không còn thuốc nào
            if (medications.length === 0) {
                document.getElementById("bangthuoc").style.display = "none";
            }
        }

        // Cập nhật window.onclick để đóng modal khi click bên ngoài
        window.onclick = function(event) {
            const modalXetNghiem = document.getElementById("modalxetnghiem");
            if (event.target === modalXetNghiem) {
                modalXetNghiem.style.display = "none";
            }
            
            const modalDonThuoc = document.getElementById("modalchitietdonthuoc");
            if (event.target === modalDonThuoc) {
                modalDonThuoc.style.display = "none";
            }
            
            const modalChuanDoan = document.getElementById("modalchuandoan");
            if (event.target === modalChuanDoan) {
                modalChuanDoan.style.display = "none";
            }

            const modalTaoDonThuoc = document.getElementById("modaltaodonthuoc");
            if (modalTaoDonThuoc && event.target === modalTaoDonThuoc) {
                modalTaoDonThuoc.style.display = "none";
            }
            
            const modalCapNhatHoSo = document.getElementById("modalcapnhathoso");
            if (event.target === modalCapNhatHoSo) {
                modalCapNhatHoSo.style.display = "none";
            }
        };

        document.getElementById('test').addEventListener('change', updateTimeSlots);
        document.getElementById('appointmentDate').addEventListener('change', updateTimeSlots);
        function updateTimeSlots() {
            const selectedTest = document.getElementById('test').value;
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
        };
    </script>
</body>
</html>
