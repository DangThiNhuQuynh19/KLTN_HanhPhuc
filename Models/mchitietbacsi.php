<?php
 include_once('ketnoi.php');
 class mBacSi{
        public function chitietbacsi($id){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from bacsi 
                        join chuyenkhoa on bacsi.machuyenkhoa = chuyenkhoa.machuyenkhoa 
                        join nguoidung on bacsi.mabacsi = nguoidung.manguoidung
                        join taikhoan on nguoidung.tentk = taikhoan.tentk
                        where taikhoan.matrangthai = 1 and bacsi.mabacsi='$id'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function lichlamvieccuabacsi($id){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT lv.ngaylam, cv.giobatdau, cv.gioketthuc
                        FROM lichlamviec lv
                        JOIN calamviec cv ON lv.macalam = cv.macalam
                        WHERE lv.mabacsi = '$id' ORDER BY ngaylam ASC";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
    }
?>