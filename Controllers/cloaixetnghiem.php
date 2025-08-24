<?php
include_once("Models/mloaixetnghiem.php");
class cLoaiXetNghiem{
    public function get_danhmucxetnghiem(){
        $p = new mLoaiXetNghiem();
        $tbl = $p->select_danhmucxetnghiem();
        $list=array();
        if(!$tbl){
            return -1;
        }else{
            if($tbl->num_rows > 0){
               while($r=$tbl->fetch_assoc()){
                    $list[]=$r;
               }
               return $list;
            }else{
                return 0;
            }
        }
    }

    public function get_chitietdanhmuc_madanhmuc($madanhmuc){
        $p = new mLoaiXetNghiem();
        $tbl = $p->select_chitietdanhmuc_madanhmuc($madanhmuc);
        $list=array();
        if(!$tbl){
            return -1;
        }else{
            if($tbl->num_rows > 0){
               while($r=$tbl->fetch_assoc()){
                    $list[]=$r;
               }
               return $list;
            }else{
                return 0;
            }
        }
    }

    public function get_loaixetnghiem(){
        $p = new mLoaiXetNghiem();
        $tbl = $p->select_loaixetnghiem();
        $list=array();
        if(!$tbl){
            return -1;
        }else{
            if($tbl->num_rows > 0){
               while($r=$tbl->fetch_assoc()){
                    $list[]=$r;
               }
               return $list;
            }else{
                return 0;
            }
        }
    }

    public function get_loaixetnghiem_maloaixetnghiem($maloaixetnghiem){
        $p = new mLoaiXetNghiem();
        $tbl = $p->select_loaixetnghiem_maloaixetnghiem($maloaixetnghiem);
        $list=array();
        if(!$tbl){
            return -1;
        }else{
            if($tbl->num_rows > 0){
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