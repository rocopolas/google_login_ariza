<?php
session_start();
require_once 'vendor/autoload.php';

$google_client = new Google_Client();
$google_client->setClientId('.apps.googleusercontent.com');
$google_client->setClientSecret('');
$google_client->setRedirectUri('http://localhost/index.php');

$google_client->addScope('email');
$google_client->addScope('profile');
?>
