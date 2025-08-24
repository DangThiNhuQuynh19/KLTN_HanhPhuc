<?php
include_once("Models/mlinhvuc.php");

class cLinhVuc{
    public function getAllLinhVuc(){
        $p = new mLinhVuc();
        $tbl = $p->dslinhvuc();
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