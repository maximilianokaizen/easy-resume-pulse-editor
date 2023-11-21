<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php'; 
require_once('../api/lib/token/TokenManager.php');

$client = new Google_Client();
$client->setClientId('223124831209-p04fqq68spt1pm60j69drbrcgknjsnl5.apps.googleusercontent.com');
$client->setClientSecret('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');

$token = $_POST['credential'];
$googleData = TokenManager::getGoogleLoginData($token);
die(var_dump($googleData));


/*
{
  "iss": "https://accounts.google.com",
  "azp": "223124831209-p04fqq68spt1pm60j69drbrcgknjsnl5.apps.googleusercontent.com",
  "aud": "223124831209-p04fqq68spt1pm60j69drbrcgknjsnl5.apps.googleusercontent.com",
  "sub": "108061482042918611723",
  "email": "maximilianokaizen@gmail.com",
  "email_verified": true,
  "nbf": 1700594478,
  "name": "Maximiliano Rossi",
  "picture": "https://lh3.googleusercontent.com/a/ACg8ocKyi8Jnc4Y3Bhyq1nQjizIDg7EbKbdz2a2e0n4bGdBW=s96-c",
  "given_name": "Maximiliano",
  "family_name": "Rossi",
  "locale": "es-419",
  "iat": 1700594778,
  "exp": 1700598378,
  "jti": "187415738fb18f7a1a8fe80fde24ff46e53f929c"
}
*/





