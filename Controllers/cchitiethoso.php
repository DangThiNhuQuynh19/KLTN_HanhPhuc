<?php
    include_once("Models/mchitiethoso.php");
    class cChiTietHoSo{
        public function create_chitiethoso($mahoso,$mabacsi,$trieuchung,$chandoan,$huongdieutri,$madonthuoc,$ketluan) {
            $p = new mChiTietHoSo();
            $tbl = $p->insert_chitiethoso($mahoso,$mabacsi,$trieuchung,$chandoan,$huongdieutri,$madonthuoc,$ketluan);
            if (!$tbl) {
                return -1;
            } else {
                return $tbl;
            }
        }

        public function get_chitiethoso_mahoso($mahoso){
            $p = new mChiTietHoSo();
            $tbl = $p->select_chitiethoso_mahoso($mahoso);
            $list=array();
            if (!$tbl) {
                return -1;
            } else {
                if($tbl->num_rows>0){
                    while($r=$tbl->fetch_assoc()){
                        $list[]=$r;
                    }
                    return $list;
                }else{
                    return 0;
                }
            }
        }

        public function get_chitiethoso_machitiethoso($machitiet){
            $p = new mChiTietHoSo();
            $tbl = $p->select_chitiethoso_machitiethoso($machitiet);
            $list=array();
            if (!$tbl) {
                return -1;
            } else {
                if($tbl->num_rows>0){
                    while($r=$tbl->fetch_assoc()){
                        $list[]=$r;
                    }
                    return $list;
                }else{
                    return 0;
                }
            }
        }
    }
?>
