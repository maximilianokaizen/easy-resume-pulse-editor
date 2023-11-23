<?php
declare(strict_types=1);

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/

require_once('../lib/sanatize/sanatize.php');
require_once('../lib/db/dbConnection.php');
require_once('../lib/token/TokenManager.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $jsonData = json_decode(file_get_contents('php://input'), true);
        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Error decoding JSON']);
            exit();
        }

        $uuid = sanitizeInput($jsonData['uuid'] ?? '');
        $token = sanitizeInput($jsonData['token'] ?? '');

        if (empty($uuid) || empty($token)) {
            throw new Exception("Please provide UUID and Token.");
        }

        $isValidToken = TokenManager::validateToken($token);
        if (!$isValidToken) {
            throw new Exception("Invalid token.");
        }

        $userUuid = TokenManager::getUserUuidFromToken($token);
        if ($userUuid === null) {
            throw new Exception("Error validating the token.");
        }

        $db = new DatabaseConnector();
        $query = "SELECT id, html, uuid, created_at, modified_at FROM resumes WHERE uuid = ? LIMIT 1";
    
        $resume = $db->executeQuery($query, [$uuid]);

        if ($resume === null || empty($resume)) {
            throw new Exception("No resume found for this UUID.");
        }

        http_response_code(200);
        echo json_encode(['success' => true, 'resume' => $resume[0]]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Expected a POST request.']);
}
?>
