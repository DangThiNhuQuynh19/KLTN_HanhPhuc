<?php
    include_once("Models/mtinhthanhpho.php");
    class cTinhThanhPho{
        public function get_tinhthanhpho() {
            $p = new mTinhThanhPho();
            $tbl = $p->select_tinhthanhpho();
            $list=array();
            if (!$tbl) {
                return -1;
            } else {
                if ($tbl->num_rows > 0) {
                    while($r=$tbl->fetch_assoc()){
                        $list[]=$r;
                    }
                    return $list;
                } else {
                    return 0;
                }
            }
        }
    }
?>