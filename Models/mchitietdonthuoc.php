<?php
    include_once('ketnoi.php');
    class mChiTietDonThuoc{
        public function insert_chitietdonthuoc($madonthuoc,$mathuoc,$lieudung,$thoigianuong,$songayuong){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "insert into chitietdonthuoc(madonthuoc,mathuoc,lieudung,thoigianuong,songayuong) 
                        values('$madonthuoc','$mathuoc','$lieudung','$thoigianuong','$songayuong')";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        
        }

        public function select_chitietdonthuoc_madonthuoc($madonthuoc){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from chitietdonthuoc ct
                join donthuoc dt on ct.madonthuoc=dt.madonthuoc
                join thuoc t on ct.mathuoc=t.mathuoc
                where ct.madonthuoc='$madonthuoc'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        
        }
    }
?>