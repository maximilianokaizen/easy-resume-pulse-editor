<?php
$response = array();

if(isset($_GET['id'])) {
    // Sanitiza el parámetro 'id' para evitar SQL injection u otras amenazas
    $id = htmlspecialchars($_GET['id']);
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>Hello World</title>
    </head>
    <body>
        <h1>Hello, world!</h1>
        <p>El ID proporcionado es: ' . $id . '</p>
    </body>
    </html>';
    $response['success'] = true;
    $response['htmlContent'] = $html;
} else {
    $response['success'] = false;
    $response['message'] = 'Se requiere el parámetro "id"';
}

header('Content-Type: application/json');
echo json_encode($response);
?>