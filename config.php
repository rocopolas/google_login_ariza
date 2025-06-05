<?php
session_start();
require_once 'vendor/autoload.php';

$google_client = new Google_Client();
$google_client->setClientId('1040481639283-lnhcfu8dqa1fctbdks3c940mmj4mthjd.apps.googleusercontent.com');
$google_client->setClientSecret('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');
$google_client->setRedirectUri('http://localhost/index.php');

$google_client->addScope('email');
$google_client->addScope('profile');
?>
