<?php

class DatabaseConnector {
    private $connection;

    public function __construct() {
        $this->connect();
    }

    private function getEnvValues() {
        $envFile = __DIR__ . '/.env'; // Ruta al archivo .env
        if (!file_exists($envFile)) {
            throw new Exception('El archivo .env no existe.');
        }
        return parse_ini_file($envFile);
    }

    private function connect() {
        $envValues = $this->getEnvValues();

        $databaseServer = $envValues['DATABASE_SERVER'];
        $databaseName = $envValues['DATABASE_NAME'];
        $databaseUser = $envValues['DATABASE_USER'];
        $databasePwd = $envValues['DATABASE_PWD'];

        $this->connection = new mysqli($databaseServer, $databaseUser, $databasePwd, $databaseName);

        if ($this->connection->connect_error) {
            die(json_encode([
                'success' => false,
                'message' => 'Error al conectar a la base de datos: ' . $this->connection->connect_error
            ]));
        }
    }

    public function isConnected() {
        return $this->connection !== null && $this->connection->ping();
    }

    public function executeQuery($query, $params = []) {
        try {
            if (!$this->isConnected()) {
                throw new Exception('La conexión a la base de datos no está disponible.');
            }
    
            $statement = $this->connection->prepare($query);
    
            if ($statement === false) {
                throw new Exception('Error al preparar la consulta: ' . $this->connection->error);
            }
    
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                $statement->bind_param($types, ...$params);
            }
    
            if (!$statement->execute()) {
                throw new Exception('Error al ejecutar la consulta: ' . $statement->error);
            }
    
            $result = $statement->get_result();
    
            if ($result === false) {
                return null; // No hay resultados
            }
    
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $statement->close();
    
            return $data;
        } catch (Exception $e) {
            die(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
        }
    }

    public function debugQuery(string $query, array $params = []) {
        $debugQuery = $query;
    
        foreach ($params as &$param) {
            if ($param === null) {
                $param = 'NULL';
            } else {
                $param = "'" . $this->connection->escape_string($param) . "'";
            }
        }
    
        $debugQuery = vsprintf(str_replace('?', '%s', $debugQuery), $params);
    
        echo "Debug Query: " . $debugQuery;
    }

    public function disconnect() {
        if ($this->isConnected()) {
            $this->connection->close();
        }
    }

    public function __destruct() {
        $this->disconnect();
    }
}
