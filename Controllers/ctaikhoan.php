<?php
include_once(__DIR__ . "/../Models/mtaikhoan.php");

class ctaiKhoan {
    // Đăng ký tài khoản
    public function dangkytk($email, $hoten, $ngaysinh, $mk) {
        $mNguoiDung = new mtaikhoan();
        return $mNguoiDung->dangkytk($email, $hoten, $ngaysinh, $mk);
    }

    public function dangnhap($email, $mk) {
        $mNguoiDung = new mtaikhoan();
        $user = $mNguoiDung->select_01_taikhoan($email, $mk);
    
        if ($user && $user->num_rows > 0) {
            $row = $user->fetch_assoc();
            $_SESSION['dangnhap'] = $row["vaitro"];
            $_SESSION["user"] = $row;
            header("Location:index.php?action=trangchu");
            exit();
        } else {
            echo '<script>alert("Email hoặc password không chính xác"); window.history.back();</script>';
        }
    }
    
    public function gettkbacsi($tentk){
        $p = new mtaikhoan();
        $tbl = $p->taikhoanbacsi($tentk);
        if(!$tbl){
            return -1;
        }else{
            if($tbl->num_rows > 0){
                return $tbl;
            }else{
                return 0;
            }
        }
    }
    public function gettkbenhnhan($id){
        $p = new mtaikhoan();
        $tbl = $p->taikhoanbenhnhan($id);
        if(!$tbl){
            return -1;
        }else{
            if($tbl->num_rows > 0){
                return $tbl;
            }else{
                return 0;
            }
        }
    }
    
}
?>