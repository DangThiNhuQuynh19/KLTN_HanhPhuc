<?php
    include_once('ketnoi.php');
    class mChiTietHoSo{
        public function insert_chitiethoso($mahoso,$mabacsi,$trieuchung,$chandoan,$huongdieutri,$madonthuoc,$ketluan){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "insert into chitiethoso(mahoso,mabacsi,ngaykham,trieuchungbandau,chandoan,huongdieutri,madonthuoc,ketluan) 
                values('$mahoso','$mabacsi',CURDATE(),'$trieuchung','$chandoan','$huongdieutri','$madonthuoc','$ketluan')";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function select_chitiethoso_mahoso($mahoso){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from chitiethoso as ct
                join bacsi as bs on ct.mabacsi=bs.mabacsi
                join chuyenkhoa as ck on ck.machuyenkhoa=bs.machuyenkhoa
                where ct.mahoso='$mahoso'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function select_chitiethoso_machitiethoso($machitiet){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from chitiethoso ct join bacsi bs on bs.mabacsi = ct.mabacsi where machitiethoso='$machitiet'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

    }
?>