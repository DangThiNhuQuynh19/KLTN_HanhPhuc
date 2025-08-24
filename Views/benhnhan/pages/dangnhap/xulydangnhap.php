<?php
include_once('Controllers/ctaikhoan.php');
include_once('Controllers/cbenhnhan.php');
$nguoidung = new ctaiKhoan();
$cbenhnhan= new cbenhnhan();
if (isset($_POST["btndangnhap"])) {
    $tentk = $_POST["tentk"];
    $password = MD5($_POST["password"]);
    $nguoidung->dangnhap($tentk, $password);

}
?>
