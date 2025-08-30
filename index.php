<?php
    // error_reporting(0);
    ob_start();
    session_start();
    if(isset($_GET["action"]))
        $page=$_GET["action"];
    else
        $page='trangchu';
    if(isset($_GET["cate"]))
        $cate=$_GET["cate"];
    if(isset($_SESSION['dangnhap']) && $_SESSION['dangnhap']==0){
        if(file_exists("Views/bacsi/pages/".$page."/index.php")){
            require("Views/bacsi/layout/header.php");
            include("Views/bacsi/pages/".$page."/index.php");
        }
        else
            include("Views/bacsi/pages/404/index.php");
    }elseif($_SESSION['name']&& $_SESSION['email']){
        if(file_exists("Views/benhnhan/pages/".$page."/index.php")){
            require("Views/benhnhan/layout/header.php");
            include("Views/benhnhan/pages/".$page."/index.php");
        }
        else
            include("Views/benhnhan/pages/404/index.php");
    }else{
        if(file_exists("Views/benhnhan/pages/".$page."/index.php")){
            require("Views/benhnhan/layout/header.php");
            include("Views/benhnhan/pages/".$page."/index.php");
        }
        else
            include("Views/benhnhan/pages/404/index.php");
    }
?>