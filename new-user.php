<?php

/*
declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require_once('api/lib/sanatize/sanatize.php');
require_once('api/lib/token/TokenManager.php');
require_once('api/internal/users/Users.php');

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $baseUrl = 'https://easyresumepulse.com/en';
} else {
    $baseUrl = 'http://localhost:8080';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $jsonData = json_decode(file_get_contents('php://input'), true);
        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar el JSON: " . json_last_error_msg());
        }
        
        $email = sanitizeInput($jsonData['email'] ?? '');
        $password = sanitizeInput($jsonData['pwd']) ?? '';
        $given_name =  sanitizeInput($jsonData['name']) ?? '';
        $last_name =  sanitizeInput($jsonData['last_name']) ?? '';

        /* user data */
        $user = new User();
        $premium = false;
        $plan = 'free';
        $social = false;
        $socialName = 'website';
        $active = 1;
        $uuidString = generateUUIDv4();


        if ($user->exist($email)) {
            die(json_encode(['success' => true, 'code' => '000']));
        }else{
            // register and send email
            $registrationResult = $user->register(
                $uuidString, 
                $email,
                $password, 
                $given_name,
                $last_name,
                false, // premium
                $plan,
                false, // social
                $socialName,
                $active,
            );
            if ($registrationResult) {
                // TODO
                // SEND EMAIL 
                $minutes = 180;
                $uuid = $user->getUuidByEmail($email);
                $token = TokenManager::generateTokenSocial($uuid, $minutes);
                $urlToRedirect = $baseUrl . '/provisionalLogin.php?token=' . $token . '&uuid=' . $uuid;
                die(json_encode(['success' => true, 'code' => '001', 'url' => $urlToRedirect]));
            }else{
                die(json_encode(['success' => true, 'code' => '002']));
            }
        }
    } catch (Exception $e) {
        // Captura cualquier excepción y devuelve un mensaje de error
        // TODO LOG
        $log = $e->getMessage();
        die(json_encode(['success' => false, 'error' => 'Error']));
    }
} else {
    // Envía una respuesta de error si no es POST
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Error']);
}

function generateUUIDv4() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
  