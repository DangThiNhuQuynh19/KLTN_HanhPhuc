<?php
session_start();
include_once(__DIR__ . '/../Controllers/clichkham.php');
date_default_timezone_set('Asia/Ho_Chi_Minh');
header('Content-Type: application/json');

// Kiểm tra nếu có đủ tham số
if (isset($_POST['bs']) && isset($_POST['bn'])) {
    $bs = $_POST['bs'];
    $bn = $_POST['bn'];

    $lich = new cLichKham();
    $kq = $lich->getlichhen($bs, $bn);

    if ($kq === -1) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Không thể kết nối cơ sở dữ liệu.'
        ]);
    } elseif ($kq === 0) {
        echo json_encode([
            'status' => 'fail',
            'message' => 'Chưa đến giờ hẹn hoặc không có lịch hẹn.'
        ]);
    } else {
        echo json_encode([
            'status' => 'ok',
            'message' => 'Đã đến giờ hẹn, bạn có thể gửi tin nhắn.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'invalid',
        'message' => 'Thiếu thông tin bác sĩ hoặc bệnh nhân.'
    ]);
}


?>
