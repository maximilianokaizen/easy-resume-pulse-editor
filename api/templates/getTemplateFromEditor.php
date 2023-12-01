<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('../lib/sanatize/sanatize.php');
require_once('../lib/db/dbConnection.php');
require_once('../lib/token/TokenManager.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {

        $token = sanitizeInput($_GET['token'] ?? '');
        $id = sanitizeInput($_GET['id'] ?? '');

        if ($token !== 'kaizen'){
            die(json_encode(['false' => true, 'template' => null])); 
        }

        $db = new DatabaseConnector();
       
        $query = "SELECT id,html,name FROM templates WHERE id=?";
        $template = $db->executeQuery($query, [$id]);

        if ($template === null || empty($template)) {
            http_response_code(200);
            die(json_encode(['success' => true, 'template' => null]));
        }else{
            die(json_encode(['success' => true, 'template' => $template]));
        }
} catch (Exception $e) {
    http_response_code(500);
    $log = $e->getMessage();
    die(json_encode(['success' => false, 'error' => '']));
}
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Expected a GET request.']);
}