<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $basePath = '/home/easyre/public_html/en';
} else {   
    $basePath = '/var/www/html';
}

$uuid = $_GET['uuid'];
require_once($basePath . '/api/internal/links/Links.php');

$link = new Links();

$htmlResume = $link->getHtml($uuid);

$dom = new DOMDocument();
$dom->loadHTML($htmlResume);
$xpath = new DOMXPath($dom);
$containers = $xpath->query("//*[@id='container' or contains(concat(' ', normalize-space(@class), ' '), ' container ')]");
foreach ($containers as $container) {
    // Modificar el atributo style del div
    $container->setAttribute('style', 'width:780px !important; margin: 0px auto !important;');
}
// Obtener el HTML modificado
$modifiedHTML = $dom->saveHTML();
die($modifiedHTML);
