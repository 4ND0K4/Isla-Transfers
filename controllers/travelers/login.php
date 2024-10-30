<?php
session_start();

require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/traveler.php';

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header('Location: http://localhost:3000');
    exit;
}

class TravelerController
{
    private $travelerModel;
    public $loginError = '';

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
        $user = $this->travelerModel->findByEmail($email);

        if ($user && $this->travelerModel->verifyPasswordEmail($email, $password)) {
            $_SESSION['user'] = $email;
            header("Location: /views/dashboard-traveler.php");
        } else {
            $_SESSION['login_error'] = 'Email o contraseña incorrecta.';
            header('Location: /views/login-traveler.php');
            exit;
        }
    }
}

$travelerController = new TravelerController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $travelerController->loginByEmail($email, $password);
}
