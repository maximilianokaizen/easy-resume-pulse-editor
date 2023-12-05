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
        $imageUrl = '';
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
       
        $image = $db->executeQuery($qry, [$user[0]['id']]);

        if ($image === null || empty($image) || empty($image[0]['image'])) {
            $imageUrl = null;
        } else {
            $imageUrl = 'https://easyresumepulse.com/en/user-images/' . $image[0]['image'];
        }

        $query = "SELECT html FROM resumes WHERE uuid = ? LIMIT 1";
    
        $resume = $db->executeQuery($query, [$uuid]);

        if ($resume === null || empty($resume)) {
            throw new Exception("No resume found for this UUID.");
        }

        $html = $resume[0]['html'];
        render($html, $template, $imageUrl);
        
        if (count($image) === 0 ){
            render($html, $template, $imageUrl);
        }else{
            /* replace html, css for real images */
            /* template 48, 50, 51 */
            if ($template == 48 || $template == 50 || $template == 51){
                $dom = new DOMDocument();
                $dom->loadHTML($html);
                $xpath = new DOMXPath($dom);
                $elements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' img-profile-image ')]");
                try {
                    if ($elements !== false && $elements->length > 0) {
                        foreach ($elements as $element) {
                            $element->setAttribute('style', 'background: url(' . $imageUrl . ') transparent center center no-repeat;');
                        }
                        $updatedHTML = $dom->saveHTML();
                        render($updatedHTML, $imageUrl, $template);
                    } else {
                        // No se encontraron elementos con la clase img-profile-image
                        $updatedHTML = $html; // Conserva el HTML original
                        render($updatedHTML, $imageUrl, $template);
                    }
                } catch (Exception $e) {
                    // Manejo de excepciones
                    $updatedHTML = $html; // Conserva el HTML original
                    render($updatedHTML, $imageUrl, $template, $e->getMessage());
                }
        }       
    }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Expected a POST request.']);
}

function render($html, $template, $image, $error = ''){
    die("$html<script>console.log('template =>, $template, imagen => $image , error => $error');</script>");
}
?>
