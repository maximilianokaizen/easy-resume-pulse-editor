<?php

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
    // Guardar el PDF en un archivo
    $pdfFilePath = 'generated_pdf.pdf';
    file_put_contents($pdfFilePath, $response);
    echo 'PDF generado y guardado en: ' . $pdfFilePath;
} else {
    echo 'Error al obtener el PDF. Código de estado HTTP: ' . $httpCode;
}

curl_close($curl);
?>
