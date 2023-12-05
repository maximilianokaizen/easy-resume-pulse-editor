<?php
// check auth

// libs
require_once('../lib/sanatize/sanatize.php');
require_once('../lib/db/dbConnection.php');
require_once('../lib/token/TokenManager.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
        $basePath = '/home/easyre/public_html/en/';
    } else {   
        $basePath = '/var/www/html/';
    }

    $token = sanitizeInput($_POST['token']);
    $uuid =  sanitizeInput($_POST['uuid']);

    if (empty($uuid) || empty($token) ) {
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
        
    try {

        $userPremium = $user[0]['premium'];
        $userId = $user[0]['id'];

        if ($userPremium >= 1){
            $remainsResumes = true;
        }else{
            $remainsResumes = false;
        }

        if (!empty($_FILES['image']['name'])) {

            $imageFile = $_FILES['image'];
            $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif'];
            $maxFileSize = 3 * 1024 * 1024; // 3MB in bytes

            $fileExtension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception("Invalid image format. Allowed formats: PNG, JPG, JPEG, GIF.");
            }

            if ($imageFile['size'] > $maxFileSize) {
                throw new Exception("Image size exceeds the maximum limit of 3MB.");
            }

            $newFileName = generateUUIDv4() . '.' . $fileExtension;
            $uploadPath = $basePath . '/user-images/' . $newFileName;
            die($uploadPath);
            if (!move_uploaded_file($imageFile['tmp_name'], $uploadPath)) {
                $errorMessage = error_get_last()['message'] ?? "Unknown error occurred.";
                throw new Exception("Error uploading image: $errorMessage");
            }

            $qry = "INSERT INTO user_images (user_id, image, active, created_at) VALUES (?, ?, ?, NOW())";
            $inserted = $db->executeQuery($qry, [$userId, $newFileName, true]);

            if ($inserted === false) {
                throw new Exception("Error inserting data into database.");
            }else{
                die(json_encode(['success' => true]));
            }

        } else {
            throw new Exception("Please select an image to upload.");
        }
        
        // insert in database
        $qry = "INSERT INTO user_images (uuid,user_id,image) VALUES (?,?,?)";

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
