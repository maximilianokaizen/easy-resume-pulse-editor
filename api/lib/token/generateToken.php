<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JWTGenerator
{
    public static function generateToken(string $uuid, int $minutes): string
    {
        $envFile = __DIR__ . '/.env';
        $envValues = parse_ini_file($envFile);
        $signer = new Sha256();
        try {
            $token = (new Builder())->setIssuer($envValues['ISSUER'])
                ->setAudience($envValues['AUDIENCE'])
                ->setId($envValues['JTI'], true)
                ->setIssuedAt(time())
                ->setNotBefore(time() + 60)
                ->setExpiration(time() + $minutes)
                ->set('uuid', $uuid)
                ->sign($signer, $envValues['SIGNING_KEY'])
                ->getToken();
            return $token->__toString();
        } catch (Throwable $e) {
            throw new Exception('Error creating token: ' . $e->getMessage());
        }
    }
}
