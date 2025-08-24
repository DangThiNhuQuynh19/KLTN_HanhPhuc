<?php
    include_once("Models/mthuoc.php");
    class cThuoc{
        public function get_thuoc() {
            $p = new mThuoc();
            $tbl = $p->select_thuoc();
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
