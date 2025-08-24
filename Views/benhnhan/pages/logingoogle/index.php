<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'config.php';

if (isset($_GET['code'])) {
    echo "Có code: " . htmlspecialchars($_GET['code']) . "<br>";
    
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    var_dump($token);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        $google_oauth = new Google_Service_Oauth2($client);
        $userinfo = $google_oauth->userinfo->get();

        echo "<pre>";
        print_r($userinfo);
        echo "</pre>";

        $_SESSION['email'] = $userinfo->email;
        $_SESSION['name'] = $userinfo->name;
        $_SESSION['user'] = [
            'tentk' => $userinfo->email, 
            'name' => $userinfo->name,
            'vaitro'=>0
        ];

        echo "Chuyển hướng sang trangchu...";
        header('Location: ?action=trangchu');
        exit();
    } else {
        echo "Lỗi khi lấy token:";
        var_dump($token);
    }
} else {
    echo "Chưa nhận được code từ Google.";
}

?>