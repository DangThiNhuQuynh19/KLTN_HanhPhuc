<?php
include_once(__DIR__ . "/../model/mchitietbacsi.php");

class cBacSi {
    public function getChiTietBacSiByID($id) {
        $p = new mBacSi();
        $tbl = $p->chitietbacsi($id);
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
    public function getLichLamViecOfBacSi($id) {
        $p = new mBacSi();
        $tbl = $p->lichlamvieccuabacsi($id);
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
}
?>