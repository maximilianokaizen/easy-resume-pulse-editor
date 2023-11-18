<?php

declare(strict_types=1);

class TokenGeneratorClient {
    private string $generateTokenUrl;

    public function __construct() {
        $envValues = $this->getEnvValues();
        $this->generateTokenUrl = $envValues['GENERATE_TOKEN_URL'] ?? '';
    }

    private function getEnvValues(): array {
        $envFile = __DIR__ . '/.env';
        return parse_ini_file($envFile);
    }

    public function generateToken(array $data): ?string {
        if (!$this->generateTokenUrl) {
            return json_encode(['success' => false, 'message' => 'No se ha proporcionado una URL para generar el token en el archivo .env']);
        }

        $ch = curl_init($this->generateTokenUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        if ($result === false) {
            return json_encode(['success' => false, 'message' => 'Error al llamar al servicio para generar el token: ' . curl_error($ch)]);
        }

        curl_close($ch);
        return $result;
    }
}
