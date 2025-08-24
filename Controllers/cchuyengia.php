<?php
include_once("Models/mchuyengia.php");

class cChuyenGia{
    public function getAllChuyenGia(){
        $p = new mChuyenGia();
        $tbl = $p->dschuyengia();
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
    public function getChuyenGiaByLinhVuc($id){
        $p = new mChuyenGia();
        $tbl = $p->chuyengiatheolinhvuc($id);
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
    public function getChuyenGiaByTenAndLinhVuc($name,$id){
        $p = new mChuyenGia();
        $tbl = $p->chuyengiatheotenandlinhvuc($name,$id);
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
    public function getChuyenGiaByName($id){
        $p = new mChuyenGia();
        $tbl = $p->chuyengiatheoten($id);
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