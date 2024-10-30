<?php
session_start();

require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/hotel.php';

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header('Location: http://localhost:3000');
    exit;
}

class HotelController
{
    private $hotelModel;
    public $loginError = '';

    public function __construct()
    {
        $pdo = db_connect();
        if (!$pdo) {
            throw new InvalidArgumentException("No se puede conectar a la base de datos");
        }
        $this->hotelModel = new Hotel($pdo);
    }

    public function loginByUsername($usuario, $password)
    {
        $user = $this->hotelModel->findByUsername($usuario);

        if ($user && $this->hotelModel->verifyPasswordUsername($usuario, $password)) {
            $_SESSION['user'] = $usuario;
            header("Location: /views/dashboard-hotel.php");
        } else {
            $_SESSION['login_error'] = 'Usuario o contraseña incorrecta.';
            header('Location: /views/login-hotel.php');
            exit;
        }
    }
}

$hotelController = new HotelController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['username'] ?? '';
    $password = $_POST['pass'] ?? '';
    $hotelController->loginByUsername($usuario, $password);
}

