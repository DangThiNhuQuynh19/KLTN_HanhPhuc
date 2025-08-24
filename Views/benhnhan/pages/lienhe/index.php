<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
      padding: 3rem 0;
    }

    .clinic-card {
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    .clinic-card:hover {
      transform: translateY(-5px);
    }

    .clinic-img {
      object-fit: cover;
      height: 100%;
      width: 100%;
    }

    .clinic-info {
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .clinic-info h3 {
      font-weight: 700;
      color: #3f3d56;
      font-size: 1.8rem;
      margin-bottom: 1rem;
    }

    .clinic-info p {
      font-size: 1rem;
      color: #555;
      margin-bottom: 0.75rem;
      line-height: 1.6;
    }

    .clinic-info i {
      color: #6c63ff;
      margin-right: 8px;
      font-size: 1.2rem;
    }

    @media (max-width: 768px) {
      .clinic-info {
        padding: 2rem 1.5rem !important;
      }

      .clinic-info h3 {
        font-size: 1.5rem;
      }

      .clinic-info p {
        font-size: 0.95rem;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="row clinic-card bg-white">
      <div class="col-md-6 p-0">
        <img src="Assets/img/lienhe.jpg" alt="Hanh Phuc Clinic" class="clinic-img">
      </div>
      <div class="col-md-6 p-5 clinic-info">
        <h3><strong>Phòng khám Đa khoa Quốc tế Hạnh Phúc</strong></h3>
        <p><i class="bi bi-geo-alt-fill"></i> Estella Place, 88 Song Hành, Phường An Phú, TP. Thủ Đức, TP. Hồ Chí Minh</p>
        <p><i class="bi bi-telephone-fill"></i> 1900 6765</p>
        <p><i class="bi bi-clock-fill"></i> Thứ Hai đến Thứ Bảy: 8:00 - 20:00<br>Chủ Nhật: 8:00 - 12:00</p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
