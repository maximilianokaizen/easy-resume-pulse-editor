<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('lib/token/TokenClient.php');

$client = new TokenGeneratorClient();

// Datos a enviar para generar el token
$data = [
    'minutes' => 30,
    'payload' => [
        'usr' => 'usuario',
        'pwd' => 'contraseña',
    ],
];

// Generar el token
$token = $client->generateToken($data);
die($token);
