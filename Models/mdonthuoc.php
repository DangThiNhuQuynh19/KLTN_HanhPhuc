<?php
    include_once('ketnoi.php');
    class mDonThuoc{
        public function select_donthuoc_mabenhnhan($mabenhnhan){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from chitiethoso as cths
                JOIN donthuoc as dt ON cths.madonthuoc=dt.madonthuoc
                JOIN hosobenhan as hs ON hs.mahoso = cths.mahoso 
                JOIN bacsi as bs on cths.mabacsi= bs.mabacsi
                where mabenhnhan='$mabenhnhan'; ";
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
                $str = "select * from chitietdonthuoc as ct 
                JOIN donthuoc as d on ct.madonthuoc = d.madonthuoc 
                JOIN thuoc as t on ct.mathuoc = t.mathuoc
                where ct.madonthuoc='$madonthuoc'; ";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function insert_donthuoc($ghichu){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "insert into donthuoc(ngaytaodonthuoc,ghichudonthuoc) values(CURDATE(),'$ghichu')";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function select_donthuoc_new(){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT * FROM donthuoc
                ORDER BY madonthuoc DESC
                LIMIT 1;";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function select_donthuoc_mahoso($mahoso){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT * FROM donthuoc dt
                join chitiethoso ct on dt.madonthuoc = ct.madonthuoc
                where ct.mahoso = '$mahoso'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
    }
?>