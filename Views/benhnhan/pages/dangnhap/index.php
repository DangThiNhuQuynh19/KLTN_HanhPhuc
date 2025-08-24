<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Đăng nhập - Bệnh viện Hạnh Phúc</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .container {
        margin: auto;
        margin-top: 100px;
        display: flex;
        width: 80%;
        max-width: 900px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .image-side {
        background: linear-gradient(135deg, #6f42c1 0%, #4a1d96 100%);
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .image-side::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        z-index: 1;
    }
    
    .image-side h1 {
        font-size: 28px;
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
    }
    
    .image-side p {
      font-size: 16px;
      text-align: center;
      margin-bottom: 30px;
      line-height: 1.6;
      position: relative;
      z-index: 2;
    }
    
    .image-side img {
      width: 180px;
      height: auto;
      margin-bottom: 30px;
      position: relative;
      z-index: 2;
    }
    
    .login-side {
      background-color: #ffffff;
      flex: 1;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    
    .login-box {
      width: 100%;
    }
    
    .login-box img.logo {
      width: 80px;
      height: auto;
      margin-bottom: 20px;
    }
    
    .login-box h2 {
      color: #6f42c1;
      margin-bottom: 30px;
      font-size: 28px;
      font-weight: 600;
    }
    
    .input-group {
      position: relative;
      margin-bottom: 25px;
    }
    
    .input-group label {
      display: block;
      text-align: left;
      margin-bottom: 8px;
      font-weight: 500;
      color: #333;
      font-size: 15px;
    }
    
    .input-group input {
      width: 100%;
      padding: 12px 15px;
      padding-left: 40px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
    }
    
    .input-group input:focus {
      outline: none;
      border-color: #6f42c1;
      box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.1);
    }
    
    .input-group i {
      position: absolute;
      left: 15px;
      bottom: 15px;
      color: #999;
    }
    
    .options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }
    
    .remember {
      display: flex;
      align-items: center;
    }
    
    .remember input {
      margin-right: 8px;
    }
    
    .forgot-password {
      color: #6f42c1;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.3s;
    }
    
    .forgot-password:hover {
      color: #5a34a5;
      text-decoration: underline;
    }
    
    button {
      width: 100%;
      padding: 14px;
      background-color: #6f42c1;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 600;
      box-shadow: 0 4px 6px rgba(111, 66, 193, 0.2);
    }
    
    button:hover {
      background-color: #5a34a5;
      transform: translateY(-2px);
      box-shadow: 0 6px 8px rgba(111, 66, 193, 0.3);
    }
    
    button:active {
      transform: translateY(0);
    }
    
    .message {
      margin-bottom: 15px;
      color: #e74c3c;
      font-size: 15px;
      text-align: center;
      padding: 10px;
      border-radius: 5px;
      background-color: rgba(231, 76, 60, 0.1);
      display: none;
    }
    
    .message.show {
      display: block;
    }
    
    .register-link {
      text-align: center;
      margin-top: 25px;
      font-size: 15px;
      color: #555;
    }
    
    .register-link a {
      color: #6f42c1;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s;
    }
    
    .register-link a:hover {
      color: #5a34a5;
      text-decoration: underline;
    }
    
    .social-login {
      margin-top: 30px;
      text-align: center;
    }
    
    .social-login p {
      color: #777;
      margin-bottom: 15px;
      position: relative;
    }
    
    .social-login p::before,
    .social-login p::after {
      content: "";
      position: absolute;
      top: 50%;
      width: 30%;
      height: 1px;
      background-color: #ddd;
    }
    
    .social-login p::before {
      left: 0;
    }
    
    .social-login p::after {
      right: 0;
    }
    
    .social-icons {
      display: flex;
      justify-content: center;
      gap: 15px;
    }
    
    .social-icons a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background-color: #f8f9fa;
      color: #333;
      font-size: 18px;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .social-icons a:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 10px rgba(0,0,0,0.15);
    }
    
    .social-icons a.facebook {
      color: #3b5998;
    }
    
    .social-icons a.google {
      color: #db4437;
    }
    
    .social-icons a.apple {
      color: #000;
    }
    
    /* Responsive styles */
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        max-width: 500px;
      }
      
      .image-side {
        padding: 30px 20px;
      }
      
      .image-side h1 {
        font-size: 24px;
      }
      
      .image-side p {
        font-size: 14px;
      }
      
      .login-side {
        padding: 30px 20px;
      }
    }
    
    @media (max-width: 480px) {
      .options {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
      
      .social-icons {
        gap: 10px;
      }
      
      .social-icons a {
        width: 40px;
        height: 40px;
      }
      
      .login-box h2 {
        font-size: 24px;
      }
    }

    .google-btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
        color: #444;
        font-weight: 500;
        font-size: 16px;
        text-decoration: none;
        transition: background-color 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-top: 20px;
    }

    .google-btn img {
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }

    .google-btn:hover {
        background-color: #f7f7f7;
        text-decoration: none;
    }

  </style>
</head>
<body>
  <div class="container">
    <div class="image-side">
      <img src="Assets/img/logo-banner.png" alt="Logo" />
      <h1>Chào mừng đến với Bệnh viện Hạnh Phúc</h1>
      <p>Đăng nhập để đặt lịch khám, xem hồ sơ bệnh án và nhận tư vấn từ đội ngũ bác sĩ chuyên nghiệp của chúng tôi.</p>
    </div>
    
    <div class="login-side">
      <div class="login-box">
        <h2>Đăng nhập</h2>
        
        <div class="message" id="errorMessage"></div>
        
        <form method="POST" id="loginForm">
          <div class="input-group">
            <label for="tentk">Tên tài khoản:</label>
            <i class="fas fa-envelope"></i>
            <input type="email" id="tentk" name="tentk" placeholder="Nhập email của bạn" required />
          </div>
          
          <div class="input-group">
            <label for="password">Mật khẩu:</label>
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu của bạn" required />
          </div>
          
          <div class="options">
            <div class="remember">
              <input type="checkbox" id="remember" name="remember" />
              <label for="remember">Ghi nhớ đăng nhập</label>
            </div>
            <a href="?quenmatkhau" class="forgot-password">Quên mật khẩu?</a>
          </div>
          
          <button type="submit" name="btndangnhap">Đăng nhập</button>
        </form>
        <?php
          require_once 'config.php';

          $login_url = $client->createAuthUrl();
        ?>
        <a href="<?= htmlspecialchars($login_url) ?>" class="google-btn">
            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo">
            <span>Đăng nhập bằng Google</span>
        </a>
        
        <div class="register-link">
          Bạn chưa có tài khoản? <a href="?action=dangky">Đăng ký ngay</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
<?php
    include_once("Views/benhnhan/pages/dangnhap/xulydangnhap.php");
?>
