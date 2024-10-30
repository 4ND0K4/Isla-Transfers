<?php
require_once __DIR__ . '/../models/admin.php';

class adminController {
    public function loginByID() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $admin = new admin();

            if ($admin->verifyLoginAdmin($username, $password)) {
                $_SESSION['is_admin_logged_in'] = true;
                header("Location: /views/dashboard-admin.php"); // Redirigir al dashboard-admin
                exit();
            } else {
                $error = "Credenciales incorrectas.";
                include __DIR__ . '/../views/login-admin.php'; //Se mantiene en la log page
            }
        } else {
            include __DIR__ . '/../views/login-admin.php';
        }
    }
    /*
    public function logout() {
        session_start();
        session_destroy();
        header("Location: /views/login-admin.php");
        exit();
    }*/
}