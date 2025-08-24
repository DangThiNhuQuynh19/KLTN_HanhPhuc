<?php
include_once("Models/mlichlamviec.php");

class cLichLamViec {
    public function updatelichlamviecday($malichlamviec){
        $p = new mLichLamViec();
        $kq = $p -> updatelichlamviecday($malichlamviec);
        if($kq){
            return $kq;
        } else {
            return false;
        }
    }
    public function updatelichlamviectrong($malichlamviec){
        $p = new mLichLamViec();
        $kq = $p -> updatelichlamviectrong($malichlamviec);
        if($kq){
            return $kq;
        } else {
            return false;
        }
    }
    public function getmalichlamviec($mabacsi,$ngaylam,$macalamviec){
        $p = new mLichLamViec();
        $tbl = $p->laymalichlamviec($mabacsi,$ngaylam,$macalamviec);
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
}
?>
