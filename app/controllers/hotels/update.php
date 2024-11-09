<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/hotel.php';

$pdo = db_connect(); // Llama a la función que devuelve la conexión PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotel = new Hotel($pdo);
    $hotel->Id_hotel = $_POST['idHotel'];
    $hotel->Id_zona = $_POST['idZone'];
    $hotel->Comision = $_POST['commission'];
    $hotel->Usuario = $_POST['user'];

    // Actualizar solo si se proporciona una nueva contraseña y hashearla
    if (!empty($_POST['pass'])) {
        $hotel->Password = password_hash($_POST['pass'], PASSWORD_BCRYPT);
    }

    if ($hotel->updateHotel()) {
        $_SESSION['update_hotel_success'] = "Hotel actualizado correctamente.";
    } else {
        $_SESSION['update_hotel_error'] = "Error al actualizar el hotel.";
    }

    // Redirigir a la página de origen para mostrar el mensaje
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>

