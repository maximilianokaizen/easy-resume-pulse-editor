<?php

function getEnviroment() {
    $envContent = file_get_contents(__DIR__ . '/.env');

    // Separar líneas y procesar cada línea
    $lines = explode("\n", $envContent);
    $envArray = [];

    foreach ($lines as $line) {
        // Ignorar comentarios y líneas vacías
        if (empty($line) || strpos(trim($line), '#') === 0) {
            continue;
        }

        // Separar por igual para obtener clave y valor
        list($key, $value) = explode('=', $line, 2);
        $envArray[trim($key)] = trim($value);
    }

    return $envArray;
}
