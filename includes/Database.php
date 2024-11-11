<?php
class Database {

  private $host = "localhost";
  private $user = "root";
  private $password = "";
  private $db_name = "lib_db";

  public $conn;

  public function connect() {
    $this->conn = new mysqli($this->host, $this->user, $this->password, $this->db_name);

    // Verificar la conexión
    if ($this->conn->connect_error) {
      die("Conexión fallida: " . $this->conn->connect_error);
    }
    

    if (!$this->conn->set_charset("utf8mb4")) {
      die("Error al establecer la codificación de caracteres utf8mb4: " . $this->conn->error);
    }
    
    return $this->conn;
  }
}
