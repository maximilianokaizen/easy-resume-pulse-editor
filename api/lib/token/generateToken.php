<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;

// Si se recibe una solicitud POST con datos JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['minutes'], $data['uuid'])) {
        $minutes = $data['minutes'];
        $uuid = $data['uuid'];
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Missing data']);
        exit;
    }
} else {
    // Si no es una solicitud POST, enviar un mensaje de error
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$envFile = __DIR__ . '/.env';
$envValues = parse_ini_file($envFile);

try {

    $token = (new Builder())->setIssuer($envValues['ISSUER']) 
                        ->setAudience($envValues['AUDIENCE'])
                        ->setId($envValues['JTI'], true) 
                        ->setIssuedAt(time()) 
                        ->setNotBefore(time() + 60) 
                        ->setExpiration(time() +  $minutes ) 
                        ->set('uuid', $uuid) 
                        ->getToken(); 
    
    echo json_encode(['success' => true, 'token' => $token->__toString()]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}