<?php
 include_once('ketnoi.php');
 class mChuyenKhoa{
        public function dschuyenkhoa(){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from chuyenkhoa";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function select_chuyenkhoa_notmabenhnhan($mabenhnhan){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str="select * from chuyenkhoa 
                where machuyenkhoa NOT IN( SELECT ck.machuyenkhoa from hosobenhan as hs 
                join chitiethoso as ct on hs.mahoso=ct.mahoso 
                join bacsi as bs on bs.mabacsi=ct.mabacsi 
                join chuyenkhoa as ck on ck.machuyenkhoa=bs.machuyenkhoa 
                WHERE hs.mabenhnhan='$mabenhnhan');";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
            
        }

        public function select_chuyenkhoa_mabacsi($mabacsi){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str="select * from chuyenkhoa as ck
                join bacsi as bs on bs.machuyenkhoa=ck.machuyenkhoa
                WHERE bs.mabacsi='$mabacsi';";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
    }
?>