<?php
include_once('Controllers/cbenhnhan.php');
include_once('Controllers/cchuyenkhoa.php');
include_once('Controllers/chosobenhandientu.php');
include_once('Controllers/cdonthuoc.php'); // Thêm controller đơn thuốc
include_once('Controllers/cthuoc.php');
include_once('Controllers/cchitietdonthuoc.php');
include_once('Controllers/cchitiethoso.php');
include_once('Controllers/cbacsi.php');
include_once("Assets/config.php");
$cbacsi = new cBacSi();
$chosobenhandientu = new cHoSoBenhAnDienTu();
$cchitietdongthuoc = new cChiTietDonThuoc();
$cchitiethoso = new cChiTietHoSo();
$cbenhnhan = new cBenhnhan();
$cchuyenkhoa = new cChuyenKhoa();
$cdonthuoc = new cDonThuoc();
$cthuoc = new cThuoc();
$mabenhnhan = $_GET['mabenhnhan'];
$thuoc = $cthuoc->get_thuoc();
$benhnhan = $cbenhnhan->getbenhnhanbyid($_GET['mabenhnhan']);
$chuyenkhoa = $cchuyenkhoa->get_chuyenkhoa_notmabenhnhan($mabenhnhan);
$bacsi= $cbacsi->getBacSiByTenTK($_SESSION['user']['tentk']);
$chuyenkhoa_bacsi = $cchuyenkhoa->get_chuyenkhoa_mabacsi($bacsi['mabacsi']);
$message = "";

if(isset($_POST['submit'])){
    // Tạo hồ sơ bệnh án mới
    if($chosobenhandientu->create_hosobenhan_mabenhnhan($mabenhnhan, $_POST['note'])){
        $hosonew = $chosobenhandientu->get_hsba_new($mabenhnhan);
        $mahoso = $hosonew[0]['mahoso']; // Lấy mã hồ sơ vừa tạo
        // Lưu đơn thuốc nếu có
        if(isset($_POST['medications']) && !empty($_POST['medications'])){
            // Tạo đơn thuốc mới
            if($cdonthuoc->create_donthuoc($_POST['note'])){
                $donthuoc = $cdonthuoc->get_donthuoc_new();
                $madonthuoc=$donthuoc[0]['madonthuoc'];
                foreach($_POST['medications'] as $thuoc){
                    $cchitietdongthuoc->create_chitietdonthuoc(
                        $madonthuoc,
                        $thuoc['tenthuoc'],
                        $thuoc['lieudung'],
                        $thuoc['thoigianuong'],
                        $thuoc['songayuong']  
                    );
                }
            }
        }
       if( $cchitiethoso->create_chitiethoso($mahoso,$bacsi['mabacsi'],$_POST['trieuchung'],$_POST['chuandoan'],$_POST['huongdieutri'],$madonthuoc,$_POST['note']) ){
            $message = "Hồ sơ bệnh án đã được tạo thành công!";
       }
       else{
            $message = "Hồ sơ bệnh án không được tạo thành công!";
       }  
    } else {
        $message = "Hồ sơ bệnh án không được tạo thành công!";
    }
}
?>
<style>
    .back-button {
        display: flex;
        align-items: center;
    }

    .back-button a {
        margin-right: 16px;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        font-size: 14px;
        transition: var(--transition);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) inset;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
        outline: none;
    }

    .form-group select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23718096' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .form-col {
        flex: 1;
        padding: 0 10px;
        min-width: 200px;
    }

    /* Patient Info */
    .patient-info {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 16px;
    }

    .patient-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        flex-shrink: 0;
    }

    .patient-avatar i {
        font-size: 48px;
        color: var(--primary);
    }

    .patient-details {
        flex: 1;
        min-width: 300px;
    }

    .patient-name {
        font-size: 20px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .patient-id {
        font-size: 14px;
        color: var(--gray);
        margin-bottom: 12px;
        display: inline-block;
        background-color: var(--light-gray);
        padding: 4px 12px;
        border-radius: 20px;
    }

    .patient-data {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
    }

    .patient-data-item {
        margin-bottom: 8px;
    }

    .data-label {
        font-size: 12px;
        color: var(--gray);
        margin-bottom: 4px;
    }

    .data-value {
        font-weight: 500;
        color: var(--dark);
    }

    /* Specialty Selection */
    .specialty-selection {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .specialty-card {
        border: 1px solid var(--light-gray);
        border-radius: var(--border-radius);
        padding: 16px;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
    }

    .specialty-card:hover {
        border-color: var(--primary);
        background-color: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .specialty-card.selected {
        border-color: var(--primary);
        background-color: var(--primary-light);
        box-shadow: var(--shadow);
    }

    .specialty-card.selected::before {
        content: "\f00c";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        top: 12px;
        right: 12px;
        color: var(--primary);
        background-color: var(--white);
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        box-shadow: var(--shadow);
    }

    .specialty-name {
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 8px;
    }

    .specialty-description {
        font-size: 13px;
        color: var(--gray);
    }

    /* Alert Messages */
    .alert {
        padding: 16px;
        border-radius: var(--border-radius);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
    }

    .alert i {
        margin-right: 12px;
        font-size: 20px;
    }

    .alert-success {
        background-color: var(--success-light);
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .alert-warning {
        background-color: var(--warning-light);
        color: var(--warning);
        border-left: 4px solid var(--warning);
    }

    .alert-info {
        background-color: var(--primary-light);
        color: var(--primary);
        border-left: 4px solid var(--primary);
    }

    .alert-danger {
        background-color: var(--danger-light);
        color: var(--danger);
        border-left: 4px solid var(--danger);
    }
    
    .tab-button {
        padding: 12px 24px;
        background-color: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 600;
        color: var(--gray);
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
        }
        
        .form-col {
            margin-bottom: 16px;
        }
        
        .patient-info {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .patient-avatar {
            margin-right: 0;
            margin-bottom: 16px;
        }
        
        .patient-data {
            grid-template-columns: 1fr;
        }
        
        .specialty-selection {
            grid-template-columns: 1fr;
        }
    }

    .modal {
        display: none; /* Ẩn ban đầu */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        overflow: auto;
        background-color: rgba(0,0,0,0.4); /* nền mờ */
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px;
        border-radius: 10px;
        width: 80%;
        max-width: 800px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        position: relative;
        max-height: 80vh;
        overflow-y: auto;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        font-weight: bold;
        color: #333;
        cursor: pointer;
    }

    /* Bảng danh sách thuốc */
    .medication-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .medication-table th,
    .medication-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid var(--light-gray);
    }

    .medication-table th {
        background-color: var(--primary-light);
        color: var(--primary-dark);
        font-weight: 600;
    }

    .medication-table tr:hover {
        background-color: var(--primary-light);
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-small {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: var(--transition);
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-danger {
        background-color: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        background-color: #d32f2f;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    .btn-success {
        background-color: var(--success);
        color: white;
    }

    .btn-success:hover {
        background-color: #388e3c;
    }

    .medication-form {
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid var(--light-gray);
    }

    .form-title {
        margin-bottom: 15px;
        font-weight: 600;
        color: var(--primary-dark);
        border-bottom: 1px solid var(--light-gray);
        padding-bottom: 10px;
    }
</style>
</head>
<body>
    <main class="container">
        <div class="content-header">
            <div class="back-button">
                <a href="medical-records.php" class="btn-icon">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>Tạo Hồ Sơ Bệnh Án</h1>
            </div>
        </div>

        <?php if (!empty($message)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Thành công!</strong> <?php echo $message; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (empty($mabenhnhan)): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Lưu ý!</strong> Vui lòng chọn bệnh nhân trước khi tạo hồ sơ bệnh án.
            </div>
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="?action=benhnhan" class="btn-primary">
                <i class="fas fa-user-injured"></i> Chọn bệnh nhân
            </a>
        </div>
        <?php elseif (!$mabenhnhan): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Lỗi!</strong> Không tìm thấy thông tin bệnh nhân với mã <strong><?php echo $mabenhnhan; ?></strong>.
            </div>
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="?action=benhnhan" class="btn-primary">
                <i class="fas fa-user-injured"></i> Chọn bệnh nhân khác
            </a>
        </div>
        <?php elseif ($chosobenhandientu->get_hoso_machuyenkhoa($mabenhnhan,$chuyenkhoa_bacsi['machuyenkhoa'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Lỗi!</strong> Hồ sơ của chuyên khoa <?php echo $chuyenkhoa_bacsi['tenchuyenkhoa'];?> đã được tạo.
            </div>
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="?action=chitietbenhnhan&id=<?php echo $mabenhnhan ?>" class="btn-primary">
                <i class="fas fa-user-injured"></i> Chọn hồ sơ để xem
            </a>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-header">
                <h2>Thông Tin Bệnh Nhân</h2>
            </div>
            <div class="card-body">
                <div class="patient-info">
                    <div class="patient-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="patient-details">
                        <h3 class="patient-name"><?php echo $benhnhan['hotenbenhnhan']; ?></h3>
                        <div class="patient-id"><?php echo $benhnhan['mabenhnhan']; ?></div>
                        
                        <div class="patient-data">
                            <div class="patient-data-item">
                                <div class="data-label">Ngày sinh</div>
                                <div class="data-value"><?php echo $benhnhan['ngaysinh']; ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Giới tính</div>
                                <div class="data-value"><?php echo $benhnhan['gioitinh']; ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Nhóm máu</div>
                                <div class="data-value"><?php echo $benhnhan['nhommau']; ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Tiền sử bệnh tật</div>
                                <div class="data-value"><?php echo decryptData($benhnhan['tiensubenhtatcuabenhnhan']); ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Địa chỉ</div>
                                <div class="data-value"><?php echo $benhnhan['sonha'].'-'.$benhnhan['quan/huyen'].'-'.$benhnhan['xa/phuong'].'-'.$benhnhan['tinh/thanhpho']; ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Số điện thoại</div>
                                <div class="data-value"><?php echo decryptData($benhnhan['sdtbenhnhan']); ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">Email</div>
                                <div class="data-value"><?php echo decryptData($benhnhan['email']); ?></div>
                            </div>
                            <div class="patient-data-item">
                                <div class="data-label">CCCD</div>
                                <div class="data-value"><?php echo decryptData($benhnhan['cccdbenhnhan']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Record Form -->
        <form action="" method="post" id="medicalRecordForm">
            <input type="hidden" name="patientId" value="<?php echo $benhnhan['mabenhnhan']; ?>">
            
            <!-- <div class="card">
                <div class="card-header">
                    <h2>Chọn Chuyên Khoa</h2>
                </div>
                <div class="card-body">
                    <div class="specialty-selection">
                        <?php foreach ($chuyenkhoa as $i): ?>
                        <div class="specialty-card" onclick="selectSpecialty('<?php echo $i['machuyenkhoa']; ?>')">
                            <div class="specialty-name"><?php echo $i['tenchuyenkhoa']; ?></div>
                            <input type="radio" name="chuyenkhoa" value="<?php echo $i['machuyenkhoa']; ?>" style="display: none;" required>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div> -->
            <div class="card">
                <div class="card-header">
                    <h2>Thông Tin Lâm Sàng</h2>
                </div>
                <div class="card-body">
                    <div class="tabs">
                        <div class="tab-header">
                            <button type="button" class="tab-button active" onclick="openTab(event, 'tab-complaint')">Lý do khám & Bệnh sử</button>
                        </div>
                    </div>
                    <!-- Lý do khám & Bệnh sử -->
                    <div id="tab-complaint" class="tab-content active">
                        <div class="form-group">
                            <label for="note">Ghi chú</label>
                            <textarea name="note" id="note" rows="3" placeholder="Nhập ghi chú..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="chiefComplaint">Triệu chứng ban đầu</label>
                            <textarea name="trieuchung" id="chiefComplaint" rows="3" required placeholder="Nhập lý do khám chính của bệnh nhân..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="chuandoan">Chuẩn đoán</label>
                            <textarea name="chuandoan" id="chuandoan" rows="3" placeholder="Nhập chuẩn đoán về bệnh của bệnh nhân..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="huongdieutri">Hướng điều trị</label>
                            <textarea name="huongdieutri" id="huongdieutri" rows="3" placeholder="Cho biết hướng điều trị..."></textarea>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 24px;">
                        <button type="button" class="btn-outline" onclick="window.location.href='?action=taohoso&mabenhnhan=<?php echo $mabenhnhan?>'">
                            <i class="fas fa-times"></i> Hủy
                        </button>
                        <div>
                            <button type="button" class="btn-primary" onclick="openMedicationPopup()">
                                <i class="fas fa-pills"></i> Thêm đơn thuốc
                            </button>
                            <button type="submit" name="submit" class="btn-primary">
                                <i class="fas fa-check"></i> Hoàn thành hồ sơ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Container ẩn để lưu trữ dữ liệu thuốc -->
            <div id="medicationsContainer"></div>
        </form>
        <?php endif; ?>
    </main>

    <!-- Đơn thuốc Modal -->
    <div id="modaltaodonthuoc" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeMedicationPopup()">&times;</span>
            <h2>Tạo Đơn Thuốc</h2>
            
            <!-- Form nhập thuốc -->
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
                            <!-- <input type="text" id="tenthuoc" placeholder="Nhập tên thuốc" required> -->
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="soluong">Số lượng</label>
                            <input type="number" id="soluong" placeholder="Số lượng" required>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="lieudung">Liều dùng</label>
                            <input type="text" id="lieudung" placeholder="Ví dụ: 3 lần/ngày">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="songayuong">Số ngày uống</label>
                            <input type="text" id="songayuong" placeholder="Ví dụ: 7 ngày">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="thoigianuong">Thời gian uống</label>
                            <input type="text" id="thoigianuong" placeholder="Ví dụ: 7 ngày">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="huongdandung">Hướng dẫn sử dụng</label>
                    <textarea id="huongdandung" rows="2" placeholder="Hướng dẫn chi tiết cách sử dụng thuốc..."></textarea>
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
            
            <div style="display: flex; justify-content: flex-end; margin-top: 20px; gap: 10px;">
                <button type="button" class="btn-outline" onclick="closeMedicationPopup()">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="button" class="btn-success" onclick="savePrescription()">
                    <i class="fas fa-save"></i> Tạo đơn thuốc
                </button>
            </div>
        </div>
    </div>

    <script>
        // Mảng lưu trữ danh sách thuốc
        let medications = [];
        
        // Specialty selection
        function selectSpecialty(chuyenkhoa) {
            // Remove selected class from all specialty cards
            var specialtyCards = document.getElementsByClassName("specialty-card");
            for (var i = 0; i < specialtyCards.length; i++) {
                specialtyCards[i].classList.remove("selected");
            }
            
            // Add selected class to the clicked specialty card
            var selectedCard = document.querySelector(`.specialty-card input[value="${chuyenkhoa}"]`).parentNode;
            selectedCard.classList.add("selected");
            
            // Check the radio button
            document.querySelector(`input[name="chuyenkhoa"][value="${chuyenkhoa}"]`).checked = true;
        }

        // Mở modal thêm đơn thuốc
        function openMedicationPopup() {
            document.getElementById("modaltaodonthuoc").style.display = "block";
            
            // Hiển thị bảng danh sách thuốc nếu đã có thuốc
            if (medications.length > 0) {
                document.getElementById("bangthuoc").style.display = "block";
            }
        }

        // Đóng modal thêm đơn thuốc
        function closeMedicationPopup() {
            document.getElementById("modaltaodonthuoc").style.display = "none";
        }

        // Thêm thuốc vào danh sách
        function addMedicationToList() {
            // Lấy thông tin thuốc từ form
            const tenthuoc = document.getElementById("tenthuoc").value;
            const soluong = document.getElementById("soluong").value;
            const lieudung = document.getElementById("lieudung").value;
            const songayuong = document.getElementById("songayuong").value;
            const thoigianuong = document.getElementById("thoigianuong").value;
            const huongdandung = document.getElementById("huongdandung").value;
            
            // Kiểm tra các trường bắt buộc
            if (!tenthuoc || !soluong) {
                alert("Vui lòng nhập đầy đủ tên thuốc và số lượng!");
                return;
            }
            
            // Thêm thuốc vào mảng
            medications.push({
                tenthuoc,
                soluong,
                lieudung,
                songayuong,
                thoigianuong,
                huongdandung
            });
            
            // Hiển thị bảng danh sách thuốc
            document.getElementById("bangthuoc").style.display = "block";
            
            // Cập nhật bảng danh sách thuốc
            updateMedicationTable();
            
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
                    <td>${med.tenthuoc}</td>
                    <td>${med.soluong}</td>
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
        
        // Xóa thuốc khỏi danh sách
        function removeMedication(index) {
            medications.splice(index, 1);
            
            // Cập nhật bảng danh sách thuốc
            updateMedicationTable();
            
            // Ẩn bảng nếu không còn thuốc nào
            if (medications.length === 0) {
                document.getElementById("bangthuoc").style.display = "none";
            }
        }
        
        // Xóa dữ liệu trong form nhập thuốc
        function clearMedicationForm() {
            document.getElementById("tenthuoc").value = "";
            document.getElementById("soluong").value = "";
            document.getElementById("lieudung").value = "";
            document.getElementById("songayuong").value = "";
            document.getElementById("thoigianuong").value = "";
            document.getElementById("huongdandung").value = "";
        }
        
        // Lưu đơn thuốc vào form chính
        function savePrescription() {
            // Kiểm tra xem có thuốc nào không
            if (medications.length === 0) {
                alert("Vui lòng thêm ít nhất một loại thuốc vào đơn!");
                return;
            }
            
            // Xóa container hiện tại
            const container = document.getElementById("medicationsContainer");
            container.innerHTML = "";
            
            // Tạo input ẩn để lưu thông tin thuốc
            medications.forEach((med, index) => {
                container.innerHTML += `
                    <input type="hidden" name="medications[${index}][tenthuoc]" value="${med.tenthuoc}">
                    <input type="hidden" name="medications[${index}][soluong]" value="${med.soluong}">
                    <input type="hidden" name="medications[${index}][lieudung]" value="${med.lieudung}">
                    <input type="hidden" name="medications[${index}][songayuong]" value="${med.songayuong}">
                    <input type="hidden" name="medications[${index}][thoigianuong]" value="${med.thoigianuong}">
                    <input type="hidden" name="medications[${index}][huongdandung]" value="${med.huongdandung}">
                `;
            });
            
            // Hiển thị thông báo
            alert(`Đã thêm ${medications.length} loại thuốc vào đơn thuốc!`);
            
            // Đóng modal
            closeMedicationPopup();
        }
        

        // Đóng khi click ra ngoài modal
        window.onclick = function(event) {
            const modal = document.getElementById("modaltaodonthuoc");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>