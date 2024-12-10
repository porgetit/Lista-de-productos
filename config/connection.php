<?php

class connection {
    private $server;
    private $database;
    private $user;
    private $password;
    private $connection;

    public function __construct($server='localhost', $database='productcatalog', $user='root', $password='') {
        $this->server = $server;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
        $this->connection = null;
    }

    private function open_connection() {
        $this->connection = new mysqli(
            $this->server,
            $this->user,
            $this->password,
            $this->database
        );

        if ($this->connection->connect_error) {
            die("Error de conexión: " . $this->connection->connect_error);
        }
    }

    private function close_connection() {
        if ($this->connection !== null) {
            $this->connection->close();
            $this->connection = null;
        }
    }

    public function run($sql) {
        $this->open_connection();

        $result = $this->connection->query($sql);
        if (!$result) {
            die("Error de consulta: " . $this->connection->error);
        }

        $this->close_connection();
        return $result;
    }
}

?>