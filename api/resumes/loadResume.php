<?php
declare(strict_types=1);

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/

require_once('../lib/sanatize/sanatize.php');
require_once('../lib/db/dbConnection.php');
require_once('../lib/token/TokenManager.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  
    try {
    
        $uuid = sanitizeInput($_GET['uuid'] ?? '');
        $token = sanitizeInput($_GET['token'] ?? '');
        $template = sanitizeInput($_GET['template'] ?? '');

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

        /* search user image */
        $qry = "SELECT image 
        FROM user_images 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 1;
        ";

        $db = new DatabaseConnector();

        /* get user */
        try {
            $query = "SELECT id,uuid,premium FROM users WHERE uuid = ? AND user_active=1";
            $user = $db->executeQuery($query, [$userUuid]);
            if ($user === null || empty($user)) {
                throw new Exception("No users found with this UUID.");
            }
        } catch (Exception $e) {
            die(json_encode(['success' => false, 'error' => $e->getMessage()]));
        }
        /* end of get user */
        print_r(var_dump($user));die();
        
        $image = $db->executeQuery($query, [$user[0]['id']]);
       
        if ($image !== null){
            $imageUrl = 'https://easyresumepulse.com/user-images/' . $image[0]['image'];
        }

        die($imageUrl);

        $query = "SELECT html FROM resumes WHERE uuid = ? LIMIT 1";
    
        $resume = $db->executeQuery($query, [$uuid]);

        if ($resume === null || empty($resume)) {
            throw new Exception("No resume found for this UUID.");
        }

        die($resume[0]['html']);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Expected a POST request.']);
}
?>
