<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$jsonData = json_decode(file_get_contents('php://input'), true);
if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception("Error al decodificar el JSON: " . json_last_error_msg());
}

$html = $jsonData['html']; 
$width = $jsonData['url']; 
$height = $jsonData['height']; 

$jsonData = json_encode(array('url' => $url, 'width' => $width, 'height' => $height));

$url = 'http://easyresumecreator.online/image';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($httpCode === 200) {
    $pdfFileName = generateRandomString() . '.png'; // Nombre aleatorio para la imagen
    $pdfFilePath = '/home/easyre/public_html/en/resumes/' . $pdfFileName;
    
    $success = file_put_contents($pdfFilePath, $response);
    
    if ($success !== false) {
        $output = array(
            'success' => true,
            'message' => 'PDF generado y guardado correctamente.',
            'filePath' => 'https://easyresumepulse.com/en/resumes/' . $pdfFileName
        );
    } else {
        $error = error_get_last();
        $output = array(
            'success' => false,
            'message' => 'Error al guardar el archivo PDF: ' . $error['message']
        );
    }
} else {
    $output = array(
        'success' => false,
        'message' => 'Error al obtener el PDF. Código de estado HTTP: ' . $httpCode
    );
}

curl_close($curl);

header('Content-Type: application/json');
echo json_encode($output);


?>