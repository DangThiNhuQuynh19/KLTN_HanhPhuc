<?php
    include_once('Controllers/cphieukhambenh.php');
    include_once('Controllers/cbacsi.php');
    include_once('Controllers/cchuyenkhoa.php');
    $cchuyenkhoa = new cChuyenKhoa();
    $chuyenkhoa_list=$cchuyenkhoa->getAllChuyenKhoa();
    $cbacsi = new cBacSi();
    $cphieukhambenh = new cPhieuKhamBenh();
    $cphieukhambenh->capnhat_trangthai_phieukham();
    $bacsi= $cbacsi->getBacSiByTenTK($_SESSION['user']['tentk']);
    $lichkham_list= $cphieukhambenh->get_lichkham_mabacsi($bacsi['mabacsi']);
    if(isset($_POST['homnay'])){
        $lichkham_list= $cphieukhambenh->get_lichkham_homnay($bacsi['mabacsi']);
    }
    if(isset($_POST["btntimkiem"])){
        $tukhoa=$_POST["tukhoa"];
        $trangthai=$_POST["trangthai"];
        $ngay=$_POST["ngay"];
        $lichkham_list= $cphieukhambenh->search_phieukham($tukhoa,$trangthai,$ngay,$bacsi['mabacsi']);
    }
?>
<div class="container">
<div class="content-header">
    <h1>Quản lý lịch hẹn</h1>
</div>

<div class="tabs">
    <div class="tab-header">
        <a href="#" class="tab-link active" data-tab="list-view">Danh sách</a>
    </div>
    
    <div class="tab-content">
        <div id="list-view" class="tab-pane active">
            <div class="card">
                <div class="card-header">
                    <h2>Tìm kiếm lịch hẹn</h2>
                </div>
                <div class="card-body">
                    <form class="search-form" method="POST">
                        <div class="search-grid">
                            <div class="search-input">
                                <i class="fas fa-search"></i>
                                <input type="text" name="tukhoa" placeholder="Tìm theo tên bệnh nhân, mã phiếu...">
                            </div>
                            
                            <div class="form-group">
                                <select name="trangthai">
                                    <option value="">Trạng thái</option>
                                    <option value="chưa khám">Chưa khám</option>
                                    <option value="đã khám">Đã khám</option>
                                    <option value="đã hủy">Đã hủy</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <input type="date" name="ngay" placeholder="Ngày khám">
                            </div>
                            
                            <button type="submit" class="btn-primary" name="btntimkiem">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
            <form method="POST" style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 10px;">
                <input type="checkbox" name="homnay" id="homnay" onchange="this.form.submit()" <?php if (isset($_POST['homnay'])) echo 'checked'; ?>>
                <label for="homnay" style="margin-left: 5px;"><b>Hôm nay</b></label>
            </form>
            <div class="card">
                <div class="card-body no-padding">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Mã phiếu</th>
                                <th>Ngày khám</th>
                                <th>Ca làm việc</th>
                                <th>Bệnh nhân</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($lichkham_list){
                                foreach ($lichkham_list as $i) {
                                    switch ($i['trangthai']) {
                                        case 'chưa khám':
                                            $statusClass = 'status-pending';
                                            break;
                                        case 'đã khám':
                                            $statusClass = 'status-completed';
                                            break;
                                        case 'đã hủy':
                                            $statusClass = 'status-canceled';
                                            break;
                                    }
                                    
                                    echo '<tr>';
                                    echo '<td>' . $i['maphieukb'] . '</td>';
                                    echo '<td>' . date('d/m/Y', strtotime($i['ngaykham'])) . '</td>';
                                    echo '<td>' . $i['giobatdau'].'-'.$i['gioketthuc'] . '</td>';
                                    echo '<td>' . $i['hotenbenhnhan'] . '</td>';
                                    echo '<td>' . number_format($i['tongtien'], 0, ',', '.') . ' VND</td>';
                                    echo '<td><span class="status-badge ' . $statusClass . '">' . $i['trangthai'] . '</span></td>';
                                    echo '<td>';
                                    if($i['trangthai']=='chưa khám'){
                                        echo '<a class="btn-primary btn-small" href="?action=tinnhan&id=mabenhnhan"><i class="fas fa-comment-medical"></i> nhắn tin</a>';
                                    }
                                    echo'</td>';
                                    echo '</tr>';
                                }
                            }else{
                                echo '<tr><td colspan="7" style="text-align:center; color:gray;">Không có lịch hẹn</td></tr>';
                            }
                            
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>