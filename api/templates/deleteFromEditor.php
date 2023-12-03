<?php
die();
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

        $token = sanitizeInput($jsonData['token'] ?? '');
        $id = sanitizeInput($jsonData['id'] ?? '');

        if ($token !== 'kaizen') {
            die(json_encode(['success' => false, 'message' => 'Invalid token.']));
        }

        $db = new DatabaseConnector();

        $query = "DELETE FROM templates WHERE id=?"; //
        $success = $db->executeQuery($query, [$id]); //

        die(json_encode(['success' => true, 'message' => 'HTML updated successfully.']));
       
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

  