<?php
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('946684689164-qhp3cu89gf0h5b2t3rdnoscd44okrj6q.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/hanhphuchospital3?action=logingoogle');
$client->addScope("email");
$client->addScope("profile");