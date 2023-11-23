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
        $password = sanitizeInput($jsonData['password']) ?? '';
        /* user data */
        $user = new User();

        $loginSuccessful = $user->checkLogin($email, $password);

        if ($loginSuccessful) {
            $minutes = 180;
            $uuid = $user->getUuidByEmail($email);
            $token = TokenManager::generateTokenSocial($uuid, $minutes);
            $urlToRedirect = $baseUrl . '/provisionalLogin.php?token=' . $token . '&uuid=' . $uuid;
            die(json_encode(['success' => true, 'code' => '001', 'url' => $urlToRedirect]));
        } else {
            die(json_encode(['success' => false, 'code' => '000']));
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

