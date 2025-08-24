<?php
    include_once("Models/mchitietdonthuoc.php");
    class cChiTietDonThuoc{
        public function create_chitietdonthuoc($madonthuoc,$mathuoc,$lieudung,$thoigianuong,$songayuong) {
            $p = new mChiTietDonThuoc();
            $tbl = $p->insert_chitietdonthuoc($madonthuoc,$mathuoc,$lieudung,$thoigianuong,$songayuong);
            if (!$tbl) {
                return -1;
            } else {
                return $tbl;
            }
            
        }

        public function get_chitietdonthuoc_madonthuoc($madonthuoc){
            $p = new mChiTietDonThuoc();
            $tbl = $p->select_chitietdonthuoc_madonthuoc($madonthuoc);
            $list = array();
            if (!$tbl) {
                return -1;
            } else {
                if($tbl->num_rows>0){
                    while($row = $tbl->fetch_assoc()){
                        $list[] = $row;
                    }
                    return $list;
                }else{
                    return 0;
                }
            }
        }
    }
?>