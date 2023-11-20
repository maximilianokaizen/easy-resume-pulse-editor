<?php
// check auth

declare(strict_types=1);

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;

require 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json'); // Establecer el encabezado para respuestas JSON

// libs
require_once('../lib/sanatize/sanatize.php');
include_once('../lib/db/dbConnection.php');
require_once('../environment.php');

$env = getEnviroment();
$appDomain = $env['APP_DOMAIN'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $jsonData = json_decode(file_get_contents('php://input'), true);
        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar el JSON: " . json_last_error_msg());
        }

        $email = sanitizeInput($jsonData['email'] ?? '');
        $password = sanitizeInput($jsonData['password'] ?? '');

        if (empty($email) || empty($password)) {
            throw new Exception("Por favor, proporcione tanto el correo electrónico como la contraseña.");
        }

        $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $db = new DatabaseConnector();

        $result = $db->executeQuery($query, [$email]);

        if ($result === null || empty($result)) {
            throw new Exception("No se encontró ningún usuario con este correo electrónico.");
        }

        $user = $result[0];
        $hashedPassword = $user['password'];
        if (md5($password) !== $hashedPassword) {
            http_response_code(401); // Unauthorized
            echo json_encode(['success' => false, 'error' => 'La contraseña proporcionada es incorrecta.']);
            exit;
        }

        http_response_code(200);

        /* generate jwt token */
        
        $payload = [
            'uuid' =>  $user['uuid'],
            'plan' =>  $user['plan'],
            'premium' => $user['premium'],
            'plan' => $user['plan'],
            'plan_start' => $user['plan_start'], 
            'plan_end' => $user['plan_end'],
            'user_active' => $user['user_active'],
        ];
        
        $minutes = 120;
        
        try {
            $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
            $algorithm    = new Sha256();
            $signingKey   = InMemory::plainText(random_bytes(32));
        
            $now   = new DateTimeImmutable();
            $token = $tokenBuilder
                ->issuedBy($env['ISSUER'])
                ->permittedFor($env['AUDIENCE'])
                ->identifiedBy($env['JTI'])
                ->canOnlyBeUsedAfter($now->modify('+1 minute'))
                ->expiresAt($now->modify('+' . $minutes . ' minutes'));
        
            foreach ($payload as $key => $value) {
                $token = $token->withClaim($key, $value);
            }
        
            $tokenString = $token->getToken($algorithm, $signingKey)->toString();
        
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        /* end of generate jwt token */
        
        echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso.', 'token' => $tokenString]);

    } catch (Exception $e) {
        http_response_code(401); // Unauthorized
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido. Se esperaba una solicitud POST.']);
}
?>
