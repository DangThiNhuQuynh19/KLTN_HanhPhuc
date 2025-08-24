<?php
 include_once('ketnoi.php');
class mLichKham {
    public function lichkhamcg($ngay, $id, $gioHienTai = null) {
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if ($con) {
            $str = "";
            // Kiểm tra nếu ngày là hôm nay và có giờ hiện tại
            if ($ngay == date('Y-m-d') && $gioHienTai !== null) {
                // Lọc ca làm việc có giờ bắt đầu >= giờ hiện tại
                $str = "SELECT * FROM khunggiokhambenh 
                        JOIN calamviec ON khunggiokhambenh.macalamviec = calamviec.macalamviec 
                        JOIN lichlamviec ON lichlamviec.macalamviec = calamviec.macalamviec 
                        JOIN nguoidung ON lichlamviec.manguoidung = nguoidung.manguoidung 
                        JOIN chuyengia ON nguoidung.manguoidung = chuyengia.machuyengia
                        LEFT JOIN phieukhambenh ON phieukhambenh.makhunggiokb = lichlamviec.malichlamviec 
                        WHERE  ngaylam = '$ngay' 
                            AND chuyengia.machuyengia = '$id'
                            AND khunggiokhambenh.giobatdau >= '$gioHienTai'
                            AND phieukhambenh.maphieukhambenh IS NULL";
            } else {
                // Ngày lớn hơn hôm nay, hiển thị tất cả ca
                $str = "SELECT * FROM khunggiokhambenh 
                        JOIN calamviec ON khunggiokhambenh.macalamviec = calamviec.macalamviec 
                        JOIN lichlamviec ON lichlamviec.macalamviec = calamviec.macalamviec 
                        JOIN nguoidung ON lichlamviec.manguoidung = nguoidung.manguoidung 
                        JOIN chuyengia ON nguoidung.manguoidung = chuyengia.machuyengia
                        LEFT JOIN phieukhambenh ON phieukhambenh.makhunggiokb = lichlamviec.malichlamviec 
                        WHERE  ngaylam = '$ngay' 
                            AND chuyengia.machuyengia = '$id'
                            AND phieukhambenh.maphieukhambenh IS NULL";
            }
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        } else {
            return false;
        }
    }
    public function lichkhambs($ngay, $id, $gioHienTai = null) {
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if ($con) {
            $str = "";
            // Kiểm tra nếu ngày là hôm nay và có giờ hiện tại
            if ($ngay == date('Y-m-d') && $gioHienTai !== null) {
                // Lọc ca làm việc có giờ bắt đầu >= giờ hiện tại
                $str = "SELECT * FROM khunggiokhambenh 
                        JOIN calamviec ON khunggiokhambenh.macalamviec = calamviec.macalamviec 
                        JOIN lichlamviec ON lichlamviec.macalamviec = calamviec.macalamviec 
                        JOIN nguoidung ON lichlamviec.manguoidung = nguoidung.manguoidung 
                        JOIN bacsi ON nguoidung.manguoidung = bacsi.mabacsi
                        LEFT JOIN phieukhambenh ON phieukhambenh.makhunggiokb = lichlamviec.malichlamviec 
                        WHERE  ngaylam = '$ngay' 
                            AND bacsi.mabacsi = '$id'
                            AND khunggiokhambenh.giobatdau >= '$gioHienTai'
                            AND phieukhambenh.maphieukhambenh IS NULL";
            } else {
                // Ngày lớn hơn hôm nay, hiển thị tất cả ca
                $str = "SELECT * FROM khunggiokhambenh 
                        JOIN calamviec ON khunggiokhambenh.macalamviec = calamviec.macalamviec 
                        JOIN lichlamviec ON lichlamviec.macalamviec = calamviec.macalamviec 
                        JOIN nguoidung ON lichlamviec.manguoidung = nguoidung.manguoidung 
                        JOIN bacsi ON nguoidung.manguoidung = bacsi.mabacsi
                        LEFT JOIN phieukhambenh ON phieukhambenh.makhunggiokb = lichlamviec.malichlamviec 
                        WHERE  ngaylam = '$ngay' 
                            AND bacsi.mabacsi = '$id'
                            AND phieukhambenh.maphieukhambenh IS NULL";
            }
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        } else {
            return false;
        }
    }
    public function xemlich($id){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "select * from lichlamviec as lv join calamviec as cv 
            on cv.macalamviec = lv.macalamviec where cv.macalamviec='$id'";
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }
    public function kiemtragiohen($bs, $bn) {
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');

        if ($con) {
            $sql = "SELECT pkb.ngaykham, clv.giobatdau, clv.gioketthuc, bs.tentk, bn.tentk
                    FROM phieukhambenh pkb
                    JOIN calamviec clv ON pkb.macalamviec = clv.macalamviec
                    JOIN bacsi bs ON pkb.mabacsi = bs.mabacsi
                    JOIN benhnhan bn ON pkb.mabenhnhan = bn.mabenhnhan
                    WHERE bs.tentk = '$bs' AND bn.tentk = '$bn'";
            $result = $con->query($sql);
            $p->dongketnoi($con);
            return $result;
        } else {
            return false;
        }
    }
}

?>