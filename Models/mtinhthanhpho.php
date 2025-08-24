<?php
include_once('ketnoi.php');
class mTinhThanhPho{
    public function select_tinhthanhpho(){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "select * from tinhthanhpho";
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }
}