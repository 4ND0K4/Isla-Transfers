<?php
session_start();
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/traveler.php';

class RegisterTravelerController {
    private $travelerModel;

    public function __construct() {
        $pdo = db_connect();
        if (!$pdo) {
            throw new InvalidArgumentException("No se puede conectar a la base de datos");
        }
        $this->travelerModel = new Traveler($pdo);
    }

    public function registerTraveler($id_viajero, $nombre, $apellido1, $apellido2, $direccion, $codigoPostal, $ciudad, $pais, $email, $password) {
        $emailReturned = $this->travelerModel->addTraveler($id_viajero, $nombre, $apellido1, $apellido2, $direccion, $codigoPostal, $ciudad, $pais, $email, $password);

        if ($emailReturned === $email) {
            header('Location: /views/login-traveler.php?success=registro_exitoso');
            exit;
        } else {
            header('Location: /views/register-traveler.php?error=registro_fallido');
            exit;
        }
    }

}

$controller = new RegisterTravelerController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_viajero = $_POST['id_traveler'] ?? ''; //Se genera current
    $nombre = $_POST['name'] ?? ''; //Obligatorio
    $apellido1 = $_POST['surname1'] ?? ''; //Obligatorio
    $apellido2 = $_POST['surname2'] ?? ''; //opcional
    $direccion= $_POST['adress'] ?? ''; // Solo al actualizar el perfil
    $codigoPostal= $_POST['zipCode'] ?? ''; // Solo al actualizar el perfil
    $ciudad= $_POST['city'] ?? ''; // Solo al actualizar el perfil
    $pais= $_POST['country'] ?? ''; // Solo al actualizar el perfil
    $email= $_POST['email'] ?? ''; //Obligatorio
    $password = $_POST['password'] ?? ''; //Obligatorio, Hasheado

    $controller->registerTraveler($id_viajero, $nombre, $apellido1, $apellido2, $direccion, $codigoPostal, $ciudad, $pais, $email, $password);
}
?>
