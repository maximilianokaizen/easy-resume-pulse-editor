<?php

require_once 'vendor/autoload.php';

$client = new Google_Client(['client_id' => '223124831209-p04fqq68spt1pm60j69drbrcgknjsnl5.apps.googleusercontent.com']);  
$payload = $client->verifyIdToken($id_token);
if ($payload) {
  $userid = $payload['sub'];
  // If request specified a G Suite domain:
  //$domain = $payload['hd'];
  die(var_dump($payload));
} else {
  // Invalid ID token
  die('redirect...');
}
