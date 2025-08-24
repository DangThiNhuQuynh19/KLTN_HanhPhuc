<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .custom-footer {
  background-color: #343177; /* Màu nền tím đậm */
  color: white;
}

.custom-footer a {
  color: white;
  text-decoration: none;
  transition: color 0.3s;
}

.custom-footer a:hover {
  color: #d1c5f0;
  text-decoration: underline;
}

.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-links li {
  margin-bottom: 10px;
}

.social-icons a {
  display: inline-block;
  margin-right: 12px;
  font-size: 24px;
  color: white;
}

.social-icons a:hover {
  color: #bba7ff;
}

</style>
</head>
<body>
    
</body>
</html>
<footer class="custom-footer">
  <div class="container py-5">
    <div class="row align-items-start">
      <!-- Cột 1: Logo + Thông tin -->
      <div class="col-md-4 mb-4">
        <img src="Assets/img/logo-footer.png" alt="Logo" style="height: 60px; margin-bottom: 20px;">
        <p><strong>Bệnh viện Đa khoa Quốc tế Hạnh Phúc</strong><br>
          <i class="bi bi-telephone"></i> 1900 6765</p>
        <hr style="border-color: rgba(255,255,255,0.2); width: 80%;">
      </div>

      <!-- Cột 2: Menu -->
      <div class="col-md-5 mb-4">
        <div class="row">
          <div class="col-6">
            <ul class="footer-links">
              <li><a href="#">Về chúng tôi</a></li>
              <li><a href="#">Chuyên khoa</a></li>
              <li><a href="#">Bác sĩ</a></li>
              <li><a href="#">Gói khám</a></li>
              <li><a href="#">Blog</a></li>
            </ul>
          </div>
          <div class="col-6">
            <ul class="footer-links">
              <li><a href="#">Chính sách</a></li>
              <li><a href="#">Tin tức</a></li>
              <li><a href="#">Liên hệ</a></li>
              <li><a href="#">Quyền riêng tư</a></li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Cột 3: Mạng xã hội -->
      <div class="col-md-3 mb-4 text-md-end text-center">
        <p><strong>Kết nối với chúng tôi</strong></p>
        <div class="social-icons">
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-youtube"></i></a>
          <a href="#"><img src="Assets/img/zalo-icon.png" alt="Zalo" style="height: 24px;"></a>
        </div>
      </div>
    </div>

    <!-- Copyright -->
    <div class="text-center mt-4" style="font-size: 14px; color: #ddd;">
      Copyright 2025 © Hanh Phuc International
    </div>
  </div>
</footer>
