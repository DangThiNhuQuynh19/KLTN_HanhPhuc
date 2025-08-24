<?php
include_once('ketnoi.php');
class mKetQuaXetNghiem{
    function select_ketquaxetnghiem_malichxetnghiem($malichxetnghiem){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "select * from lichxetnghiem l
            join  benhnhan bn on bn.mabenhnhan = l.mabenhnhan
            join  loaixetnghiem loai on loai.maloaixetnghiem=l.maloaixetnghiem
            join  hosobenhan hs on hs.mahoso=l.mahoso
            join  chitiethoso ct on ct.mahoso=hs.mahoso
            join  bacsi bs on ct.mabacsi = bs.mabacsi 
            join  chuyenkhoa ck on bs.machuyenkhoa = ck.machuyenkhoa 
            where malichxetnghiem= '$malichxetnghiem'
            group by malichxetnghiem";
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }
    function select_ketquaxetnghiem($malichxetnghiem){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "select * from ketquaxetnghiem where malichxetnghiem='$malichxetnghiem'";
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }

    function select_ketquaxetnghiem_homnay($mabacsi){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "select * from ketquaxetnghiem as kq
            join lichxetnghiem as l on kq.malichxetnghiem=l.malichxetnghiem
            join loaixetnghiem as loai on loai.maloaixetnghiem=l.maloaixetnghiem
            join chuyenkhoa as ck on ck.machuyenkhoa = loai.machuyenkhoa
            join hosobenhan as hs on hs.mahoso=l.mahoso
            join benhnhan as bn on bn.mabenhnhan=hs.mabenhnhan
            join chitiethoso as ct on ct.mahoso= l.mahoso
            join khunggioxetnghiem as k on k.makhunggioxetnghiem=l.makhunggio
            where ct.mabacsi='$mabacsi' AND DATE(kq.thoigiantao) = CURDATE()
            group by l.malichxetnghiem ";
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }

    function select_ketquaxetnghiem_homqua($mabacsi){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "select * from ketquaxetnghiem as kq
            join lichxetnghiem as l on kq.malichxetnghiem=l.malichxetnghiem
            join chitiethoso as ct on ct.mahoso= l.mahoso
            where ct.mabacsi='$mabacsi' AND DATE(kq.thoigiantao) = CURDATE() - 1
            group by l.malichxetnghiem ";
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }

    function select_ketquaxetnghiem_gannhat($mabacsi){
        $p = new clsKetNoi();
        $con = $p->moketnoi();
        $con->set_charset('utf8');
        if($con){
            $str = "select * from ketquaxetnghiem as kq
            join lichxetnghiem as l on kq.malichxetnghiem=l.malichxetnghiem
            join chitiethoso as ct on ct.mahoso= l.mahoso
            join bacsi as bs on bs.mabacsi=ct.mabacsi
            join benhnhan as bn on bn.mabenhnhan= l.mabenhnhan
            join chuyenkhoa as ck on bs.machuyenkhoa=ck.machuyenkhoa
            where ct.mabacsi='$mabacsi'
            group by l.malichxetnghiem
            ORDER BY thoigiantao DESC LIMIT 3";
            $tbl = $con->query($str);
            $p->dongketnoi($con);
            return $tbl;
        }else{
            return false; 
        }
    }

}
?>