<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('../lib/sanatize/sanatize.php');
require_once('../lib/db/dbConnection.php');
require_once('../lib/token/TokenManager.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $jsonData = json_decode(file_get_contents('php://input'), true);
        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar el JSON: " . json_last_error_msg());
        }

        $name = sanitizeInput($jsonData['templateName']);
        $html = sanitizeInput($jsonData['html']);
        $css = sanitizeInput($jsonData['css']);
        $uuidString = generateUUIDv4();

        $db = new DatabaseConnector();
       
        $query = "INSERT INTO templates (uuid, name, html, css, premium, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $params = array($uuidString, $name, $html, $css, 0);
        $inserted = $db->executeQuery($query, $params);

        if ($inserted === null || empty($inserted)) {
            http_response_code(200);
            die(json_encode(['success' => true]));
        } else {
            http_response_code(200);
            die(json_encode(['success' => true, 'templateId' => $inserted]));
        }
    } catch (Exception $e) {
        http_response_code(500);
        $log = $e->getMessage();
        die(json_encode(['success' => false, 'error' => $log]));
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Expected a POST request.']);
}

function generateUUIDv4() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

?>

  