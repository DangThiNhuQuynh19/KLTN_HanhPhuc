<?php
 include_once('ketnoi.php');
 class mBenhNhan{
        public function xembenhnhantheotentk($tentk){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from benhnhan where tentk = '$tentk' and quanhe  LIKE 'bản thân' limit 1";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function getBenhNhanByTenTK($tentk) {
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from benhnhan where tentk = '$tentk'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function getBenhNhanChinhByTenTK($tentk) {
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from benhnhan where tentk = '$tentk' and quanhe = 'bản thân'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function getBenhNhanByID($id) {
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from benhnhan where mabenhnhan = '$id'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function capnhatbenhnhan($mabenhnhan, $hotenbenhnhan, $ngaysinh, $gioitinh, $nghenghiep, $cccdbenhnhan,
                                $dantoc, $email, $sdtbenhnhan, $tinh, $quan, $xa, $sonha, $quanhe,
                                $tiensubenhtatcuagiadinh, $tiensubenhtatcuabenhnhan, $nhommau) {
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if ($con) {
                // Sử dụng dấu backtick để bao quanh tên cột
                $str = "UPDATE benhnhan 
                        SET hotenbenhnhan='$hotenbenhnhan', ngaysinh='$ngaysinh', gioitinh='$gioitinh', nghenghiep='$nghenghiep', 
                            cccdbenhnhan='$cccdbenhnhan', dantoc='$dantoc', email='$email', sdtbenhnhan='$sdtbenhnhan',
                            `tinh/thanhpho`='$tinh', `quan/huyen`='$quan', `xa/phuong`='$xa', sonha='$sonha', quanhe='$quanhe',
                            tiensubenhtatcuagiadinh='$tiensubenhtatcuagiadinh', tiensubenhtatcuabenhnhan='$tiensubenhtatcuabenhnhan', 
                            nhommau='$nhommau' 
                        WHERE mabenhnhan='$mabenhnhan'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            } else {
                return false;
            }
        }

        public function select_benhnhan_mabacsi($mabacsi){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from benhnhan as b join phieukhambenh as p on b.mabenhnhan=p.mabenhnhan where mabacsi='$mabacsi' GROUP BY b.mabenhnhan ORDER BY b.mabenhnhan DESC";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function select_benhnhan_id($id){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from benhnhan where mabenhnhan = '$id'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function insertbenhnhan($hotenbenhnhan, $ngaysinh, $gioitinh, $nghenghiep, $cccdbenhnhan,
                               $dantoc, $email, $sdtbenhnhan, $tinh, $quan, $xa, $sonha, $quanhe,
                               $tiensubenhtatcuagiadinh, $tiensubenhtatcuabenhnhan, $nhommau, $tentk) {
            $p = new clsketnoi();
            $con = $p->moketnoi();
            $truyvan = "INSERT INTO benhnhan(hotenbenhnhan, ngaysinh, gioitinh, nghenghiep, cccdbenhnhan,
                                            dantoc, email, sdtbenhnhan, `tinh/thanhpho`, `quan/huyen`, `xa/phuong`,
                                            sonha, quanhe, tiensubenhtatcuagiadinh, tiensubenhtatcuabenhnhan, nhommau, tentk) 
                        VALUES ('$hotenbenhnhan', '$ngaysinh', '$gioitinh', '$nghenghiep', '$cccdbenhnhan',
                                '$dantoc', '$email', '$sdtbenhnhan', '$tinh', '$quan', '$xa', '$sonha', '$quanhe',
                                '$tiensubenhtatcuagiadinh', '$tiensubenhtatcuabenhnhan', '$nhommau', '$tentk')";
            // Thực thi câu truy vấn
            $kq = mysqli_query($con, $truyvan);
            $p->dongketnoi($con);
            
            return $kq;
        }
        public function deletebenhnhan($id) {
            $p = new clsketnoi();
            $con = $p->moketnoi();
            $truyvan = "DELETE FROM benhnhan WHERE mabenhnhan = $id";
            $tbl = mysqli_query($con, $truyvan);
            $p->dongketnoi($con);
            return $tbl;
        }


        public function timkiem_benhnhan_tukhoa($tukhoa, $mabacsi){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
        
            if ($con) {
                $sql = "SELECT * 
                        FROM benhnhan AS b 
                        JOIN phieukhambenh AS p ON b.mabenhnhan = p.mabenhnhan 
                        WHERE p.mabacsi = '$mabacsi'";
        
                if (!empty($tukhoa)) {
                    $sql .= " AND (b.mabenhnhan = '$tukhoa' 
                                OR b.hotenbenhnhan LIKE '%$tukhoa%' 
                                OR b.cccdbenhnhan LIKE '%$tukhoa%')";
                }
        
                $sql .= " GROUP BY b.mabenhnhan 
                          ORDER BY b.mabenhnhan DESC";
        
                $result = $con->query($sql);
                $p->dongketnoi($con);
                return $result;
            } else {
                return false;
            }
        }
    }
    
?>