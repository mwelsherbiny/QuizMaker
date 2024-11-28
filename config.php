<?php

use Dotenv\Dotenv;

require_once "google-api/vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__, ".env");
$dotenv->load();

$gClient = new Google\Client();
$gClient->setClientId($_ENV["GOOGLE_CLIENT_ID"]);
$gClient->setClientSecret($_ENV["GOOGLE_CLIENT_SECRET"]);
$gClient->setApplicationName("QuizMaker");
$gClient->setRedirectUri("http://localhost/QuizMaker/api/google_auth");
$gClient->addScope('email');
$gClient->addScope('profile');
$gClient->setAccessType('offline');

$authUrl =  $gClient->createAuthUrl();
