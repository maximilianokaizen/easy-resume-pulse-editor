<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('api/internal/email/Email.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';
        $contactEmail = $_POST['email'] ?? '';
        $selectedPlan = $_POST['plan'] ?? '';
        $message = $_POST['message'] ?? '';

        $email = new Email();
        $recipient = 'maximilianokaizen@gmail.com'; // Cambiar al destinatario correcto
        $subject = 'New Form Submission';
        $content = "<p>Name: $firstName $lastName</p>" .
                   "<p>Email: $contactEmail</p>" .
                   "<p>Selected Plan: $selectedPlan</p>" .
                   "<p>Message: $message</p>";
        
        $result = $email->sendEmail($recipient, $subject, $content);
        die(json_encode(['success' => true]));
    } catch (Exception $e) {
        die(json_encode(['success' => false]));
    }
}
