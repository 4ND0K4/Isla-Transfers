<?php
require_once __DIR__ . "/db.php";

class  Vehicle
{
    private $table = 'transfer_vehiculo';
    private $conn;
    public $Id_vehiculo;
    public $Descripcion;
    public $Email_conductor;
    public $Password;

    public function __construct() {
        $this->conn = db_connect();
    }

    public function readAllVehicles() {
        return db_query_fetchall('SELECT * FROM ' . $this->table);
    }

    public function addVehicle() {
        $query = 'INSERT INTO ' . $this->table . ' (Id_vehiculo, Descripcion, Email_conductor, Password) VALUES (:id_vehiculo, :descripcion, :email_conductor, :password)';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_vehiculo", $this->Id_vehiculo);
        $stmt->bindParam(":descripcion", $this->Descripcion);
        $stmt->bindParam(":email_conductor", $this->Email_conductor);
        $stmt->bindParam(":password", $this->Password); // Contraseña ya hasheada

        return $stmt->execute();
    }
    
    public function updateVehicle() {
        // Consulta base para actualizar el vehículo
        $query = 'UPDATE ' . $this->table . ' SET Descripcion=:descripcion, Email_conductor=:email_conductor';

        // Solo incluir la contraseña en el SQL si se ha proporcionado una nueva
        if (!empty($this->Password)) {
            $query .= ', Password=:password';
        }
        $query .= ' WHERE Id_vehiculo=:id_vehiculo';

        $stmt = $this->conn->prepare($query);

        // Bindeo de parámetros
        $stmt->bindParam(":id_vehiculo", $this->Id_vehiculo);
        $stmt->bindParam(":descripcion", $this->Descripcion);
        $stmt->bindParam(":email_conductor", $this->Email_conductor);

        // Solo bindear la contraseña si es nueva y hasheada
        if (!empty($this->Password)) {
            $stmt->bindParam(":password", $this->Password);
        }

        return $stmt->execute();
    }
    
    public function deleteVehicle($id_vehicle) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE Id_vehiculo = :id_vehiculo';
        return db_query_execute($query, [':id_vehiculo' => $id_vehicle]);
    }
}