<?php
    include_once("Models/mdonthuoc.php");
    class cDonThuoc{
        public function get_donthuoc_mabenhnhan($mabenhnhan) {
            $p = new mDonThuoc();
            $tbl = $p->select_donthuoc_mabenhnhan($mabenhnhan);
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

        public function get_chitietdonthuoc_madonthuoc($madonthuoc) {
            $p = new mDonThuoc();
            $tbl = $p->select_chitietdonthuoc_madonthuoc($madonthuoc);
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

        public function create_donthuoc($ghichu){
            $p = new mDonThuoc();
            $tbl = $p->insert_donthuoc($ghichu);
            if(!$tbl){
                return -1;
            }else{
                return $tbl;
            }
        }

        public function get_donthuoc_new(){
            $p = new mDonThuoc();
            $tbl = $p->select_donthuoc_new();
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

        public function get_donthuoc_mahoso($mahoso){
            $p = new mDonThuoc();
            $tbl = $p->select_donthuoc_mahoso($mahoso);
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