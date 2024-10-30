<?php
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/booking.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_booking'])) {
    $id_booking = $_GET['id_booking'];
    $db = db_connect();
    $booking = new Booking($db);

    // Log para verificar si el ID de la reserva es el correcto
    error_log("Intentando eliminar reserva con ID: " . $id_booking);

    if ($booking->deleteBooking($id_booking)) {
        // Log de éxito en eliminación
        error_log("Reserva con ID " . $id_booking . " eliminada correctamente.");
        header('Location: /views/dashboard-admin.php?page=reserva');
        exit;
    } else {
        // Log de error en caso de fallo
        error_log("Error al borrar la reserva con ID " . $id_booking);
        echo "Error al borrar la reserva";
    }
} else {
    error_log("ID de reserva no especificado o método de solicitud incorrecto.");
}
?>
