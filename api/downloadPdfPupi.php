<?php

// Datos HTML para enviar al servicio en formato JSON
$htmlData = '<html><body><h1>Hello, World!</h1><p>This is a test HTML content.</p></body></html>';
$jsonData = json_encode(array('html' => $htmlData));

// URL del servicio que genera el PDF
$url = 'http://easyresumecreator.online/pdf';

// Configuración de la solicitud cURL
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

// Ejecutar la solicitud cURL
$response = curl_exec($curl);

// Verificar si la solicitud fue exitosa
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ($httpCode === 200) {
    $pdfFilePath = '/home/easyre/public_html/en/resumes/generated_pdf.pdf';
    $success = file_put_contents($pdfFilePath, $response);
    
    if ($success !== false) {
        echo 'PDF generado y guardado en: ' . $pdfFilePath;
    } else {
        $error = error_get_last();
        echo 'Error al guardar el archivo: ' . $error['message'];
    }
} else {
    echo 'Error al obtener el PDF. Código de estado HTTP: ' . $httpCode;
}

// Cerrar la sesión cURL
curl_close($curl);
?>