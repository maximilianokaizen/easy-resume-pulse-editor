<?php
require_once 'vendor/autoload.php'; // Ruta a tu archivo autoload de la biblioteca google/apiclient

// Configuración de la autenticación
$client = new Google_Client();
$client->setClientId('223124831209-p04fqq68spt1pm60j69drbrcgknjsnl5.apps.googleusercontent.com');
$client->setClientSecret('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');

// Verificar si hay un código de autorización
if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']); // Intercambiar código por token de acceso
    $_SESSION['access_token'] = $client->getAccessToken(); // Almacenar el token en la sesión o en una base de datos
}

// Verificar si hay un token de acceso almacenado
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']); // Configurar el token almacenado en el cliente

    // Crear servicio de la API de Google que quieres usar (por ejemplo, Gmail API)
    $service = new Google_Service_Gmail($client);

    // Ejemplo: Obtener información del perfil del usuario
    $userInfo = $service->userinfo->get();

    // Mostrar información del usuario
    echo 'ID de usuario: ' . $userInfo->getId() . '<br>';
    echo 'Nombre: ' . $userInfo->getName() . '<br>';
    echo 'Email: ' . $userInfo->getEmail() . '<br>';
} else {
    // Si no hay token de acceso, redirigir al usuario a la página de autenticación de Google
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
}