<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bệnh viện Hạnh Phúc</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            overflow-x: hidden;
            width: 100%;
        }

        .main {
            width: 100%;
            overflow: visible;
        }

        /* Banner Section */
        .banner {
            position: relative;
            z-index: 1;
            width: 100%;
            overflow: hidden; /* Ngăn chặn tràn nội dung */
        }

        .banner img {
            width: 100%;
            border-bottom-left-radius: 20%;
            border-bottom-right-radius: 20%;
            max-height: 650px;
            object-fit: cover;
        }

        .banner-text {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            padding-left: 5%;
            width: 50%;
            z-index: 1;
        }

        .banner-heading {
            font-size: clamp(1.8rem, 4vw, 3.5rem);
            font-weight: 500;
            color: rgb(105, 65, 145);
            font-family: 'Georgia', serif;
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .booking-button {
            display: inline-block;
            margin-top: 10px;
            padding: 12px 24px;
            font-size: clamp(0.9rem, 1.5vw, 1.25rem);
            font-weight: bold;
            background-color: rgb(105, 65, 145);
            color: white;
            text-shadow: 0 0 6px rgba(255, 255, 255, 0.4);
            border: none;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .booking-button:hover {
            background-color: rgb(85, 45, 125);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            transform: scale(1.05);
            text-shadow: 0 0 12px rgba(255, 255, 255, 0.7);
        }

        .corner-icon {
            position: absolute;
            bottom: 0;
            right: 5%;
            width: 120px;
            max-width: 15%;
            height: auto;
            z-index: 1;
        }

        /* Introduction Section */
        .introduce {
            padding: 80px 20px 40px;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            font-size: clamp(1rem, 2vw, 1.8rem);
            color: #3e4083;
            line-height: 1.6;
        }

        /* Departments & Doctors Sections */
        .khoa {
            padding: 60px 20px;
            color: #3e4083;
            font-family: 'Georgia', serif;
            text-align: center;
        }

        .khoa h1 {
            margin-bottom: 40px;
            font-size: clamp(1.5rem, 3vw, 2.5rem);
        }

        .departments-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            padding: 0 10px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .department-card {
            display: inline-block;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px 15px;
            text-align: center;
            width: 100%;
            max-width: 250px;
            text-decoration: none;
            transition: transform 0.3s ease;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .department-card:hover {
            transform: translateY(-6px);
        }

        .icon-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #f3e8ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            transition: background-color 0.3s ease;
        }

        .icon-circle1 {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
        }

        .department-card:hover .icon-circle {
            background-color: #9b59b6;
        }

        .department-card:hover .icon {
            filter: brightness(0) invert(1);
        }

        .icon {
            width: 80px;
            height: auto;
            max-width: 100%;
        }

        .icon1 {
            width: 120px;
            height: auto;
            max-width: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .title {
            font-size: 16px;
            color: #222;
            font-weight: 600;
            line-height: 1.5;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .title1 {
            font-size: 14px;
            color: #222;
            font-weight: 400;
            line-height: 1.5;
            font-family: sans-serif;
            font-style: italic;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #9b59b6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #800080;
        }

        /* Media Queries */
        @media (max-width: 992px) {
            .banner-text {
                width: 60%;
            }
            .corner-icon {
                width: 100px;
            }
        }

        @media (max-width: 768px) {
            .banner img {
                border-bottom-left-radius: 10%;
                border-bottom-right-radius: 10%;
            }
            .banner-text {
                width: 100%;
                padding: 0 20px;
                text-align: center;
                top: 40%;
            }
            .corner-icon {
                display: none; /* Ẩn icon góc trên mobile */
            }
            .department-card {
                max-width: 220px;
            }
            .icon-circle, .icon-circle1 {
                width: 100px;
                height: 100px;
            }
            .icon {
                width: 60px;
            }
            .icon1 {
                width: 100px;
            }
        }

        @media (max-width: 576px) {
            .banner img {
                border-bottom-left-radius: 8%;
                border-bottom-right-radius: 8%;
                min-height: 300px;
            }
            .banner-heading {
                font-size: clamp(1.5rem, 5vw, 2.5rem);
            }
            .department-card {
                max-width: 100%;
            }
            .departments-container {
                gap: 15px;
            }
            .introduce {
                padding: 60px 20px 30px;
            }
            .khoa {
                padding: 40px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="banner">
            <img src="Assets/img/banner.jpg" alt="Banner">
            <div class="banner-text">
                <div class="banner-heading">
                    CHĂM SÓC <br>SỨC KHỎE <br>GIA ĐÌNH<br>CỦA BẠN
                </div>
                <a href="?action=bacsi" class="booking-button">Đặt lịch khám online</a>
            </div>
            <img class="corner-icon" src="Assets/img/logo-banner.png" alt="icon">
        </div>
        
        <div class="introduce">
            <p>Bệnh viện Hạnh Phúc tự hào mang đến dịch vụ chăm sóc sức khỏe chất lượng cao cho mọi gia đình, 
                với đội ngũ bác sĩ tận tâm và trang thiết bị hiện đại. Giờ đây, bạn có thể dễ dàng kết 
                nối với bác sĩ qua dịch vụ khám bệnh trực tuyến – tiện lợi, nhanh chóng và an toàn ngay tại nhà.
            </p>
        </div>
        
        <div class="khoa">
            <h1>Chuyên Khoa</h1>
            <div class="departments-container">
                <a href="?action=chitietchuyenkhoa&id=1" class="department-card">
                    <div class="icon-circle">
                        <img src="Assets/img/khoa-noi-co-xuong-khop.png" alt="Cơ xương khớp" class="icon">
                    </div>
                    <p class="title">KHOA CƠ XƯƠNG KHỚP</p>
                </a>
                <a href="?action=chitietchuyenkhoa&id=2" class="department-card">
                    <div class="icon-circle">
                        <img src="Assets/img/khoa-noitimmach.png" alt="Tim mạch" class="icon">
                    </div>
                    <p class="title">KHOA TIM MẠCH</p>
                </a>
                <a href="?action=chitietchuyenkhoa&id=6" class="department-card">
                    <div class="icon-circle">
                        <img src="Assets/img/logo-tieu-hoa-gan-mat-f.png" alt="Tiêu hóa gan mật tụy" class="icon">
                    </div>
                    <p class="title">KHOA TIÊU HÓA - GAN MẬT - TỤY</p>
                </a>
                <a href="?action=chitietchuyenkhoa&id=4" class="department-card">
                    <div class="icon-circle">
                        <img src="Assets/img/icon-khoa-long-nguc-mach-mau.png" alt="Ngoại lồng ngực" class="icon">
                    </div>
                    <p class="title">KHOA NGOẠI LỒNG NGỰC – MẠCH MÁU</p>
                </a>
            </div>
            <div style="padding-top: 30px;">
                <a href="?action=chuyenkhoa" class="btn">Xem thêm</a>
            </div>
        </div>
        
        <div class="khoa">
            <h1>Bác Sĩ</h1>
            <div class="departments-container">
                <a href="?action=chitietbacsi&id=1" class="department-card">
                    <div class="icon-circle1">
                        <img src="Assets/img/dang-hong-hoa-avt.png" alt="danghonghoa" class="icon1">
                    </div>
                    <p class="title1">KHOA CƠ XƯƠNG KHỚP</p>
                    <p class="title">PGS.TS.BS ĐẶNG HỒNG HOA</p>
                </a>
                <a href="?action=chitietbacsi&id=3" class="department-card">
                    <div class="icon-circle1">
                        <img src="Assets/img/hoangdung.png" alt="dung" class="icon1">
                    </div>
                    <p class="title1">KHOA DA LIỄU</p>
                    <p class="title">BS.CKI Thạch Thị Hoàng Dung</p>
                </a>
                <a href="?action=chitietbacsi&id=24" class="department-card">
                    <div class="icon-circle1">
                        <img src="Assets/img/trong.png" alt="trong" class="icon1">
                    </div>
                    <p class="title1">KHOA NHI</p>
                    <p class="title">ThS.BS Lê Anh Trọng</p>
                </a>
                <a href="?action=chitietbacsi&id=42" class="department-card">
                    <div class="icon-circle1">
                        <img src="Assets/img/honganh.png" alt="anh" class="icon1">
                    </div>
                    <p class="title1">KHOA PHỤC HỒI CHỨC NĂNG</p>
                    <p class="title">BS.CKI Đỗ Thị Hồng Ánh</p>
                </a>
            </div>
            <div style="padding-top: 30px;">
                <a href="?action=bacsi" class="btn">Xem thêm</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php
require_once("Views/benhnhan/layout/footer.php");
?>