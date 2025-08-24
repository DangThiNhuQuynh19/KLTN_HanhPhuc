<?php
    include_once("Models/mkhunggioxetnghiem.php");
    class cKhungGioXetNghiem{
        public function get_khunggioxetnghiem(){
            $p = new mKhungGioXetNghiem();
            $tbl = $p->select_khunggioxetnghiem();
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
        public function get_khunggioxetnghiem_makhunggio($makhunggio){
            $p = new mKhungGioXetNghiem();
            $tbl = $p->select_khunggioxetnghiem_makhunggio($makhunggio);
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