<?php
 include_once('ketnoi.php');
 class mLichLamViec{
        public function updatelichlamviecday($malichlamviec){
            $p = new clsketnoi();
            $truyvan = "UPDATE lichlamviec SET ghichu ='đã đặt' where malichlamviec='$malichlamviec'";
            $con = $p->moketnoi();
            $kq = mysqli_query($con, $truyvan);
            $p->dongketnoi($con);
            return $kq;
        }
        public function updatelichlamviectrong($malichlamviec){
            $p = new clsketnoi();
            $truyvan = "UPDATE lichlamviec SET ghichu ='' where malichlamviec='$malichlamviec'";
            $con = $p->moketnoi();
            $kq = mysqli_query($con, $truyvan);
            $p->dongketnoi($con);
            return $kq;
        }
        public function laymalichlamviec($mabacsi,$ngaylam,$macalamviec){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select malichlamviec from lichlamviec where mabacsi='$mabacsi' and ngaylam='$ngaylam' and
                        macalamviec='$macalamviec'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
    }
?>