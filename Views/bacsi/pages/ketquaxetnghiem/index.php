<?php
include_once('Controllers/cketquaxetnghiem.php');
include_once('Assets/config.php');
$cketquaxetnghiem = new cKetQuaXetNghiem();
$kq = $cketquaxetnghiem->get_ketquaxetnghiem_malichxetnghiem($_GET['id']);
$count_kq= $cketquaxetnghiem->count_nhanxet_ketquaxetnghiem($_GET['id']);
$kq_list=$cketquaxetnghiem->get_ketquaxetnghiem($_GET['id']);
?> 
<style>
  .patient-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .info-group {
            margin-bottom: 1rem;
        }
      
</style>
<div class="container">
        <div class="content-header" style=" display: flex; justify-content: center;">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title" >
                    Kết Quả Xét Nghiệm
                </h1>
                <p class="page-subtitle"  style=" display: flex; justify-content: center;">
                    Mã phiếu: <?php echo $_GET['id'];?>
                </p>
                
                <div class="action-buttons" style=" display: flex; justify-content: center;">
                    <a href="javascript:window.print()">
                        <span class= btn-primary><i class="fas fa-print"></i>In kết quả</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Patient Information -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-user-circle"></i>
                    Thông Tin Bệnh Nhân
                </h2>
            </div>
            <div class="card-body">
                <div class="patient-info">
                    <div>
                        <div class="info-group">
                            <div class="info-label">Họ tên</div>
                            <div class="info-value"><?php echo $kq[0]['hotenbenhnhan'] ?></div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Mã bệnh nhân</div>
                            <div class="info-value"><?php echo $kq[0]['mabenhnhan'] ?></div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">CCCD</div>
                            <div class="info-value"><?php echo decryptData($kq[0]['cccdbenhnhan']); ?></div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Giới tính</div>
                            <div class="info-value"><?php echo $kq[0]['gioitinh']; ?></div>
                        </div>
                    </div>
                    <div>
                    <div class="info-group">
                            <div class="info-label">Ngày sinh</div>
                            <div class="info-value"><?php echo $kq[0]['ngaysinh']; ?></div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">SĐT</div>
                            <div class="info-value"><?php echo decryptData($kq[0]['sdtbenhnhan']); ?></div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">Địa chỉ</div>
                            <div class="info-value"><?php echo $kq[0]['sonha'].','.$kq[0]['xa/phuong'].','.$kq[0]['quan/huyen'].','.$kq[0]['tinh/thanhpho'] ; ?></div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?php echo decryptData($kq[0]['email']); ?></div>
                        </div>
                    </div>
                    
                    <div>
                        
                        <div class="info-group">
                            <div class="info-label">Nghề nghiệp</div>
                            <div class="info-value"><?php echo $kq[0]['nghenghiep']; ?></div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Dân tộc</div>
                            <div class="info-value"><?php echo $kq[0]['dantoc']; ?></div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Nhóm máu</div>
                            <div class="info-value"><?php echo $kq[0]['nhommau']; ?></div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Tiền sử bệnh tật</div>
                            <div class="info-value"><?php echo decryptData($kq[0]['tiensubenhtatcuabenhnhan']); ?></div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Tiền sử bệnh tật người thân: </div>
                            <div class="info-value"><?php echo decryptData($kq[0]['tiensubenhtatcuagiadinh']); ?></div>
                        </div>
                        
                    </div>
                    
                    <div>
                        <div class="info-group">
                            <div class="info-label">Ngày xét nghiệm</div>
                            <div class="info-value"><?php echo $kq[0]['ngayhen']; ?></div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Bác sĩ chỉ định</div>
                            <div class="info-value"><?php echo $kq[0]['hoten']; ?></div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Khoa phòng</div>
                            <div class="info-value"><?php echo $kq[0]['tenchuyenkhoa']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Result Summary -->
        <div class="card summary-card">
            <div class="card-header">
                <h2 class="summary-title">Tổng Quan Kết Quả</h2>
                <div class="summary-date">
                    <i class="far fa-calendar-alt"></i>
                    <?php echo $kq[0]['ngayhen']; ?>
                </div>
            </div>
            
            <div class="card-body">
                <div class="summary-stat">
                    <div class="stat-value">
                        <i class="fas fa-chart-pie"></i>
                        <?php echo number_format(($count_kq['binhthuong']/($count_kq['binhthuong']+$count_kq['cao']+$count_kq['hoicao']))*100, 1); ?>%
                    </div>
                    <div class="stat-label">Chỉ số bình thường</div>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: <?php echo ($count_kq['binhthuong']/($count_kq['binhthuong']+$count_kq['cao']+$count_kq['hoicao']))*100; ?>%"></div>
                    </div>
                </div>
                
                <div class="summary-stat">
                    <div class="stat-value">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo ($count_kq['cao']+$count_kq['hoicao']); ?>
                    </div>
                    <div class="stat-label">Chỉ số bất thường</div>
                </div>
                
                <div class="summary-stat">
                    <div class="stat-value">
                        <i class="fas fa-flask"></i>
                        <?php echo count($kq_list); ?>
                    </div>
                    <div class="stat-label">Tổng số chỉ số xét nghiệm</div>
                </div>
            </div>
        </div>

        <!-- Test Results -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-vial"></i>
                    Kết Quả Xét Nghiệm
                </h2>
            </div>
            <div class="card-body">   
                
                    <div id="tab-<?php echo $index; ?>" class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tên xét nghiệm</th>
                                    <th>Kết quả</th>
                                    <th>Đơn vị</th>
                                    <th>Giá trị tham chiếu</th>
                                    <th>Đánh giá</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($kq_list as $i): ?>
                                    <tr>
                                        <td><?php echo $i['tenchisoxetnghiem']; ?></td>
                                        <td><strong><?php echo $i['giatriketqua']; ?></strong></td>
                                        <td><?php echo $i['donvikq']; ?></td>
                                        <td><?php echo $i['khoangthamchieu']; ?></td>
                                        <td>
                                            <?php if ($i['nhanxet'] === 'Bình thường'): ?>
                                                <span class="status-badge status-normal">
                                                    <i class="fas fa-check-circle"></i> Bình thường
                                                </span>
                                            <?php elseif ($i['nhanxet'] === 'Cao'): ?>
                                                <span class="status-badge status-high">
                                                    <i class="fas fa-arrow-up"></i> Cao
                                                </span>
                                            <?php else: ?>
                                                <span class="status-badge status-low">
                                                    <i class="fas fa-arrow-down"></i> Thấp
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                
            </div>
        </div>

        
</div>

