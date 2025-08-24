<?php
include_once("ketnoi.php");

class mtaikhoan{
    private $conn;

    public function __construct() {
        $p = new clsketnoi();
        $this->conn = $p->moketnoi();
    }

    // Đăng ký tài khoản
    public function dangkytk($email, $hoten, $ngaysinh, $mk) {
        // Kiểm tra email đã tồn tại
        $stmtCheck = $this->conn->prepare("SELECT * FROM taikhoan WHERE tentk = ?");
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();
    
        if ($result->num_rows > 0) {
            return "email_ton_tai"; // Nếu email đã tồn tại
        }
    
        // Băm mật khẩu bằng password_hash (sử dụng bcrypt mặc định)
        $hashedPassword = md5($mk);
    
        // Thêm vào bảng taikhoan
        $stmtInsertTK = $this->conn->prepare("INSERT INTO taikhoan (tentk, matkhau, vaitro) VALUES (?, ?, 1)");
        $stmtInsertTK->bind_param("ss", $email, $hashedPassword);
        if ($stmtInsertTK->execute()) {
            // Thêm vào bảng benhnhan
            $quanhe = "bản thân";
            $stmtInsertBN = $this->conn->prepare("INSERT INTO benhnhan (hotenbenhnhan, ngaysinh, email, quanhe, tentk) VALUES (?, ?, ?, ?, ?)");
            $stmtInsertBN->bind_param("sssss", $hoten, $ngaysinh, $email, $quanhe, $email);
            return $stmtInsertBN->execute() ? true : "Lỗi khi thêm bệnh nhân.";
        } else {
            return "Lỗi khi tạo tài khoản.";
        }
    }
    
    public function select_01_taikhoan($tentk, $matkhau) {
        $truyvan = "SELECT * FROM taikhoan WHERE tentk = ? and matkhau= ?";
        $stmt = $this->conn->prepare($truyvan);
        $stmt->bind_param("ss", $tentk, $matkhau);
        $stmt->execute();
        return $stmt->get_result(); 
    }
    public function taikhoanbacsi($tentk){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "SELECT tk.tentk, bs.hoten, bs.imgbs
                    from taikhoan tk join bacsi bs on tk.tentk = bs.tentk 
                    join phieukhambenh pkb on pkb.mabacsi = bs.mabacsi
                    join benhnhan b on pkb.mabenhnhan = b.mabenhnhan
                    where vaitro=0 and b.tentk = '$tentk' group by tk.tentk";
    
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }
    public function taikhoanbenhnhan($id){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "SELECT * from taikhoan tk join benhnhan b on tk.tentk = b.tentk 
                    join phieukhambenh pkb on b.mabenhnhan = pkb.mabenhnhan
                    where vaitro=1 and quanhe='bản thân' and pkb.mabacsi='$id' group by tk.tentk";
    
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }
}
?>