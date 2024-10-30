<?php
require_once __DIR__ . "/db.php";

class  Hotel {
    private $table = 'tranfer_hotel';
    private $db;
    public $Id_hotel;
    public $Id_zona;
    public $Comision;
    public $Usuario;

    public $Password;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findByUsername($usuario) {
        $stmt = $this->db->prepare("SELECT * FROM tranfer_hotel WHERE Usuario = :usuario");
        $stmt->bindValue(':usuario', $usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function verifyPasswordUsername($usuario, $password) {
        $user = $this->findByUsername($usuario);
        if ($user && password_verify($password, $user['Password'])) {
            return true;
        } else {
            return false;
        }
    }

    public function readAllHotels() {
        return db_query_fetchall('SELECT * FROM ' . $this->table);
    }



    public function addHotel($id_hotel, $id_zona, $comision, $usuario, $password) {
        $this->db->beginTransaction();

        try {
            if ($this->findByUsername($usuario)) {
                throw new Exception("El usuario ya existe.");
            }
            $sql = "INSERT INTO tranfer_hotel (Id_hotel, Id_zona, Comision, Usuario, Password) VALUES (:id_hotel, :id_zona, :Comision, :usuario, :password)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_hotel', $id_hotel);
            $stmt->bindValue(':id_zona', $id_zona);
            $stmt->bindValue(':Comision', $comision);
            $stmt->bindValue(':usuario', $usuario);
            $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
            $stmt->execute();
            /*$id_hotel = $this->db->lastInsertId();*/

            $this->db->commit();

            return $usuario;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error en addUser: ' . $e->getMessage());
            return null;
        }
    }

    public function updateHotel() {
        $query = 'UPDATE ' . $this->table . ' SET Id_zona=:id_zona, Comision=:Comision, Usuario=:usuario, Password=:password WHERE Id_hotel=:id_hotel';
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(":id_hotel", $this->Id_hotel);
        $stmt->bindParam(":id_zona", $this->Id_zona);
        $stmt->bindParam(":Comision", $this->Comision);
        $stmt->bindParam(":usuario", $this->Usuario);
        $stmt->bindParam(":password", $this->Password);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteHotel($id_hotel) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE Id_hotel = :id_hotel';
        return db_query_execute($query, [':id_hotel' => $id_hotel]);
    }
}