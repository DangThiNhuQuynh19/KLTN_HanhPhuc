<?php
 include_once('ketnoi.php');
 class mChuyenKhoa{
        public function chitietchuyenkhoa($id){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from chuyenkhoa where machuyenkhoa='$id'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
    }
?>