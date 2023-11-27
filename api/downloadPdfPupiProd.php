<?php
ini_set('memory_limit', '1024M');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require 'vendor/autoload.php';
require_once 'dompdf/autoload.inc.php';
require_once('internal/templates/Templates.php');

$jsonInput = file_get_contents('php://input');

$requestData = json_decode($jsonInput, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $htmlWithoutCss = $requestData['html'];
    $templateId = $requestData['template'];

    $customCss = getTemplateCustomCss($templateId);

    $htmlWithCss = insertCssIntoHtmlHead($htmlWithoutCss, $customCss);

    $htmlWithCss = addFooter($htmlWithCss);

    $pdfFileName = generateRandomString() . '.pdf';
    $pdfFilePath = '/home/easyre/public_html/en/resumes/' . $pdfFileName;

    $url = 'http://easyresumecreator.online/pdf';
    $jsonData = array('html' => $htmlWithCss);
    $jsonData = json_encode($jsonData);

    if ($jsonData === false) {
        throw new Exception('Error al codificar a JSON: ' . json_last_error_msg());
    }
    
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

}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function getTemplateCustomCss($templateId) {
    $template = new Template();
    $css = $template->getCss(intval($templateId));
    return $css;
}

function insertCssIntoHtmlHead($htmlWithoutCss, $customCss) {
    $headPattern = '/<head.*?>(.*?)<\/head>/is';
    if (preg_match($headPattern, $htmlWithoutCss, $matches)) {
        $headContent = $matches[1];
        $modifiedHeadContent = "<style>$customCss</style>" . $headContent;
        $htmlWithCss = preg_replace($headPattern, "<head>$modifiedHeadContent</head>", $htmlWithoutCss);
        return $htmlWithCss;
    }
    return "<style>$customCss</style>" . $htmlWithoutCss;
}

function addFooter($html) {
    $footer = "
    <div class='footer-kaizen' style='width: 100%;text-align:center;padding:10px 0;font-size:16px; margin: 0px auto'>
    Generated with https://easyresumepulse.com | Created by https://kaizenpulse.com/index-en.html
    </div>
    </body></html>";
    $result = str_replace("</body></html>", $footer, $html);
    return $result;
}
