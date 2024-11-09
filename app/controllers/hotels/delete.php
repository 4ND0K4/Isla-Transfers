<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/hotel.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['idHotel'])) {
    $id_hotel = $_GET['idHotel'];
    $db = db_connect();
    $hotel = new Hotel($db);

    // Intentar eliminar el hotel
    if ($hotel->deleteHotel($id_hotel)) {
        $_SESSION['delete_hotel_success'] = "Hotel eliminado correctamente.";
    } else {
        $_SESSION['delete_hotel_error'] = "Error al intentar eliminar el hotel.";
    }

    // Redirigir a la página de origen para mostrar el mensaje
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>

