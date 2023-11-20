<?php

declare(strict_types=1);

require 'generateToken.php';

// Definir el UUID y los minutos de expiración
$uuid = 'tu_uuid_aqui';
$minutes = 60; // Ejemplo de 60 minutos de expiración

try {
    // Llamar al método estático para generar el token
    $token = JWTGenerator::generateToken($uuid, $minutes);

    // Hacer algo con el token generado, como imprimirlo
    echo "Token generado: " . $token;
} catch (Exception $e) {
    // Capturar y manejar cualquier excepción lanzada desde el método
    echo "Error: " . $e->getMessage();
}
