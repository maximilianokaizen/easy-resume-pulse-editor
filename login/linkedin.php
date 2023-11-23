<?php

die('dev');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php'; 
require_once('../api/lib/token/TokenManager.php');
require_once('../api/internal/users/Users.php');

use Lcobucci\JWT\Parser;
use League\OAuth2\Client\Provider\LinkedIn;

$uuidString = generateUUIDv4();
$minutes = 180;

$envFile = __DIR__ . '/.env';
$env = parse_ini_file($envFile);

if ($env['ENVIRONMENT'] !== 'LOCAL'){
  $baseUrl = 'https://easyresumepulse.com/en';
}else{
  $baseUrl = 'http://localhost:8080';
}

$provider = new LinkedIn([
    'clientId'     => $env['LINKEDIN_CLIENT'],
    'clientSecret' => $env['LINKEDIN_SECRET'],
    'redirectUri'  => $baseUrl . '/provisionalLogin.php',
]);

if (!isset($_GET['code'])) {
    // Redirige al usuario a la URL de autorización de LinkedIn
    $authUrl = $provider->getAuthorizationUrl();
    header('Location: ' . $authUrl);
    exit;
} else {
    // El usuario ha autorizado la aplicación, obtenemos el token de acceso
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Con el token, puedes obtener los detalles del usuario
    $linkedinUser = $provider->getResourceOwner($token);
    // ... Procesar datos del usuario (por ejemplo, guardar en la base de datos)
    var_dump($linkedinUser->toArray()); // Muestra los datos del usuario de LinkedIn
}


/*
$user = new User();
if (!$user->exist($email)) {
  // register
  $randomPassword = password_hash(base64_encode(random_bytes(8)), PASSWORD_DEFAULT);
  $premium = false;
  $plan = 'free';
  $social = true;
  $socialName = 'google';
  $active = 1;
  $registrationResult = $user->register(
    $uuidString, 
    $email,
    $randomPassword, 
    $given_name,
    $family_name,
    0, // premium
    $plan,
    1, // social
    $socialName,
    $active,
  );
  if ($registrationResult) {
    // generate JWT for this user
    $token = TokenManager::generateTokenSocial($uuidString, $minutes);
    header('Location: ' . $baseUrl . '/provisionalLogin.php?token=' . $token . '&uuid=' . $uuidString);
  }else{
    header('Location: ' . $baseUrl . '/index.php?err=1&code=001');
  }
}else{
  // registered user
  // generate JWT for this user
  $uuid = $user->getUuidByEmail($email);
  $token = TokenManager::generateTokenSocial($uuid, $minutes);
  $urlToRedirect = $baseUrl . '/provisionalLogin.php?token=' . $token . '&uuid=' . $uuid;
  if ($uuid === NULL){
    header('Location: ' . $baseUrl . '/index.php?err=1&code=002');
  }else{
    header('Location: ' . $urlToRedirect);
  }
}
*/

function generateUUIDv4() {
  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
      mt_rand(0, 0x0fff) | 0x4000,
      mt_rand(0, 0x3fff) | 0x8000,
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
  );
}





