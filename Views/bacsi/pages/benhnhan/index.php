<?php
    include_once('Controllers/cbenhnhan.php');
    include_once('Controllers/cbacsi.php');
    include_once('Controllers/cphieukhambenh.php');
    include_once("Assets/config.php");
    $cphieukhambenh = new cPhieuKhamBenh();
    $cbenhnhan = new cBenhNhan();
    $cbacsi = new cBacSi();
    $bacsi= $cbacsi->getBacSiByTenTK($_SESSION['user']['tentk']);
    $benhnhan_list= $cbenhnhan->get_benhnhan_mabacsi( $bacsi['mabacsi']);
    if(isset($_POST["btntimkiem"])){
        $benhnhan_list= $cbenhnhan->get_benhnhan_tukhoa($_POST["tukhoa"],$bacsi['mabacsi'] );
    }
    if(isset($_POST['homnay'])){
        $benhnhan_list= $cphieukhambenh->get_lichkham_homnay($bacsi['mabacsi']);
    }
    
?>
<body>
    <div class="container">
        <div class="content-header">
            <h1>Quản lý bệnh nhân</h1>
            <a href="#" class="btn-primary"><i class="fas fa-plus"></i> Thêm bệnh nhân</a>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2>Tìm kiếm bệnh nhân</h2>
              
            </div>
            <div class="card-body">
                <form class="search-form" method="POST">
                    <div class="search-input">
                        <i class="fas fa-search"></i>
                        <input type="text" name="tukhoa" placeholder="Tìm theo tên, mã bệnh nhân...">
                    </div>
                    <br>
                    <button type="submit" class="btn-primary" name="btntimkiem">Tìm kiếm</button>
                </form>
            </div>
        </div>
        <form method="POST" style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 10px;">
            <input value="homnay" type="checkbox" name="homnay" id="homnay" onchange="this.form.submit()" <?php if (isset($_POST['homnay'])) echo 'checked'; ?>>
            <label for="homnay" style="margin-left: 5px;"><b>Chỉ hiển thị bệnh nhân hôm nay</b></label>
        </form>
        <div class="card">
            <div class="card-body no-padding">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã BN</th>
                            <th>Họ tên</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>Số điện thoại</th>
                            <th>cccd</th>
                            <th>Email</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($benhnhan_list){
                                foreach ($benhnhan_list as $i) {
                                    echo '<tr>';
                                    echo '<td>' . $i['mabenhnhan'] . '</td>';
                                    echo '<td>' . $i['hotenbenhnhan'] . '</td>';
                                    echo '<td>' . $i['ngaysinh'] . '</td>';
                                    echo '<td>' . $i['gioitinh'] . '</td>';
                                    echo '<td>' . $i['sdtbenhnhan'] . '</td>';
                                    echo '<td>' . $i['cccdbenhnhan']. '</td>';
                                    echo '<td>' . $i['email'] . '</td>';
                                    echo '<td class="actions">';
                                    echo '<a class="btn-primary btn-small" style="display: flex;" href="?action=chitietbenhnhan&id=' . $i['mabenhnhan'] . '" class="btn-small">Chi tiết</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            }else{
                                echo '<tr><td colspan="7" style="text-align:center; color:gray;">Chưa có bệnh nhân</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php require("Views/bacsi/layout/footer.php"); ?>
