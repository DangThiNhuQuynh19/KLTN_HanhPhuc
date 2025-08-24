<?php

class ChatUserModel {
    private $matinnhan;
    private $tentk_gui;     // Tên tài khoản người gửi
    private $tentk_nhan;    // Tên tài khoản người nhận
    private $noidung;       // Nội dung tin nhắn
    private $thoigiangui;   // Thời gian gửi tin nhắn
    private $connect;       // Kết nối CSDL

    public function __construct() {
        require_once('Models/ketnoi.php');
        $db = new clsketnoi();
        $this->connect = $db->moKetNoi();  // Mở kết nối CSDL
    }

    // Thiết lập người gửi
    public function setSender($tentk_gui) {
        $this->tentk_gui = $tentk_gui;
    }

    // Thiết lập người nhận
    public function setReceiver($tentk_nhan) {
        $this->tentk_nhan = $tentk_nhan;
    }

    // Thiết lập nội dung
    public function setMessage($noidung) {
        $this->noidung = $noidung;
    }

    // Thiết lập mã tin nhắn
    public function setIdMessage($matinnhan) {
        $this->matinnhan = $matinnhan; 
    }

    // Lưu tin nhắn vào CSDL và trả về ID tin nhắn
    public function saveMessage() {
        $this->thoigiangui = date('Y-m-d H:i:s');

        $query = "INSERT INTO tinnhan (tentk_gui, tentk_nhan, noidung, thoigiangui)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->connect->prepare($query);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ssss", $this->tentk_gui, $this->tentk_nhan, $this->noidung, $this->thoigiangui);

        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    // Lấy danh sách tin nhắn giữa 2 tài khoản
    public function getMessages($user1, $user2) {
        // Chuẩn bị câu lệnh SQL
        $stmt = $this->connect->prepare("SELECT * FROM tinnhan 
            WHERE (tentk_gui = ? AND tentk_nhan = ?) 
            OR (tentk_gui = ? AND tentk_nhan = ?) 
            ORDER BY thoigiangui ASC");
    
        // Kiểm tra nếu câu lệnh chuẩn bị không thành công
        if (!$stmt) {
            error_log('Prepare failed: ' . $this->connect->error);
            return false; // Trả về false nếu có lỗi trong việc chuẩn bị câu lệnh
        }
    
        // Ràng buộc tham số
        $stmt->bind_param("ssss", $user1, $user2, $user2, $user1);
    
        // Kiểm tra nếu câu lệnh không thể thực thi
        if (!$stmt->execute()) {
            error_log('Execute failed: ' . $stmt->error);
            return false; // Trả về false nếu câu lệnh không được thực thi thành công
        }
    
        $result = $stmt->get_result();
    
        // Kiểm tra nếu không có dữ liệu trả về
        if ($result->num_rows === 0) {
            return []; // Trả về mảng rỗng nếu không có tin nhắn
        }
    
        // Mảng để chứa các tin nhắn
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = [
                'sender' => $row['tentk_gui'],
                'receiver' => $row['tentk_nhan'],
                'message' => $row['noidung'],
                'time' => $row['thoigiangui'],
                'messageId' => $row['matinnhan'] 
            ];
        }
    
        return $messages;
    }
    // Đóng kết nối
    public function close() {
        $this->connect->close();
    }
}
?>