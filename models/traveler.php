<?php
require_once __DIR__ . "/db.php";
class Traveler
{
    private $table = 'transfer_viajeros';
    private $db;
    public $Id_viajero;
    public $Nombre;
    public $Apellido1;
    public $Apellido2;
    public $Direccion;
    public $CodigoPostal;
    public $Ciudad;
    public $Pais;
    public $Email;
    public $Password;

    public function __construct($db)
    {
        $this->db = $db;
    }


    ///////////////////////////////////LOGIN/////////////////////////////////

    // Login para Viajeros/Travelers (por email)
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM transfer_viajeros WHERE Email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Chequea el email//
    public function verifyPasswordEmail($email, $password)
    {
        $user = $this->findByEmail($email);
        // Usar password_verify para comparar la contraseña ingresada con la hasheada en la base de datos
        if ($user && password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }


    /////////////////////////////CRUD///////////////////////////////////77

    public function readTraveler() {
        return db_query_fetchall('SELECT * FROM transfer_viajeros WHERE Id_viajero = :id_viajero');
    }

    public function addTraveler($id_viajero, $nombre, $apellido1, $apellido2, $direccion, $codigoPostal, $ciudad, $pais, $email, $password)
    {
        $this->db->beginTransaction();

        try {
            if ($this->findByEmail($email)) {
                return false;
            }
            $sql = "INSERT INTO transfer_viajeros (Id_viajero, Nombre, Apellido1, Apellido2, Direccion, CodigoPostal, Ciudad, Pais, Email, Password) VALUES (:id_viajero, :nombre, :apellido1, :apellido2, :direccion, :codigoPostal, :ciudad, :pais, :email, :password)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_viajero', $id_viajero, PDO::PARAM_INT);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':apellido1', $apellido1);
            $stmt->bindValue(':apellido2', $apellido2);
            $stmt->bindValue(':direccion', $direccion);
            $stmt->bindValue(':codigoPostal', $codigoPostal);
            $stmt->bindValue(':ciudad', $ciudad);
            $stmt->bindValue(':pais', $pais);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', password_hash($password, PASSWORD_BCRYPT)); // o PASSWORD_DEFAULT

            $stmt->execute();
            $this->db->commit();
            return $email;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error en addUser: ' . $e->getMessage());
            return null;
        }
    }
    public function updateTraveler() {
        $query = 'UPDATE ' . $this->table . ' SET Nombre=:nombre, Apellido1=:apellido1, Apellido2=:apellido2, Direccion=:direccion, CodigoPostal=:codigoPostal, Ciudad=:ciudad, Pais=:pais, Email=:email';

        // Solo incluir la contraseña en el SQL si se ha proporcionado una nueva
        if (!empty($this->Password)) {
            $query .= ', Password=:password';
        }
        $query .= ' WHERE Id_viajero=:id_viajero';

        $stmt = $this->db->prepare($query);

        // Bind de parámetros
        $stmt->bindParam(":id_viajero", $this->Id_viajero);
        $stmt->bindParam(":nombre", $this->Nombre);
        $stmt->bindParam(":apellido1", $this->Apellido1);
        $stmt->bindParam(":apellido2", $this->Apellido2);
        $stmt->bindParam(":direccion", $this->Direccion);
        $stmt->bindParam(":codigoPostal", $this->CodigoPostal);
        $stmt->bindParam(":ciudad", $this->Ciudad);
        $stmt->bindParam(":pais", $this->Pais);
        $stmt->bindParam(":email", $this->Email);

        // Solo bindear la contraseña si es nueva y hasheada
        if (!empty($this->Password)) {
            $stmt->bindParam(":password", $this->Password);
        }

        return $stmt->execute();
    }
