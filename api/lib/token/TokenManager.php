<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;

class TokenManager
{
    private static function getEnvValues(): array
    {
        $envFile = __DIR__ . '/.env';
        return parse_ini_file($envFile);
    }

    public static function generateToken(string $uuid, int $minutes): string
    {
        $envValues = self::getEnvValues();
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

    public static function validateToken(string $tokenString): bool
    {
        $signer = new Sha256();
        $envValues = self::getEnvValues();
        $token = (new Parser())->parse($tokenString);
        $validationData = new ValidationData();
        $validationData->setIssuer($envValues['ISSUER']);
        $validationData->setAudience($envValues['AUDIENCE']);
        $validationData->setId($envValues['JTI']);
        if ($token->verify($signer, $envValues['SIGNING_KEY'])){
            return true;
        }else{
            return false;
        }
    }
}
