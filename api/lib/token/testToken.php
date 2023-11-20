<?php

declare(strict_types=1);

require 'TokenManager.php';

// Definir el UUID y los minutos de expiración
$uuid = 'tu_uuid_aqui';
$minutes = 60; // Ejemplo de 60 minutos de expiración

try {
    // Llamar al método estático para generar el token
    $token = TokenManager::generateToken($uuid, $minutes);
  
    // Hacer algo con el token generado, como imprimirlo
    //echo "Token generado: " . $token;

    // Validar el token recibido
    try {
        $isValid = TokenManager::validateToken($token);
        if ($isValid) {
            echo "El token es válido.";
        } else {
            echo "El token NO es válido.";
        }
    } catch (Exception $e) {
        echo "Error al validar el token: " . $e->getMessage();
    }

} catch (Exception $e) {
    // Capturar y manejar cualquier excepción lanzada desde el método
    echo "Error: " . $e->getMessage();
}
