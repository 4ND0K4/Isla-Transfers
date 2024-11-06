<?php
// Declaraciones de inclusión
require_once __DIR__ . '/../../models/db.php';
require_once __DIR__ . '/../../models/booking.php';

// Verificar que la solicitud sea GET y que se haya pasado un ID de reserva
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_booking'])) {
    $id_booking = $_GET['id_booking']; // Capturar el ID de la reserva desde los parámetros de la URL

    // Conectar a la base de datos
    $db = db_connect();
    if (!$db) {
        die("Error al conectar con la base de datos."); // Detener ejecución si falla la conexión
    }

    // Crear instancia del modelo Booking
    $booking = new Booking($db);

    // Intentar eliminar la reserva con el ID proporcionado
    if ($booking->deleteBooking($id_booking)) {
        // Si la eliminación es exitosa, redirigir al dashboard
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        // Mostrar un mensaje en caso de error al eliminar la reserva
        echo "Error al borrar la reserva";
    }
}
?>
