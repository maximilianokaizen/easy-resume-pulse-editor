<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\RelatedTo;
use Lcobucci\JWT\Validation\Validator;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;

use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;

use DateTimeInterface;
use Lcobucci\Clock\Clock;

require 'vendor/autoload.php';

$parser = new Parser(new JoseEncoder());

// Inicializa la respuesta por defecto como fallo
$response = ['success' => false];

$envFile = __DIR__ . '/.env';
$envValues = parse_ini_file($envFile);

// Obtiene el contenido JSON enviado por el método POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);

try {
    if (!isset($data['token'])) {
        $response['message'] = 'Token not valid';
        $response['success'] = false;
    } else {
        $token = $parser->parse($data['token']); // Mover la creación del token aquí

        $validator = new Validator();
        $currentTime = time();

        $constraints = [
            new IssuedBy($envValues['ISSUER']),
            new PermittedFor($envValues['AUDIENCE']),
        ];
        
        foreach ($constraints as $constraint) {
            if (!$validator->validate($token, $constraint)) {
                $response['message'] = 'Token validation failed';
                $response['success'] = false;
                header('Content-Type: application/json');
                die(json_encode($response));
            }
        }
        
        /* devolvemos el token ya validado  */
       
        try {
            assert($token instanceof UnencryptedToken);
            $data = $token->claims()->all();
            $jsonData = json_encode($data);
            $expDate = json_decode($jsonData, true)['exp']['date'];
            $expirationDateTime = new DateTime($expDate);
            $currentDateTime = new DateTime();
            if ($currentDateTime > $expirationDateTime) {
                $errorResponse = ['success' => false, 'error' => 'Token expired'];
                die(json_encode($errorResponse));
            } 
            header('Content-Type: application/json');
            $successResponse = [
                'success' => true,
                'token' => json_decode($jsonData, true) 
            ];
            die(json_encode($successResponse));
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            $error = $e->getMessage();
            $response['message'] = $error;
            $response['success'] = false;
            die(json_encode($response));
        }

        $response['message'] = '';
        $response['success'] = true;
        die(json_encode($response));

      
    }
} catch (TokenInvalidException $exception) {
    $response['message'] = $exception->getMessage();
} catch (\Throwable $exception) {
    $response['message'] = 'Error occurred: ' . $exception->getMessage();
}

// Envia la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
