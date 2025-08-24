<?php
 include_once('ketnoi.php');
 class mPhieuKhamBenh{
        public function insertphieukham($maphieukb,$ngaykham,$macalamviec,$mabacsi,$mabenhnhan,$tongtien,$trangthai){
            $p = new clsketnoi();
            $truyvan = "INSERT INTO phieukhambenh(maphieukb,ngaykham,macalamviec,mabacsi,mabenhnhan,tongtien,
                            trangthai) VALUES ('$maphieukb','$ngaykham','$macalamviec','$mabacsi','$mabenhnhan',
                            '$tongtien','$trangthai')";
            $con = $p->moketnoi();
            $kq = mysqli_query($con, $truyvan);
            $p->dongketnoi($con);
            return $kq;
        }
        public function phieukhambenhcuabn($idbn){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT *
                    FROM phieukhambenh pk 
                    JOIN bacsi bs ON pk.mabacsi = bs.mabacsi 
                    JOIN calamviec cv ON pk.macalamviec = cv.macalamviec
                    WHERE pk.mabenhnhan = '$idbn'";
        
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function kiemTraTrungLich($mabenhnhan, $ngaykham, $macalamviec) {
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT * FROM phieukhambenh 
                    WHERE mabenhnhan = '$mabenhnhan' 
                      AND ngaykham = '$ngaykham' 
                      AND macalamviec = '$macalamviec'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }   
        public function phieukhambenhcuataikhoan($tentk, $status) {
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
        
            if ($con) {
                $sql = "SELECT pk.maphieukb, pk.ngaykham, ca.giobatdau, pk.trangthai, ca.gioketthuc, bs.hoten AS hotenbacsi,
                bn.hotenbenhnhan, ck.tenchuyenkhoa
                FROM phieukhambenh pk
                JOIN bacsi bs ON pk.mabacsi = bs.mabacsi
                JOIN benhnhan bn ON pk.mabenhnhan = bn.mabenhnhan
                JOIN chuyenkhoa ck ON bs.machuyenkhoa = ck.machuyenkhoa
                JOIN calamviec ca ON ca.macalamviec=pk.macalamviec
                WHERE bn.tentk  = '$tentk'";

                if (!empty($status)) {
                    $sql .= " AND pk.trangthai = '$status'";
                }
        
                $tbl = $con->query($sql);
        
                $p->dongketnoi($con);
                return $tbl;
            } else {
                return false;
            }
        }
        public function huyPhieuKhamBenh($maphieukb) {
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "update phieukhambenh set trangthai='đã hủy' WHERE maphieukb = '$maphieukb'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }   
        public function updatePhieuKhamBenh($maphieukb) {
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "update phieukhambenh set trangthai='đã khám' WHERE maphieukb = '$maphieukb'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }   
        public function phieukhamtheoidpk($maphieukb) {
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * FROM phieukhambenh WHERE maphieukb = '$maphieukb' ";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        } 
        
        public function select_lichkham_mabacsi($mabacsi){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT bs.hoten, pk.trangthai, pk.*, ca.*, bn.*
                FROM phieukhambenh AS pk
                JOIN calamviec AS ca ON ca.macalamviec = pk.macalamviec
                JOIN bacsi AS bs ON pk.mabacsi = bs.mabacsi
                JOIN benhnhan AS bn ON bn.mabenhnhan = pk.mabenhnhan
                WHERE bs.mabacsi = '$mabacsi' ";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function update_trangthai_phieukhambenh(){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if ($con) {
                $sql = "UPDATE phieukhambenh pk
                        JOIN calamviec ca ON pk.macalamviec = ca.macalamviec
                        SET pk.trangthai = 'đã khám'
                        WHERE (
                            CURDATE() > pk.ngaykham OR
                            (CURDATE() = pk.ngaykham AND CURTIME() > ca.gioketthuc)
                        )
                        AND pk.trangthai != 'đã khám'";
                        
                $result = $con->query($sql);
                $p->dongketnoi($con);
                return $result;
            } else {
                return false;
            }
        }

        public function select_phieukham_homnay($mabacsi){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT bs.hoten, pk.trangthai, pk.*, ca.*, bn.*, ck.tenchuyenkhoa
                FROM phieukhambenh AS pk
                JOIN calamviec AS ca ON ca.macalamviec = pk.macalamviec
                JOIN bacsi AS bs ON pk.mabacsi = bs.mabacsi
                JOIN chuyenkhoa ck ON ck.machuyenkhoa=bs.machuyenkhoa
                JOIN benhnhan AS bn ON bn.mabenhnhan = pk.mabenhnhan
                WHERE bs.mabacsi = '$mabacsi' AND  pk.ngaykham = CURDATE()";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function select_phieukham_homqua($mabacsi){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT bs.hoten, pk.trangthai, pk.*, ca.*, bn.*
                FROM phieukhambenh AS pk
                JOIN calamviec AS ca ON ca.macalamviec = pk.macalamviec
                JOIN bacsi AS bs ON pk.mabacsi = bs.mabacsi
                JOIN benhnhan AS bn ON bn.mabenhnhan = pk.mabenhnhan
                WHERE bs.mabacsi = '$mabacsi' AND  pk.ngaykham = CURDATE()-1";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function timkiem_phieukham($tukhoa,$trangthai,$ngay,$mabacsi){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT bs.hoten, pk.trangthai, pk.*, ca.*, bn.*
                FROM phieukhambenh AS pk
                JOIN calamviec AS ca ON ca.macalamviec = pk.macalamviec
                JOIN bacsi AS bs ON pk.mabacsi = bs.mabacsi
                JOIN benhnhan AS bn ON bn.mabenhnhan = pk.mabenhnhan
                WHERE bs.mabacsi = '$mabacsi'";
                if (!empty($tukhoa)) {
                    $str .= " AND (bn.hotenbenhnhan LIKE '%$tukhoa%' OR pk.maphieukb LIKE '%$tukhoa%')";
                }
                if (!empty($trangthai)) {
                    $str .= " AND pk.trangthai = '$trangthai'";
                }
                if (!empty($ngay)) {
                    $ngay_mysql = date("Y-m-d", strtotime($ngay));
                    $str .= " AND pk.ngaykham = '$ngay_mysql'";
                }
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function select_phieukham_trongtuan($mabacsi){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT COUNT(*) AS solichhentrongtuan
                FROM phieukhambenh
                WHERE WEEK(ngaykham, 1) = WEEK(CURDATE(), 1)
                AND YEAR(ngaykham) = YEAR(CURDATE())
                AND mabacsi = '$mabacsi';";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }

        public function select_lickham_sapden($mabacsi){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT bs.hoten, pk.trangthai, pk.*, ca.*, bn.*, c.*, bs.*
                FROM phieukhambenh AS pk
                JOIN calamviec AS ca ON ca.macalamviec = pk.macalamviec
                JOIN bacsi AS bs ON pk.mabacsi = bs.mabacsi
                JOIN benhnhan AS bn ON bn.mabenhnhan = pk.mabenhnhan
                JOIN chuyenkhoa AS c ON c.machuyenkhoa = bs.machuyenkhoa
                WHERE bs.mabacsi = '$mabacsi'
                AND pk.ngaykham >= CURDATE() 
                AND ca.giobatdau >= CURTIME()
                ORDER BY pk.ngaykham ASC, ca.giobatdau ASC
                LIMIT 1;";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
    }
?>