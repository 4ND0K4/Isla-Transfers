<?php
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/hotel.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['idHotel'])) {
    $id_hotel = $_GET['idHotel'];
    $db = db_connect();
    $hotel = new Hotel($db);

    // Debug: Verificar que el id_hotel se recibe correctamente
    error_log("Intentando eliminar hotel con ID: " . $id_hotel);

    if ($hotel->deleteHotel($id_hotel)) {
        // Debug: Confirmar eliminación
        error_log("Hotel con ID " . $id_hotel . " eliminado correctamente.");
        header('Location: /views/hotel.php');
        exit;
    } else {
        error_log("Error al intentar borrar el hotel con ID " . $id_hotel);
        echo "Error al borrar el hotel";
    }
} else {
    error_log("ID del hotel no especificado o método incorrecto.");
}
?>
