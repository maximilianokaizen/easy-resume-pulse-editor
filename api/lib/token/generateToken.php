<?php

declare(strict_types=1);

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;

require 'vendor/autoload.php';

// Si se recibe una solicitud POST con datos JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['minutes'], $data['payload'])) {
        $minutes = $data['minutes'];
        $payload = $data['payload'];
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
    $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
    $algorithm    = new Sha256();
    $signingKey   = InMemory::plainText(random_bytes(32));

    $now   = new DateTimeImmutable();
    $token = $tokenBuilder
        ->issuedBy($envValues['ISSUER'])
        ->permittedFor($envValues['AUDIENCE'])
        ->identifiedBy($envValues['JTI'])
        ->canOnlyBeUsedAfter($now->modify('+1 minute'))
        ->expiresAt($now->modify('+' . $minutes . ' minutes'));

    foreach ($payload as $key => $value) {
        $token = $token->withClaim($key, $value);
    }

    $tokenString = $token->getToken($algorithm, $signingKey)->toString();

    echo json_encode(['success' => true, 'token' => $tokenString]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}