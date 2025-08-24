<?php
require_once('ketnoi.php');
 class mBacSi{
        public function dsbacsi(){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from bacsi 
                        join chuyenkhoa on bacsi.machuyenkhoa = chuyenkhoa.machuyenkhoa 
                        join nguoidung on bacsi.mabacsi = nguoidung.manguoidung
                        join taikhoan on nguoidung.tentk = taikhoan.tentk
                        where taikhoan.matrangthai = 1 order by bacsi.mabacsi asc";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function chitietbacsi($id){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select nguoidung.hoten, bacsi.motabs, bacsi.gioithieubs,bacsi.imgbs, bacsi.capbac from bacsi 
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
        
        public function bacsitheoten($name){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT * FROM bacsi
                JOIN chuyenkhoa ON bacsi.machuyenkhoa = chuyenkhoa.machuyenkhoa
                WHERE bacsi.hoten LIKE '%$name%'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function bacsitheokhoa($id){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from bacsi join chuyenkhoa on bacsi.machuyenkhoa = chuyenkhoa.machuyenkhoa where bacsi.machuyenkhoa='$id'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function bacsitheotenandkhoa($name,$id){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT * FROM bacsi 
                JOIN chuyenkhoa ON bacsi.machuyenkhoa = chuyenkhoa.machuyenkhoa 
                WHERE bacsi.hoten LIKE '%$name%' AND bacsi.machuyenkhoa='$id'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        
        public function xembacsitheotentk($tentk){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from bacsi where tentk = '$tentk' limit 1";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
    }
?>