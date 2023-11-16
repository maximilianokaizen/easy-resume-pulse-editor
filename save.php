<?php

define('MAX_FILE_LIMIT', 1024 * 1024 * 2); // 2 Megabytes max html file size

try {
    if (isset($_POST['startTemplateUrl']) && !empty($_POST['startTemplateUrl'])) {
        $startTemplateUrl = sanitizeFileName($_POST['startTemplateUrl']);
        $html = file_get_contents($startTemplateUrl);
    } else if (isset($_POST['html'])){
        $html = substr($_POST['html'], 0, MAX_FILE_LIMIT);
    } else {
        throw new Exception("No se proporcionaron datos válidos.");
    }

    $response = [
        'html' => $html,
        'success' => true
    ];


    echo json_encode($response);
} catch (Exception $e) {
    $errorResponse = [
        'error' => $e->getMessage(),
        'success' => false
    ];

    echo json_encode($errorResponse);
}

function sanitizeFileName($file, $allowedExtension = 'html') {
    $file = __DIR__ . '/' . preg_replace('@\?.*$@', '', preg_replace('@\.{2,}@', '', preg_replace('@[^\/\\a-zA-Z0-9\-\._]@', '', $file)));

    if ($allowedExtension) {
        $file = preg_replace('/\.[^.]+$/', '', $file) . ".$allowedExtension";
    }
    return $file;
}
