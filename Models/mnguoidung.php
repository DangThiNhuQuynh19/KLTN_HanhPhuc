<?php
include_once('ketnoi.php');
class mXaPhuong{
    public function select_xaphuong_mathanhpho($mathanhpho){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "select * from xaphuong where matinhthanhpho = '$mathanhpho'";
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }
}