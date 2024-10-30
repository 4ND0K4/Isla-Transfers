<?php
session_start();
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/hotel.php';

class RegisterHotelController {
    private $hotelModel;

    public function __construct() {
        $pdo = db_connect();
        if (!$pdo) {
            throw new InvalidArgumentException("No se puede conectar a la base de datos");
        }
        $this->hotelModel = new Hotel($pdo);
    }
    public function registerHotel($id_hotel, $id_zona, $comision, $usuario, $password) {
        $usernameReturned = $this->hotelModel->addHotel($id_hotel, $id_zona, $comision, $usuario, $password);

        if ($usernameReturned) {
            header('Location: /views/hotel.php');
            exit;
        } else {
            header('Location: /views/hotel.php?error=registro_fallido');
            exit;
        }
    }
}

$controller = new RegisterHotelController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_hotel = $_POST['idHotel'] ?? '';
    $id_zona = $_POST['idZone'] ?? '';
    $comision = $_POST['commission'] ?? '';
    $usuario = $_POST['user'] ?? '';
    $password = $_POST['pass'] ?? '';

    $controller->registerHotel($id_hotel, $id_zona, $comision, $usuario, $password);
}
?>

