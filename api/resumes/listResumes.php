<?php

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

        /* get user */
        try {
            $db = new DatabaseConnector();
            $query = "SELECT id FROM users WHERE uuid = ? AND user_active=1";
            $user = $db->executeQuery($query, [$userUuid]);
            if ($user === null || empty($user)) {
                throw new Exception("No users found with this UUID.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        /* end of get user */

        $userId = $user[0]['id'];

        $query = "SELECT id, uuid, name, created_at, modified_at FROM resumes WHERE user_id = ?";
        $resumes = $db->executeQuery($query, [$userId]);

        if ($resumes === null || empty($resumes)) {
            http_response_code(200);
            die(json_encode(['success' => true, 'resumes' => []]));
        }

        http_response_code(200);
        echo json_encode(['success' => true, 'resumes' => $resumes]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Expected a POST request.']);
}
