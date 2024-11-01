<?php
session_start();

require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/traveler.php';

$travelerController = new TravelerController();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $travelerController->logout();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $travelerController->loginByEmail($email, $password);
}

class TravelerController
{
    private $travelerModel;

    public function __construct()
    {
        $pdo = db_connect();
        if (!$pdo) {
            throw new InvalidArgumentException("No se puede conectar a la base de datos");
        }
        $this->travelerModel = new Traveler($pdo);
    }

    public function loginByEmail($email, $password)
    {
        // Usar verifyPasswordEmail para autenticar al usuario
        if ($this->travelerModel->verifyPasswordEmail($email, $password)) {
            $_SESSION['user'] = $email;
            header("Location: /views/dashboard-traveler.php");
            exit();
        } else {
            $_SESSION['login_error'] = 'Email o contraseña incorrecta.';
            header('Location: /views/login-traveler.php');
            exit();
        }
    }

    public function logout()
    {
        // Verifica si la sesión ya está activa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: /views/login-traveler.php");
        exit();
    }
}
?>