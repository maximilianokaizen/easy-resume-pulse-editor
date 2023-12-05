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
        
        if ($imageUrl !== null){
            render($html, $imageUrl);
        }else{
            simpleRender($html);
        }
       
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Expected a POST request.']);
}

function render($html, $image, $error = ''){
    // Expresión regular para buscar URLs de imágenes en atributos 'src' de etiquetas <img>
    $imgPattern = '/<img[^>]+src=["\']([^"\']+)[^>]*>/i';
    // Reemplazar las URLs de las imágenes en atributos 'src' de etiquetas <img>
    $html = preg_replace_callback($imgPattern, function($matches) use ($image) {
        return str_replace($matches[1], $image, $matches[0]);
    }, $html);

    // Expresión regular para buscar URLs de imágenes en propiedades de fondo (background-image)
    $cssPattern = '/url\s*\(\s*[\'\"]?\s*(https?:\/\/[^\'\"\)]+)\s*[\'\"]?\s*\)/i';
    // Reemplazar las URLs de las imágenes en propiedades de fondo (background-image)
    $html = preg_replace_callback($cssPattern, function($matches) use ($image) {
        return str_replace($matches[1], $image, $matches[0]);
    }, $html);

    // Mostrar el HTML procesado o el HTML original si falla el reemplazo
    echo $html;
}

// simple
function simpleRender($html, $image, $error = ''){
    echo $html;
}

?>
