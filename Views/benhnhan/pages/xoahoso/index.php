<?php
include_once('Controllers/cBenhNhan.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mabenhnhan'])) {
        $mabenhnhan = intval($_POST['mabenhnhan']);

        $benhnhanController = new cBenhNhan();
        $ketqua = $benhnhanController->deletebenhnhan($mabenhnhan);

        if ($ketqua) {
            // Xóa thành công -> chuyển về trang danh sách
            header("Location: index.php?action=caidat");
            exit();
        } else {
            echo "<p style='color:red; text-align:center;'>Xóa bệnh nhân thất bại. Vui lòng thử lại.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>Không tìm thấy mã bệnh nhân.</p>";
    }
} else {
    echo "<p style='color:red; text-align:center;'>Yêu cầu không hợp lệ.</p>";
}
?>