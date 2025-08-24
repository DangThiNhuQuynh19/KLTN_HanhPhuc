<?php
include_once("Models/mchuyenkhoa.php");

class cChuyenKhoa{
    public function getAllChuyenKhoa(){
        $p = new mChuyenKhoa();
        $tbl = $p->dschuyenkhoa();
        if(!$tbl){
            return -1;
        }else{
            if($tbl->num_rows > 0){
                return $tbl;
            }else{
                return 0;
            }
        }
    }

    public function get_chuyenkhoa_notmabenhnhan($mabenhnhan){
        $p = new mChuyenKhoa();
        $tbl = $p->select_chuyenkhoa_notmabenhnhan($mabenhnhan);
        if(!$tbl){
            return -1;
        }else{
            if($tbl->num_rows > 0){
                return $tbl;
            }else{
                return 0;
            }
        }
    }

    public function get_chuyenkhoa_mabacsi($mabacsi){
        $p = new mChuyenKhoa();
        $tbl = $p->select_chuyenkhoa_mabacsi($mabacsi);
        if(!$tbl){
            return -1;
        }else{
            if($tbl->num_rows > 0){
                return $tbl->fetch_assoc();
            }else{
                return 0;
            }
        }
    }
}
?>