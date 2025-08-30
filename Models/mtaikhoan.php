<?php
include_once("ketnoi.php");

class mtaikhoan{
    private $conn;

    public function __construct() {
        $p = new clsketnoi();
        $this->conn = $p->moketnoi();
    }

    // Đăng ký tài khoản
    public function dangkytk ($mabenhnhan, $email, $hoten, $ngaysinh, $sdt, $cccd, $cccd_truoc_name, $birth_cert_name, $cccd_sau_name, $gioitinh, $nghenghiep, $tiensucuagiadinh, $tiensucuabanthan, $sonha, $xa, $tinh, $matkhau, $manguoigiamho,$gh_hoten, $gh_dob, $gh_diachi, $gh_sdt,$gh_email, $gh_cccd, $gh_cccd_truoc_name, $gh_cccd_sau_name) {
        // Kiểm tra email đã tồn tại
        $dantoc='kinh';
        $stmtCheck = $this->conn->prepare("SELECT * FROM taikhoan WHERE tentk = ?");
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();
        
        if ($result->num_rows > 0) {
            return "email_ton_tai"; // Nếu email đã tồn tại
        }
    
        // Băm mật khẩu (tốt hơn nên dùng password_hash)
        $hashedPassword = md5($matkhau);
    
        // Thêm vào bảng taikhoan
        $stmtInsertTK = $this->conn->prepare("INSERT INTO taikhoan (tentk, matkhau, mavaitro, matrangthai) VALUES (?, ?, 1,1)");
        $stmtInsertTK->bind_param("ss", $email, $hashedPassword);
        
        if ($stmtInsertTK->execute()) {
            // Tính tuổi bệnh nhân
            $today = new DateTime();
            $dob = new DateTime($ngaysinh);
            $age = $today->diff($dob)->y;
    
            // Thêm vào bảng nguoidung
            $stmtInsertND = $this->conn->prepare("INSERT INTO nguoidung (manguoidung, hoten, ngaysinh, gioitinh, cccd, cccd_matruoc, cccd_matsau, giaykhaisinh, dantoc, sdt, sonha, maxaphuong, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtInsertND->bind_param("sssssssssssss", $mabenhnhan, $hoten, $ngaysinh, $gioitinh, $cccd, $cccd_truoc_name, $cccd_sau_name, $birth_cert_name, $dantoc, $sdt, $sonha, $xa, $email);
            
            if($stmtInsertND->execute()){
                // Nếu <16 hoặc >60 thì mới thêm người giám hộ
                if($age < 16 || $age > 60){
                    $stmtInsertNGH = $this->conn->prepare("INSERT INTO nguoigiamho(manguoigiamho, hoten, email, diachi) VALUES(?,?,?,?)");
                    $stmtInsertNGH->bind_param("ssss", $manguoigiamho, $gh_hoten, $gh_email, $gh_diachi);
                    if(!$stmtInsertNGH->execute()){
                        return "Lỗi khi thêm người giám hộ.";
                    }
                }
    
                // Thêm bệnh nhân
                $stmtInsertBN = $this->conn->prepare("INSERT INTO benhnhan(mabenhnhan, nghenghiep, tiensubenhtatcuagiadinh, tiensubenhtatcuabenhnhan, manguoigiamho) VALUES (?, ?, ?, ?, ?)");
                $stmtInsertBN->bind_param("sssss", $mabenhnhan, $nghenghiep, $tiensucuagiadinh, $tiensucuabanthan, $manguoigiamho);
                return $stmtInsertBN->execute() ? true : "Lỗi khi thêm bệnh nhân.";
            } else {
                return "Lỗi khi thêm thông tin người dùng.";
            }
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