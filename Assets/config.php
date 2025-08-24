<?php
// config.php - lưu khóa mã hóa và phương thức mã hóa

define('ENCRYPTION_KEY', 'mySuperSecretKey123!'); // bạn nên thay đổi thành chuỗi bí mật của riêng bạn
define('ENCRYPTION_METHOD', 'AES-256-CBC');
define('ENCRYPTION_IV', substr(hash('sha256', 'myInitVector'), 0, 16)); // 16 bytes IV

// Hàm mã hóa dữ liệu
function encryptData($data) {
    return openssl_encrypt($data, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, ENCRYPTION_IV);
}

// Hàm giải mã dữ liệu (sử dụng khi cần hiển thị)
function decryptData($encrypted) {
    return openssl_decrypt($encrypted, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, ENCRYPTION_IV);
}
?>
