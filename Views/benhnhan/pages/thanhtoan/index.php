<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán viện phí</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 100px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .payment-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .info-section {
            flex: 1;
            padding: 20px;
        }

        .qr-section {
            flex: 1;
            text-align: center;
            padding: 20px;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-item label {
            font-weight: bold;
            color: #34495e;
            display: block;
            margin-bottom: 5px;
        }

        .info-item span {
            color: #2c3e50;
        }

        .total {
            font-size: 1.2em;
            color: #e74c3c;
            font-weight: bold;
        }

        .button {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
        }

        .button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<?php
    include_once('Controllers/cbacsi.php');
    include_once('Controllers/cBenhNhan.php');
    include_once('Controllers/clichkham.php');
    include_once('Controllers/cphieukhambenh.php'); 
    include_once('Controllers/clichlamviec.php'); 
    $pPhieuKham = new cPhieuKhambenh();
    $pBacSi = new cBacSi();
    $tblBacSi = $pBacSi->getBacSiById( $_SESSION['mabacsi'])->fetch_assoc();
    $pLichKham = new cLichKham();
    $pBenhNhan = new cBenhNhan();
    $tblLich = $pLichKham->getlich($_SESSION['macalamviec'])->fetch_assoc();
    $benhnhan = $pBenhNhan->getbenhnhanbyid($_SESSION['mabenhnhan']);

    if(isset($_POST['btnxacnhan'])){
        $result = $pPhieuKham->insertphieukham($_SESSION['maphieukhambenh'], $_SESSION['ngaykham'], $_SESSION['macalamviec'], $_SESSION['mabacsi'], $_SESSION['mabenhnhan'], $_SESSION['tongtien'], $_SESSION['trangthai']);

        if ($result) {
            // Lấy malichlamviec từ mabacsi, ngaykham, macalamviec
            $pLich = new cLichLamViec();
            $tbl = $pLich->getmalichlamviec($_SESSION['mabacsi'], $_SESSION['ngaykham'], $_SESSION['macalamviec']);
            
            if ($tbl && $tbl != -1 && $tbl != 0) {
                $row = $tbl->fetch_assoc();
                $malichlamviec = $row['malichlamviec'];
                // Cập nhật ghi chú là "đã đặt"
                $pLich->updatelichlamviecday($malichlamviec);
            }
        
            echo '<script>alert("Đặt lịch khám thành công!!!"); location.href="?action=lichhen";</script>';
        } else {
            echo '<div class="text-danger text-center">Đặt lịch khám thất bại. Vui lòng thử lại.</div>';
        }
    }
?>
 <div class="container">
    
    <div class="payment-info">
        <div class="info-section">
            <div class="info-item">
                <label>Mã bệnh nhân:</label>
                <span><?php echo $_SESSION['mabenhnhan'];?></span>
            </div>
            <div class="info-item">
                <label>Họ và tên:</label>
                <span><?php echo $benhnhan['hotenbenhnhan'];?></span>
            </div>
            <div class="info-item">
                <label>Bác sĩ:</label>
                <span><?php echo $tblBacSi['hoten'];?></span>
            </div>
            <div class="info-item">
                <label>Ngày khám</label>
                <span><?php echo $_SESSION['ngaykham'];?></span>
            </div>
            <div class="info-item">
                <label>Giờ khám</label>
                <span><?php echo $tblLich['giobatdau']."-".$tblLich['gioketthuc'];?></span>
            </div>
            <div class="info-item">
                <label>Tổng tiền:</label>
                <span class="total"><?php echo number_format($_SESSION['tongtien'], 0, ',', '.') . ' VND';?></span>
            </div>
        </div>
        <form action="" method="post">
            <div class="qr-section">
                <h2>Quét mã QR để thanh toán</h2>
                <div class="qr-code">
                    <!-- Thay thế src bằng link ảnh QR thực tế -->
                    <img src="https://img.vietqr.io/image/VCB-9355706358-compact.png?amount=<?php echo $_SESSION['tongtien'];?>
                    &addInfo=<?php echo $_SESSION['maphieukhambenh'];?>
                    &accountName=benhvienhanhphuc" alt="QR Code thanh toán" style="width: 100%;">
                </div>
                <p>Hoặc chuyển khoản đến:</p>
                <p>Ngân hàng: VCB</p>
                <p>STK: 9355706358</p>
                <p>Chủ TK: BỆNH VIỆN HẠNH PHÚC</p>
                <button type="submit" class="button" name ="btnxacnhan">Xác nhận đã thanh toán</button>
            </div>
        </form>
    </div>
</div>
