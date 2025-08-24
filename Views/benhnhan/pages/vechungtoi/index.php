<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tầm nhìn & Sứ mệnh</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: white;
      margin-top: 100px;
    }

    /* Animation fade-up */
    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Tầm nhìn & Sứ mệnh */
    .vision-mission-section {
      background-color: #fdf9f4;
      border-radius: 80px 0 0 0;
      padding: 60px 40px;
      position: relative;
      overflow: hidden;
      animation: fadeUp 1.2s ease-out;
      margin-bottom: 60px;
    }

    .vision-mission-section h2 {
      color: #4b3f72;
      font-weight: bold;
      font-size: 2.5rem;
      margin-bottom: 30px;
    }

    .vision-mission-section p {
      color: #4b3f72;
      font-size: 1.3rem;
      line-height: 2;
      margin-bottom: 20px;
    }

    .logo-overlay {
      position: absolute;
      bottom: 20px;
      right: 40px;
      width: 100px;
      opacity: 1;
    }

    /* Giá trị cốt lõi */
    .giatri {
      background-color: #f2f4ff;
      border-radius: 40px;
      padding: 50px 30px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      animation: fadeUp 1.2s ease-out;
    }

    .giatri p {
      color: #3b3b58;
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 30px;
    }

    .giatri img {
      max-width: 100%;
      height: auto;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      transition: transform 0.4s ease;
    }

    .giatri img:hover {
      transform: scale(1.03);
    }

    @media (max-width: 768px) {
      .vision-mission-section {
        padding: 40px 20px;
        border-radius: 40px 0 0 0;
      }

      .logo-overlay {
        width: 70px;
        right: 20px;
        bottom: 20px;
      }

      .vision-mission-section h2 {
        font-size: 2rem;
      }

      .vision-mission-section p {
        font-size: 1.1rem;
      }

      .giatri p {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <!-- TẦM NHÌN & SỨ MỆNH -->
    <div class="vision-mission-section mt-5">
      <h2>Tầm nhìn & Sứ mệnh</h2>
      <p>
        Tại Hạnh Phúc, chúng tôi hướng tới mục tiêu trở thành đơn vị tiên phong trong lĩnh vực chăm sóc sức khoẻ đa chuyên khoa cao cấp và trải nghiệm toàn diện.
      </p>
      <p>
        Sứ mệnh của chúng tôi là định hình văn hóa chăm sóc sức khỏe đa chuyên khoa cao cấp tại Việt Nam thông qua mạng lưới bệnh viện và phòng khám đạt tiêu chuẩn quốc tế.
      </p>
      <img src="Assets/img/logo-banner.png" alt="logo trang trí" class="logo-overlay">
    </div>

    <!-- GIÁ TRỊ CỐT LÕI -->
    <div class="giatri mb-5">
      <p>Giá trị cốt lõi</p>
      <img src="Assets/img/giatri.png" alt="Giá trị cốt lõi">
    </div>
  </div>

</body>
</html>
