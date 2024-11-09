<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/hotel.php';

class RegisterHotelController {
    private $hotelModel;

    public function __construct() {
        $pdo = db_connect();
        if (!$pdo) {
            $_SESSION['create_hotel_error'] = "No se puede conectar a la base de datos.";
            header('Location: /views/hotel.php');
            exit;
        }
        $this->hotelModel = new Hotel($pdo);
    }

    public function registerHotel($id_hotel, $id_zona, $comision, $usuario, $password) {
        $usernameReturned = $this->hotelModel->addHotel($id_hotel, $id_zona, $comision, $usuario, $password);

        if ($usernameReturned) {
            $_SESSION['create_hotel_success'] = "Hotel registrado correctamente.";
        } else {
            $_SESSION['create_hotel_error'] = "Error: No se pudo registrar el hotel.";
        }

        // Redirigir a la página de origen para mostrar el mensaje
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

$controller = new RegisterHotelController();

function generateHotelUser($length = 6) {
    return substr(str_shuffle("0123456789"), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_hotel = $_POST['idHotel'] ?? '';
    $id_zona = $_POST['idZone'] ?? '';
    $comision = $_POST['commission'] ?? '';
    $usuario = generateHotelUser(); //El usuario será el código generado por la función
    $password = $_POST['pass'] ?? '';

    $controller->registerHotel($id_hotel, $id_zona, $comision, $usuario, $password);
}
?>
