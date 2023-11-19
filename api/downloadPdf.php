<?php

require_once('../lib/enabledCors.php');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$jsonInput = file_get_contents('php://input');

$requestData = json_decode($jsonInput, true);

function validateParameter($paramName) {
    global $requestData;

    if (!isset($requestData[$paramName]) || empty($requestData[$paramName])) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => "El parámetro $paramName es requerido"));
        exit;
    }
}

$requiredParams = ['token', 'htmlContent', 'template'];

foreach ($requiredParams as $param) {
    validateParameter($param);
}

$token = $requestData['token'];
$htmlWithoutCss = $requestData['htmlContent'];
$template = $requestData['template'];

// Obtener el CSS personalizado del template desde la base de datos
$customCss = getTemplateCustomCss($template);

// Insertar el CSS dentro de los tags <head> y </head> del HTML
$htmlWithCss = insertCssIntoHtmlHead($htmlWithoutCss, $customCss);

$dompdf = new Dompdf();
$dompdf->loadHtml($htmlWithCss);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream();

function getTemplateCustomCss($template) {
    
    return '
    @media print {
        body {
          margin: 2cm;
        }
      }
    
      #template-container{
        padding: 2px;
        width: 780px;
        margin: 36px; 
        font-family: Arial, sans-serif;
        margin: 0px auto;
      }
    
      #editable-page {
        max-width: 21cm;
        margin: 0 auto;
      }
    
      .editableBlock {
        padding: 1cm;
      }
    
      h1 {
        margin-bottom: 0.5cm;
        font-size: 2.5em;
        text-align: left; /* Cambiado a la izquierda */
      }
    
      h2 {
        font-size: 1.5em;
        margin-bottom: 0.5cm;
        color: #555; /* Color gris para la descripción */
      }
    
      h3 {
        font-size: 1.2em;
        margin-bottom: 0.3cm;
        color: #888; /* Color gris más claro para los títulos de sección */
      }
    
      .job {
        margin-bottom: 1cm;
      }
    
      .job p {
        margin: 0;
      }
    
      /* Estilos para la versión web */
      .separator {
        border-bottom: 1px dashed #ddd; /* Línea punteada en lugar de sólida */
        margin-bottom: 20px; /* Ajusta el valor según tu preferencia */
      }
    
      /* Estilos para la versión de impresión (PDF) */
      @media print {
        .separator {
          border-bottom: 1px dashed #ddd; /* Línea punteada para la versión de impresión */
        }
      }
    
      .job ul {
        list-style-type: disc;
        margin-left: 20px;
        color: #555; /* Puedes ajustar el color según tu preferencia */
      }
    
      .job li {
        margin-bottom: 8px;
      }
    
      .contact-info {
        float: right;
        margin-top: -20px;
        margin-right: -100px;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
        background-color: #f9f9f9;
        font-size: 14px;
        text-align: center; /* Centrar el texto */
      }
    
      /* Estilos para la versión de impresión (PDF) */
      @media print {
        .contact-info {
          float: none; /* Elimina la flotación para la versión de impresión */
          margin-top: 0; /* Elimina el margen superior para la versión de impresión */
          page-break-before: always; /* Inicia una nueva página antes de la sección de contacto */
          float: right;
          width: 250px;
          margin-right: 10px;
          font-size: 12px;
          text-align: center; /* Centrar el texto */
        }
      }
    
      .contact-info ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
      }
    
      .contact-info li {
        margin-bottom: 8px;
      }
    
      .contact-info a {
        color: #007bff; /* Color de enlace azul */
        text-decoration: none;
        font-weight: bold;
      }
    
      .contact-info a:hover {
        text-decoration: underline;
      }
    
      #resume-description {
        padding-top: 15px;
        color: #777; /* Color gris para la descripción */
        font-size : 24px;
      }
    
      /* Estilos para el footer */
      .footer {
        width: 780px;
        background-color: #eee; /* Gris más claro para el fondo del footer */
        color: #333; /* Color más oscuro para el texto del footer */
        text-align: center;
        padding: 10px;
        border-top: 1px solid #ccc; /* Borde superior del footer */
        font-size: 12px;
      }
    
      /* Estilos para la información de contacto en el footer */
      .contact-info-footer {
        text-align: center;
      }
    
      .circle-container {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #fff; /* Color de fondo del círculo */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra alrededor del círculo */
        margin-top: -20px;
        margin-right: -100px;
        float: right;
        margin-top: 20px;
        margin-right: -100px;
      }
    
      /* Estilos para la versión de impresión (PDF) */
      @media print {
        .circle-container {
          width: 120px;
          height: 120px;
          border-radius: 50%;
          overflow: hidden;
          background-color: #fff; /* Color de fondo del círculo */
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra alrededor del círculo */
          margin-top: -20px;
          margin-right: -100px;
          float: right;
          margin-top: -20px;
          margin-right: 20px;
        }
      }
    
      .circle-container img {
        display: block;
        width: 200px;
        height: auto;
      }
    ';
}

function insertCssIntoHtmlHead($htmlWithoutCss, $customCss) {
    $headPattern = '/<head.*?>(.*?)<\/head>/is'; // Patrón para encontrar el contenido dentro de <head> y </head> tags

    if (preg_match($headPattern, $htmlWithoutCss, $matches)) {
        $headContent = $matches[1]; // Contenido dentro de <head> y </head> tags
        $modifiedHeadContent = "<style>$customCss</style>" . $headContent; // Inserta el CSS dentro del contenido de <head>
        $htmlWithCss = preg_replace($headPattern, "<head>$modifiedHeadContent</head>", $htmlWithoutCss); // Reemplaza el contenido de <head> con el contenido modificado
        return $htmlWithCss;
    }

    // Si no se encuentra el tag <head>, simplemente agrega el CSS al principio del HTML
    return "<style>$customCss</style>" . $htmlWithoutCss;
}
