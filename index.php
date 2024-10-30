<?php
require_once 'models/db.php';

$userType = $_GET['user_type'] ?? null; // 'admin' , 'hotel' , 'traveler'
$action = $_GET['action'] ?? null;

switch ($userType) {
    case 'admin':
        require_once 'controllers/adminController.php';
        $controller = new adminController();

        switch ($action) {
            case 'login':
                $controller->loginByID();
                break;
            case 'logout':
                $controller->logout();
                break;
            default:
                header("Location: /views/login-admin.php");
                exit();
        }
        break;

    case 'traveler':
        require_once 'controllers/travelers/login.php';
        $controller = new TravelerController();

        switch ($action) {
            case 'login':
                $email = $_POST['email'] ?? null;
                $password = $_POST['password'] ?? null;
                $controller->loginByEmail($email, $password);
                break;
            case 'logout':
                $controller->logout();
                break;
            default:
                include 'views/login-traveler.php';
                exit();
        }
        break;


    case 'hotel':
        require_once 'controllers/hotels/login.php';
        $controller = new HotelController();

        switch ($action) {
            case 'login':
                $controller->loginByUsername();
                break;
            case 'logout':
                $controller->logout();
                break;
            default:
                include 'views/login-hotel.php';
                exit();
        }
        break;

    default:
        // Redirigir a una página de error o página de inicio si el user_type no es válido
        header("Location: /views/login-traveler.php");
        exit();
}
?>


