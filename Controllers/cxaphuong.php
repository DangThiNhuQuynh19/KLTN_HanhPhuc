<?php
    include_once("Models/mxaphuong.php");
    class cXaPhuong{
        public function get_xaphuong() {
            $p = new mXaPhuong();
            $tbl = $p->select_xaphuong();
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