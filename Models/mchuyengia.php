<?php
require_once('ketnoi.php');
 class mChuyenGia{
        public function dschuyengia(){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from chuyengia 
                        join linhvuc on chuyengia.malinhvuc = linhvuc.malinhvuc 
                        join nguoidung on chuyengia.machuyengia = nguoidung.manguoidung
                        join taikhoan on nguoidung.tentk = taikhoan.tentk
                        where taikhoan.matrangthai = 1 order by chuyengia.machuyengia desc";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function chuyengiatheolinhvuc($id){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "select * from chuyengia join linhvuc on chuyengia.malinhvuc = linhvuc.malinhvuc where chuyengia.malinhvuc='$id'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function chuyengiatheotenandlinhvuc($name,$id){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT * FROM chuyengia 
                JOIN linhvuc ON chuyengia.malinhvuc = linhvuc.malinhvuc
                JOIN nguoidung ON chuyengia.machuyengia = nguoidung.manguoidung
                WHERE nguoidung.hoten LIKE '%$name%' AND chuyengia.malinhvuc='$id'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
        public function chuyengiatheoten($name){
            $p = new clsKetNoi();
            $con = $p->moketnoi();
            $con->set_charset('utf8');
            if($con){
                $str = "SELECT * FROM chuyengia
                JOIN linhvuc ON chuyengia.malinhvuc = linhvuc.malinhvuc
                JOIN nguoidung ON chuyengia.machuyengia = nguoidung.manguoidung
                WHERE nguoidung.hoten LIKE '%$name%'";
                $tbl = $con->query($str);
                $p->dongketnoi($con);
                return $tbl;
            }else{
                return false; 
            }
        }
    }
?>