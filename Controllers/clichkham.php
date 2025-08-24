<?php
include_once(__DIR__ . '/../Models/mlichkham.php');

class cLichKham {
    public function getLichKhamOfBacSiByNgay($ngay, $id, $gioHienTai = null) {
        $p = new mLichKham();
        $tbl = $p->lichkham($ngay, $id, $gioHienTai);
    
        if (!$tbl) {
            return false;
        } else {
            return ($tbl->num_rows > 0) ? $tbl : false;
        }
    }
    
    
    public function getlich($id){
        $p = new mLichKham();
        $tbl = $p->xemlich($id);
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
    public function getlichhen($bs, $bn) {
        $p = new mLichKham();
        $tbl = $p->kiemtragiohen($bs, $bn);

        if (!$tbl) {
            return -1; // Không kết nối được CSDL
        } else {
            if ($tbl->num_rows > 0) {
                while ($row = $tbl->fetch_assoc()) {
                    $ngayKham = $row['ngaykham'];
                    $today = date('Y-m-d');

                    if ($ngayKham === $today) {
                        $batdau = strtotime($ngayKham . ' ' . $row['giobatdau']);
                        $ketthuc = strtotime($ngayKham . ' ' . $row['gioketthuc']);
                        $now = time();

                        if ($now >= $batdau && $now <= $ketthuc) {
                            return true; // Đúng thời điểm
                        }
                    }
                }
                return 0; // Có lịch nhưng không phải hôm nay hoặc chưa đến giờ
            } else {
                return 0; // Không có lịch
            }
        }
    }
}
?>
