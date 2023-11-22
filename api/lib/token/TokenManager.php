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

    public static function generateTokenSocial(string $uuid, int $minutes): string
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

    public static function verifyUuidInToken(string $tokenString, string $uuidToVerify): bool {

        $envValues = self::getEnvValues();
        $signer = new Sha256();
        $token = (new Parser())->parse($tokenString);

        if (!$token->verify($signer, $envValues['SIGNING_KEY'])) {
            return false; // La firma del token es inválida
        }

        $claims = $token->getClaims();

        if (isset($claims['uuid']) && $claims['uuid']->getValue() === $uuidToVerify) {
            return true;
        }
        return false; 
    }

    public static function getUserUuidFromToken(string $tokenString): ?string {
        try {
            $token = (new Parser())->parse($tokenString);
            $claims = $token->getClaims();

            if (isset($claims['uuid'])) {
                return $claims['uuid']->getValue();
            }

            return null; // El claim 'uuid' no está presente en el token
        } catch (\Throwable $e) {
            // Manejar cualquier excepción al analizar el token
            throw new Exception('Error parsing token: ' . $e->getMessage());
        }
    }

    public static function getGoogleLoginData(string $token): array {
        try {
            $parsedToken = (new Parser())->parse($token);
            $claims = $parsedToken->getClaims();
    
            $data = [
                'email' => isset($claims['email']) ? $claims['email']->getValue() : null,
                'email_verified' => isset($claims['email_verified']) ? $claims['email_verified']->getValue() : null,
                'nbf' => isset($claims['nbf']) ? $claims['nbf']->getValue() : null,
                'name' => isset($claims['name']) ? $claims['name']->getValue() : null,
                'picture' => isset($claims['picture']) ? $claims['picture']->getValue() : null,
                'given_name' => isset($claims['given_name']) ? $claims['given_name']->getValue() : null,
                'family_name' => isset($claims['family_name']) ? $claims['family_name']->getValue() : null,
            ];
    
            return $data;
        } catch (\Throwable $e) {
            // Manejar cualquier excepción al analizar el token
            throw new Exception('Error parsing token: ' . $e->getMessage());
        }
    }

}
