<?php
include_once("Models/mphieukhambenh.php");

class cPhieuKhamBenh {
    public function insertphieukham($maphieukb,$ngaykham,$macalamviec,$mabacsi,$mabenhnhan,$tongtien,$trangthai){
        $p = new mPhieuKhamBenh();
        $kq = $p -> insertphieukham($maphieukb,$ngaykham,$macalamviec,$mabacsi,$mabenhnhan,$tongtien,$trangthai);
        if($kq){
            return $kq;
        } else {
            return false;
        }
    }
    public function getAllPhieuKhamBenhOfBN($idbn){
        $p = new mPhieuKhamBenh();
        $tbl = $p->phieukhambenhcuabn($idbn);
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
    public function kiemTraTrungLich($mabenhnhan, $ngaykham, $macalamviec) {
        $model = new mPhieukhambenh();
        $result = $model->kiemTraTrungLich($mabenhnhan, $ngaykham, $macalamviec);
        
        if ($result && $result->num_rows > 0) {
            return true; // Đã có lịch trùng
        }
        return false; // Không có lịch trùng
    }
    public function getAllPhieuKhamBenhOfTK($tentk,$trangthai) {
        $p = new mPhieuKhamBenh();
        $tbl = $p->phieukhambenhcuataikhoan($tentk,$trangthai);
        if (!$tbl) {
            return -1;
        } else {
            if ($tbl->num_rows > 0) {
                return $tbl;
            } else {
                return 0;
            }
        }
    }
    public function updatephieukhambenh($maphieukb) {
        $p = new mPhieuKhamBenh();
        $kq = $p->updatePhieuKhamBenh($maphieukb);
        if ($kq) { 
            return true;
        } else {
            return false;
        }
        
    }

    public function cancelPhieuKhamBenh($maphieukb) {
        $p = new mPhieuKhamBenh();
        $result = $p->huyPhieuKhamBenh($maphieukb); 
        return $result;
    }
    public function getPhieuKhamBenhOfIDPK($id){
        $p = new mPhieuKhamBenh();
        $tbl = $p->phieukhamtheoidpk($id);
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

    public function get_lichkham_mabacsi($mabacsi){
        $p = new mPhieuKhamBenh();
        $tbl = $p->select_lichkham_mabacsi($mabacsi);
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

    public function get_lichkham_homnay($mabacsi){
        $p = new mPhieuKhamBenh();
        $tbl = $p->select_phieukham_homnay($mabacsi);
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

    public function count_benhnhan_homnay($mabacsi){
        $list = $this->get_lichkham_homnay($mabacsi);
        return is_array($list) ? count($list) : 0;
    }

    public function get_lichkham_homqua($mabacsi){
        $p = new mPhieuKhamBenh();
        $tbl = $p->select_phieukham_homqua($mabacsi);
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

    public function count_benhnhan_homqua($mabacsi){
        $list = $this->get_lichkham_homqua($mabacsi);
        return is_array($list) ? count($list) : 0;
    }

    public function count_benhnhan($mabacsi){
        $so_homnay = $this->count_benhnhan_homnay($mabacsi);
        $so_homqua = $this->count_benhnhan_homqua($mabacsi);
    
        $chenhlech = $so_homnay - $so_homqua;
    
        if ($chenhlech > 0) {
            $trangthai = 'Tăng';
        } elseif ($chenhlech < 0) {
            $trangthai = 'Giảm';
        } else {
            $trangthai = 'Không đổi';
        }
    
        return [
            'homnay' => $so_homnay,
            'homqua' => $so_homqua,
            'chenhlech' => $chenhlech,
            'trangthai' => $trangthai
        ];
    }
    
    public function search_phieukham($tukhoa,$trangthai,$ngay,$mabacsi){
        $p = new mPhieuKhamBenh();
        $tbl = $p->timkiem_phieukham($tukhoa,$trangthai,$ngay,$mabacsi);
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

    public function capnhat_trangthai_phieukham(){
        $p = new mPhieuKhamBenh();
        $kq = $p->update_trangthai_phieukhambenh();
        if ($kq) { 
            return true;
        } else {
            return false;
        }
    }

    public function get_sophieukham_trongtuan($mabacsi){
        $p = new mPhieuKhamBenh();
        $tbl = $p->select_phieukham_trongtuan($mabacsi);
            if (!$tbl) {
                return -1;
            } else {
                if ($tbl->num_rows > 0) {
                    return $tbl->fetch_assoc();
                } else {
                    return 0;
                }
        }
    }

    public function get_lichkham_sapden($mabacsi){
        $p = new mPhieuKhamBenh();
        $tbl = $p->select_lickham_sapden($mabacsi);
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
