<?php
// check auth

// libs
require_once('../lib/sanatize/sanatize.php');
require_once('../lib/db/dbConnection.php');
require_once('../lib/token/TokenManager.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $jsonData = json_decode(file_get_contents('php://input'), true);
        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        $linkUuid = generateUUIDv4();
        $uuid = sanitizeInput($jsonData['uuid'] ?? '');
        $token = sanitizeInput($jsonData['token']) ?? '';
        $templateId = sanitizeInput($jsonData['template']) ?? '';
        
        if (empty($uuid) || empty($token) || empty($templateId)) {
            throw new Exception("Please provide both UUID, Token, TemplateId.");
        }

        // Validar el token recibido
        try {
            $isValidToken = TokenManager::validateToken($token);
            if (!$isValidToken) {
                throw new Exception("Invalid Token.");
            }
        } catch (Throwable $e) {
            throw new Exception("Error validating token: " . $e->getMessage());
        }

        $userUuid = TokenManager::getUserUuidFromToken($token);
       
        if ($userUuid === null){
            throw new Exception("Error validating token (02): " . $e->getMessage());
        }

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

        $userPremium = $user[0]['premium'];

        if ($userPremium >= 1){
            $remainsResumes = true;
        }else{
            $remainsResumes = false;
        }

        /* get html and css from template */
        try {
            $query = "SELECT html FROM templates WHERE id = ?";
            $template = $db->executeQuery($query, [$templateId]);
            if ($template === null || empty($template)) {
                throw new Exception("No template found with this TemplateId.");
            }
        } catch (Exception $e) {
            die(json_encode(['success' => false, 'error' => $e->getMessage()]));
        }
    
        $html = $template[0]['html'];
        /* end of get template*/

        $now = new DateTime();
        $now->add(new DateInterval('PT30M'));
        $dateToInsert = $now->format('Y-m-d H:i:s');

        $params = [$linkUuid, $uuid, $templateId, $user[0]['id'], $dateToInsert ];
        $query = "INSERT INTO links (uuid, resume_uuid, template_id, user_id, expiration_date) VALUES (?, ?, ?, ?, ?)";
      
        try {
            $result = $db->executeQuery($query, $params);
            if ($result === null) {
                die(json_encode(['success' => true, 'url' => 
                'https://easyresumepulse.com/en/my-awesome-resume/index.php?uuid=' . $linkUuid])); 
            }
        } catch (Exception $e) {
            die(json_encode(['success' => false, 'error' => $e->getMessage()]));
        }
    
        die(json_encode(['success' => true]));

    } catch (Exception $e) {
    
        die(json_encode(['success' => false, 'error' => $e->getMessage()]));
    }

    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Datos insertados correctamente.']);
} else {
    
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido. Se esperaba una solicitud POST.']);
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
