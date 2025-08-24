<?php
include_once("Models/mchitietchuyenkhoa.php");

class cChuyenKhoa {
    public function getChiTietChuyenKhoaByID($id) {
        $p = new mChuyenKhoa();
        $tbl = $p->chitietchuyenkhoa($id);
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
