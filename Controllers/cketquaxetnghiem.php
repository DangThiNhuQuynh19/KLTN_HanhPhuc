<?php
include_once("Models/mketquaxetnghiem.php");
class cKetQuaXetNghiem {
    public function get_ketquaxetnghiem_malichxetnghiem($malichxetnghiem) {
        $p = new mKetQuaXetNghiem();
        $tbl = $p->select_ketquaxetnghiem_malichxetnghiem($malichxetnghiem);
        if (!$tbl) {
            return -1; 
        } else {
            if ($tbl->num_rows > 0) {
                $list = array();
                while ($r = $tbl->fetch_assoc()) {
                    $list[] = $r;
                }
                return $list;
            } else {
                return 0; 
            }
        }
    }

    public function get_ketquaxetnghiem($malichxetnghiem){
        $p = new mKetQuaXetNghiem();
        $tbl = $p->select_ketquaxetnghiem($malichxetnghiem);
        if (!$tbl) {
            return -1; 
        } else {
            if ($tbl->num_rows > 0) {
                $list = array();
                while ($r = $tbl->fetch_assoc()) {
                    $list[] = $r;
                }
                return $list;
            } else {
                return 0; 
            }
        }
    }

    function count_nhanxet_ketquaxetnghiem($malichxetnghiem) {
        $kq = $this->get_ketquaxetnghiem($malichxetnghiem);
        $nhanxetcount = array();
        $binhthuong = 0;
        $hoicao = 0;
        $cao = 0;
    
        if ($kq) {
            foreach ($kq as $i) {
                if ($i['nhanxet'] == 'Bình thường') {
                    $binhthuong++;
                } else if ($i['nhanxet'] == 'Hơi cao') {
                    $hoicao++;
                } else if ($i['nhanxet'] == 'Cao') {
                    $cao++;
                }
            }
            $nhanxetcount = array(
                'binhthuong' => $binhthuong,
                'hoicao' => $hoicao,
                'cao' => $cao
            );
            return $nhanxetcount;
        }
        
        return null; 
    }

    public function get_ketquaxetnghiem_homnay($mabacsi){
        $p = new mKetQuaXetNghiem();
        $tbl = $p->select_ketquaxetnghiem_homnay($mabacsi);
        $list=array();
            if (!$tbl) {
                return -1;
            } else {
                if ($tbl->num_rows > 0) {
                    while($r=$tbl->fetch_assoc()){
                        $list[]=$r;
                    }
                    return $list;
                } else {
                    return 0;
                }
        }
    }

    public function count_ketquaxetnghiem_homnay($mabacsi){
        $list = $this->get_ketquaxetnghiem_homnay($mabacsi);
        return is_array($list) ? count($list) : 0;
    }

    public function get_ketquaxetnghiem_homqua($mabacsi){
        $p = new  mKetQuaXetNghiem();
        $tbl = $p->select_ketquaxetnghiem_homqua($mabacsi);
        $list=array();
            if (!$tbl) {
                return -1;
            } else {
                if ($tbl->num_rows > 0) {
                    while($r=$tbl->fetch_assoc()){
                        $list[]=$r;
                    }
                    return $list;
                } else {
                    return 0;
                }
        }
    }

    public function count_ketquaxetnghiem_homqua($mabacsi){
        $list = $this->get_ketquaxetnghiem_homqua($mabacsi);
        return is_array($list) ? count($list) : 0;
    }

    public function count_ketquaxetnghiem($mabacsi){
        $so_homnay = $this->count_ketquaxetnghiem_homnay($mabacsi);
        $so_homqua = $this->count_ketquaxetnghiem_homqua($mabacsi);
    
        $chenhlech = $so_homnay - $so_homqua;
    
        if ($chenhlech > 0) {
            $trangthai = 'Tăng';
        } elseif ($chenhlech < 0) {
            $trangthai = 'Giảm';
        } else {
            $trangthai = 'Không đổi';
        }
    
        return [
            'homnay' => $so_homnay,
            'homqua' => $so_homqua,
            'chenhlech' => $chenhlech,
            'trangthai' => $trangthai
        ];
    }

    public function get_ketquaxetnghiem_gannhat($mabacsi){
        $p = new  mKetQuaXetNghiem();
        $tbl = $p->select_ketquaxetnghiem_gannhat($mabacsi);
        $list=array();
            if (!$tbl) {
                return -1;
            } else {
                if ($tbl->num_rows > 0) {
                    while($r=$tbl->fetch_assoc()){
                        $list[]=$r;
                    }
                    return $list;
                } else {
                    return 0;
                }
        }
    }
    
    
}
?>
