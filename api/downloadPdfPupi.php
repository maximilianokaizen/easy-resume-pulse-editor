<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$htmlData = '<html><body><h1>Hello, World!</h1><p>This is a test HTML content.</p></body></html>';
$jsonData = json_encode(array('html' => $htmlData));

$url = 'http://easyresumecreator.online/pdf';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($httpCode === 200) {
    $pdfFileName = generateRandomString() . '.pdf'; // Nombre aleatorio para el PDF
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