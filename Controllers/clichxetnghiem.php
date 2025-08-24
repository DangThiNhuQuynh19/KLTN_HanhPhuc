<?php
include_once("Models/mlichxetnghiem.php");

class cLichXetNghiem{
    public function get_lichxetnghiem_mabacsi($mabacsi){
        $p = new mLichXetNghiem();
        $tbl = $p->select_lichxetnghiem_mabacsi($mabacsi);
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
    
    public function get_lichxetnghiem_tukhoa($tukhoa, $machuyenkhoa, $trangthai,$mabacsi){
        $p = new mLichXetNghiem();
        $tbl = $p->timkiem_lichxetnghiem($tukhoa , $machuyenkhoa , $trangthai, $mabacsi);
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

    public function get_lichxetnghiem_malichxetnghiem($mabacsi,$malichxetnghiem){
        $p = new mLichXetNghiem();
        $tbl = $p->select_lichxetnghiem_malichxetnghiem($mabacsi,$malichxetnghiem);
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

    public function get_lichxetnghiem_mabenhnhan($mabenhnhan){
        $p = new mLichXetNghiem();
        $tbl = $p->select_lichxetnghiemchitiet_mabenhnhan($mabenhnhan);
        $list=array();
        if(!$tbl){
            return -1;
        }else{
            if($tbl->num_rows > 0){
                while ($r=$tbl->fetch_assoc()){
                    $list[]=$r;
                }
                return $list;
            }else{
                return 0;
            }
        }
    }

    public function getlichxetnghiemtheotentk($tentk){
        $p = new mLichXetNghiem();
        $tbl = $p->lichxetnghiemtheotentk($tentk);
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

    public function get_lichxetnghiem(){
        $p = new mLichXetNghiem();
        $tbl = $p->select_LichXetNghiem();
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

    public function create_lichxetnghiem($mabenhnhan,$maloaixetnghiem,$ngayhen,$makhunggio,$trangthailichxetnghiem,$mahoso,$img){
        $p = new mLichXetNghiem();
        $tbl = $p->insert_lichxetnghiem($mabenhnhan,$maloaixetnghiem,$ngayhen,$makhunggio,$trangthailichxetnghiem,$mahoso,$img);
        if(!$tbl){
            return -1;
        }else{
            return 0;
        }
    }

    public function get_lichxetnghiem_mahoso($mahoso){
        $p = new mLichXetNghiem();
        $tbl = $p->select_lichxetnghiem_mahoso($mahoso);
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