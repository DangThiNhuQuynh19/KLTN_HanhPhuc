<?php
$mabenhnhan = $_GET['id'] ?? '';
include_once('Controllers/cbenhnhan.php');
include_once('Controllers/chosobenhandientu.php');
include_once('Controllers/clichxetnghiem.php');
include_once('Controllers/cdonthuoc.php');
include_once("Assets/config.php");
$clichxetnghiem = new cLichXetNghiem();
$chsba = new cHoSoBenhAnDienTu();
$cbenhnhan = new cBenhNhan();
$cdonthuoc = new cDonThuoc();
$bn= $cbenhnhan->get_benhnhan_id($mabenhnhan);
$hsba_list= $chsba->get_hsba_mabenhnhan($mabenhnhan);
$lichxetnghiem_list=$clichxetnghiem->get_lichxetnghiem_mabenhnhan($mabenhnhan);
$donthuoc_list= $cdonthuoc->get_donthuoc_mabenhnhan($mabenhnhan);
$active_tab = $_GET['tab'] ?? 'medical-records';
?>    
<div class="container">
    <div class="content-header">
        <div class="back-button">
            <a href="?action=benhnhan" class="btn-icon"><i class="fas fa-arrow-left"></i></a>
            <h1>Hồ sơ bệnh nhân: <?php echo $bn['mabenhnhan']; ?></h1>
        </div>
    </div>
    
    <div class="patient-detail">
        <div class="patient-sidebar">
            <div class="card">
                <div class="card-body">
                    <div class="patient-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="patient-name"><?php echo $bn['hotenbenhnhan']; ?></h2>
                    <p class="patient-id">Mã BN: <?php echo $bn['mabenhnhan']; ?></p>
                    
                    <div class="patient-info">
                        <div class="info-row">
                            <span class="info-label">Ngày sinh:</span>
                            <span class="info-value"><?php echo $bn['ngaysinh']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Giới tính:</span>
                            <span class="info-value"><?php echo $bn['gioitinh']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Điện thoại:</span>
                            <span class="info-value"><?php echo decryptData($bn['sdtbenhnhan']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Địa chỉ:</span>
                            <span class="info-value"><?php echo $bn['sonha'].",". $bn['xa/phuong'].",".$bn['quan/huyen'].",".$bn['tinh/thanhpho']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Nhóm máu:</span>
                            <span class="info-value"><?php echo $bn['nhommau']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tiền sử bệnh tật:</span>
                            <span class="info-value"><?php echo decryptData($bn['tiensubenhtatcuabenhnhan']); ?></span>
                        </div>
                    </div>
                    
                    <div class="patient-actions">
                        <a href="?action=datlichxetnghiem&id=<?php echo $mabenhnhan;?>" class="btn-outline btn-full"><i class="fas fa-flask"></i> Thêm xét nghiệm</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="patient-content">
            <form method="POST" style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 10px;">
            <a href="?action=taohoso&mabenhnhan=<?php echo $mabenhnhan;?>" class="btn-primary"><i class="fas fa-plus"></i> Thêm hồ sơ</a>
            </form>
            <div class="tabs">
                <div class="tab-header">
                    <a href="?action=chitietbenhnhan&id=<?php echo $mabenhnhan; ?>&tab=medical-records" class="tab-link <?php echo $active_tab === 'medical-records' ? 'active' : ''; ?>">Hồ sơ bệnh án</a>
                    <a href="?action=chitietbenhnhan&id=<?php echo $mabenhnhan; ?>&tab=tests" class="tab-link <?php echo $active_tab === 'tests' ? 'active' : ''; ?>">Xét nghiệm</a>
                    <a href="?action=chitietbenhnhan&id=<?php echo $mabenhnhan; ?>&tab=prescriptions" class="tab-link <?php echo $active_tab === 'prescriptions' ? 'active' : ''; ?>">Đơn thuốc</a>
                </div>
                <div class="tab-content">
                    <?php if ($active_tab === 'medical-records'): ?>
                        <div class="card">
                            <div class="card-header">
                                <h2>Hồ sơ bệnh án</h2>
                                <p>Lịch sử bệnh án của bệnh nhân</p>  
                            </div>
                            <div class="card-body">
                                <div class="medical-records">
                                    <?php
                                    if($hsba_list){
                                        foreach ($hsba_list as $i) {
                                            echo '<div class="record-item">';
                                            echo '<div class="record-header">';
                                            echo '<h3>' . $i['tenchuyenkhoa'] . '</h3>';
                                            echo '<span class="record-date">' . $i['ngaytao'] . '</span>';
                                            echo '</div>';
                                            echo '<div class="record-details">';
                                            echo '<p><strong>Bác sĩ:</strong> ' . $i['hoten'] . '</p>';
                                            echo '<p><strong>Triệu chứng ban đầu:</strong> ' . $i['trieuchungbandau'] . '</p>';
                                            echo '<p><strong>Chẩn đoán:</strong> ' . $i['chandoan'] . '</p>';
                                            echo '<p><strong>Ghi chú:</strong> ' . $i['huongdieutri'] . '</p>';
                                            echo '</div>';
                                            echo '<div class="record-actions">';
                                            echo '<a href="?action=chitiethoso&mahoso='.$i['mahoso'] .'" class="btn-small">Xem chi tiết</a>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    }else{
                                        echo '</div>';
                                            echo '<div class="record-details">';
                                            echo '<p><strong>Chưa có hồ sơ nào gần đây</strong></p>';
                                            echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($active_tab === 'tests'): ?>
                        <div class="card">
                            <div class="card-header">
                                <h2>Xét nghiệm</h2>
                                <p>Kết quả xét nghiệm của bệnh nhân</p>
                            </div>
                            <div class="card-body">
                                <div class="test-records">
                                    <?php
                                    if($lichxetnghiem_list){
                                        foreach ($lichxetnghiem_list as $i) {
                                            switch ($i['trangthailichxetnghiem']) {
                                                case 'Đã hoàn thành':
                                                    $style = 'status-completed';
                                                    break;
                                                case 'Chờ xác nhận':
                                                    $style = 'status-processing';
                                                    break;
                                                case 'Đã đặt lịch':
                                                    $style = 'status-pending';
                                                    break;
                                                case 'Đã hủy':
                                                    $style = 'status-canceled';
                                                    break;
                                            }
                                            
                                            echo '<div class="test-item">';
                                            echo '<div class="test-header">';
                                            echo '<h3>' . $i['tenloaixetnghiem'] . '</h3>';
                                            echo '<span class="test-date" style="margin-right: 5px; margin-left: 5px;">' . $i['ngayhen'] . '</span>';
                                            echo '</div>';
                                            echo '<div class="test-details">';
                                            echo '<p><strong>Chuyên khoa:</strong> ' . $i['tenchuyenkhoa'] . '</p>';
                                            echo '<p><strong>Trạng thái:</strong> <span class="status-badge ' . $style . '">' . $i['trangthailichxetnghiem'] . '</span></p>';
                                            echo '</div>';
                                            echo '<div class="test-actions">';
                                            if ($i['trangthailichxetnghiem'] === 'Đã hoàn thành') {
                                                echo '<a href="?action=ketquaxetnghiem&id=' . $i['malichxetnghiem'] . '" class="btn-small">Xem kết quả</a>';
                                            } elseif ($i['trangthailichxetnghiem'] === 'Đã hủy') {
                                                echo '<a href="#" class="btn-small disabled">Đã xóa</a>';
                                            }else{
                                                echo '<a href="#" class="btn-small disabled">Chờ kết quả</a>';
                                            }
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    }else{
                                        echo '</div>';
                                            echo '<div class="record-details">';
                                            echo '<p><strong>Chưa có lịch xét nghiệm</strong></p>';
                                            echo '</div>';
                                    }
                                    
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($active_tab === 'prescriptions'): ?>
                        <div class="card">
                            <div class="card-header">
                                <h2>Đơn thuốc</h2>
                                <p>Lịch sử đơn thuốc của bệnh nhân</p>
                            </div>
                            <div class="card-body">
                                <div class="prescription-records">
                                    <?php
                                    if($donthuoc_list){
                                        foreach ($donthuoc_list as $i) {
                                            echo '<div class="prescription-item">';
                                            echo '<div class="prescription-header">';
                                            echo '<h3>#' . $i['ghichudonthuoc'] . '</h3>';
                                            echo '<span class="prescription-date">' . $i['ngaytaodonthuoc'] . '</span>';
                                            echo '</div>';
                                            echo '<div class="prescription-details">';
                                            echo '<p><strong>Bác sĩ:</strong> ' . $i['hoten'] . '</p>';
                                            echo '<p><strong>Chẩn đoán:</strong> ' . $i['chandoan'] . '</p>';
                                            echo '<div class="medications">';
                                            $chitietdonthuoc= $cdonthuoc->get_chitietdonthuoc_madonthuoc($i['madonthuoc']);
                                            foreach ($chitietdonthuoc as $j) {
                                                echo '<div class="medication">';
                                                echo '<i class="fas fa-pills"></i>';
                                                echo '<span>' . $j['tenthuoc'] . ' - ' . $j['lieudung'].'-'.$j['thoigianuong'].'-'.$j['songayuong'] . '</span>';
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                            echo '</div>';
                                            echo '<div class="prescription-actions">';
                                            echo '<a href="javascript:window.print()" class="btn-small">In đơn thuốc</a>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    }else{
                                        echo '</div>';
                                        echo '<div class="record-details">';
                                        echo '<p><strong>Chưa có đơn thuốc</strong></p>';
                                        echo '</div>';
                                    }
                                    
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?> 
                </div>
            </div>
        </div>
    </div>
</div>
<?php require("Views/bacsi/layout/footer.php"); ?>
