<?php

require_once 'vendor/autoload.php'; 
require_once('../api/lib/token/TokenManager.php');
require_once('../api/internal/users/Users.php');

use Lcobucci\JWT\Parser;

$uuidString = generateUUIDv4();
$minutes = 180;

$envFile = __DIR__ . '/.env';
$env = parse_ini_file($envFile);

if ($env['ENVIRONMENT'] !== 'LOCAL'){
  $baseUrl = 'https://easyresumepulse.com/en';
}else{
  $baseUrl = 'http://localhost:8080';
}

if ($env['ENVIRONMENT'] !== 'LOCAL'){
  $client = new Google_Client();
  $client->setClientId('223124831209-p04fqq68spt1pm60j69drbrcgknjsnl5.apps.googleusercontent.com');
  $client->setClientSecret('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');
  $token = $_POST['credential'];
  $token = (new Parser())->parse((string) $token); // Parses from a string
  $claims = $token->getClaims();
  $email = $claims['email']->getValue();
  $email_verified =  $claims['email_verified']->getValue();
  $name = $claims['name']->getValue();
  $picture =  $claims['picture']->getValue();
  $given_name = $claims['given_name']->getValue();
  $family_name =  $claims['family_name']->getValue();
  die('$email =>' . $email);
}else{
  $email = 'rossi.maxi@gmail.com';
  $email_verified = true;
  $name = 'Maximiliano Rossi';
  $picture =  '';
  $given_name = 'Maximiliano';
  $family_name =  'Rossi';
}

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

function generateUUIDv4() {
  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
      mt_rand(0, 0x0fff) | 0x4000,
      mt_rand(0, 0x3fff) | 0x8000,
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
  );
}





