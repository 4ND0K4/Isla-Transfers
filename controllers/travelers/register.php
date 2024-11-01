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
    $id_viajero = $_POST['id_traveler'] ?? '';
    $nombre = $_POST['name'] ?? '';
    $apellido1 = $_POST['surname1'] ?? '';
    $apellido2 = $_POST['surname2'] ?? '';
    $direccion= $_POST['adress'] ?? '';
    $codigoPostal= $_POST['zipCode'] ?? '';
    $ciudad= $_POST['city'] ?? '';
    $pais= $_POST['country'] ?? '';
    $email= $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $controller->registerTraveler($id_viajero, $nombre, $apellido1, $apellido2, $direccion, $codigoPostal, $ciudad, $pais, $email, $password);
}
?>