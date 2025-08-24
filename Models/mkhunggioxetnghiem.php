<?php
include_once("ketnoi.php");
class mKhungGioXetNghiem{
    public function select_khunggioxetnghiem(){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "select * from khunggioxetnghiem";
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }

    public function select_khunggioxetnghiem_makhunggio($makhunggio){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "select * from khunggioxetnghiem where makhunggioxetnghiem='$makhunggio'";
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }
}
?>