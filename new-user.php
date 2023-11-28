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
include_once('api/internal/email/Email.php');

$Email = new Email();

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
        $active = 0;
        $uuidString = generateUUIDv4($email);
        $activationCode = generateActivationCode($email);
        
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
                false,
                $plan,
                false,
                $socialName,
                $active,
            );
            if ($registrationResult) {
                $emailText = generateActivationEmailHTML($email, $activationCode);
                $recipient = 'hello@easyresumepulse.com';
                $subject = 'Registration in easyresumepulse.com';
                $content = $emailText;
                try {
                    $resultEmail = $Email->sendEmail($recipient, $subject, $content);
                    if ($resultEmail) {
                        $urlToRedirect = $baseUrl . '/post-register.php';
                        die(json_encode(['success' => true, 'code' => '001', 'url' => $urlToRedirect]));
                    } else {
                        die(json_encode(['success' => false, 'code' => '003', 'error' => 'Error sending email']));
                    }
                } catch (Exception $e) {
                    die(json_encode(['success' => false, 'code' => '004', 'error' => $e->getMessage()]));
                }
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

function generateActivationCode($email) {
    $additionalString = 'kaizenCity2023';
    $combinedString = $email . $additionalString;
    $activationCode = md5($combinedString);
    return $activationCode;
}

function generateActivationEmailHTML($email, $hash) {
    $activationLink = "https://easyresumepulse.com/en/activate-user.php?email=" . urlencode($email) . "&hash=" . $hash;
    $html = '<!DOCTYPE html>';
    $html .= '<html>';
    $html .= '<head>';
    $html .= '<title>Account Activation</title>';
    $html .= '</head>';
    $html .= '<body>';
    $html .= '<p>Hi,</p>';
    $html .= '<p>To activate your account, please click on the following link:</p>';
    $html .= '<p>';
    $html .= '<a href="' . $activationLink . '">Activate Account</a>';
    $html .= '</p>';
    $html .= '<p>Thank you.</p>';
    $html .= '</body>';
    $html .= '</html>';
    return $html;
}

