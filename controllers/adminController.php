<?php
require_once __DIR__ . '/../models/admin.php';

$controller = new adminController();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $controller->logout();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->loginByID();
}

class adminController {
    public function loginByID() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $key = $_POST['key'];

            $admin = new admin();

            if ($admin->verifyLoginAdmin($id, $key)) {
                $_SESSION['admin'] = $id; // Almacena el ID del admin en la sesión
                header("Location: /views/dashboard-admin.php"); // Redirigir al dashboard-admin
                exit();
            } else {
                $_SESSION['error'] = "Credenciales incorrectas. Intente nuevamente.";
                header("Location: /views/login-admin.php"); // Redirige a la página de login
                exit();
            }
        } else {
            include __DIR__ . '/../views/login-admin.php';
        }
    }
    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: /views/login-admin.php");
        exit();
    }
}
