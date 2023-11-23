<?php

declare(strict_types=1);

header('Content-Type: application/json');
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
*/

require_once('../lib/sanatize/sanatize.php');
include_once('../lib/db/dbConnection.php');
require_once('../lib/token/TokenManager.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $jsonData = json_decode(file_get_contents('php://input'), true);
        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        $uuid = sanitizeInput($jsonData['uuid'] ?? '');
        $receivedToken = $jsonData['token'] ?? '';

        if (empty($uuid) || empty($receivedToken)) {
            throw new Exception("Please provide both UUID and Token.");
        }

        // Validar el token recibido
        try {
            $isValidToken = TokenManager::validateToken($receivedToken);
            if (!$isValidToken) {
                throw new Exception("Invalid Token.");
            }
        } catch (Throwable $e) {
            throw new Exception("Error validating token: " . $e->getMessage());
        }

        $query = "SELECT id,uuid,email,name,plan,premium,plan_start,plan_end FROM users WHERE uuid = ? AND user_active=1";
        $db = new DatabaseConnector();

        $user = $db->executeQuery($query, [$uuid]);

        if ($user === null || empty($user)) {
            throw new Exception("No users found with this UUID.");
        }

        if ($user[0]['uuid'] !== $uuid) {
            throw new Exception("Error: Mismatch.");
        }

        http_response_code(200);
        echo json_encode(['success' => true, 'user' => $user[0]]);
      

    } catch (Exception $e) {
        http_response_code(403); // Forbidden
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Expected a POST request.']);
}