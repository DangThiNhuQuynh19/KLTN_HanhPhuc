<?php
 include_once('ketnoi.php');
 class mLinhVuc{
        public function dslinhvuc(){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from linhvuc";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        
    }
?>