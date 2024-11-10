<?php
require_once __DIR__ . "/db.php";

class Tour
{
    private $table = 'transfer_excursion';
    private $conn;
    public $Id_excursion;
    public $Fecha_excursion;
    public $Hora_entrada_excursion;
    public $Hora_salida_excursion;
    public $Descripcion;
    public $Num_excursionistas;
    public $Email_cliente;
    public $Id_hotel;
    public $Id_vehiculo;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAllTours() {
        return db_query_fetchall('SELECT * FROM ' . $this->table);
    }

    public function addTour() {
        $query = 'INSERT INTO ' . $this->table . ' 
                  (Fecha_excursion, Hora_entrada_excursion, Hora_salida_excursion, Descripcion, Num_excursionistas, Email_cliente, Id_hotel, Id_vehiculo) 
                  VALUES (:fecha_excursion, :hora_entrada_excursion, :hora_salida_excursion, :descripcion, :num_excursionistas, :email_cliente, :id_hotel, :id_vehiculo)';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":fecha_excursion", $this->Fecha_excursion);
        $stmt->bindParam(":hora_entrada_excursion", $this->Hora_entrada_excursion);
        $stmt->bindParam(":hora_salida_excursion", $this->Hora_salida_excursion);
        $stmt->bindParam(":descripcion", $this->Descripcion);
        $stmt->bindParam(":num_excursionistas", $this->Num_excursionistas);
        $stmt->bindParam(":email_cliente", $this->Email_cliente);
        $stmt->bindParam(":id_hotel", $this->Id_hotel);
        $stmt->bindParam(":id_vehiculo", $this->Id_vehiculo);

        return $stmt->execute();
    }

    public function updateTour() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET Fecha_excursion = :fecha_excursion, Hora_entrada_excursion = :hora_entrada_excursion, 
                      Hora_salida_excursion = :hora_salida_excursion, Descripcion = :descripcion, 
                      Num_excursionistas = :num_excursionistas, Email_cliente = :email_cliente, 
                      Id_hotel = :id_hotel, Id_vehiculo = :id_vehiculo 
                  WHERE Id_excursion = :id_excursion';
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(":id_excursion", $this->Id_excursion);
        $stmt->bindParam(":fecha_excursion", $this->Fecha_excursion);
        $stmt->bindParam(":hora_entrada_excursion", $this->Hora_entrada_excursion);
        $stmt->bindParam(":hora_salida_excursion", $this->Hora_salida_excursion);
        $stmt->bindParam(":descripcion", $this->Descripcion);
        $stmt->bindParam(":num_excursionistas", $this->Num_excursionistas);
        $stmt->bindParam(":email_cliente", $this->Email_cliente);
        $stmt->bindParam(":id_hotel", $this->Id_hotel);
        $stmt->bindParam(":id_vehiculo", $this->Id_vehiculo);
    
        return $stmt->execute();
    }
    

    public function deleteTour($id_excursion) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE Id_excursion = :id_excursion';
        return db_query_execute($query, [':id_excursion' => $id_excursion]);
    }
}
