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
        $htmlContent = $jsonData['htmlContent'] ?? '';

        if (empty($uuid) || empty($token) || empty($htmlContent)) {
            throw new Exception("Please provide UUID, Token, and HTML content.");
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
        $query = "SELECT id FROM resumes WHERE uuid = ?";
        $resume = $db->executeQuery($query, [$uuid]);
        if ($resume === null || empty($resume)) {
            throw new Exception("No resume found with this UUID.");
        }

        $resumeId = $resume[0]['id'];

        $currentDateTime = date('Y-m-d H:i:s');
        $updateQuery = "UPDATE resumes SET html = ?, modified_at = ? WHERE id = ?";
        $updateParams = [$htmlContent, $currentDateTime, $resumeId];

        $result = $db->executeQuery($updateQuery, $updateParams);
        if ($result === null) {
            die(json_encode(['success' => true])); 
        }

    } catch (Exception $e) {
        die(json_encode(['success' => false, 'error' => $e->getMessage()]));
    }

    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Data updated successfully.']);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Expected a POST request.']);
}
?>
