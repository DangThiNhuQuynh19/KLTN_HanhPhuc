<?php
error_reporting(0);
    include_once('Controllers/cbenhnhan.php');
    $cbenhnhan= new cbenhnhan();
    if(isset($_SESSION["dangnhap"]) && isset($_SESSION["user"])){
        $user = $_SESSION["user"];
        $benhnhan = $cbenhnhan->getbenhnhanbytk($user["tentk"]);
        if($_SESSION["dangnhap"]==1 || isset($_SESSION["name"])){
            $vaitro="Bệnh nhân";
        }else{
            $vaitro="Bác sĩ";
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hanh Phuc Hospital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        /* HEADER */
        .header {
            width: 100%;
            height: 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000; 
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 0 40px;
            transition: all 0.3s ease;
            position: fixed;
        }
        
        .header_logo {
            display: flex;
            align-items: center;
        }
        
        .header_logo img {
            width: 180px;
            height: auto;
            transition: all 0.3s ease;
        }
        
        .menu {
            flex: 1;
            display: flex;
            justify-content: center;
        }
        
        .menu ul {
            list-style-type: none;
            display: flex;
            justify-content: center;
            margin: 0;
            padding: 0;
        }
        
        .menu li {
            margin: 0 18px;
            position: relative;
        }
        
        .menu li a {
            text-decoration: none;
            color: #3c1561;
            font-weight: 500;
            font-size: 18px;
            padding: 8px 0;
            transition: all 0.3s ease;
            display: block;
            position: relative;
        }
        
        .menu li a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #3c1561;
            bottom: 0;
            left: 0;
            transition: width 0.3s ease;
        }
        
        .menu li a:hover:after,
        .menu li a.active:after {
            width: 100%;
        }
        
        .menu li a:hover {
            color: #3c1561;
        }
        
        .menu li a.active {
            color: #3c1561;
            font-weight: 600;
        }
        
        /* User dropdown styling */
        .user-dropdown {
            position: relative;
            margin-left: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        
        .user-dropdown:hover {
            background-color: #f5f5f5;
        }
        
        .user-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid #e6e6e6;
            transition: all 0.3s ease;
        }
        
        .user-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .user-info {
            margin-left: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }
        
        .user-role {
            font-size: 12px;
            color: #666;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 55px;
            right: 0;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            width: 220px;
            display: none;
            z-index: 9999;
            overflow: hidden;
            transition: all 0.3s ease;
            transform-origin: top right;
            transform: scale(0.95);
            opacity: 0;
        }
        
        .dropdown-menu.show {
            display: block;
            transform: scale(1);
            opacity: 1;
        }
        
        .dropdown-header {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            text-align: center;
        }
        
        .dropdown-header h4 {
            margin: 0;
            color: #333;
            font-size: 16px;
        }
        
        .dropdown-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.2s;
            border-left: 3px solid transparent;
        }
        
        .dropdown-menu a:hover {
            background-color: #f8f9fa;
            border-left: 3px solid #3c1561;
        }
        
        .dropdown-menu a i {
            margin-right: 10px;
            color: #3c1561;
            width: 20px;
            text-align: center;
        }
        
        .dropdown-divider {
            height: 1px;
            background-color: #f0f0f0;
            margin: 5px 0;
        }
        
        .acc {
            display: flex;
            gap: 10px;
        }
        
        .btn-login, .btn-register {
            display: inline-block;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 30px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .btn-login {
            background-color: white;
            color: #3c1561;
            border: 1px solid #3c1561;
        }
        
        .btn-login:hover {
            background-color: #f5f5f5;
            transform: translateY(-2px);
        }
        
        .btn-register {
            background-color: #3c1561;
            color: white;
            border: 1px solid #3c1561;
        }
        
        .btn-register:hover {
            background-color: #4d1a7c;
            transform: translateY(-2px);
        }
        
        /* Responsive styles */
        @media (max-width: 1024px) {
            .header {
                padding: 0 20px;
            }
            .menu li {
                margin: 0 10px;
            }
        }
        
        @media (max-width: 768px) {
            .menu {
                display: none;
            }
            .mobile-menu-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="header">
            <div class="header_logo">
                <a href="?action=trangchu"> 
                    <img src="Assets/img/logo.png" alt="Hanh Phuc Hospital Logo" style="width:200px;">
                </a>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="?action=vechungtoi" class="<?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] === 'vechungtoi') ? 'active' : ''; ?>">Về chúng tôi</a></li>
                    <li><a href="?action=chuyenkhoa" class="<?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] === 'chuyenkhoa') ? 'active' : ''; ?>">Chuyên khoa</a></li>
                    <li><a href="?action=bacsi" class="<?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] === 'bacsi') ? 'active' : ''; ?>">Bác sĩ</a></li>
                    <li><a href="?action=chuyengia" class="<?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] === 'chuyengia') ? 'active' : ''; ?>">Chuyên gia tư vấn</a></li>
                    <li><a href="?action=lienhe" class="<?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] === 'lienhe') ? 'active' : ''; ?>">Liên hệ</a></li>
                    <li><a href="?action=blog" class="<?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] === 'blog') ? 'active' : ''; ?>">Blog</a></li>
                </ul>
            </div>

            <?php if (isset($_SESSION['dangnhap']) && $_SESSION['dangnhap'] == 1 || isset($_SESSION['name'])): ?>
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-icon" id="userIcon">
                        <img src="Assets/img/user-icon.png" alt="User">
                    </div>
                    <?php if(isset($_SESSION['name'])):?>
                        <div class="user-info">
                            <span class="user-name"><?php echo $_SESSION['name']?></span>
                            <span class="user-role"><?php echo $vaitro?></span>
                        </div>
                    <?php endif ?>
                    <div class="user-info">
                        <span class="user-name"><?php echo $benhnhan["hotenbenhnhan"]?></span>
                        <span class="user-role"><?php echo $vaitro?></span>
                    </div>
                    <div class="dropdown-menu" id="dropdownMenu">
                        <div class="dropdown-header">
                            <h4>Tài khoản của bạn</h4>
                        </div>
                        <a href="?action=hosobenhandientu"><i class="fas fa-file-medical"></i> Hồ sơ bệnh án điện tử</a>
                        <a href="?action=lichhen"><i class="fas fa-calendar-check"></i> Lịch hẹn của bạn</a>
                        <a href="?action=lichxetnghiem"><i class="fas fa-stethoscope"></i> Lịch hẹn xét nghiệm</a>
                        <a href="?action=tinnhan"><i class="fas fa-envelope"></i> Tin nhắn</a>
                        <div class="dropdown-divider"></div>
                        <a href="?action=caidat"><i class="fas fa-cog"></i> Cài đặt tài khoản</a>
                        <a href="?action=dangxuat"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="acc">
                    <a href="?action=dangnhap" class="btn-login">Đăng nhập</a>
                    <a href="?action=dangky" class="btn-register">Đăng ký</a>
                </div>
            <?php endif; ?>

        </div>
        
        <!-- Rest of your content goes here -->
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userDropdown = document.getElementById('userDropdown');
            const dropdownMenu = document.getElementById('dropdownMenu');
            
            if (userDropdown && dropdownMenu) {
                // Khi click vào dropdown user
                userDropdown.addEventListener('click', function(event) {
                    event.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                    
                    // Thêm hiệu ứng ripple khi click
                    const ripple = document.createElement('span');
                    ripple.classList.add('ripple');
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
                
                // Ngăn sự kiện click trong dropdown lan ra ngoài
                dropdownMenu.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
                
                // Đóng dropdown khi click ra ngoài
                document.addEventListener('click', function() {
                    if (dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.remove('show');
                    }
                });
                
                // Thêm hiệu ứng khi scroll
                window.addEventListener('scroll', function() {
                    const header = document.querySelector('.header');
                    if (window.scrollY > 50) {
                        header.style.height = '70px';
                        header.style.boxShadow = '0 2px 15px rgba(0,0,0,0.1)';
                    } else {
                        header.style.height = '80px';
                        header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.08)';
                    }
                });
            }
        });
    </script>
</body>
</html>