<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('api/internal/email/Email.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
try {
    $jsonData = json_decode(file_get_contents('php://input'), true);
    if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error al decodificar el JSON: " . json_last_error_msg());
    }
    $contactEmail = $jsonData['email'];
    $email = new Email();
    $recipient = 'rossi.maxi@gmail.com';
    $subject = 'Hello World';
    $content = '<p>Helllooooo from ' .  $contactEmail . '</p>';
    $result = $email->sendEmail($recipient, $subject, $content);
    die(json_encode(['success' => true]));
    } catch (Exception $e) {
        die(json_encode(['success' => false]));
    }
}


